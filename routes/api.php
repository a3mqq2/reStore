<?php

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SmileOneController;
use App\Http\Controllers\SupplierController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('payment-methods/{paymentMethod}', [CategoryController::class, 'get_payment_method']);
Route::get('payment-methods', [CategoryController::class, 'get_payment_methods']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('customers', [CustomerController::class, 'index']);
Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);
Route::post('products', [ProductController::class, 'store']);
Route::post('products/{product}', [ProductController::class, 'update']);
Route::post('apply-coupon', [CouponController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders/{order}', [OrderController::class, 'show']);
Route::put('/orders/{order}', [OrderController::class, 'update']);
Route::delete('/orders/{order}', [OrderController::class, 'destroy']);
Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/cart-items', [CartController::class, 'cartItems']);
Route::post('/cart/clear', [CartController::class, 'clearCart']);
Route::get('/items-price', [CartController::class, 'itemsPrice']);
Route::post('/submit-order', [CartController::class, 'submitOrder']);
Route::post('/submit-rating', [OrderController::class, 'submitRating']);
Route::post('/cart/remove/{itemId}', [CartController::class, 'removeItem']);
Route::post('/smileone/role-query', [SmileOneController::class, 'roleQuery']);
Route::post('/smileone/{name}/productlist', [SmileOneController::class, 'getProductList']);
Route::post('/check-provider-balance', [SmileOneController::class, 'checkProviderBalance']);
