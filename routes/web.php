<?php

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\SmileoneProduct;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MoogoldController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\CashbackController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SmileOneController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\RedemptionController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductCashbackController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ChargingRequirementController;
use App\Http\Controllers\VariantRedemptionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ApplicationController::class, 'index'])->name('website.home');

Route::get('/search-products', [ProductController::class, 'search'])->name('search.products');
Route::get('/profile', [ApplicationController::class, 'profile'])->name('profile')->middleware('auth:customer');
Route::get('/cart', [ApplicationController::class, 'cart'])->name('website.cart');
Route::get('/category/{ProductCategory}', [ApplicationController::class, 'category'])->name('website.category');
Route::get('/product-details/{product}', [ApplicationController::class, 'show_product'])->name('website.show-product');
Route::get('/about', [ApplicationController::class, 'about'])->name('about');
Route::get('/faq', [ApplicationController::class, 'faqs'])->name('faq');
Route::get('/checkout', [ApplicationController::class, 'checkout'])->name('checkout');
Route::get('/policy', [ApplicationController::class, 'policy'])->name('policy');
Route::get('/returns', [ApplicationController::class, 'returns'])->name('returns');
Route::get('/contact', [ApplicationController::class, 'contact'])->name('contact');
Route::get('/shop/accounts', [ApplicationController::class, 'accounts'])->name('website.accounts');
Route::get('/shop/accounts/category/{accountCategory}', [ApplicationController::class, 'accountsByCategory'])->name('website.accounts.category');
Route::get('/shop/accounts/{account}', [ApplicationController::class, 'accountDetails'])->name('website.account.details');
Route::get('register', [ApplicationController::class, 'register'])->name('register')->middleware('guest:customer');
Route::get('/category', [ApplicationController::class, 'category'])->name('category');
Route::get('/reset', [ApplicationController::class, 'reset'])->name('website.reset');
Route::get('/reset-link', [ApplicationController::class, 'reset_link'])->name('website.reset-link');
Route::post('/reset-send', [ApplicationController::class, 'reset_send'])->name('website.reset-send');
Route::post('/reset-send-store', [ApplicationController::class, 'reset_send_store'])->name('website.reset-send-store');
Route::post('register', [ApplicationController::class, 'register_store'])->name('website.register-store')->middleware('guest:customer');;
Route::get('verification', [ApplicationController::class, 'showVerificationForm'])->name('verification');
Route::post('verify-otp', [ApplicationController::class, 'verifyOtp'])->name('verify.otp');
Route::post('update-payment-method', [ApplicationController::class, 'updatePaymentMethod'])->name('updatePaymentMethod');
Route::post('profile-update', [ApplicationController::class, 'profileUpdate'])->name('website.profileUpdate')->middleware('auth:customer');
Route::post('/customer/add-balance', [ApplicationController::class, 'addBalance'])->name('customer.addBalance')->middleware(['auth:customer','profile.complete']);
Route::post('/customer/transfer-balance', [CustomerController::class, 'transferBalance'])->name('customer.transferBalance')->middleware(['profile.complete','auth:customer']);
Route::post('/redeem/{product}', [CustomerController::class, 'redeem'])->name('customer.redeem');
Route::post('/redeem-order', [CustomerController::class, 'createOrderFromCashback'])->name('customer.createOrderFromCashback');

Route::post('/cashback/redeem', [CashbackController::class, 'storeRedemption'])->name('redeem.cashback');

// Variant Redemptions Routes (للزبائن)
Route::middleware(['auth:customer'])->group(function () {
    Route::post('/variant-redemptions', [VariantRedemptionController::class, 'store'])->name('variant-redemptions.store');
    Route::get('/api/variant-redemptions/available', [VariantRedemptionController::class, 'availableVariants'])->name('variant-redemptions.available');
    Route::get('/api/variant-redemptions/all-redeemable', [VariantRedemptionController::class, 'allRedeemableVariants'])->name('variant-redemptions.all-redeemable');
    Route::get('/api/variant-redemptions/my', [VariantRedemptionController::class, 'myRedemptions'])->name('variant-redemptions.my');
});

