<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Requirement;
use App\Models\ListItem;
use App\Models\PaymentMethod;
use App\Http\Controllers\SmileOneController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmileOneProductSeeder extends Seeder
{
    /**
     * تشغيل الـ Seeder
     * استخدام: php artisan db:seed --class=SmileOneProductSeeder -- --smileone_id=mobilelegends
     */
    public function run(): void
    {
        // الحصول على smileone_name من الـ arguments
        $smileone_name = $this->command->option('smileone_id') ?? $this->command->argument('smileone_id') ?? null;

        if (!$smileone_name) {
            $this->command->error('يجب تحديد smileone_id. مثال: php artisan db:seed --class=SmileOneProductSeeder');
            $this->command->info('أو استخدم الأمر التالي:');
            $this->command->info('php artisan smileone:import {smileone_name}');
            return;
        }

        $this->importSmileOneProduct($smileone_name);
    }

    /**
     * استيراد منتج من SmileOne
     */
    public function importSmileOneProduct(string $smileone_name): ?Product
    {
        $smileone = new SmileOneController();

        $this->command->info("جاري جلب بيانات المنتج: {$smileone_name}...");

        // جلب قائمة المنتجات (variants)
        $productListResponse = $smileone->getProductList($smileone_name);

        if (!isset($productListResponse['data']) || empty($productListResponse['data'])) {
            $this->command->error("لم يتم العثور على منتجات لـ: {$smileone_name}");
            $this->command->info("الاستجابة: " . json_encode($productListResponse));
            return null;
        }

        // جلب قائمة السيرفرات (للـ requirements)
        $serverListResponse = $smileone->getServerList($smileone_name);

        DB::beginTransaction();

        try {
            // إنشاء المنتج الرئيسي
            $product = Product::create([
                'name' => $this->getProductDisplayName($smileone_name),
                'description' => "منتج {$smileone_name} من SmileOne",
                'category_id' => 1, // الشحن عن طريق ID
                'smileone_name' => $smileone_name,
                'smileone_id' => $productListResponse['data'][0]['id'] ?? null,
                'active' => true,
                'image'=> '-',
            ]);

            $this->command->info("تم إنشاء المنتج: {$product->name} (ID: {$product->id})");

            // إنشاء المتطلبات (Requirements)
            $this->createRequirements($product, $serverListResponse);

            // إنشاء الـ Variants
            $this->createVariants($product, $productListResponse['data']);

            DB::commit();

            $this->command->info("تم استيراد المنتج بنجاح!");
            $this->command->info("عدد الـ Variants: " . count($productListResponse['data']));

            return $product;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("حدث خطأ: " . $e->getMessage());
            Log::error('SmileOne Seeder Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return null;
        }
    }

    /**
     * إنشاء المتطلبات (Requirements)
     */
    private function createRequirements(Product $product, array $serverListResponse): void
    {
        // إنشاء متطلب الـ User ID
        $userIdRequirement = Requirement::create([
            'product_id' => $product->id,
            'name' => 'معرف اللاعب (User ID)',
            'type' => 'text',
        ]);
        $this->command->info("تم إنشاء متطلب: معرف اللاعب (User ID)");

        // إذا كان هناك سيرفرات، أنشئ متطلب اختيار السيرفر
        if (isset($serverListResponse['data']) && !empty($serverListResponse['data'])) {
            $serverRequirement = Requirement::create([
                'product_id' => $product->id,
                'name' => 'السيرفر (Server)',
                'type' => 'list',
            ]);

            foreach ($serverListResponse['data'] as $server) {
                ListItem::create([
                    'requirement_id' => $serverRequirement->id,
                    'item' => $server['name'] ?? $server['id'],
                ]);
            }

            $this->command->info("تم إنشاء متطلب: السيرفر (" . count($serverListResponse['data']) . " سيرفر)");
        }
    }

    /**
     * إنشاء الـ Variants
     */
    private function createVariants(Product $product, array $variants): void
    {
        // الحصول على جميع طرق الدفع النشطة
        $paymentMethods = PaymentMethod::where('active', 1)->get();

        foreach ($variants as $variantData) {
            $variant = Variant::create([
                'product_id' => $product->id,
                'name' => $variantData['name'] ?? $variantData['spu'],
                'smileone_id' => $variantData['id'],
                'smileone_points' => $variantData['price'] ?? 0,
                'is_active' => true,
            ]);

            // إنشاء أسعار أولية بقيمة 0 لكل طريقة دفع
            foreach ($paymentMethods as $paymentMethod) {
                VariantPrice::create([
                    'variant_id' => $variant->id,
                    'payment_method_id' => $paymentMethod->id,
                    'price' => 0,
                ]);
            }

            $this->command->line("  - تم إنشاء Variant: {$variant->name} (SmileOne ID: {$variant->smileone_id})");
        }
    }

    /**
     * الحصول على اسم العرض للمنتج
     */
    private function getProductDisplayName(string $smileone_name): string
    {
        $names = [
            'mobilelegends' => 'Mobile Legends',
            'pubgmobile' => 'PUBG Mobile',
            'freefire' => 'Free Fire',
            'genshinimpact' => 'Genshin Impact',
            'loveanddeepspace' => 'Love and Deepspace',
            'honkaistarrail' => 'Honkai: Star Rail',
            'codmobile' => 'Call of Duty Mobile',
        ];

        return $names[$smileone_name] ?? ucwords(str_replace(['_', '-'], ' ', $smileone_name));
    }
}
