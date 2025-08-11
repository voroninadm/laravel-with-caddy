<?php

namespace Database\Factories;

use App\Models\MachinesType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MachineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'title' => $this->faker->word,
            'type_id' => $this->faker->numberBetween(1, 10),
            'is_viket' => null
        ];
    }
}
