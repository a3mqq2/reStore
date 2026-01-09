<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\City;
use App\Models\Order;
use App\Models\Banner;
use App\Models\Content;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Redemption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Notifications\SendOtpCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Faq;
use App\Models\ProductCategory;
use App\Models\AccountCategory;
use App\Models\Account;
use App\Notifications\SendResetLink;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\BalanceTransaction;
use App\Models\Cashback;
use App\Models\Variant;
use App\Models\VariantRedemption;

class ApplicationController extends Controller
{
    private function loadCommonData() {
        $banners = Banner::all();
        $products = Product::whereNull('product_category_id')->where('active',1)->get();
        $paymentMethods = PaymentMethod::where('active', 1)->get();
        $categories = ProductCategory::all();
        $currentPaymentMethod = PaymentMethod::where('id', Cookie::get('payment_method'))->where('active', 1)->first() ? Cookie::get('payment_method') : 1;
    
        if (!$currentPaymentMethod && $paymentMethods->isNotEmpty()) {
            $currentPaymentMethod = $paymentMethods->first()->id;
            Cookie::queue('payment_method', $currentPaymentMethod, 60*24*30);
        }

        $content = Content::first();
        $cities = City::all();
        $faqs = Faq::all();
        $customer = auth('customer')->check() ? auth('customer')->user() : null;
        $cartItemCount = 0;
        $cartItemCount = CartItem::when($customer, function($q) use($customer) {
            $q->where('customer_id', $customer->id);
        })->orWhere('session_id', Cookie::get('guest_session_id'))->sum('quantity');





    
        $count = $cartItemCount;
        $ratedOrders = Order::whereNotNull('rating')->where('show_on_homepage', 1)->with('customer')->get();
        $accountCategories = AccountCategory::where('is_active', true)
            ->withCount('availableAccounts')
            ->having('available_accounts_count', '>', 0)
            ->take(6)
            ->get();
        $accounts = Account::available()
            ->with('category')
            ->latest()
            ->take(8)
            ->get();
        return compact('banners', 'products', 'paymentMethods', 'currentPaymentMethod', 'content', 'cities', 'count','ratedOrders','faqs','categories','accountCategories','accounts');
    }
    
    

    public function index() {
        $data = $this->loadCommonData();
        Cookie::queue('payment_method', 1, 60*24*30);
        return view('website.index', $data);
    }

    public function updatePaymentMethod(Request $request) {
        $request->validate([
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        $paymentMethodId = $request->input('payment_method_id');
        Cookie::queue('payment_method', $paymentMethodId, 60*24*30);

        return response()->json(['status' => 'success']);
    }

    public function register() {
        $data = $this->loadCommonData();
        return view('website.register', $data);
    }

    public function register_store(Request $request) {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|unique:customers,phone_number|regex:/^[\+]?[0-9\s\-\(\)]{7,20}$/',
            'email' => 'required|email|unique:customers,email',
            'city_id' => 'required|exists:cities,id',
            'password' => 'required|confirmed|min:8',
        ]);

        try {
            DB::beginTransaction();

            // Double-check for existing customer to prevent race condition
            $existingCustomer = Customer::where('email', $request->email)
                ->orWhere('phone_number', $request->phone)
                ->lockForUpdate()
                ->first();

            if ($existingCustomer) {
                DB::rollback();
                if ($existingCustomer->email === $request->email) {
                    return redirect()->back()->withInput()->withErrors(['email' => 'البريد الإلكتروني مسجل مسبقاً']);
                } else {
                    return redirect()->back()->withInput()->withErrors(['phone' => 'رقم الهاتف مسجل مسبقاً']);
                }
            }

            $customer = new Customer();
            $customer->name = $request->name;
            $customer->phone_number = $request->phone;
            $customer->email = $request->email;
            $customer->city_id = $request->city_id;
            $customer->password = Hash::make($request->password);
            $customer->otp = Str::random(6); // Generate a random 6-character OTP
            $customer->balance = 0;
            $customer->cashback = 0;
            $customer->save();

            // $customer->notify(new SendOtpCode($customer->otp, $customer));

            DB::commit();
            Cookie::queue('payment_method', 1, 60*24*30);
            return redirect('/');
        } catch(\Illuminate\Database\QueryException $e) {
            Log::error('Database error during registration: ' . $e->getMessage());
            DB::rollback();

            // Check if it's a duplicate entry error
            if ($e->getCode() == 23000 || strpos($e->getMessage(), 'Duplicate entry') !== false) {
                if (strpos($e->getMessage(), 'email') !== false) {
                    return redirect()->back()->withInput()->withErrors(['email' => 'البريد الإلكتروني مسجل مسبقاً']);
                } else if (strpos($e->getMessage(), 'phone') !== false) {
                    return redirect()->back()->withInput()->withErrors(['phone' => 'رقم الهاتف مسجل مسبقاً']);
                }
            }

            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.']);
        } catch(\Exception $e) {
            Log::error('Error during registration: ' . $e->getMessage());
            DB::rollback();

            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء التسجيل. حاول مرة أخرى.']);
        }
    }

