<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incidence;

class IncidenceController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Incidencias obtenidas exitosamente.',
            'incidences' => Incidence::with('patient')->get()
        ], 200);
    }

    public function inProgress()
    {
        return response()->json([
            'message' => 'Incidencias en curso obtenidas exitosamente.',
            'incidences' => Incidence::with('patient')->whereNull('fecha_fin')->get()
        ], 200);
    }

    public function show(Incidence $incidence)
    {
        return response()->json([
            'message' => 'Incidencia obtenida exitosamente.',
            'incidences' => Incidence::with('patient')->find($incidence->id)
        ], 200);
    }
}
