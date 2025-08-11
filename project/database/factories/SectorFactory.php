<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->word,
            'name' => $this->faker->word,
            'is_virtual' => false,
        ];
    }
}
