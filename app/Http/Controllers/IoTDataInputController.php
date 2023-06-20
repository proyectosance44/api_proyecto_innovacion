<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RFIDRequest;
use App\Models\Incidence;
use App\Models\Patient;
use App\Services\DataProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IoTDataInputController extends Controller
{
    private DataProcessingService $dataProcessor;

    public function __construct(DataProcessingService $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
    }

    public function rfid(RFIDRequest $request)
    {
        $id_rfid = $this->dataProcessor->processData($request->validated())['id_rfid'];
        $patient = Patient::with('contacts', 'medications')->where('id_rfid', $id_rfid)->first();
        $incidenceInProgress = Incidence::where('patient_dni', $patient->dni)->whereNull('fecha_fin')->first();
        $message = 'RFID recibido exitosamente, ';

        if ($incidenceInProgress === null) {
            $incidence = new Incidence();
            $incidence->patient_dni = $patient->dni;
            $incidence->recorrido_paciente = [];
            $incidence->save();
            $message = $message . 'el paciente ha escapado.';
        } else {
            $incidenceInProgress->fecha_fin = Carbon::now()->timestamp;
            $incidenceInProgress->save();
            $message = $message . 'el paciente ha vuelto a entrar.';
            $patient = null;
        }

        return response()->json([
            'message' => $message,
            'patient' => $patient
        ], 200);
    }

    public function lora()
    {
    }
}
