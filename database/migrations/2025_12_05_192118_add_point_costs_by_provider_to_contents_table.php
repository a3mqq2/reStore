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
            $table->decimal('point_cost_libyana', 10, 4)->nullable()->after('point_cost');
            $table->decimal('point_cost_almadar', 10, 4)->nullable()->after('point_cost_libyana');
            $table->decimal('point_cost_red', 10, 4)->nullable()->after('point_cost_almadar');
            $table->decimal('point_cost_vfcash', 10, 4)->nullable()->after('point_cost_red');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn([
                'point_cost_libyana',
                'point_cost_almadar',
                'point_cost_red',
                'point_cost_vfcash',
            ]);
        });
    }
};
