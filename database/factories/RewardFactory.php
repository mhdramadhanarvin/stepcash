<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reward>
 */
class RewardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'description' => fake()->paragraph(),
            'quantity' => fake()->numberBetween(50, 500),
            'price' => fake()->numberBetween(1, 20),
            'thumbnail' => 'fe95b30d4c61605f0a773b4a98553b1c.png',
            'status' => fake()->randomElement(['waiting_approving', 'draft', 'publish']),
        ];
    }
}
