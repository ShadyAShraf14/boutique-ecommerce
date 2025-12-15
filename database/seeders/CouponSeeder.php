<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    public function run()
    {
        Coupon::create([
            'code' => 'SAMI200',
            'type' => 'fixed',
            'value' => 200,
            'min_order_total' => 300,
            'max_uses' => null,
            'used_count' => 0,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDays(30),
            'is_active' => true,
        ]);
    }
}
