<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'emoji' => fake()->randomElement(['🍽️', '🔥', '🥗', '☕', '🥤', '🍕', '🍔', '🥙']),
            'sort_order' => fake()->numberBetween(1, 10),
        ];
    }
}