<?php

declare(strict_types=1);

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
        if (!self::isValid(strval($value)))
            $fail('El campo :attribute no es un dni válido.');
    }

    public static function isValid(string $dni): bool
    {
        if (preg_match("/^([0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKE])$/i", $dni) !== 1)
            return false;
        return self::calculateDni(intval(substr($dni, 0, strlen($dni) - 1))) === strtoupper($dni);
    }

    public static function calculateDni(int $number): string
    {
        assert($number >= 0 && $number <= 99999999);
        $letters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        return str_pad(strval($number), 8, '0', STR_PAD_LEFT) . $letters[$number % strlen($letters)];
    }
}
