<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;

class IncidenceController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Seguimientos de pacientes obtenidos exitosamente.',
            'incidences' => Incidence::with('patient')->get()
        ], 200);
    }

    public function show(Incidence $incidence)
    {
        return response()->json([
            'message' => 'Seguimiento de paciente obtenido exitosamente.',
            'incidences' => Incidence::with('patient')->find($incidence->id)
        ], 200);
    }
}
