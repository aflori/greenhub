<?php

namespace Database\Factories;

use App\Models\Adress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

require_once __DIR__.'/../utility.php';

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomUser = getRandomEntity(User::class);
        $randomAdress = getRandomEntity(Adress::class);

        return [
            'number' => fake()->randomNumber(5),
            'order_date' => fake()->dateTime(),
            'delivery_date' => fake()->dateTime(),
            'bill' => fake()->randomFloat(2, 0, 99999.99),
            'vat_rate' => fake()->randomFloat(2, 0, 1),
            'shipping_fee' => 0,
            'total_price' => fake()->randomFloat(2, 0, 999999.99),
            'buyer_id' => $randomUser->id,
            'facturation_adress' => $randomAdress->id,
            'delivery_adress' => $randomAdress->id,
        ];
    }
}
