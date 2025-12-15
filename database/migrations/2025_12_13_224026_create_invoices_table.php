<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->cascadeOnDelete();

            // رقم الفاتورة الظاهر للعميل
            $table->string('invoice_number')->unique();

            // نسخة الفاتورة (Revision)
            $table->unsignedInteger('version')->default(1);

            // مسار PDF داخل storage (public disk)
            $table->string('pdf_path')->nullable();

            // تاريخ إصدار الفاتورة
            $table->timestamp('issued_at')->nullable();

            // Snapshot بيانات مالية/عنوان وقت الإنشاء (عشان لو العنوان اتغير بعدين)
            $table->json('snapshot')->nullable();

            // أي بيانات إضافية
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->unique(['order_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
