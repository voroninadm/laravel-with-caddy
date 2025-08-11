<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Worker>
 */
class WorkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            "job" => fake()->word,
            "category" => null,
            "is_test_passed" => null,
            "last_test_date" => null,
            "last_test_result" => null,
            'password' => '$2y$10$72eV4C5J5zIct.ZswYYp2.ItH1UnvkhhjRMaw/8N7yAL46dsLJvC6', // 123456
            'is_blocked' => false
        ];
    }
}
