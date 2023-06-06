<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class MedicationPatientRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? "required" : "";
        $rules = [
            'urgente' => [$required, 'boolean']
        ];

        if (request()->isMethod('post')) {
            $rules['patient_dni'] = ['required', 'string', 'exists:patients,dni'];
            $rules['medication_num_registro'] = [
                'required', 'string', 'exists:medications,num_registro',
                Rule::unique('medication_patient')
                    ->where(fn (Builder $query) => $query->where('patient_dni', request()->input('patient_dni')))
            ];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->patient_dni))
            $preparations['patient_dni'] = strtoupper($this->patient_dni);
        if (isset($this?->medication_num_registro))
            $preparations['medication_num_registro'] = strtoupper($this->medication_num_registro);
        $this->merge($preparations);
    }
}
