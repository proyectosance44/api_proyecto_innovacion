<?php

namespace App\Http\Controllers;

use App\Enums\LogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationPatientRequest;
use App\Models\Medication;
use App\Models\Patient;
use App\Services\PatientLogService;
use Illuminate\Support\Facades\DB;

class MedicationPatientController extends Controller
{
    private PatientLogService $patientLogService;

    public function __construct(PatientLogService $patientLogService)
    {
        $this->patientLogService = $patientLogService;
    }

    public function attach(MedicationPatientRequest $request)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData) {
            $patient = Patient::findOrFail($validatedData['patient_dni']);
            $medication = Medication::findOrFail($validatedData['medication_num_registro']);
            $patient->medications()->attach($medication, ['urgente' => $validatedData['urgente']]);
            $this->patientLogService->logActionInPatientMedication($patient, LogAction::Assignment, $medication);
        });
        return response()->json([
            'message' => 'Medicación asignada al paciente exitosamente.'
        ], 201);
    }

    public function update(MedicationPatientRequest $request, Medication $medication, Patient $patient)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData, $patient, $medication) {
            if (isset($validatedData['urgente']))
                $patient->medications()->updateExistingPivot($medication, ['urgente' => $validatedData['urgente']]);
            $this->patientLogService->logActionInPatientMedication($patient, LogAction::Modification, $medication);
        });
        return response()->json([
            'message' => 'Medicación asignada al paciente actualizada exitosamente.'
        ], 201);
    }

    public function detach(Medication $medication, Patient $patient)
    {
        DB::transaction(function () use ($patient, $medication) {
            $patient->medications()->detach($medication);
            $this->patientLogService->logActionInPatientMedication($patient, LogAction::Omission, $medication);
        });
        return response()->json([
            'message' => 'Medicación desasignada del paciente exitosamente.'
        ], 200);
    }
}
