<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Dni;
use App\Rules\NotBlank;
use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = request()->isMethod('post') ? 'required' : "";
        $unique = request()->isMethod('post') ? 'unique:users' : Rule::unique('users')->ignore($this->route('user')->id);

        return [
            'dni' => [$required, 'string', 'size:9', new Dni(), $unique],
            'name' => [$required, 'string', 'max:255', new NotBlank()],
            'apellidos' => [$required, 'string', 'max:255', new NotBlank()],
            'rol' => [$required, 'string', 'in:admin,trabajador'],
            'email' => [$required, 'string', 'email', 'max:255', $unique],
            'telefono' => [$required, 'string', 'size:9', new Telefono(), $unique],
            'password' => [$required, 'string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()]
        ];
    }

    protected function prepareForValidation(): void
    {
        $preparations = [];
        if (isset($this?->dni))
            $preparations['dni'] = strtoupper($this->dni);
        $this->merge($preparations);
    }
}
