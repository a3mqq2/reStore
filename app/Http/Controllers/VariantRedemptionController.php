<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderRequirement;
use App\Models\PaymentMethod;
use App\Models\Requirement;
use App\Models\VariantRedemption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VariantRedemptionController extends Controller
{
    /**
     * عرض قائمة الـ variants القابلة للاسترداد للزبون
     */
    public function availableVariants()
    {
        $customer = auth('customer')->user();

        $redeemableVariants = Variant::where('is_redeemable', true)
            ->where('is_active', true)
            ->where('redemption_cost', '<=', $customer->redemption_balance)
            ->with('product')
            ->get();

        return response()->json([
            'success' => true,
            'redemption_balance' => $customer->redemption_balance,
            'variants' => $redeemableVariants
        ]);
    }

    /**
     * عرض جميع الـ variants القابلة للاسترداد (بغض النظر عن رصيد الزبون)
     */
    public function allRedeemableVariants()
    {
        $customer = auth('customer')->user();

        $redeemableVariants = Variant::where('is_redeemable', true)
            ->where('is_active', true)
            ->with('product')
            ->get();

        return response()->json([
            'success' => true,
            'redemption_balance' => $customer->redemption_balance,
            'variants' => $redeemableVariants
        ]);
    }

    /**
     * إنشاء طلب استرداد جديد (ينشئ طلب Order فعلي)
     */
    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:variants,id',
            'notes' => 'nullable|string|max:500',
            'requirements' => 'nullable|array',
        ]);

        $customer = auth('customer')->user();
        $variant = Variant::with(['product', 'product.requirements', 'product.requirements.listItems'])->findOrFail($request->variant_id);
        $product = $variant->product;

        // التحقق من أن الـ variant قابل للاسترداد
        if (!$variant->is_redeemable) {
            return back()->with('error', 'هذا المنتج غير قابل للاسترداد');
        }

        // التحقق من أن الـ variant نشط
        if (!$variant->is_active) {
            return back()->with('error', 'هذا المنتج غير متاح حالياً');
        }

        // التحقق من رصيد الاسترداد الكافي
        if ($customer->redemption_balance < $variant->redemption_cost) {
            return back()->with('error', 'رصيد الاسترداد غير كافي. تحتاج إلى ' . $variant->redemption_cost . ' نقطة');
        }

        DB::beginTransaction();
        try {
            // خصم رصيد الاسترداد
            $customer->redemption_balance -= $variant->redemption_cost;
            $customer->save();

            // الحصول على طريقة دفع افتراضية (الرصيد أو أول طريقة متاحة)
            $paymentMethod = PaymentMethod::where('active', 1)->first();

            // إنشاء الطلب (مثل submitOrder في CartController)
            $order = new Order();
            $order->customer_id = $customer->id;
            $order->payment_method_id = $paymentMethod->id;
            $order->order_date = now();
            $order->order_notes = 'طلب استرداد' . ($request->notes ? ' - ' . $request->notes : '');
            $order->total_amount = 0;
            $order->discounted_total = 0;
            $order->payment_notes = 'تم الدفع من رصيد الاسترداد (' . $variant->redemption_cost . ' نقطة)';
            $order->status = 'new';
            $order->save();

            // إضافة المنتج للطلب
            $orderProduct = new OrderProduct();
            $orderProduct->order_id = $order->id;
            $orderProduct->product_id = $variant->product_id;
            $orderProduct->variant_id = $variant->id;
            $orderProduct->variant = $variant->id; // حقل variant المطلوب
            $orderProduct->name = $product->name . ' - ' . $variant->name;
            $orderProduct->quantity = 1;
            $orderProduct->price = 0;
            $orderProduct->cost = 0;
            $orderProduct->save();

            // حفظ المتطلبات
            $requirementsData = [];
            if ($request->has('requirements') && $product->category_id == 1) {
                foreach ($request->requirements as $requirementId => $value) {
                    $requirement = Requirement::find($requirementId);
                    if ($requirement) {
                        $orderRequirement = new OrderRequirement();
                        $orderRequirement->order_product_id = $orderProduct->id;
                        $orderRequirement->name = $requirement->name;
                        $orderRequirement->value = $value;
                        $orderRequirement->save();

                        $requirementsData[$requirement->name] = $value;
                    }
                }
            }

            // استدعاء Moogold أو SmileOne إذا كان المنتج يدعمهم
            if ($product->moogold_id) {
                // Moogold
                if ($product->category_id == 2) {
                    // كروت (Vouchers)
                    $moogold_request = new Request([
                        'category'   => 2,
                        'product_id' => $variant->moogold_id ?? $product->moogold_id,
                        'quantity'   => 1,
                    ]);

                    $moogoldController = app(MoogoldController::class);
                    $response = $moogoldController->createOrder($moogold_request);

                    if ($response->status() != 200) {
                        DB::rollBack();
                        Log::error('خطأ في إنشاء طلب Moogold:', ['error' => $response->json()]);
                        // إرجاع الرصيد للعميل
                        $customer->redemption_balance += $variant->redemption_cost;
                        $customer->save();
                        return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . ($response->json()['message'] ?? 'خطأ غير معروف'));
                    }

                    $orderId = $response->json('order_id');
                    $detailResponse = $moogoldController->getOrderDetail($orderId);

                    if (!$detailResponse->failed()) {
                        $itemData = $detailResponse->json('item.0') ?? [];
                        $vouchers = $itemData['voucher_code'] ?? [];
                        $orderProduct->voucher_codes = json_encode($vouchers, JSON_UNESCAPED_UNICODE);
                        $orderProduct->save();
                    }

                    $order->status = 'approved';
                    $order->save();

                } elseif ($product->category_id == 1) {
                    // شحن مباشر (Direct Top-up)
                    $moogold_request = new Request([
                        'category'   => 1,
                        'product-id' => $variant->moogold_id ?? $product->moogold_id,
                        'quantity'   => 1,
                    ]);

                    foreach ($requirementsData as $name => $value) {
                        $moogold_request->merge([$name => $value]);
                    }

                    $moogoldController = app(MoogoldController::class);
                    $response = $moogoldController->createOrder($moogold_request);

                    if ($response->failed()) {
                        DB::rollBack();
                        Log::error('خطأ في إنشاء طلب Moogold:', ['error' => $response->json()]);
                        $customer->redemption_balance += $variant->redemption_cost;
                        $customer->save();
                        return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . ($response->json()['message'] ?? 'خطأ غير معروف'));
                    }

                    $order->status = 'approved';
                    $order->save();
                }

            } elseif ($product->smileone_name) {
                // SmileOne
                $id = $requirementsData[array_keys($requirementsData)[0]] ?? null;
                $server = $requirementsData[array_keys($requirementsData)[1]] ?? 1;

                if (!$id) {
                    DB::rollBack();
                    $customer->redemption_balance += $variant->redemption_cost;
                    $customer->save();
                    return back()->with('error', 'يرجى إدخال معرف اللاعب');
                }

                $smileoneController = new SmileOneController();
                $smileRequest = new Request([
                    'userid'    => $id,
                    'zoneid'    => $server,
                    'product'   => $product->smileone_name,
                    'productid' => $variant->smileone_id,
                ]);

                $response = $smileoneController->createPurchase($smileRequest);

                if ($response['status'] != 200) {
                    DB::rollBack();
                    Log::error('خطأ في إنشاء طلب SmileOne:', ['error' => $response['message']]);
                    $customer->redemption_balance += $variant->redemption_cost;
                    $customer->save();
                    return back()->with('error', 'حدث خطأ أثناء إنشاء الطلب: ' . $response['message']);
                }

                $order->status = 'approved';
                $order->save();
            }

            // إنشاء سجل الاسترداد مع ربطه بالطلب
            VariantRedemption::create([
                'customer_id' => $customer->id,
                'variant_id' => $variant->id,
                'order_id' => $order->id,
                'amount_used' => $variant->redemption_cost,
                'notes' => $request->notes,
                'status' => $order->status === 'approved' ? 'completed' : 'pending',
            ]);

            DB::commit();

            $message = $order->status === 'approved'
                ? 'تم تنفيذ طلب الاسترداد بنجاح! رقم الطلب: #' . $order->id
                : 'تم إرسال طلب الاسترداد بنجاح! رقم الطلب: #' . $order->id;

            return redirect()->route('customer.profile')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            // إرجاع الرصيد في حالة الخطأ
            $customer->redemption_balance += $variant->redemption_cost;
            $customer->save();
            Log::error('خطأ في طلب الاسترداد:', ['error' => $e->getMessage()]);
            return back()->with('error', 'حدث خطأ أثناء معالجة الطلب: ' . $e->getMessage());
        }
    }

    /**
     * عرض سجل استردادات الزبون
     */
    public function myRedemptions()
    {
        $customer = auth('customer')->user();

        $redemptions = VariantRedemption::where('customer_id', $customer->id)
            ->with('variant.product')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'redemption_balance' => $customer->redemption_balance,
            'redemptions' => $redemptions
        ]);
    }

    /**
     * عرض جميع طلبات الاسترداد (للأدمن)
     */
    public function index()
    {
        $redemptions = VariantRedemption::with(['customer', 'variant.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('variant_redemptions.index', compact('redemptions'));
    }

    /**
     * تحديث حالة طلب الاسترداد (للأدمن)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $redemption = VariantRedemption::findOrFail($id);
        $oldStatus = $redemption->status;

        // إذا تم إلغاء الطلب، إرجاع الرصيد للزبون
        if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
            $customer = $redemption->customer;
            $customer->redemption_balance += $redemption->amount_used;
            $customer->save();
        }

        // إذا تم إعادة تفعيل طلب ملغي، خصم الرصيد مجدداً
        if ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
            $customer = $redemption->customer;
            if ($customer->redemption_balance < $redemption->amount_used) {
                return back()->with('error', 'رصيد الزبون غير كافي لإعادة تفعيل الطلب');
            }
            $customer->redemption_balance -= $redemption->amount_used;
            $customer->save();
        }

        $redemption->status = $request->status;
        $redemption->save();

        return back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}
