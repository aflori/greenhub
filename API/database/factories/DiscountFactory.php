<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributes = [
            "amount" => 1+fake()->randomNumber(2, false),
            "is_percentage" => fake()->boolean(),
        ];
        if ($attributes["is_percentage"]) {
            $attributes["amount"] /= 100.0;
        }
        return $attributes;
    }
}
