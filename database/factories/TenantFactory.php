<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();
        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'domain' => null,
            'database' => null,
            'data' => [],
            'is_active' => true,
            'trial_ends_at' => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}