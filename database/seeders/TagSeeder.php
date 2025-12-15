<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'Men',   'slug' => 'men'],
            ['name' => 'Women', 'slug' => 'women'],
            ['name' => 'Kids',  'slug' => 'kids'],
            // زوّدي اللي محتجاه
        ];

        foreach ($tags as $tag) {
            Tag::updateOrCreate(
                ['slug' => $tag['slug']], // لو موجود بنفس الـ slug → هيعمل update بس
                [
                    'name'      => $tag['name'],
                    'is_active' => 1,
                ]
            );
        }
    }
}
