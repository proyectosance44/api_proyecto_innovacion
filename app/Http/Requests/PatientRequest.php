<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Dni;
use App\Rules\NotBlank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? 'required' : "";
        $unique = request()->isMethod('post') ? 'unique:patients' : Rule::unique('patients')->ignore($this->route('patient')->dni, 'dni');

        return [
            'dni' => [$required, 'string', 'size:9', new Dni(), $unique],
            'id_lora' => [$required, 'string', 'max:255', $unique],
            'id_rfid' => [$required, 'string', 'max:255', 'regex:/^([\da-f]+)$/i', $unique],
            'nombre' => [$required, 'string', 'max:255', new NotBlank()],
            'apellidos' => [$required, 'string', 'max:255', new NotBlank()]
        ];
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->dni))
            $preparations['dni'] = strtoupper($this->dni);
        if (isset($this?->id_lora))
            $preparations['id_lora'] = strtolower($this->id_lora);
        if (isset($this?->id_rfid))
            $preparations['id_rfid'] = strtolower($this->id_rfid);
        $this->merge($preparations);
    }
}
