<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.google.recaptcha_secret_key');
        if (!$secret) return;

        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secret,
            'response' => $value,
        ]);

        $body = $response->json();

        if (!($body['success'] ?? false)) {
            $fail(__('Verifikasi reCAPTCHA gagal. Silakan coba lagi.'));
        }
    }
}
