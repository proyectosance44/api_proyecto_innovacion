<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class ContactPatientRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? "required" : "";
        $orden_pref_unique = request()->isMethod('post') ?
            Rule::unique('contact_patient')
            ->where(fn (Builder $query) => $query->where('patient_dni', request()->input('patient_dni'))) :
            Rule::unique('contact_patient')
            ->where(fn (Builder $query) => $query->where('patient_dni', $this->route('patient')->dni))
            ->ignore(DB::table('contact_patient')->where('patient_dni', $this->route('patient')->dni)->where('contact_dni', $this->route('contact')->dni)->value('id'));

        $rules = [
            'orden_pref' => [$required, 'integer', 'min:1', 'max:255', $orden_pref_unique]
        ];

        if (request()->isMethod('post')) {
            $rules['patient_dni'] = ['required', 'string', 'exists:patients,dni'];
            $rules['contact_dni'] = [
                'required', 'string', 'exists:contacts,dni',
                Rule::unique('contact_patient')
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
        if (isset($this?->contact_dni))
            $preparations['contact_dni'] = strtoupper($this->contact_dni);
        $this->merge($preparations);
    }
}
