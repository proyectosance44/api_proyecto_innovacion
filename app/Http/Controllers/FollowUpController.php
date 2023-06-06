<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;

class FollowUpController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Seguimientos de pacientes obtenidos exitosamente.',
            'follow_ups' => FollowUp::with('patient')->get()
        ], 200);
    }

    public function show(FollowUp $followUp)
    {
        return response()->json([
            'message' => 'Seguimiento de paciente obtenido exitosamente.',
            'follow_up' => FollowUp::with('patient')->find($followUp->id)
        ], 200);
    }
}
