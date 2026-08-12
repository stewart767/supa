<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CseeNumberRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Format e.g. S0101/0001/2022 or P0101/0001/2022
        if (!preg_match('/^[SP]\d{4}\/\d{4}\/\d{4}$/i', (string) $value)) {
            $fail('The :attribute must follow standard NECTA index format (e.g., S0101/0001/2022).');
        }
    }
}
