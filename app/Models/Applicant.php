<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'baru' => 'Baru',
        'dihubungi' => 'Dihubungi',
        'wawancara' => 'Wawancara',
        'tugas_seleksi' => 'Tugas seleksi',
        'perjanjian_ditandatangani' => 'Perjanjian ditandatangani',
        'masa_percobaan' => 'Masa percobaan',
        'partner_aktif' => 'Partner aktif',
        'ditolak' => 'Ditolak',
    ];

    /** Recruitment stages before an agreement exists. */
    public const PRE_AGREEMENT_STATUSES = ['baru', 'dihubungi', 'wawancara', 'tugas_seleksi'];

    /** Stages that require a signed agreement; these may not move back to recruitment stages. */
    public const POST_AGREEMENT_STATUSES = ['perjanjian_ditandatangani', 'masa_percobaan', 'partner_aktif'];

    public const ACTIVITIES = [
        'mahasiswa' => 'Mahasiswa',
        'bekerja' => 'Bekerja',
        'freelance' => 'Freelance',
        'lainnya' => 'Lainnya',
    ];

    protected $fillable = [
        'full_name',
        'whatsapp',
        'domicile',
        'birth_date',
        'current_activity',
        'experience',
        'motivation',
        'social_link',
        'agreed_commission',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'agreed_commission' => 'boolean',
            'agreement_signed_at' => 'datetime',
        ];
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicantStatusLog::class)->latest('created_at');
    }

    public function partnerLeads(): HasMany
    {
        return $this->hasMany(PartnerLead::class);
    }
}
