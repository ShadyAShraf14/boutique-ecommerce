<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Main categories
            [
                'name'      => 'Clothes',
                'slug'      => 'clothes',
                'parent_id' => null,
            ],
            [
                'name'      => 'Shoes',
                'slug'      => 'shoes',
                'parent_id' => null,
            ],
            [
                'name'      => 'Accessories',
                'slug'      => 'accessories',
                'parent_id' => null,
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']], // مفتاح التفرّد
                [
                    'name'      => $cat['name'],
                    'parent_id' => $cat['parent_id'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
