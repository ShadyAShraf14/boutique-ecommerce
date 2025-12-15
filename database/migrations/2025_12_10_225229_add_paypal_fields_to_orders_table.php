<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('paypal_order_id')->nullable()->after('payment_reference');
            $table->string('paypal_capture_id')->nullable()->after('paypal_order_id');
            $table->string('paypal_payer_id')->nullable()->after('paypal_capture_id');
            $table->string('paypal_payer_email')->nullable()->after('paypal_payer_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'paypal_order_id',
                'paypal_capture_id',
                'paypal_payer_id',
                'paypal_payer_email',
            ]);
        });
    }
};
