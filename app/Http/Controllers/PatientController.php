<?php

namespace App\Http\Controllers;

use App\Enums\LogAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\PatientPhotoRequest;
use App\Http\Requests\PatientRequest;
use App\Models\Patient;
use App\Services\DataProcessingService;
use App\Services\PatientLogService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
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
            'message' => 'Pacientes obtenidos exitosamente.',
            'patients' => Patient::with('medications', 'contacts', 'patient_logs', 'incidences')->get()
        ], 200);
    }

    public function store(PatientRequest $request)
    {
        DB::transaction(function () use ($request) {
            $patient = Patient::create($this->dataProcessor->processData($request->validated()));
            $this->patientLogService->logActionInPatient($patient, LogAction::Creation);
        });
        return response()->json([
            'message' => 'Paciente creado exitosamente.'
        ], 201);
    }

    public function show(Patient $patient)
    {
        return response()->json([
            'message' => 'Paciente obtenido exitosamente.',
            'patient' => Patient::with('medications', 'contacts', 'patient_logs', 'incidences')->find($patient->dni)
        ], 200);
    }

    public function update(PatientRequest $request, Patient $patient)
    {
        DB::transaction(function () use ($patient, $request) {
            $patient->update($this->dataProcessor->processData($request->validated()));
            $this->patientLogService->logActionInPatient($patient, LogAction::Modification);
        });
        return response()->json([
            'message' => 'Paciente actualizado exitosamente.'
        ], 201);
    }

    public function destroy(Patient $patient)
    {
        DB::transaction(function () use ($patient) {
            $this->patientLogService->logActionInPatient($patient, LogAction::Deleted);
            $patient->delete();
        });
        return response()->json([
            'message' => 'Paciente eliminado exitosamente.'
        ], 200);
    }

    public function storePhoto(PatientPhotoRequest $request, Patient $patient)
    {
        $request->validated();
        $photo = $request->file('photo');
        $this->deletePhoto($patient);
        $photo->storeAs('patientPhotos', $patient->dni . '.' . $photo->getClientOriginalExtension());
        $patient->nombre_foto = $patient->dni . '.' . $photo->getClientOriginalExtension();
        $patient->save();
        return response()->json([
            'message' => 'Foto almacenada exitosamente.'
        ], 201);
    }

    public function showPhoto(Patient $patient)
    {
        $allPath = 'app/patientPhotos/default.jpg';
        if ($patient->nombre_foto !== null && Storage::exists($path = 'patientPhotos/' . $patient->nombre_foto))
            $allPath = 'app/' . $path;
        return response()->file(storage_path($allPath));
    }

    public function destroyPhoto(Patient $patient)
    {
        $this->deletePhoto($patient);
        return response()->json([
            'message' => 'Foto eliminada exitosamente.'
        ], 200);
    }

    private function deletePhoto(Patient $patient)
    {
        if ($patient->nombre_foto !== null) {
            Storage::delete('patientPhotos' . '/' . $patient->nombre_foto);
            $patient->nombre_foto = null;
            $patient->save();
        }
    }
}
