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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('question'); // FAQ question
            $table->text('answer'); // FAQ answer
            $table->unsignedInteger('order')->default(0); // For ordering FAQs
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
