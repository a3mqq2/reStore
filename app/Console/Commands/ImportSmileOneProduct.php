<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantPrice;
use App\Models\Requirement;
use App\Models\ListItem;
use App\Models\PaymentMethod;
use App\Http\Controllers\SmileOneController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportSmileOneProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'smileone:import {smileone_name : اسم المنتج في SmileOne (مثل: mobilelegends, pubgmobile)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'استيراد منتج من SmileOne وإنشاء المنتج والـ Variants والـ Requirements تلقائياً';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $smileone_name = $this->argument('smileone_name');

        $this->info("جاري استيراد المنتج: {$smileone_name}");
        $this->info("=====================================");

        $smileone = new SmileOneController();

        // جلب قائمة المنتجات (variants)
        $this->info("جاري جلب قائمة المنتجات...");
        $productListResponse = $smileone->getProductList($smileone_name);

        if (!isset($productListResponse['data']) || empty($productListResponse['data'])) {
            $this->error("لم يتم العثور على منتجات لـ: {$smileone_name}");
            $this->info("الاستجابة: " . json_encode($productListResponse));
            return 1;
        }

        $this->info("تم العثور على " . count($productListResponse['data']) . " variant");

        // عرض هيكل البيانات للتشخيص
        if ($this->option('verbose')) {
            $this->line("هيكل البيانات:");
            $this->line(json_encode($productListResponse['data'][0] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        // جلب قائمة السيرفرات (للـ requirements)
        $this->info("جاري جلب قائمة السيرفرات...");
        $serverListResponse = $smileone->getServerList($smileone_name);

        if (isset($serverListResponse['data']) && !empty($serverListResponse['data'])) {
            $this->info("تم العثور على " . count($serverListResponse['data']) . " سيرفر");
        } else {
            $this->warn("لا توجد سيرفرات لهذا المنتج");
        }

        // التأكد قبل الاستيراد
        if (!$this->confirm('هل تريد المتابعة في إنشاء المنتج؟')) {
            $this->info('تم الإلغاء.');
            return 0;
        }

        DB::beginTransaction();

        try {
            // إنشاء المنتج الرئيسي
            $product = Product::create([
                'name' => $this->getProductDisplayName($smileone_name),
                'description' => "منتج {$smileone_name} من SmileOne",
                'category_id' => 1, // الشحن عن طريق ID
                'smileone_name' => $smileone_name,
                'active' => true,
                'image' => '-',
            ]);

            $this->info("تم إنشاء المنتج: {$product->name} (ID: {$product->id})");

            // إنشاء المتطلبات (Requirements)
            $this->createRequirements($product, $serverListResponse);

            // إنشاء الـ Variants
            $this->createVariants($product, $productListResponse['data']);

            DB::commit();

            $this->newLine();
            $this->info("=====================================");
            $this->info("تم استيراد المنتج بنجاح!");
            $this->info("- اسم المنتج: {$product->name}");
            $this->info("- معرف المنتج: {$product->id}");
            $this->info("- SmileOne Name: {$product->smileone_name}");
            $this->info("- عدد الـ Variants: " . count($productListResponse['data']));
            $this->newLine();
            $this->warn("ملاحظة: الأسعار تم تعيينها بقيمة 0. يجب تحديثها من لوحة التحكم.");

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("حدث خطأ: " . $e->getMessage());
            Log::error('SmileOne Import Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return 1;
        }
    }

    /**
     * إنشاء المتطلبات (Requirements)
     */
    private function createRequirements(Product $product, array $serverListResponse): void
    {
        // إنشاء متطلب الـ User ID
        Requirement::create([
            'product_id' => $product->id,
            'name' => 'معرف اللاعب (User ID)',
            'type' => 'text',
        ]);
        $this->line("  + تم إنشاء متطلب: معرف اللاعب (User ID)");

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

            $this->line("  + تم إنشاء متطلب: السيرفر (" . count($serverListResponse['data']) . " سيرفر)");
        }
    }

    /**
     * إنشاء الـ Variants
     */
    private function createVariants(Product $product, array $variants): void
    {
        // الحصول على جميع طرق الدفع النشطة
        $paymentMethods = PaymentMethod::where('active', 1)->get();

        $this->info("جاري إنشاء الـ Variants...");

        $bar = $this->output->createProgressBar(count($variants));
        $bar->start();

        $counter = 0;
        foreach ($variants as $index => $variantData) {
            $counter++;

            // إذا كان variantData ليس مصفوفة، تخطاه
            if (!is_array($variantData)) {
                $this->warn("تخطي البيانات غير الصالحة: " . json_encode($variantData));
                $bar->advance();
                continue;
            }

            // محاولة الحصول على المعرف من مفاتيح مختلفة
            $smileoneId = $variantData['id'] ?? $variantData['product_id'] ?? $variantData['productid'] ?? $variantData['sku'] ?? $index ?? $counter;
            $variantName = $variantData['name'] ?? $variantData['spu'] ?? $variantData['product_name'] ?? 'Variant ' . $counter;
            $points = $variantData['price'] ?? $variantData['smile_price'] ?? $variantData['cost'] ?? 0;

            $variant = Variant::create([
                'product_id' => $product->id,
                'name' => $variantName,
                'smileone_id' => $smileoneId,
                'smileone_points' => $points,
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

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
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
            'valorant' => 'Valorant',
            'clashofclans' => 'Clash of Clans',
            'clashroyale' => 'Clash Royale',
            'brawlstars' => 'Brawl Stars',
        ];

        return $names[$smileone_name] ?? ucwords(str_replace(['_', '-'], ' ', $smileone_name));
    }
}
