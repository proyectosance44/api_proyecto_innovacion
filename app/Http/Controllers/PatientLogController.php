<?php

namespace App\Http\Controllers;

use App\Models\PatientLog;

class PatientLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            'message' => 'Registros de pacientes obtenidos exitosamente.',
            'patient_logs' => PatientLog::all()
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(PatientLog $patientLog)
    {
        return response()->json([
            'message' => 'Registro de paciente obtenido exitosamente.',
            'patient_log' => $patientLog
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PatientLog $patientLog)
    {
        $patientLog->delete();

        return response()->json([
            'message' => 'Registro de paciente eliminado exitosamente',
            'patient_log' => $patientLog
        ], 200);
    }
}
