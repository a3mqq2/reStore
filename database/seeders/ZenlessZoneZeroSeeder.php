<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Requirement;
use App\Models\PaymentMethod;
use App\Http\Controllers\MoogoldController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ZenlessZoneZeroSeeder extends Seeder
{
    /**
     * استخدام: php artisan db:seed --class=ZenlessZoneZeroSeeder
     */
    public function run(): void
    {
        $moogoldId = 9477186;
        $name = 'Zenless Zone Zero';

        $moogold = app(MoogoldController::class);

        $this->command->info("جاري جلب بيانات المنتج من Moogold (ID: {$moogoldId})...");

        $response = $moogold->getProductDetail($moogoldId);

        if ($response->failed()) {
            $this->command->error("فشل الاتصال بـ Moogold API. Status: " . $response->status());
            return;
        }

        $productData = $response->json();

        if (!isset($productData['Variation'])) {
            $this->command->error("بيانات المنتج غير صالحة.");
            return;
        }

        DB::beginTransaction();

        try {
            $product = Product::create([
                'name' => $name,
                'description' => $productData['Product_Name'],
                'category_id' => 1,
                'moogold_id' => $moogoldId,
                'moogold_category_id' => 50,
                'active' => true,
                'image' => $productData['Image_URL'] ?? '-',
            ]);

            $this->command->info("تم إنشاء المنتج: {$product->name} (ID: {$product->id})");

            // إنشاء المتطلبات: User ID و Server
            foreach ($productData['fields'] ?? [] as $field) {
                Requirement::create([
                    'product_id' => $product->id,
                    'name' => $field,
                    'type' => 'text',
                ]);
                $this->command->info("تم إنشاء متطلب: {$field}");
            }

            // إنشاء الـ Variants
            $paymentMethods = PaymentMethod::where('active', 1)->get();

            foreach ($productData['Variation'] as $variantData) {
                $variant = Variant::create([
                    'product_id' => $product->id,
                    'name' => $variantData['variation_name'],
                    'moogold_id' => $variantData['variation_id'],
                    'moogold_usd' => $variantData['variation_price'],
                ]);

                foreach ($paymentMethods as $paymentMethod) {
                    VariantPrice::create([
                        'variant_id' => $variant->id,
                        'payment_method_id' => $paymentMethod->id,
                        'price' => 0,
                    ]);
                }

                $this->command->line("  - {$variant->name} (Moogold ID: {$variant->moogold_id}, USD: {$variant->moogold_usd})");
            }

            DB::commit();

            $this->command->info("تم استيراد المنتج بنجاح!");
            $this->command->info("عدد الـ Variants: " . count($productData['Variation']));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("حدث خطأ: " . $e->getMessage());
            Log::error('ZenlessZoneZero Seeder Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }
}
