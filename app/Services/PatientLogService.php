<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\LogAction;
use App\Models\Contact;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\PatientLog;
use App\Models\User;

class PatientLogService
{
    private function logAction(Patient $patient, LogAction $action, string $description)
    {
        $patientLog = new PatientLog();
        $patientLog->user_id = auth()->id();
        $patientLog->patient_dni = $patient->dni;
        $patientLog->accion = $action;
        $patientLog->descripcion = $description;
        $patientLog->save();
    }

    public function logActionInPatient(Patient $patient, LogAction $action)
    {
        $description = '';
        $user = auth()->id() ? User::findOrFail(auth()->id()) : null;
        switch ($action) {
            case LogAction::Creation:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha creado el paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Modification:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha modificado el paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Deleted:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha borrado el paciente con DNI "' . $patient->dni . '".';
                break;
            default:
                break;
        }
        $this->logAction($patient, $action, $description);
    }

    public function logActionInPatientMedication(Patient $patient, LogAction $action, Medication $medication)
    {
        $description = '';
        $user = auth()->id() ? User::findOrFail(auth()->id()) : null;
        switch ($action) {
            case LogAction::Assignment:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha asignado la medicación con número de resgistro "' . $medication->num_registro . '" al paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Modification:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha modificado la medicación con número de registro "' . $medication->num_registro . '" asignada al paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Omission:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha omitido la medicación con número de registro "' . $medication->num_registro . '" asignada al paciente con DNI "' . $patient->dni . '".';
                break;
            default:
                break;
        }
        $this->logAction($patient, $action, $description);
    }

    public function logActionInPatientContact(Patient $patient, LogAction $action, Contact $contact)
    {
        $description = '';
        $user = auth()->id() ? User::findOrFail(auth()->id()) : null;
        switch ($action) {
            case LogAction::Assignment:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha asignado el contacto con DNI "' . $contact->dni . '" al paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Modification:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha modificado el contacto con DNI "' . $contact->dni . '" asignado al paciente con DNI "' . $patient->dni . '".';
                break;
            case LogAction::Omission:
                $description = 'El usuario con DNI "' . $user?->dni . '" ha omitido el contacto con DNI "' . $contact->dni . '" asignado al paciente con DNI "' . $patient->dni . '".';
                break;
            default:
                break;
        }
        $this->logAction($patient, $action, $description);
    }
}
