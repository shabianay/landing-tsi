<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Recaptcha implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.google.recaptcha_secret_key');
        if (!$secret) return;

        if (! is_string($value) || $value === '') {
            $fail(__('Verifikasi reCAPTCHA gagal. Silakan coba lagi.'));

            return;
        }

        try {
            $response = Http::asForm()
                ->connectTimeout(3)
                ->timeout(5)
                ->retry(2, 200, throw: false)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $value,
                ]);
        } catch (ConnectionException) {
            $fail(__('Verifikasi reCAPTCHA gagal. Silakan coba lagi.'));

            return;
        }

        $body = $response->json();

        if (!($body['success'] ?? false)) {
            $fail(__('Verifikasi reCAPTCHA gagal. Silakan coba lagi.'));
        }
    }
}
