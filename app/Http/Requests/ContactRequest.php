<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Dni;
use App\Rules\NotBlank;
use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

class ContactRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? 'required' : "";
        $unique = request()->isMethod('post') ? 'unique:contacts' : Rule::unique('contacts')->ignore($this->route('contact')->dni, 'dni');
        $rules = [
            'dni' => [$required, 'string', 'size:9', new Dni(), $unique],
            'nombre' => [$required, 'string', 'max:255', new NotBlank()],
            'apellidos' => [$required, 'string', 'max:255', new NotBlank()],
            'telefono' => [$required, 'string', 'size:9', new Telefono(), $unique]
        ];

        if (request()->isMethod('post')) {
            $rules['patient_dni'] = ['required', 'string', 'exists:patients,dni'];
            $rules['orden_pref'] = [
                'required', 'integer', 'min:1', 'max:255',
                Rule::unique('contact_patient')
                    ->where(fn (Builder $query) => $query->where('patient_dni', request()->input('patient_dni')))
            ];
        }

        return $rules;
    }

    protected function prepareForValidation(): void
    {
        /*Se ponen a mayúsculas los campos que deben ser únicos porque
        sino la regla de laravel unique no funciona al no coincidir exactamente los campos.*/
        $preparations = [];
        if (isset($this?->dni))
            $preparations['dni'] = strtoupper($this->dni);
        if (isset($this?->patient_dni))
            $preparations['patient_dni'] = strtoupper($this->patient_dni);
        $this->merge($preparations);
    }
}
