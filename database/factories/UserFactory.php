<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Rules\Dni;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'dni' => Dni::calculateDni($this->faker->unique()->randomNumber(8, false)),
            'name' => $this->faker->name(),
            'apellidos' => $this->faker->lastName() . " " . $this->faker->lastName(),
            'rol' => 'trabajador', //Por defecto trabajador
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'telefono' => strval(rand(6, 7)) . str_pad(strval($this->faker->unique()->randomNumber(8)), 8, '0', STR_PAD_LEFT),
            'password' => Hash::make("123456789Aa-"),
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
