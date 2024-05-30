<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pseudoname' => fake()->unique()->word(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            // default value from laravel file generation
            // create only 1 hash per seeding
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'client',
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
            'company_id' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Define user role as company member
     */
    public function grantCompanyRole(): Factory
    {
        return $this->state(function (array $attributes) {
            $associatedCompany = Company::inRandomOrder()->first();

            $attributes['role'] = 'company';
            $attributes['company_id'] = $associatedCompany->id;

            return $attributes;
        });
    }

    /**
     * Define user role as admin
     */
    public function grantAdminRole(): Factory
    {
        return $this->state(function (array $attributes) {
            $attributes['role'] = 'admin';
            $attributes['company_id'] = null;

            return $attributes;
        });
    }

    /**
     * reset user role
     */
    public function resetRole(): Factory
    {
        return $this->state(function (array $attributes) {
            $attributes['role'] = 'client';

            return $attributes;
        });
    }
}
