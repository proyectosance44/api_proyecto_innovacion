<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Hash;

class DataProcessingService
{
    private static array $functionsToProcessData;

    public function __construct()
    {
        if (!isset(self::$functionsToProcessData)) {
            self::$functionsToProcessData = [
                'dni' => fn (string $dni): string => strtoupper($dni),
                'name' => fn (string $name): string => fixSpaces($name),
                'nombre' => fn (string $name): string => fixSpaces($name),
                'apellidos' => fn (string $apellidos): string => fixSpaces($apellidos),
                'password' => fn (string $password): string => Hash::make($password),
                'id_lora' => fn (string $id_lora): string => strtolower($id_lora),
                'id_rfid' => fn (string $id_rfid): string => strtolower($id_rfid),
                'num_registro' => fn (string $num_registro): string => strtoupper($num_registro)
            ];
        }
    }

    public function processData(array $dataToProcess): array
    {
        $processedData = [];
        foreach ($dataToProcess as $key => $data) {
            $processedData[$key] = isset(self::$functionsToProcessData[$key]) ?
                self::$functionsToProcessData[$key]($data) : $data;
        }
        return $processedData;
    }
}
