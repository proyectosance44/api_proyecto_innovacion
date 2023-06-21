<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoRaRequest;
use App\Http\Requests\RFIDRequest;
use App\Models\Incidence;
use App\Models\Patient;
use App\Services\DataProcessingService;
use Illuminate\Support\Carbon;

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
            // Ubicación de la puerta
            $incidence->recorrido_paciente = [["latitud" => 42.013456, "longitud" => -4.538743]];
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

    public function lora(LoRaRequest $request)
    {
        $validatedData = $this->dataProcessor->processData($request->validated());
        $patient = Patient::where('id_lora', $validatedData['id_lora'])->first();
        $incidenceInProgress = Incidence::where('patient_dni', $patient->dni)->whereNull('fecha_fin')->first();
        $is_the_patient_in = true;

        if ($incidenceInProgress !== null) {
            $patientRoute = $incidenceInProgress->recorrido_paciente;
            $patientRoute[] = ['latitud' => floatval($validatedData['latitude']), 'longitud' => floatval($validatedData['longitude'])];
            $incidenceInProgress->recorrido_paciente = $patientRoute;
            $incidenceInProgress->save();
            $is_the_patient_in = false;
        }

        return response()->json([
            'message' => 'LoRa recibido exitosamente.',
            'is_the_patient_in' => $is_the_patient_in
        ], 200);
    }
}
