<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Brand;
use App\Models\Discount;
require_once __DIR__ .'/../utility.php';

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $associatedBrand = getRandomEntity(Brand::class);

        return [
            "name" => fake()->word(),
            "price" => fake()->randomFloat(2, 0, 99999),
            "vat_rate" => fake()->randomFloat(2, 0, 1),
            "stock" => fake()->randomDigit(),
            "description" => fake()->text(500),
            "environmental_impact" => fake()->randomDigit(),
            "origin" => fake()->word(),
            "measuring_unit" => "kg",
            "measure" => 5.5,
            'brand_id' => $associatedBrand->id,
        ];
    }

    public function withDiscount(): Factory
    {
        return $this->state(function (array $attributes) {
            $discount = Discount::inRandomOrder()->first();
            $attributes["discount_id"] = $discount->id;
            return $attributes;
        });
    }
}
