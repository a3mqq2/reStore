<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Models\OrderRequirement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\CancelOrderNotification;
use App\Notifications\OrderStatusNotification;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->filled('customer_name')) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('order_date')) {
            $query->where('order_date', $request->order_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_method_id')) {
            $query->where('payment_method_id', $request->payment_method_id);
        }

        if ($request->filled('min_total_amount')) {
            $query->where('total_amount', '>=', $request->min_total_amount);
        }

        if ($request->filled('max_total_amount')) {
            $query->where('total_amount', '<=', $request->max_total_amount);
        }

        $orders = $query->orderByDesc('id')->paginate(10);

        $paymentMethods = \App\Models\PaymentMethod::where('active',1)->get();

        return view('orders.index', [
            'orders' => $orders,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            Log::info('Store Order Request:', $request->all());

            // Create the order
            $order = new Order();
            $order->customer_id = $request->customer_id;
            $order->payment_method_id = $request->payment_method_id;
            $order->order_date = $request->order_date;
            $order->order_notes = $request->order_notes;
            $order->coupon_id = $request->coupon_id;
            $order->total_amount = $request->total_amount;
            $order->discounted_total = $request->discounted_total;
            $order->from_cashback = $request->from_cashback;
            if($request->from_cashback) {
                $order->status = "under_payment";
            }

            $order->save();

            Log::info('Order created with ID:', ['order_id' => $order->id]);

            // Loop through the products and save them
            foreach ($request->products as $productData) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $productData['product_id'];
                $orderProduct->variant_id = $productData['variant_id'];
                $orderProduct->name = $productData['name'];
                $orderProduct->quantity = $productData['quantity'];
                $orderProduct->variant = $productData['variant'];
                $orderProduct->price = $productData['price'];
                $orderProduct->save();

                Log::info('OrderProduct created with ID:', ['order_product_id' => $orderProduct->id]);

                // Loop through the requirements and save them only if category_id is 1
                if ($productData['category_id'] == 1 && isset($productData['requirements'])) {
                    foreach ($productData['requirements'] as $requirementData) {
                        $orderRequirement = new OrderRequirement();
                        $orderRequirement->order_product_id = $orderProduct->id;
                        $orderRequirement->name = $requirementData['name'];
                        $orderRequirement->value = $requirementData['value'];
                        $orderRequirement->save();

                        Log::info('OrderRequirement created with ID:', ['order_requirement_id' => $orderRequirement->id]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order created successfully.', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating order:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error creating order.', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id, Request $request)
    {
        $order = Order::with(['customer', 'paymentMethod', 'products.requirements'])->findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json(['order' => $order]);
        }
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        DB::beginTransaction();
    
        try {
            Log::info('Update Order Request:', $request->all());

            // Update the order
            $order->customer_id = $request->customer_id;
            $order->payment_method_id = $request->payment_method_id;
            $order->order_date = $request->order_date;
            $order->order_notes = $request->order_notes;
            $order->coupon_id = $request->coupon_id;
            $order->total_amount = $request->total_amount;
            $order->discounted_total = $request->discounted_total;
            $order->status = $request->status;
            $order->save();

            Log::info('Order updated with ID:', ['order_id' => $order->id]);

            // Remove old products and requirements
            foreach ($order->products as $orderProduct) {
                $orderProduct->requirements()->delete();
                $orderProduct->delete();
            }

            // Loop through the products and save them
            foreach ($request->products as $productData) {
                $orderProduct = new OrderProduct();
                $orderProduct->order_id = $order->id;
                $orderProduct->product_id = $productData['product_id'];
                $orderProduct->variant_id = $productData['variant_id'];
                $orderProduct->name = $productData['name'];
                $orderProduct->quantity = $productData['quantity'];
                $orderProduct->variant = $productData['variant'];
                $orderProduct->price = $productData['price'];
                $orderProduct->save();

                Log::info('OrderProduct updated with ID:', ['order_product_id' => $orderProduct->id]);

                // Loop through the requirements and save them
                if (!empty($productData['requirements'])) {
                    foreach ($productData['requirements'] as $requirementData) {
                        $orderRequirement = new OrderRequirement();
                        $orderRequirement->order_product_id = $orderProduct->id;
                        $orderRequirement->name = $requirementData['name'];
                        $orderRequirement->value = $requirementData['value'];
                        $orderRequirement->save();

                        Log::info('OrderRequirement updated with ID:', ['order_requirement_id' => $orderRequirement->id]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating order:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error updating order.', 'error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
    
        try {
            // Remove products and requirements
            foreach ($order->products as $orderProduct) {
                $orderProduct->requirements()->delete();
                $orderProduct->delete();
            }

            // Delete the order
            $order->delete();

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Order deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order:', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error deleting order.', 'error' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:under_payment,canceled,approved',
            'notes' => 'nullable|string',
        ]);

        $order = Order::findOrFail($request->order_id);
        $order->status = $request->status;
        $order->order_notes .= ' ' . $request->notes;
        $order->payment_code = $request->payment_code;
        $order->save();

        // إذا كانت حالة الطلب "مكتمل"، نحدث الكاش باك ورصيد الاسترداد من المتغير المحدد
        if ($order->status == "approved") {
            // نبدأ بحساب إجمالي الكاش باك ورصيد الاسترداد من المتغيرات (variants)
            $totalCashback = 0;
            $totalRedemptionValue = 0;

            foreach ($order->products as $orderProduct) {
                // العثور على المتغير المحدد للمنتج
                $variant = Variant::find($orderProduct->variant_id);
                if ($variant) {
                    // حساب الكاش باك وضربه في كمية المنتج المطلوبة
                    if ($variant->cashback) {
                        $totalCashback += ($variant->cashback * $orderProduct->quantity);
                    }
                    // حساب قيمة الاسترداد وضربها في كمية المنتج المطلوبة
                    if ($variant->redemption_value > 0) {
                        $totalRedemptionValue += ($variant->redemption_value * $orderProduct->quantity);
                    }
                }
            }

            // تحديث الكاش باك الخاص بالزبون
            if ($totalCashback > 0) {
                $order->customer->cashback += $totalCashback;
            }

            // تحديث رصيد الاسترداد الخاص بالزبون
            if ($totalRedemptionValue > 0) {
                $order->customer->redemption_balance += $totalRedemptionValue;
            }

            $order->customer->save();

            // إشعار الزبون بالموافقة على الطلب
            // $order->customer->notify(new OrderStatusNotification($order, 'شكرا لشرائك من خدماتنا'));
        }

        // إذا تم إلغاء الطلب، يتم إشعار الزبون
        if ($order->status == "canceled") {
            // $order->customer->notify(new CancelOrderNotification($order, 'لم تكتمل عملية الشراء'));
        }

        return redirect()->route('orders.index')->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    
    public function submitRating(Request $request)
    {
        $rating = $request->input('rating');
        $notes = $request->input('notes');
        $order = Order::where('customer_id', $request->customer_id)->latest()->first();
       
        if($order) {
            $order->rating = $rating;
            $order->rating_notes = $notes;
            $order->save();
        }

        return response()->json(['success' => true]);
    }

    public function ratedOrders()
    {
        // Retrieve all orders that have a rating and include customer relation
        $orders = Order::whereNotNull('rating')->with('customer')->paginate();

        return view('orders.rated', compact('orders'));
    }

    /**
     * Toggle display on homepage.
     */
    public function toggleDisplayOnHomepage(Request $request, Order $order)
    {
        // Toggle the 'show_on_homepage' field
        $order->show_on_homepage = !$order->show_on_homepage;
        $order->save();

        return response()->json([
            'status' => 'success',
            'message' => 'تم تعديل الحالة بنجاح!'
        ]);
    }
    
}
