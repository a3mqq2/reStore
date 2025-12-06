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
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('secret_number');
            $table->string('serial_number');
            $table->decimal('amount', 8, 2); // Add amount
            $table->enum('status', ['new', 'used'])->default('new');
            $table->unsignedBigInteger('customer_id')->nullable(); // Add nullable customer_id
            $table->timestamp('used_at')->nullable(); // Add used_at timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};
