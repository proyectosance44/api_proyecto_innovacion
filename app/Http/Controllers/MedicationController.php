<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicationRequest;
use App\Models\Medication;
use App\Services\DataProcessingService;

class MedicationController extends Controller
{
    private DataProcessingService $dataProcessor;

    public function __construct(DataProcessingService $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
    }

    public function index()
    {
        return response()->json([
            'message' => 'Medicaciones obtenidas exitosamente.',
            'medications' => Medication::with('patients')->get()
        ], 200);
    }

    public function store(MedicationRequest $request)
    {
        Medication::create($this->dataProcessor->processData($request->validated()));
        return response()->json([
            'message' => 'Medicación creada exitosamente.',
        ], 201);
    }


    public function show(Medication $medication)
    {
        return response()->json([
            'message' => 'Medicación obtenida exitosamente.',
            'medication' => Medication::with('patients')->find($medication->num_registro)
        ], 200);
    }

    public function update(MedicationRequest $request, Medication $medication)
    {
        $medication->update($this->dataProcessor->processData($request->validated()));
        return response()->json([
            'message' => 'Medicación actualizada exitosamente.',
        ], 201);
    }

    public function destroy(Medication $medication)
    {
        $medication->delete();
        return response()->json([
            'message' => 'Medicación eliminada exitosamente'
        ], 200);
    }
}
