<?php

// database/migrations/2025_12_01_000200_create_shipping_methods_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // Aramex Outside, Aramex Speed...
            $table->string('code')->nullable(); // ARAMEX_OUTSIDE...
            $table->decimal('price', 10, 2);  // 50.00, 80.00

            // لو حابب تربط بالدولة (اختياري):
            $table->foreignId('country_id')->nullable()
                  ->constrained()
                  ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
