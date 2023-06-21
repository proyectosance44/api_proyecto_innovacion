<?php

namespace App\Console\Commands;

use App\Models\Patient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class LocationSimulator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:location-simulator {patient_dni}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $patientRoutes = json_decode(Storage::get('populateDatabase/patientRoutes.json'), true);
        $patientRoute = $patientRoutes[random_int(0, count($patientRoutes) - 1)];
        $patient = Patient::find($this->argument('patient_dni'));
        $is_the_patient_in = false;

        for ($i = 1; $i < count($patientRoute) && !$is_the_patient_in; $i++) {
            $response = Http::post('http://127.0.0.1:8000/api/lora', [
                'id_lora' => $patient->id_lora,
                'latitude' => $patientRoute[$i]['latitud'],
                'longitude' => $patientRoute[$i]['longitud']
            ]);

            if (!($is_the_patient_in = $response['is_the_patient_in'])) {
                $this->info('Paciente con DNI ' . $patient->dni . ' en ' . $patientRoute[$i]['latitud'] . ', ' .  $patientRoute[$i]['longitud']);
                sleep(15);
            }
        }

        $this->info('El paciente con DNI ' .  $patient->dni . ' está dentro del recinto.');
    }
}
