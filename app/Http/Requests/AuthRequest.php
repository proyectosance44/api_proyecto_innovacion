<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class AuthRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return request()->isMethod('post') ? [] : [
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->mixedCase()->letters()->numbers()->symbols()->uncompromised()],
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
