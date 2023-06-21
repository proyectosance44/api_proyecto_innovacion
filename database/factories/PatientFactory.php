<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LogAction;
use App\Models\Incidence;
use App\Models\Patient;
use App\Rules\Dni;
use App\Services\PatientLogService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            'nombre_foto' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Patient $patient) {
            (new PatientLogService)->logActionInPatient($patient, LogAction::Creation);

            $patientRoutes = json_decode(Storage::get('populateDatabase/patientRoutes.json'), true);
            $numOfIncidences = random_int(0, 3);
            for ($i = 0; $i < $numOfIncidences; $i++) {
                $incidence = new Incidence();
                $incidence->patient_dni = $patient->dni;
                $incidence->fecha_inicio = Carbon::now()->subDays($numOfIncidences - $i)->timestamp;
                $incidence->fecha_fin = Carbon::now()->subDays($numOfIncidences - $i)->addMinutes(random_int(20, 50))->timestamp;
                $incidence->recorrido_paciente = $patientRoutes[random_int(0, count($patientRoutes) - 1)];
                $incidence->save();
            }
        });
    }
}
