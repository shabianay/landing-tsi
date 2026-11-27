<?php

namespace App\Support;

/**
 * Example website screens for the home "Jasa" section.
 * Demo content only: brands and copy are illustrative, not client work.
 */
class WebsiteExamples
{
    /** @return array<string, array{label: string, url: string, brand: string, headline: string, sub: string, cta: string, tiles: array<int, string>}> */
    public static function all(): array
    {
        return [
            'profil' => [
                'label' => 'Company Profile',
                'url' => 'bisnismu.co.id',
                'brand' => 'PT Bisnismu',
                'headline' => 'Mitra logistik antar pulau sejak 2015',
                'sub' => 'Layanan · Armada · Klien · Kontak',
                'cta' => 'Minta penawaran',
                'tiles' => ['Layanan', 'Armada', 'Klien kami'],
            ],
            'landing' => [
                'label' => 'Landing Page',
                'url' => 'promo.bisnismu.com',
                'brand' => 'Kelas Desain',
                'headline' => 'Kuasai desain grafis dalam 30 hari',
                'sub' => 'Kelas online · sertifikat · mentor',
                'cta' => 'Daftar sekarang',
                'tiles' => ['Materi', 'Testimoni', 'Harga'],
            ],
            'toko' => [
                'label' => __('Toko Online'),
                'url' => 'tokobisnismu.com',
                'brand' => 'Toko Bisnismu',
                'headline' => 'Koleksi baru sudah datang',
                'sub' => 'Gratis ongkir se-kota',
                'cta' => 'Belanja sekarang',
                'tiles' => ['Kemeja Linen', 'Tote Bag', 'Topi Bucket'],
            ],
            'sistem' => [
                'label' => __('Sistem Informasi'),
                'url' => 'ppdb.sekolahmu.sch.id',
                'brand' => 'PPDB Online',
                'headline' => 'Pendaftaran siswa baru dibuka',
                'sub' => 'Daftar · Unggah berkas · Cek hasil',
                'cta' => 'Daftar online',
                'tiles' => ['Pendaftar 512', 'Diverifikasi 430', 'Diterima 280'],
            ],
            'lainnya' => [
                'label' => __('Lainnya (Konsultasi Dulu)'),
                'url' => 'websiteanda.com',
                'brand' => 'Website Anda',
                'headline' => 'Belum ada di daftar? Ceritakan idenya',
                'sub' => 'Kita petakan halaman dan fiturnya bersama',
                'cta' => 'Konsultasi gratis',
                'tiles' => ['Halaman?', 'Fitur?', 'Target?'],
            ],
        ];
    }
}
