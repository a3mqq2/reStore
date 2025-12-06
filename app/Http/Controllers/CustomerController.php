<?php

namespace App\Http\Controllers;

use App\Models\BalanceTransaction;
use App\Models\Cashback;
use App\Models\City;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->filled('city_id')) {
            $query->where('city_id', $request->input('city_id'));
        }



        if ($request->filled('email')) {
            $query->where('email', $request->input('email'));
        }

        if ($request->filled('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
        }

        // Add more filters as needed

        $customers = $query->orderByDesc('balance')->paginate();
        if($request->wantsJson()) {
            return response($customers, 200);
        }
        $cities = City::all();
        return view('customers.index', compact('customers','cities'));
    }

    public function create()
    {
        $cities = City::all();
        return view('customers.create',compact('cities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'city_id' => 'required',
            'phone_number' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:customers',
        ]);

        $validated['balance'] = 0;
        $validated['cashback'] = 0;
        $validated['password'] = Hash::make($request->password);
        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'تم إضافة الزبون بنجاح.');
    }

    public function edit(Customer $customer)
    {
        $cities = City::all();
        return view('customers.edit', compact('customer','cities'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required',
            'city_id' => 'required',
            'phone_number' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
        ]);

        $customer->update($request->all());

        if($request->password) {
            $customer->password = Hash::make($request->password);
            $customer->save();
        }

        return redirect()->route('customers.index')
            ->with('success', 'تم تحديث بيانات الزبون بنجاح.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'تم حذف الزبون بنجاح.');
    }


    public function transferBalance(Request $request)
    {
        $request->validate([
            'recipient_code' => 'required|exists:customers,code',
            'amount' => 'required|numeric|min:1',
        ]);


        
        $sender = Auth::guard('customer')->user();
        $recipient = Customer::where('code', $request->recipient_code)->first();


        if($sender->id == $recipient->id) {
            return redirect()->back()->withErrors(['لا يمكنك تحويل الرصيد لنفسك']);
        }

        if ($sender->balance < $request->amount) {
            return redirect()->back()->withErrors(['رصيدك غير كاف لإتمام العملية']);
        }

        DB::beginTransaction();
        try {
            // Decrease the balance of the sender
            $sender->balance -= $request->amount;
            $sender->save();

            // Increase the balance of the recipient
            $recipient->balance += $request->amount;
            $recipient->save();

            // Send email notifications
            Mail::send('emails.transfer', ['amount' => $request->amount, 'senderName' => $sender->name, 'recipientName' => $recipient->name], function ($message) use ($sender) {
                $message->to($sender->email);
                $message->subject('تم تحويل الرصيد بنجاح');
            });

            Mail::send('emails.transfer', ['amount' => $request->amount, 'senderName' => $sender->name, 'recipientName' => $recipient->name], function ($message) use ($recipient) {
                $message->to($recipient->email);
                $message->subject('تم استلام الرصيد بنجاح');
            });

            DB::commit();
            return redirect()->back()->with('success', 'تم تحويل الرصيد بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['حدث خطأ أثناء تحويل الرصيد']);
        }
    }

    public function createOrderFromCashback(Request $request, OrderController $orderController)
    {
        // استرجاع المنتج
        $cashback = Cashback::findOrFail($request->cashback_id);
        $product = Product::find($request->product_id);
    
        // التحقق من أن العميل لديه نقاط كافية لاسترداد المنتج
        $customer = auth('customer')->user();
        if ($customer->cashback < $cashback->amount) {
            return redirect()->back()->withErrors(['رصيد نقاط الكاش باك غير كافٍ لاسترداد هذا المنتج.']);
        }
    
        // دمج بيانات الطلب مع المتطلبات
        $request->merge([
            'customer_id' => $customer->id,
            'payment_method_id' => 1,  // No payment method because it's cashback
            'order_date' => now(),
            'order_notes' => 'Order created from cashback',
            'coupon_id' => null,
            'total_amount' => 0,  // Set it to 0 since it's from cashback
            'discounted_total' => 0,
            'from_cashback' => 1,
            'payment_method_id' => 1,
            'cashback_id' => $cashback->id,
            'status' => 'under_payment',
            'products' => [
                [
                    'product_id' => $product->id,
                    'variant_id' => $request->variant_id,
                    'variant' => $request->variant_id,
                    'category_id' => $product->category_id,
                    'name' => $product->name,
                    'quantity' => $request->quantity,
                    'price' => 0,  // Assuming it's free since it's from cashback
                    'requirements' => $request->requirements ?? [],  // استلام المتطلبات
                ]
            ],
        ]);
    
        // استدعاء طريقة store لإنشاء الطلب
        $response = $orderController->store($request);
    
        // التحقق من نجاح إنشاء الطلب
        if ($response->getData()->success) {
            // خصم النقاط من العميل
            $customer->cashback -= $cashback->amount;
            $customer->save();
    
            return redirect()->back()->with('success', 'تم إنشاء الطلب بنجاح باستخدام نقاط.');
        } else {
            return redirect()->back()->withErrors(['حدث خطأ أثناء إنشاء الطلب. الرجاء المحاولة مرة أخرى.']);
        }
    }

    /**
     * Add balance to customer account
     */
    public function addBalance(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $balanceBefore = $customer->balance;
            $customer->balance += $request->amount;
            $customer->save();

            BalanceTransaction::create([
                'customer_id' => $customer->id,
                'type' => 'add',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $customer->balance,
                'description' => $request->description ?? 'إضافة رصيد يدوي',
                'performed_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم إضافة الرصيد بنجاح. الرصيد الجديد: ' . number_format($customer->balance, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['حدث خطأ أثناء إضافة الرصيد']);
        }
    }

    /**
     * Subtract balance from customer account
     */
    public function subtractBalance(Request $request, Customer $customer)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        if ($customer->balance < $request->amount) {
            return redirect()->back()->withErrors(['رصيد العميل غير كافٍ لإتمام العملية']);
        }

        DB::beginTransaction();
        try {
            $balanceBefore = $customer->balance;
            $customer->balance -= $request->amount;
            $customer->save();

            BalanceTransaction::create([
                'customer_id' => $customer->id,
                'type' => 'subtract',
                'amount' => $request->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $customer->balance,
                'description' => $request->description ?? 'خصم رصيد يدوي',
                'performed_by' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'تم خصم الرصيد بنجاح. الرصيد الجديد: ' . number_format($customer->balance, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['حدث خطأ أثناء خصم الرصيد']);
        }
    }

    /**
     * Show balance history for a customer
     */
    public function balanceHistory(Customer $customer)
    {
        $transactions = $customer->balanceTransactions()
            ->with('performedBy')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('customers.balance-history', compact('customer', 'transactions'));
    }
}
