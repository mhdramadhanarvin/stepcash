<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RewardClaim>
 */
class RewardClaimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => '',
            'reward_id' => '',
            'code' => fake()->regexify('[A-Z]{5}[0-4]{3}'),
            'price' => fake()->numberBetween(1, 20),
            'status' => fake()->randomElement(['waiting_confirmation', 'on_progress', 'ready_to_pickup', 'done', 'rejected']),
            'reason_rejection' => ''
        ];
    }
}
