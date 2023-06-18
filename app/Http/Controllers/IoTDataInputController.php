<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RFIDRequest;
use App\Services\DataProcessingService;
use Illuminate\Http\Request;

class IoTDataInputController extends Controller
{
    private DataProcessingService $dataProcessor;

    public function __construct(DataProcessingService $dataProcessor)
    {
        $this->dataProcessor = $dataProcessor;
    }

    public function rfid(RFIDRequest $request)
    {
        /*if(){

        }*/
        $this->dataProcessor->processData($request->validated())['id_rfid'];
    }

    public function lora()
    {
    }
}
