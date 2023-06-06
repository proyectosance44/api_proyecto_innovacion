<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PatientLog;

class PatientLogController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Registros de pacientes obtenidos exitosamente.',
            'patient_logs' => PatientLog::with('patient', 'user')->get()
        ], 200);
    }

    public function show(PatientLog $patientLog)
    {
        return response()->json([
            'message' => 'Registro de paciente obtenido exitosamente.',
            'patient_log' => PatientLog::with('patient', 'user')->find($patientLog->id)
        ], 200);
    }
}
