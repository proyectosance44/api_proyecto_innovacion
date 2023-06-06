<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\NotBlank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MedicationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? 'required' : "";
        $unique = request()->isMethod('post') ? 'unique:medications' : Rule::unique('medications')->ignore($this->route('medication')->num_registro, 'num_registro');

        return [
            'num_registro' => [$required, 'string', 'max:255', 'regex:/^([\da-z]+)$/i', $unique],
            'nombre' => [$required, 'string', 'max:255', new NotBlank()],
        ];
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->num_registro))
            $preparations['num_registro'] = strtoupper($this->num_registro);
        $this->merge($preparations);
    }
}
