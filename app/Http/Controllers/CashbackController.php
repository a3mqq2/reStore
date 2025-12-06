<?php

namespace App\Http\Controllers;

use App\Models\Cashback;
use App\Models\Redemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewRedemptionNotification;
use Illuminate\Support\Facades\Storage;

class CashbackController extends Controller
{
    /**
     * عرض قائمة الاستردادات النقدية.
     */
    public function index()
    {
        $cashbacks = Cashback::paginate(10);
        return view('cashbacks.index', compact('cashbacks'));
    }

    /**
     * عرض نموذج إنشاء استرداد نقدي جديد.
     */
    public function create()
    {
        return view('cashbacks.create');
    }

    /**
     * تخزين استرداد نقدي جديد.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_details' => 'nullable|string',
            'amount' => 'required|numeric',
        ]);

        // رفع الصورة إذا تم تحميلها
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('products', 'public');
        }

        Cashback::create([
            'product_name' => $request->product_name,
            'product_image' => $imagePath,
            'product_details' => $request->product_details,
            'amount' => $request->amount,
        ]);

        return redirect()->route('cashbacks.index')->with('success', 'تم إضافة الاسترداد النقدي بنجاح.');
    }

    /**
     * عرض نموذج تعديل استرداد نقدي.
     */
    public function edit($id)
    {
        $cashback = Cashback::findOrFail($id);
        return view('cashbacks.edit', compact('cashback'));
    }

    /**
     * تحديث الاسترداد النقدي المحدد.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'product_details' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $cashback = Cashback::findOrFail($id);

        // تحديث الصورة إذا تم تحميلها
        if ($request->hasFile('product_image')) {
            // حذف الصورة القديمة
            if ($cashback->product_image) {
                Storage::disk('public')->delete($cashback->product_image);
            }

            // تخزين الصورة الجديدة
            $cashback->product_image = $request->file('product_image')->store('products', 'public');
        }

        // تحديث بقية البيانات
        $cashback->update([
            'product_name' => $request->product_name,
            'product_details' => $request->product_details,
            'amount' => $request->amount,
        ]);

        return redirect()->route('cashbacks.index')->with('success', 'تم تحديث الاسترداد النقدي بنجاح.');
    }

    /**
     * حذف الاسترداد النقدي المحدد.
     */
    public function destroy($id)
    {
        $cashback = Cashback::findOrFail($id);

        // حذف الصورة إذا كانت موجودة
        if ($cashback->product_image) {
            Storage::disk('public')->delete($cashback->product_image);
        }

        $cashback->delete();

        return redirect()->route('cashbacks.index')->with('success', 'تم حذف الاسترداد النقدي بنجاح.');
    }

    /**
     * تغيير حالة التفعيل للاسترداد النقدي.
     */
    public function toggleStatus(Request $request, $id)
    {
        $cashback = Cashback::findOrFail($id);
        $cashback->active = $request->active;
        $cashback->save();

        return response()->json(['success' => true]);
    }


    public function storeRedemption(Request $request)
    {
        $request->validate([
            'cashback_id' => 'required|exists:cashbacks,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $cashback = Cashback::find($request->cashback_id);
        $customer = auth('customer')->user();

        if ($customer->cashback < $cashback->amount) {
            return redirect()->back()->withErrors(['رصيد النقاط غير كافٍ للاسترداد.']);
        }

        // Deduct cashback from the customer's balance
        $customer->cashback -= $cashback->amount;
        $customer->save();

        // Create the redemption
        $redemption = Redemption::create([
            'customer_id' => $customer->id,
            'cashback_id' => $cashback->id,
            'notes' => $request->notes,
            'status' => Redemption::STATUS_PENDING,  // Default status 'قيد التنفيذ'
        ]);


        $adminEmail =  'ezo706174@gmail.com';
        Mail::to($adminEmail)->send(new NewRedemptionNotification($redemption, $customer));

        return redirect()->back()->with('success', 'تم استرداد النقاط بنجاح!');
    }

    public function completeRedemption($id)
    {
        $redemption = Redemption::findOrFail($id);
        $redemption->update([
            'status' => Redemption::STATUS_COMPLETED,
        ]);

        return redirect()->back()->with('success', 'تم تحديث حالة الاسترداد إلى "منفذ".');
    }

}
