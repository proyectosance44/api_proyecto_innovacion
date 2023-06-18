<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RFIDRequest;
use App\Models\Incidence;
use App\Services\DataProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        $patient_dni = DB::table('patients')->select('dni')->where('id_rfid', $id_rfid)->first();
        $incidenceInProgress = Incidence::where('patient_dni',)
            ->whereNull('fecha_fin')->first();
        if ($incidenceInProgress === null) {
            $incidence = new Incidence();
            $incidence->patient_dni = $patient_dni;
            $incidence->recorrido_paciente = [];
        } else {
            $incidenceInProgress->fecha_fin = Carbon::now()->timestamp;
        }
        $incidence->save();
    }

    public function lora()
    {
    }
}
