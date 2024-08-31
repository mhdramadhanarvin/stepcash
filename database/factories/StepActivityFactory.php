<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StepActivity>
 */
class StepActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 0,
            'step' => fake()->numberBetween(1000, 10000),
            'calory' => fake()->numberBetween(200, 1000),
            'distance' => fake()->numberBetween(200, 1000),
            'time_spent' => fake()->numberBetween(10, 100),
        ];
    }
}