    public function showVerificationForm(Request $request) {
        $email = $request->query('email');
        $data = $this->loadCommonData();
        return view('website.verification', array_merge($data, compact('email')));
    }

    public function verifyOtp(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:customers,email',
            'otp' => 'required|size:6',
        ]);
    
        $customer = Customer::where('email', $request->email)->where('otp', $request->otp)->first();
    
        if ($customer) {
            $customer->otp = null;
            $customer->email_verified_at = now();
            $customer->save();
    
            return redirect()->route('website.login')->with('success', 'تم التحقق من بريدك الإلكتروني بنجاح.');
        }
    
        return redirect()->back()->withErrors(['otp' => 'رمز التحقق غير صحيح.']);
    }

    public function profile() {
        $data = $this->loadCommonData();
        $data['orders'] = auth('customer')->user()->orders;
        $data['redeemableProducts'] = Cashback::where('active', 1)->get();
        $data['myRedemptions'] = Redemption::where('customer_id', auth('customer')->id())->with('cashback')->get();

        // بيانات استردادات الـ variants الجديدة
        $data['redeemableVariants'] = Variant::where('is_redeemable', true)
            ->where('is_active', true)
            ->with('product')
            ->get();
        $data['myVariantRedemptions'] = VariantRedemption::where('customer_id', auth('customer')->id())
            ->with('variant.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view("website.profile", $data);
    }

    public function login() {
        $data = $this->loadCommonData();
        return view('website.login', $data);
    }

    public function do_login(Request $request) {
        $request->validate([
            "email" => "required|exists:customers,email",
            "password" => "required",
        ]);
    
        try {
            DB::beginTransaction();
            $user = Customer::where('email', $request->email)->first();
            if(!$user) {
                return redirect()->back()->withInput()->withErrors(['email' => "لم يتم العثور على مستخدم بهذا البريد"]);
            }
    
            $remember = $request->has('remember'); // Get the "remember me" option
    
            if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
                $user = Auth::guard('customer')->user();
    
                // حذف الرموز القديمة للمستخدم
                $user->tokens()->delete();
    
                // إنشاء رمز جديد
                $token = $user->createToken('authToken')->plainTextToken;
    
                // إعداد الكوكيز لحفظ الرمز ومعرف المستخدم
                $cookie = cookie('ast', $token, config('auth.token_expiration_minutes'));
                $customerIdCookie = cookie('customerId', $user->id, config('auth.token_expiration_minutes'));
    
                DB::commit();
                Cookie::queue('payment_method', 1, 60*24*30);
    
                return redirect('/')->withCookie($cookie)->withCookie($customerIdCookie);
            } else {
                return redirect()->back()->withInput()->withErrors(['email' => "كلمة المرور خاطئة"]);
            }
        } catch(\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['حدث خطأ ما يرجى التحقق لاحقاََ']);
        }
    }
    
    
    

    public function profileUpdate(Request $request) {
        $request->validate([
            "name" => "required",
            "phone_number" => "required",
            "email" => "required",
            "password" => "nullable|confirmed",
            "city_id" => "required",
        ]);

        try {
            DB::beginTransaction();
            $customer = auth('customer')->user();
            $customer->name = $request->name;
            $customer->phone_number = $request->phone_number;
            $customer->email = $request->email;

            if($request->password) {
                $customer->password = $request->password;
            }

            $customer->city_id = $request->city_id;
            $customer->save();

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث الملف بنجاح');
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
    }


    public function about() {
        $data = $this->loadCommonData();
        return view('website.about', $data);
    }


    public function faqs() {
        $data = $this->loadCommonData();
        return view('website.faqs', $data);
    }


    public function policy() {
        $data = $this->loadCommonData();
        return view('website.policy', $data);
    }


    public function returns() {
        $data = $this->loadCommonData();
        return view('website.returns', $data);
    }


    public function contact() {
        $data = $this->loadCommonData();
        return view('website.contact', $data);
    }

    
    public function category(ProductCategory $ProductCategory, Request $request) {
        // $productCategory 
        $data = $this->loadCommonData();
        $data['ProductCategory'] = $ProductCategory;
        $data['products'] = Product::where('product_category_id', $ProductCategory->id)->where('active', true)->get();
        return view('website.category',$data);
    }


    public function reset() {
        $data = $this->loadCommonData();
        return view('website.reset', $data);
    }

    public function reset_send(Request $request) {
        $request->validate([
            "email" => "required|exists:customers,email",
        ]);

        try {
            DB::beginTransaction();
            $customer = Customer::where('email', $request->email)->first();
            if(!$customer) {
                return redirect()->back()->withErrors(['لم يتم العثور على حسابك']);
            }

            // $customer->notify(new SendResetLink($customer->email, $customer));
            DB::commit();
            return redirect()->back()->with('success', 'تم ارسال رابط اعادة التعيين عبر البريد الالكتروني بنجاح');
        } catch(\Exception $e) {
            dd($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['هناك خطا يرجى التواصل مع الدعم الفني']);
        }
    }

    public function reset_link() {
        $data = $this->loadCommonData();
        return view('website.change-password', $data);
    }

    public function reset_send_store(Request $request)
    {
        $request->validate([
            "email" => "required|email",
            "password" => "required|confirmed",
            "token" => "required"
        ]);
    
        $user = Customer::where('email', $request->email)->first();
    
        if (!$user) {
            return redirect()->back()->withErrors(['حدث خطأ ما يرجى المحاولة لاحقاً']);
        }



        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('website.login')->with('success', 'تم تغيير كلمة المرور بنجاح');
    
        // $hashedToken = hash('sha256', $request->token);
    
        // // Check the token in the personal_access_tokens table
        // $token = PersonalAccessToken::where('token', $hashedToken)
        //                             ->where('tokenable_id', $user->id)
        //                             ->where('tokenable_type', Customer::class)
        //                             ->first();
    
        // dd($token);
        // if ($token) {
        //     $user->password = $request->password;
        //     $user->save();
    
        //     // Optionally, you can delete the used token
        //     $token->delete();
    
        //     return redirect()->back()->with('success', 'تم تغيير كلمة المرور بنجاح');
        // } else {
        //     return redirect()->back()->with('error', 'حدث خطأ ما يرجى المحاولة لاحقاً');
        // }
    }

    public function addBalance(Request $request)
    {
        $request->validate([
            'secret_number' => 'required|string',
        ]);

        $card = Card::where('secret_number', $request->secret_number)
                    ->where('status', 'new')
                    ->first();

        if ($card) {
            $customer = Auth::guard('customer')->user();
            $balanceBefore = $customer->balance;
            $customer->balance += $card->amount;
            $customer->save();

            // تسجيل عملية الرصيد
            BalanceTransaction::create([
                'customer_id' => $customer->id,
                'type' => 'add',
                'amount' => $card->amount,
                'balance_before' => $balanceBefore,
                'balance_after' => $customer->balance,
                'description' => 'شحن رصيد ببطاقة رقم: ' . $card->secret_number,
            ]);

            $card->status = 'used';
            $card->customer_id = $customer->id;
            $card->used_at = now();
            $card->save();

            return redirect()->back()->with('success', 'تم اضافة الرصيد بنجاح.');
        }

        return redirect()->back()->withErrors([ 'الرقم السري غير صحيح أو البطاقة مستخدمة بالفعل.']);
    }

    public function show_product(Product $product) {
        if(!$product->active) {
            abort(404);
        }
        $data = $this->loadCommonData();
        $data['product'] = $product;
        return view('website.product-details', $data);
    }

    public function cart()
    {
        $data = $this->loadCommonData();

        $user = auth('customer')->user();
        if ($user) {
            // Filter cart items to only include those with active products
            $cartItems = $user->cartItems()
                ->with('product')
                ->whereHas('product', function ($query) {
                    $query->where('active', true); // Assuming 'active' is a boolean column in the products table
                })
                ->get();

            $total = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });
        } else {
            $cartItems = collect();
            $total = 0;
        }

        $data['cartItems'] = $cartItems;
        $data['total'] = $total;
        $data['paymentMethod'] = PaymentMethod::with('currency')->where('id', $data['currentPaymentMethod'])->first();
        
        return view('website.cart', $data);
    }

    public function accounts()
    {
        $data = $this->loadCommonData();

        $categories = AccountCategory::where('is_active', true)
            ->withCount('availableAccounts')
            ->get();

        $accounts = Account::with('category')
            ->where('status', 'available')
            ->latest()
            ->paginate(12);

        $telegramUsername = $data['content']->telegram ?? null;

        return view('website.accounts', array_merge($data, compact('categories', 'accounts', 'telegramUsername')));
    }

    public function accountsByCategory(AccountCategory $accountCategory)
    {
        if (!$accountCategory->is_active) {
            abort(404);
        }

        $data = $this->loadCommonData();

        $categories = AccountCategory::where('is_active', true)
            ->withCount('availableAccounts')
            ->get();

        $accounts = Account::with('category')
            ->where('account_category_id', $accountCategory->id)
            ->where('status', 'available')
            ->latest()
            ->paginate(12);

        $telegramUsername = $data['content']->telegram ?? null;

        return view('website.accounts', array_merge($data, compact('categories', 'accounts', 'accountCategory', 'telegramUsername')));
    }

    /**
     * Show account details page
     */
    public function accountDetails(Account $account)
    {
        // Only show available accounts
        if ($account->status !== 'available') {
            abort(404);
        }

        $data = $this->loadCommonData();
        $telegramUsername = $data['content']->telegram ?? null;

        // Get related accounts from the same category
        $relatedAccounts = Account::with('category')
            ->where('account_category_id', $account->account_category_id)
            ->where('id', '!=', $account->id)
            ->where('status', 'available')
            ->latest()
            ->take(4)
            ->get();

        return view('website.account-details', array_merge($data, compact('account', 'telegramUsername', 'relatedAccounts')));
    }

}
