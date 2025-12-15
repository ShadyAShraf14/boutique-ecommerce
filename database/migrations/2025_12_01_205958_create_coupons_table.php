<?php

// database/migrations/2025_12_01_000000_create_coupons_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent']); // خصم ثابت أو نسبة
            $table->decimal('value', 10, 2);
            $table->decimal('min_order_total', 10, 2)->nullable(); // أقل إجمالي طلب علشان يشتغل
            $table->unsignedInteger('max_uses')->nullable(); // لو null يبقى غير محدود
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
