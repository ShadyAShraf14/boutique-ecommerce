<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class ShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            [
                'name'  => 'Standard Shipping',
                'price' => 20,
            ],
            [
                'name'  => 'Express Shipping',
                'price' => 50,
            ],
        ];

        foreach ($methods as $m) {
            ShippingMethod::updateOrCreate(
                ['name' => $m['name']],     // مفتاح التفرّد
                [
                    'price'     => $m['price'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
