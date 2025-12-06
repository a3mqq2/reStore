<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            // قيمة الاسترداد التي يحصل عليها الزبون عند شراء هذا الـ variant
            $table->decimal('redemption_value', 10, 2)->default(0)->after('cashback');

            // هل يمكن استرداد هذا الـ variant؟
            $table->boolean('is_redeemable')->default(false)->after('redemption_value');

            // القيمة المطلوبة للاسترداد (الحد الأدنى لرصيد الاسترداد)
            $table->decimal('redemption_cost', 10, 2)->nullable()->after('is_redeemable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn(['redemption_value', 'is_redeemable', 'redemption_cost']);
        });
    }
};
