<?php

// database/migrations/2025_12_01_000130_create_user_addresses_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->foreignId('country_id')
                  ->constrained()
                  ->restrictOnDelete();

            $table->foreignId('state_id')
                  ->constrained()
                  ->restrictOnDelete();

            $table->foreignId('city_id')
                  ->constrained()
                  ->restrictOnDelete();

            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();

            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('postal_code')->nullable();

            $table->boolean('is_default_shipping')->default(false);
            $table->boolean('is_default_billing')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
