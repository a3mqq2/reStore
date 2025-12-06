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
        Schema::table('coupons', function (Blueprint $table) {
            $table->enum('discount_type', ['percentage', 'amount'])->default('percentage'); // Discount type
            $table->decimal('discount_amount', 8, 2)->nullable(); // Discount amount
            $table->boolean('active')->default(1); // Active status
            $table->unsignedBigInteger('product_id')->nullable(); // Product ID
            $table->unsignedBigInteger('variant_id')->nullable(); // Variant ID
            $table->decimal('discount_percentage', 5, 2)->nullable()->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
