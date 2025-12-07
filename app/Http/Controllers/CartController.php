<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Mail\OrderVoucherMail;
use App\Models\OrderRequirement;
use App\Mail\NewOrderNotification;
use Illuminate\Support\Facades\DB;
use App\Models\CartItemRequirement;
use App\Models\ListItem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cookie;
use App\Notifications\OrderStatusNotification;
use App\Models\BalanceTransaction;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'requirements' => 'nullable|array',
            'payment_method_id' => 'required',
            'variant_id' => 'nullable|exists:variants,id', // variant_id is nullable
        ]);

        
        
        $requirements = $request->input('requirements', []);
    
        // تحقق إذا كان المنتج يحتوي على متغيرات
        $hasVariants = Product::find($request->product_id)->variants()->exists();
    
        // إذا كان المنتج لا يحتوي على متغيرات، يجب أن يكون variant_id = null
        $variantId = $hasVariants ? $request->variant_id : null;
    

        $sessionId = $request->session_id ?? Cookie::get('guest_session_id');
        $customerId = $request->customer_id;
        // تحقق من وجود عنصر في السلة بنفس المنتج والمتغير للعميل أو الضيف (استناداً إلى session_id أو customer_id)
        $cartItem = CartItem::where(function($query) use ($customerId, $sessionId) {
                if ($customerId) {
                    $query->where('customer_id', $customerId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('product_id', $request->product_id)
            ->where('variant_id', $variantId)
            ->first();
    
        if ($cartItem) {
            // تحديث الكمية إذا كان العنصر موجودًا
            $cartItem->update([
                'quantity' => $request->quantity,
            ]);
        } else {
            // إنشاء عنصر جديد في السلة إذا لم يكن موجودًا
            $cartItem = CartItem::create([
                'customer_id' => $customerId,
                'session_id' => $customerId ? null : $sessionId, // إذا كان العميل مسجل الدخول، session_id يكون null
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'variant_id' => $variantId,
            ]);
        }
    
        // حفظ المتطلبات في الجدول الجديد
        if ($request->has('requirements')) {
            // حذف المتطلبات القديمة لهذا العنصر في السلة
            CartItemRequirement::where('cart_item_id', $cartItem->id)->delete();
    
            foreach ($request->requirements as $requirementId => $value) {
                CartItemRequirement::create([
                    'cart_item_id' => $cartItem->id,
                    'requirement_id' => $requirementId,
                    'value' => $value,
                ]);
            }
        }
    
        // حفظ طريقة الدفع في الكوكيز
        Cookie::queue('payment_method', $request->payment_method_id, 60 * 24 * 30);
    
        // إرجاع الاستجابة مع sessionId لتأكيد الكوكيز
        return response()->json([
            'message' => 'تمت إضافة المنتج إلى السلة بنجاح',
            'cartItem' => $cartItem,
            'sessionId' => $sessionId, // إرجاع sessionId للمستخدم للتحقق
        ]);
    }
    


    public function cartItems(Request $request)
    {
        
        $customerId = null;
        if($request->customer_id) {
            $customerId = $request->customer_id;
        }

        $sessionId = $request->session_id ?? Cookie::get('guest_session_id');
        $paymentMethod = $request->paymentMethod ?? null;


        $currency = null;
        if ($paymentMethod) {
            $paymentMethodRecord = PaymentMethod::find($paymentMethod);
            if ($paymentMethodRecord) {
                $currency = $paymentMethodRecord->currency;
            }
        }

        $paymentMethods = PaymentMethod::where('active',1)->get();


        // جلب العناصر من السلة بناءً على session_id للضيف أو customer_id للمستخدم المسجل
        $cartItems = CartItem::where(function($query) use ($customerId, $sessionId) {

                

                if ($sessionId) {
                    $query->where('session_id', $sessionId);
                }

                if($customerId) {
                    $query->orWhere('customer_id', $customerId);
                }

                
            })
            ->with(['product', 'variant', 'product.variants', 'variant.prices', 'requirements', 'requirements.requirement'])
            ->get();

        // حساب إجمالي السعر بناءً على الكمية وطريقة الدفع
        $total = $cartItems->sum(function ($item) use ($paymentMethod) {
            if ($item->variant) {
                // جلب السعر المناسب للمتغير بناءً على طريقة الدفع
                $priceRecord = $item->variant->prices->firstWhere('payment_method_id', $paymentMethod);
                $price = $priceRecord ? $priceRecord->price : $item->variant->price;
            } else {
                // جلب السعر المناسب للمنتج بناءً على طريقة الدفع
                $priceRecord = $item->product->prices->firstWhere('payment_method_id', $paymentMethod);
                $price = $priceRecord ? $priceRecord->price : $item->product->price;
            }

            return $item->quantity * $price; // حساب الإجمالي بناءً على السعر والكمية
        });

        // إرجاع الاستجابة كـ JSON
        return response()->json([
            'items' => $cartItems,
            'total' => $total,
            'paymentMethod' => $paymentMethod,
            'currency' => $currency,
            'paymentMethods' => $paymentMethods,
            'myBalance' => Customer::find($customerId)->balance ?? 0, // إضافة رصيد المستخدم
            'isLoggedIn' => auth()->check(), // إضافة حالة تسجيل الدخول
        ]);
    }



    
    public function clearCart(Request $request)
    {
        $sessionId = $request->session_id;
        CartItem::where('session_id', $sessionId)->orWhere('customer_id', $request->customer_id)->delete();

        return response()->json(['message' => 'Cart cleared successfully']);
    }


    public function removeItem($itemId)
    {
        $customerId = auth()->id();

        $cartItem = CartItem::where('id', $itemId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found or does not belong to the customer'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Item removed successfully']);
    }

    public function updateItemQuantity($itemId, Request $request)
    {
        $customerId = auth()->id();

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $itemId)
            ->where('customer_id', $customerId)
            ->first();

        if (!$cartItem) {
            return response()->json(['error' => 'Item not found or does not belong to the customer'], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['message' => 'Item quantity updated successfully']);
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'couponCode' => 'required|string',
        ]);

        return response()->json(['message' => 'Coupon applied successfully']);
    }

    public function itemsPrice(Request $request) {
        $customerId = auth()->id();
        $cartItems = CartItem::where('customer_id', $customerId)
        ->orWhere('session_id', Cookie::get('guest_session_id'))
        ->with(['product','product.discount', 'variant', 'variant.prices', 'requirements', 'requirements.requirement'])
        ->get();

        $total = 0;
        foreach ($cartItems as $cartItem) {
            $price = $cartItem->variant->prices->where('payment_method_id', $request->payment_method_id)->first();
            $cartItem->price = $price->price * $cartItem->quantity;
            $total += floatval($price->price) * floatval($cartItem->quantity);
        }

        return response()->json(['total' => $total], 200);
    }

  public function submitOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $order = new Order();
            $order->customer_id = $request->customer_id;
            $order->payment_method_id = $request->paymentMethod;
            $order->order_date = now();
            $order->order_notes = $request->notes;
            $order->coupon_id = $request->couponCode ? $this->getCouponId($request->couponCode) : null;
            $order->total_amount = $request->totalAmount;
            $order->discounted_total = $request->discountedTotal;
            $order->payment_notes = $request->notes;
            $order->session_id = auth()->check() ? null : $request->session;
            $order->phone = $request->phone;
            $order->name = $request->name;

            $balanceBefore = null;
            $balanceUser = null;

            // Charge customer wallet if payment method is wallet (ID = 1)
            // This uses discounted_total which is calculated from calculated_price (sell price)
            if ($request->paymentMethod == 1) {
                $user = Customer::find($request->customer_id);
                if ($user->balance < $order->discounted_total) {
                    return response()->json(['success' => false, 'message' => 'الرصيد غير كاف في المحفظة.'], 400);
                }

                $balanceBefore = $user->balance;
                $user->decrement('balance', $order->discounted_total);
                $user->save();
                $balanceUser = $user;
                $order->status = 'under_payment';
            }

            $order->save();

            if ($balanceUser && $balanceBefore !== null) {
                BalanceTransaction::create([
                    'customer_id' => $balanceUser->id,
                    'type' => 'order_payment',
                    'amount' => $order->discounted_total,
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceUser->balance,
                    'description' => 'دفع طلب رقم #' . $order->id,
                    'related_order_id' => $order->id,
                ]);
            }

            foreach (json_decode($request->cartItems, true) as $productData) {

                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $productData['product']['id'];
                $orderProduct->variant_id = $productData['variant_id'];
                $orderProduct->name = $productData['product']['name'];
                $orderProduct->quantity = $productData['quantity'];
                // price is calculated_price (sell price) from variant
                $orderProduct->price = $productData['price'];
                // cost is calculated_cost (buy price) from variant - used for SmileOne/MooGold
                $orderProduct->cost = $productData['cost'] ?? null;
                $orderProduct->variant = $productData['variant_id'];
                $orderProduct->save();

                if ($request->paymentMethod == 1 && $orderProduct->product->moogold_id && $productData['product']['category_id'] == 2) {

                    $moogoldRequest = new Request([
                        'category' => 2,
                        'product_id' => $orderProduct->variantObj?->moogold_id ?? $orderProduct->product->moogold_id,
                        'quantity' => $orderProduct->quantity,
                    ]);

                    $moogoldController = app(\App\Http\Controllers\MoogoldController::class);
                    $response = $moogoldController->createOrder($moogoldRequest);

                    if ($response->status() != 200) {
                        DB::rollBack();
                        return response()->json(['success' => false], 422);
                    }

                    $moogoldOrderId = $response->json('order_id');

                    if (!$moogoldOrderId) {
                        DB::rollBack();
                        return response()->json(['success' => false], 422);
                    }

                    $detailResponse = $moogoldController->getOrderDetail($moogoldOrderId);

                    $status = $detailResponse->json('order_status');
                    $itemData = $detailResponse->json('item.0') ?? [];
                    $vouchers = $itemData['voucher_code'] ?? [];

                    if ($status === 'completed' && !empty($vouchers)) {

                        $orderProduct->voucher_codes = json_encode($vouchers, JSON_UNESCAPED_UNICODE);

                        if (!$orderProduct->email_sent_at) {
                            Mail::to($order->customer->email)
                                ->queue(new \App\Mail\OrderVoucherMail(
                                    $order,
                                    $orderProduct,
                                    $vouchers
                                ));
                            $orderProduct->email_sent_at = now();
                        }

                        $orderProduct->save();
                    }
                }
            }

            CartItem::where('customer_id', auth()->id())->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }


    private function getCouponId($couponCode)
    {
        $coupon = \App\Models\Coupon::where('code', $couponCode)->first();
        return $coupon ? $coupon->id : null;
    }

    private function calculateTotalAmount($cartItems)
    {
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
