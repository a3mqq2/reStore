<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * تشغيل الهجرة.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender'); // مصدر الرسالة: Libyana، Almadar، أو غير معروف
            $table->text('message'); // محتوى الرسالة
            $table->timestamp('timestamp')->nullable(); // الطابع الزمني للرسالة
            $table->boolean('status')->default(false); // حالة الرسالة: تم تحديث الرصيد أم لا
            $table->decimal('value', 10, 3)->nullable(); // قيمة التحويل (دينار أو د.ل)
            $table->unsignedBigInteger('customer_id')->nullable(); // معرف الزبون (Foreign Key)
            $table->string('source_number')->nullable(); // رقم المرسل الأصلي
            $table->string('type')->nullable(); // نوع الرسالة: Libyana، Almadar، أو Unknown
            $table->timestamps();

            // إضافة المفتاح الأجنبي إذا كان جدول customers موجودًا
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * عكس الهجرة.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
