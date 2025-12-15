<?php


namespace Database\Factories;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->value('id') ?? 1,
            'user_id'    => User::inRandomOrder()->value('id'),
            'rating'     => $this->faker->numberBetween(1, 5),
            'title'      => $this->faker->sentence(),
            'comment'    => $this->faker->paragraph(),
            'is_approved'=> true,
        ];
    }
}
