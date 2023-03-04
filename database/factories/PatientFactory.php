<?php

namespace Database\Factories;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Patient>
 */
class PatientFactory extends Factory
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
            'id_lora' => bin2hex(Str::random(8) . strval(fake()->unique()->randomNumber())),
            'id_rfid' => bin2hex(Str::random(8) . strval(fake()->unique()->randomNumber())),
            'nombre' => fake()->name(),
            'apellidos' => fake()->lastName() . " " . fake()->lastName(),
            'ruta_foto' => null,
        ];
    }
}
