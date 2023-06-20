<?php

declare(strict_types=1);

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Enums\LogAction;
use App\Models\Contact;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\User;
use App\Services\PatientLogService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Administradores
        User::factory(1, [
            'dni' => '00000000T',
            'name' => 'admin',
            'apellidos' => 'admin admin',
            'rol' => 'admin',
            'email' => 'admin@gmail.com',
        ])->create();
        User::factory(2, ['rol' => 'admin'])->create();

        // Trabajadores
        User::factory(10)->create();

        // Paciente con RFID y LoRa a probar
        Patient::factory(1, [
            'id_rfid' => '52311c1f',
            'nombre' => 'Alfonso',
            'apellidos' => 'Aguayo Román'
        ])->create();

        // Pacientes
        $pacientes = Patient::factory(50)->create();

        // Asignacion de medicaciones a pacientes
        $pacientes->each(function ($paciente) {
            Medication::factory(rand(0, 3))->create()->each(function ($medicacion) use ($paciente) {
                $medicacion->patients()->attach($paciente, ['urgente' => rand(0, 1)]);
                (new PatientLogService())->logActionInPatientMedication($paciente, LogAction::Assignment, $medicacion);
            });
        });

        // Asignacion de medicaciones a más de un paciente
        $totalPacientes = count($pacientes);
        Medication::factory(20)->create()->each(function ($medicacion) use ($pacientes, $totalPacientes) {
            $indiceComienzo = rand(0, $totalPacientes - 1);
            $fin = rand(2, 3);
            for ($i = 0; $i < $fin; $i++) {
                $paciente = $pacientes[$indiceComienzo + $fin - 1 <= $totalPacientes - 1 ? $indiceComienzo + $i : $i];
                $paciente->medications()->attach($medicacion, ['urgente' => rand(0, 1)]);
                (new PatientLogService())->logActionInPatientMedication($paciente, LogAction::Assignment, $medicacion);
            }
        });

        // Pacientes sin medicación
        Patient::factory(5)->create();

        // Medicaciones sin paciente
        Medication::factory(10)->create();

        // Asignacion de contactos a pacientes
        $pacientesConContactos = Patient::all()->take(45); // No se cogen todos para que haya pacientes sin contactos
        $pacientesConContactos->each(function ($paciente) {
            Contact::factory(rand(0, 4))->create()->each(function ($contacto, $key) use ($paciente) {
                $contacto->patients()->attach($paciente, ['orden_pref' => $key + 1]);
                (new PatientLogService())->logActionInPatientContact($paciente, LogAction::Assignment, $contacto);
            });
        });

        // Asignacion de contactos a más de un paciente
        $totalPacientesConContactos = count($pacientesConContactos);
        Contact::factory(10)->create()->each(function ($contacto) use ($pacientesConContactos, $totalPacientesConContactos) {
            $indiceComienzo = rand(0, $totalPacientesConContactos - 1);
            $fin = rand(1, 3);
            for ($i = 0; $i < $fin; $i++) {
                $pacienteComparteContacto = $pacientesConContactos[$indiceComienzo + $fin - 1 <= $totalPacientesConContactos - 1 ? $indiceComienzo + $i : $i];
                $pacienteComparteContacto->contacts()->attach($contacto, ['orden_pref' => DB::table('contact_patient')->where('patient_dni', $pacienteComparteContacto->dni)->count() + 1]);
                (new PatientLogService())->logActionInPatientContact($pacienteComparteContacto, LogAction::Assignment, $contacto);
            }
        });

        // Incidencias
    }
}
