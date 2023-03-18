<?php

namespace Database\Factories;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dni' => DatabaseSeeder::calcularDni(fake()->unique()->randomNumber(8, false)),
            'name' => fake()->name(),
            'apellidos' => fake()->lastName() . " " . fake()->lastName(),
            'rol' => 'trabajador', //Por defecto trabajador
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'telefono' => strval(random_int(6, 7)) . str_pad(fake()->unique()->randomNumber(8), 8, '0', STR_PAD_LEFT),
            'password' => Hash::make("123456789Aa-"), // password
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
}
