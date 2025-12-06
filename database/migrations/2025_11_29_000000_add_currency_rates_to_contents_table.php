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
        Schema::table('contents', function (Blueprint $table) {
            $table->decimal('dollar_buy_rate', 10, 2)->nullable()->after('point_cost');
            $table->decimal('dollar_sell_rate', 10, 2)->nullable()->after('dollar_buy_rate');
            $table->decimal('smileone_point_usd', 10, 4)->nullable()->after('dollar_sell_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn(['dollar_buy_rate', 'dollar_sell_rate', 'smileone_point_usd']);
        });
    }
};
