<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // صاحب الطلب
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // عنوان الشحن المستخدم
            $table->foreignId('address_id')
                  ->constrained('user_addresses')
                  ->restrictOnDelete();

            // طريقة الشحن
            $table->foreignId('shipping_method_id')
                  ->constrained('shipping_methods')
                  ->restrictOnDelete();

            // قيم مالية
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            // الدفع
            $table->string('payment_method')->default('paypal'); // paypal / omnipal ...
            $table->string('payment_status')->default('pending'); // pending / paid / failed
            $table->string('payment_reference')->nullable();      // رقم العملية من PayPal بعدين

            // حالة الطلب العامة
            $table->string('status')->default('pending'); // pending / processing / shipped / completed / cancelled

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
