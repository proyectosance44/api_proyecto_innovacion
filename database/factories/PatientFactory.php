<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LogAction;
use App\Models\Patient;
use App\Rules\Dni;
use App\Services\PatientLogService;
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
            'dni' => Dni::calculateDni($this->faker->unique()->randomNumber(8, false)),
            'id_lora' => bin2hex(Str::random(8) . strval($this->faker->unique()->randomNumber())),
            'id_rfid' => bin2hex(Str::random(8) . strval($this->faker->unique()->randomNumber())),
            'nombre' => $this->faker->name(),
            'apellidos' => $this->faker->lastName() . " " . $this->faker->lastName(),
            'ruta_foto' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Patient $patient) {
            (new PatientLogService)->logActionInPatient($patient, LogAction::Creation);
        });
    }
}
