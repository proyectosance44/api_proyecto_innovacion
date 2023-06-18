<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RFIDRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_rfid' => ['required', 'string', 'max:255', 'regex:/^([\da-f]+)$/i', 'exists:patients,id_rfid'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->id_rfid))
            $preparations['id_rfid'] = strtolower($this->id_rfid);
        $this->merge($preparations);
    }
}
