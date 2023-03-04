<?php

namespace App\Observers;

use App\Models\Patient;
use App\Models\PatientLog;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        $this->logAction($patient, 'creación');
    }

    /**
     * Handle the Patient "updated" event.
     */
    public function updated(Patient $patient): void
    {
        $this->logAction($patient, 'modificación');
    }

    /**
     * Handle the Patient "deleted" event.
     */
    public function deleted(Patient $patient): void
    {
        $this->logAction($patient, 'borrado lógico');
    }

    /**
     * Handle the Patient "restored" event.
     */
    public function restored(Patient $patient): void
    {
        $this->logAction($patient, 'restauración');
    }

    /**
     * Handle the Patient "force deleted" event.
     */
    public function forceDeleted(Patient $patient): void
    {
        $this->logAction($patient, 'borrado físico');
    }

    private function logAction(Patient $patient, string $accion)
    {
        $patientLog = new PatientLog();

        $patientLog->user_id = !auth() || !auth()->id() ? null : auth()->id();
        $patientLog->patient_dni = $patient->dni;
        $patientLog->accion = $accion;

        $patientLog->save();
    }
}
