<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoRaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_lora' => ['required', 'string', 'max:255', 'regex:/^([\da-f]+)$/i', 'exists:patients,id_lora'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric']
        ];
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->id_lora))
            $preparations['id_lora'] = strtolower($this->id_lora);
        $this->merge($preparations);
    }
}
