<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Dni implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::isValid($value)) $fail('El campo :attribute no es un dni válido.');
    }

    private static function isValid(mixed $dni)
    {
        $letras = ['t', 'r', 'w', 'a', 'g', 'm', 'y', 'f', 'p', 'd', 'x', 'b', 'n', 'j', 'z', 's', 'q', 'v', 'h', 'l', 'c', 'k', 'e'];
        $strDni = strval($dni);

        if (!preg_match("/^([0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE])$/i", $strDni, $matches)) return false;

        return $letras[intval($matches[0]) % count($letras)] === strtolower(substr($strDni, -1));
    }
}
