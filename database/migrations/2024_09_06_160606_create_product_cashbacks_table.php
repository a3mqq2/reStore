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
        Schema::create('cashbacks', function (Blueprint $table) {
            $table->id();
            // الحقول الجديدة للمنتج البسيط
            $table->string('product_name');
            $table->string('product_image')->nullable(); // حقل اختياري للصورة
            $table->text('product_details')->nullable(); // حقل اختياري للتفاصيل
            $table->decimal('amount', 8, 2)->default(0); // القيمة بالنقاط
            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashbacks');
    }
};
