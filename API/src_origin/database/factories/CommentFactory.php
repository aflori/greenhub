<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

require_once __DIR__.'/../utility.php';

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomUser = getRandomEntity(User::class);

        return [
            'title' => fake()->word(),
            'comment' => fake()->sentence(),
            'rating' => fake()->numberBetween(0, 5),
            'coment_on_table' => 'none',
            'table_key' => 'b7ed12fb-c1b2-11ee-8f73-d9283ebdfbb0',
            'author_id' => $randomUser->id,
        ];
    }
}
