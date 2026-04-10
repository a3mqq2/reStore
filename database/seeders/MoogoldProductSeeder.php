<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Requirement;
use App\Models\ListItem;
use App\Models\PaymentMethod;
use App\Http\Controllers\MoogoldController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MoogoldProductSeeder extends Seeder
{
    /**
     * تشغيل الـ Seeder
     * استخدام: php artisan db:seed --class=MoogoldProductSeeder
     */
    public function run(): void
    {
        $products = [
            // ['moogold_id' => 2362359, 'name' => 'Mobile Legends (Indonesia)'],
            // ['moogold_id' => 4690648, 'name' => 'Mobile Legends (Malaysia)'],
            ['moogold_id' => 8957883, 'name' => 'Mobile Legends (Philippines)'],
        ];

        foreach ($products as $p) {
            $this->importProduct($p['moogold_id'], $p['name']);
        }
    }

    private function importProduct(int $moogoldId, string $name): void
    {
        $moogold = app(MoogoldController::class);

        $this->command->info("جاري جلب بيانات المنتج من Moogold (ID: {$moogoldId})...");

        $response = $moogold->getProductDetail($moogoldId);

        if ($response->failed()) {
            $this->command->error("فشل الاتصال بـ Moogold API. Status: " . $response->status());
            return;
        }

        $productData = $response->json();

        if (!isset($productData['Product_Name']) || !isset($productData['Variation'])) {
            $this->command->error("بيانات المنتج غير صالحة.");
            $this->command->info("الاستجابة: " . json_encode($productData));
            return;
        }

        DB::beginTransaction();

        try {
            // إنشاء المنتج الرئيسي
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

            // إنشاء المتطلبات (Requirements) من fields
            $this->createRequirements($product, $productData['fields'] ?? []);

            // إنشاء الـ Variants
            $this->createVariants($product, $productData['Variation']);

            DB::commit();

            $this->command->info("تم استيراد المنتج بنجاح!");
            $this->command->info("عدد الـ Variants: " . count($productData['Variation']));

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("حدث خطأ: " . $e->getMessage());
            Log::error('Moogold Seeder Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    /**
     * إنشاء المتطلبات (Requirements) من fields API
     * Mobile Legends fields: ["User ID", "Server ID"]
     */
    private function createRequirements(Product $product, array $fields): void
    {
        foreach ($fields as $field) {
            Requirement::create([
                'product_id' => $product->id,
                'name' => $field,
                'type' => 'text',
            ]);

            $this->command->info("تم إنشاء متطلب: {$field}");
        }
    }

    /**
     * إنشاء الـ Variants
     */
    private function createVariants(Product $product, array $variations): void
    {
        $paymentMethods = PaymentMethod::where('active', 1)->get();

        foreach ($variations as $variantData) {
            $variant = Variant::create([
                'product_id' => $product->id,
                'name' => $variantData['variation_name'],
                'moogold_id' => $variantData['variation_id'],
                'moogold_usd' => $variantData['variation_price'],
            ]);

            // إنشاء أسعار أولية بقيمة 0 لكل طريقة دفع
            foreach ($paymentMethods as $paymentMethod) {
                VariantPrice::create([
                    'variant_id' => $variant->id,
                    'payment_method_id' => $paymentMethod->id,
                    'price' => 0,
                ]);
            }

            $this->command->line("  - {$variant->name} (Moogold ID: {$variant->moogold_id}, USD: {$variant->moogold_usd})");
        }
    }
}
