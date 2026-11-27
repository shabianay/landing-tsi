<?php

namespace App\Http\Requests;

use App\Models\Applicant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreApplicantRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $phone = $this->input('whatsapp');

        if (! is_string($phone)) {
            return;
        }

        $digits = preg_replace('/\D+/', '', $phone);

        if (str_starts_with($digits, '08')) {
            $digits = '62'.substr($digits, 1);
        }

        $this->merge(['whatsapp' => $digits]);
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:100'],
            'whatsapp' => [
                'bail',
                'required',
                'string',
                'regex:/^628[0-9]{8,11}$/',
                // Hidden (soft-deleted) applicants may apply again; JobController restores their record.
                Rule::unique('applicants', 'whatsapp')->withoutTrashed(),
            ],
            'domicile' => ['required', 'string', 'max:100'],
            'birth_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:'.now()->subYears(18)->toDateString(),
            ],
            'current_activity' => ['required', Rule::in(array_keys(Applicant::ACTIVITIES))],
            'experience' => ['nullable', 'string', 'max:5000'],
            'motivation' => ['required', 'string', 'min:20', 'max:2000'],
            'social_link' => [
                'required',
                'url',
                'max:255',
                'regex:/^https?:\/\/(www\.)?(instagram\.com|linkedin\.com)\//i',
            ],
            'agreed_commission' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'whatsapp.regex' => 'Masukkan nomor WhatsApp Indonesia yang diawali 08 atau 628.',
            'whatsapp.unique' => 'Nomor WhatsApp ini sudah terdaftar.',
            'motivation.min' => 'Alasan tertarik minimal 20 karakter.',
            'motivation.max' => __('Alasan ketertarikan maksimal 2.000 karakter.'),
            'birth_date.before_or_equal' => __('Pendaftar harus berusia minimal 18 tahun.'),
            'social_link.regex' => 'Gunakan tautan Instagram atau LinkedIn yang valid.',
            'agreed_commission.accepted' => 'Persetujuan sistem komisi wajib dicentang.',
        ];
    }
}
