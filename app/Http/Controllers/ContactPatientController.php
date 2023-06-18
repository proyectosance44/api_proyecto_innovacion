<?php

namespace App\Http\Controllers;

use App\Enums\LogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactPatientRequest;
use App\Models\Contact;
use App\Models\Patient;
use App\Services\PatientLogService;
use Illuminate\Support\Facades\DB;


class ContactPatientController extends Controller
{
    private PatientLogService $patientLogService;

    public function __construct(PatientLogService $patientLogService)
    {
        $this->patientLogService = $patientLogService;
    }
    public function attach(ContactPatientRequest $request)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData) {
            $patient = Patient::findOrFail($validatedData['patient_dni']);
            $contact = Contact::findOrFail($validatedData['contact_dni']);
            $patient->contacts()->attach($contact, ['orden_pref' => $validatedData['orden_pref']]);
            $this->patientLogService->logActionInPatientContact($patient, LogAction::Assignment, $contact);
        });
        return response()->json([
            'message' => 'Contacto asignado al paciente exitosamente.'
        ], 201);
    }

    public function update(ContactPatientRequest $request, Contact $contact, Patient $patient)
    {
        $validatedData = $request->validated();
        DB::transaction(function () use ($validatedData, $patient, $contact) {
            if (isset($validatedData['orden_pref']))
                $patient->contacts()->updateExistingPivot($contact, ['orden_pref' => $validatedData['orden_pref']]);
            $this->patientLogService->logActionInPatientContact($patient, LogAction::Modification, $contact);
        });
        return response()->json([
            'message' => 'Contacto asignado al paciente actualizado exitosamente.'
        ], 201);
    }

    public function detach(Contact $contact, Patient $patient)
    {
        DB::transaction(function () use ($contact, $patient) {
            $patient->contacts()->detach($contact);
            $this->patientLogService->logActionInPatientContact($patient, LogAction::Omission, $contact);
            if (!DB::table('contact_patient')->where('contact_dni', $contact->dni)->exists())
                $contact->delete();
        });
        return response()->json([
            'message' => 'Contacto desasignado del paciente exitosamente.'
        ], 200);
    }
}
