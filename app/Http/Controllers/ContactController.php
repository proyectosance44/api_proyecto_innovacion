<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\LogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Models\Patient;
use App\Services\DataProcessingService;
use App\Services\PatientLogService;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    private DataProcessingService $dataProcessor;
    private PatientLogService $patientLogService;

    public function __construct(DataProcessingService $dataProcessor, PatientLogService $patientLogService)
    {
        $this->dataProcessor = $dataProcessor;
        $this->patientLogService = $patientLogService;
    }

    public function index()
    {
        return response()->json([
            'message' => 'Contactos obtenidos exitosamente.',
            'contacts' => Contact::with('patients')->get()
        ], 200);
    }

    public function store(ContactRequest $request)
    {
        $validatedData = $this->dataProcessor->processData($request->validated());
        $patient_dni = $validatedData["patient_dni"];
        $orden_pref = $validatedData['orden_pref'];
        unset($validatedData["patient_dni"]);
        unset($validatedData["orden_pref"]);

        DB::transaction(function () use ($validatedData, $patient_dni, $orden_pref) {
            $contact = Contact::create($validatedData);
            $patient = Patient::findOrFail($patient_dni);
            $contact->patients()->attach($patient, ['orden_pref' => $orden_pref]);
            $this->patientLogService->logActionInPatientContact($patient, LogAction::Assignment, $contact);
        });

        return response()->json([
            'message' => 'Contacto creado exitosamente.',
        ], 201);
    }

    public function show(Contact $contact)
    {
        return response()->json([
            'message' => 'Contacto obtenido exitosamente.',
            'contact' => Contact::with('patients')->find($contact->dni)
        ], 200);
    }

    public function update(ContactRequest $request, Contact $contact)
    {
        $contact->update($this->dataProcessor->processData($request->validated()));
        return response()->json([
            'message' => 'Contacto actualizado exitosamente.'
        ], 201);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json([
            'message' => 'Contacto eliminado exitosamente.'
        ], 200);
    }
}
