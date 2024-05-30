<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adress>
 */
class AdressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->randomNumber(2, false),
            'road' => fake()->lexify('rue de ??????????'),
            'postal_code' => fake()->randomNumber(5, true),
            'city' => fake()->regexify('[A-Z]{1}[a-z]{5}'),
            'country' => 'France',
        ];
    }
}
