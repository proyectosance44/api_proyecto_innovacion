<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Rules\Dni;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
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
            'nombre' => $this->faker->name(),
            'apellidos' => $this->faker->lastName() . " " . $this->faker->lastName(),
            'telefono' => strval(rand(6, 7)) . str_pad(strval($this->faker->unique()->randomNumber(8)), 8, '0', STR_PAD_LEFT)
        ];
    }
}
