<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotBlank implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (self::isBlank(strval($value)))
            $fail('El campo :attribute no puede estar en blanco.');
    }

    public static function isBlank(string $string): bool
    {
        return preg_match("/^(\s*)$/", $string) === 1;
    }
}