Route::get('/login', [ApplicationController::class, 'login'])->name('website.login');
Route::post('/login', [ApplicationController::class, 'do_login'])->name('website.do-login')->middleware('guest:customer');
Route::get('/admin/login/', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/do-login', [AuthController::class, 'do_login'])->name('do-login')->middleware('guest');
Route::get('/customer-logout', [AuthController::class, 'customer_logout'])->name('customer-logout');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('/google/redirect', [AuthController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');



Route::get('auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('facebook.redirect');
Route::get('auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('facebook.callback');


Route::get('/smileone/get', [SmileOneController::class, 'getRequest']);
Route::post('/smileone/post', [SmileOneController::class, 'postRequest']);



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home', [HomeController::class, 'home'])->name('home');

    Route::middleware('can:users')->group(function () {
        Route::resource('users', UsersController::class);
    });

    Route::middleware('can:reports')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/orders', [ReportController::class, 'ordersReport'])->name('reports.orders');
        Route::get('/reports/top-customers', [ReportController::class, 'topCustomersReport'])->name('reports.top-customers');
        Route::get('/reports/top-products', [ReportController::class, 'topProductsReport'])->name('reports.top-products');
        Route::get('/reports/top-cities', [ReportController::class, 'topCitiesReport'])->name('reports.top-cities');
        Route::get('/reports/used-coupons', [ReportController::class, 'usedCouponsReport'])->name('reports.used-coupons');
        Route::get('/reports/profit', [ReportController::class, 'profitReport'])->name('reports.profit');
    });

    Route::middleware('can:cities')->group(function () {
        Route::resource('cities', CityController::class);
    });

    Route::middleware('can:content')->group(function () {
        Route::delete('/banners/{banner}', [ContentController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners', [ContentController::class, 'store_banner'])->name('banners.store');
        Route::get('/content', [ContentController::class, 'index'])->name('content.index');
        Route::post('/content', [ContentController::class, 'store'])->name('content.store');
    });


    Route::put('/redemptions/{id}/complete', [RedemptionController::class, 'complete'])->name('redemption.complete');
    Route::resource('redemptions', RedemptionController::class);

    // Variant Redemptions Management (للأدمن)
    Route::get('/variant-redemptions', [VariantRedemptionController::class, 'index'])->name('variant-redemptions.index');
    Route::post('/variant-redemptions/{id}/update-status', [VariantRedemptionController::class, 'updateStatus'])->name('variant-redemptions.update-status');
    
    Route::middleware('can:payment_methods')->group(function () {
        Route::patch('payment-methods/{paymentMethod}/toggle-active', [PaymentMethodController::class, 'toggleActive'])->name('payment_methods.toggle_active');
        Route::resource('payment-methods', PaymentMethodController::class);
    });

    Route::middleware('can:customers')->group(function () {
        Route::post('/customers/{customer}/add-balance', [CustomerController::class, 'addBalance'])->name('customers.addBalance');
        Route::post('/customers/{customer}/subtract-balance', [CustomerController::class, 'subtractBalance'])->name('customers.subtractBalance');
        Route::get('/customers/{customer}/balance-history', [CustomerController::class, 'balanceHistory'])->name('customers.balanceHistory');
        Route::resource('customers', CustomerController::class);
        Route::get('/cards/{id}/print', [CardController::class, 'print'])->name('cards.print');
        Route::resource('cards', CardController::class);
    });


    Route::resource('faqs', FaqController::class);


    Route::middleware('can:products')->group(function () {
        Route::post('/variants/toggle-active', [ProductController::class, 'toggleActive'])->name('variants.toggleActive');
        Route::post('/variants/update-smileone-points', [ProductController::class, 'updateSmileonePoints'])->name('variants.updateSmileonePoints');
        Route::post('/products/{product}/variants', [ProductController::class, 'addVariant'])->name('products.addVariant');
        Route::post('/products/{product}/update-image', [ProductController::class, 'updateImage'])->name('products.updateImage');
        Route::get('/products/{product}/get-variants', [ProductController::class, 'getVariants'])->name('getVariants');
        Route::get('/variants/{variant}/edit-variant', [ProductController::class, 'editVariant'])->name('variants.edit');
        Route::put('variants/{variant}', [ProductController::class, 'updateVariant'])->name('variants.update');
        Route::patch('/products/{product}/toggle', [ProductController::class, 'toggleStatus'])->name('products.toggle');
        Route::put('/products/{id}/update-cashback', [ProductController::class, 'updateCashback'])->name('products.updateCashback');
        Route::resource('products', ProductController::class);
        Route::resource('product_categories', ProductCategoryController::class);
    });

    Route::middleware('can:coupons')->group(function () {
        Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::resource('coupons', CouponController::class);
    });


    Route::put('/cashbacks/toggle/{id}', [CashbackController::class, 'toggleStatus']);
    Route::resource('cashbacks', CashbackController::class);


    Route::middleware('can:orders')->group(function () {
        Route::post('orders/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
        Route::post('/toggle-display-on-homepage/{order}', [OrderController::class, 'toggleDisplayOnHomepage']);
        Route::get('/rated-orders', [OrderController::class, 'ratedOrders'])->name('orders.rated');
        Route::resource('orders', OrderController::class);
    });

    Route::middleware('can:discounts')->group(function () {
        Route::resource('discounts', DiscountController::class);
    });
    
    Route::resource('messages', MessageController::class);

    // Account Categories and Accounts Routes
    Route::resource('account-categories', \App\Http\Controllers\AccountCategoryController::class);
    Route::resource('accounts', \App\Http\Controllers\AccountController::class);
    Route::post('accounts/{account}/sell', [\App\Http\Controllers\AccountController::class, 'sell'])->name('accounts.sell');
});

Route::post('/receive-sms', [MessageController::class, 'receive_sms'])->name('r');

Route::get('update-smileone-names', function() {
    $smileOneController = new SmileOneController();
    $products = Product::whereNotNull('smileone_name')->get();
    // request class
    $request = new \Illuminate\Http\Request();
    $request->replace([
        'smileone_name' => 'loveanddeepspace',
        'smileone_id' => 18760,
    ]);
    $productVariants = $smileOneController->getServerList('loveanddeepspace');
    dd($productVariants);

});




Route::get('/smileone/sync-variants', [SmileOneController::class, 'syncSmileOneVariants']);


Route::get('/sync-moogold-products', [MoogoldController::class, 'syncAllMoogoldProducts']);


Route::get('/test-moogold/{id}', function ($id, MoogoldController $moogold) {


    return $moogold->serverList($id);  
});


Route::get('add-product/{id}', function($product_id,$moogold_id) {

});