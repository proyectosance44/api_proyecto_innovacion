<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Telefono implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::isValid(strval($value)))
            $fail('El campo :attribute no es un teléfono válido.');
    }

    public static function isValid(string $telephone): bool
    {
        return preg_match('/^(6|7)[0-9]{8}$/', $telephone) === 1;
    }
}
