<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Rules\Dni;
use App\Rules\NotBlank;
use App\Rules\Telefono;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $unique = Rule::unique('users')->ignore(auth()->id());

        return [
            'email' => ['string', 'email', 'max:255', $unique],
            'telefono' => ['string', 'size:9', new Telefono(), $unique],
        ];
    }
}
