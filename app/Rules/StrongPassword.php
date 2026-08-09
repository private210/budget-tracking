<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    /**
     * Min 10 karakter, wajib huruf besar, huruf kecil, angka, dan simbol.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen((string) $value) < 10) {
            $fail('Kata sandi minimal 10 karakter.');
        }

        if (! preg_match('/[a-z]/', (string) $value)) {
            $fail('Kata sandi harus mengandung huruf kecil.');
        }

        if (! preg_match('/[A-Z]/', (string) $value)) {
            $fail('Kata sandi harus mengandung huruf besar.');
        }

        if (! preg_match('/[0-9]/', (string) $value)) {
            $fail('Kata sandi harus mengandung angka.');
        }

        if (! preg_match('/[^a-zA-Z0-9]/', (string) $value)) {
            $fail('Kata sandi harus mengandung simbol (contoh: @ # $ % !).');
        }
    }
}
