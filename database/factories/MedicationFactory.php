<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medication>
 */
class MedicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicamento = Http::get('https://cima.aemps.es/cima/rest/medicamentos?pagesize=1&pagina=' . strval($this->faker->unique()->numberBetween(1, 24300)))['resultados'][0];
        return [
            'num_registro' => $medicamento['nregistro'],
            'nombre' => $medicamento['nombre']
        ];
    }
}
