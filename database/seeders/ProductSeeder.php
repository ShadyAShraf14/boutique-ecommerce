<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'name'        => 'Demo Laptop',
                'slug'        => 'demo-laptop',
                'description' => 'Sample laptop description.',
                'price'       => 15000,
            ],
            [
                'category_id' => 1,
                'name'        => 'Demo Phone',
                'slug'        => 'demo-phone',
                'description' => 'Sample phone description.',
                'price'       => 8000,
            ],
            [
                'category_id' => 2,
                'name'        => 'Running Shoes',
                'slug'        => 'running-shoes',
                'description' => 'Comfortable running shoes.',
                'price'       => 1200,
            ],
        ];

        foreach ($products as $p) {
            Product::updateOrCreate(
                ['slug' => $p['slug']], // مفتاح uniqueness
                [
                    'category_id' => $p['category_id'],
                    'name'        => $p['name'],
                    'description' => $p['description'],
                    'price'       => $p['price'],
                    'is_active'   => 1,
                ]
            );
        }
    }
}
