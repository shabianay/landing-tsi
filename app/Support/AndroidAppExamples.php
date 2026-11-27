<?php

namespace App\Support;

/**
 * Example app screens for the Android service page and the home section.
 * Demo content only: names, numbers and items are illustrative, not client work.
 */
class AndroidAppExamples
{
    /** @return array<string, array{label: string, app: string, kicker: string, headline: string, rows: array<int, array{0: string, 1: string, 2: string}>, action: string}> */
    public static function all(): array
    {
        return [
            'toko' => [
                'label' => __('Toko Online'),
                'app' => 'Toko Bisnismu',
                'kicker' => 'Promo minggu ini',
                'headline' => 'Gratis ongkir se-kota',
                'rows' => [['Kemeja Linen', 'Rp149.000', 'done'], ['Tote Bag Kanvas', 'Rp89.000', 'done'], ['Topi Bucket', 'Rp65.000', 'todo'], ['Kaos Polos', 'Rp55.000', 'todo']],
                'action' => 'Masukkan keranjang',
            ],
            'kasir' => [
                'label' => __('Kasir (POS)'),
                'app' => 'Kasir Kedai',
                'kicker' => 'Pesanan meja 4',
                'headline' => 'Total Rp86.000',
                'rows' => [['Kopi Susu × 2', 'Rp44.000', 'done'], ['Roti Bakar × 1', 'Rp22.000', 'done'], ['Air Mineral × 2', 'Rp20.000', 'done'], ['Diskon member', '–', 'todo']],
                'action' => 'Bayar',
            ],
            'absensi' => [
                'label' => __('Absensi'),
                'app' => 'Absensi Karyawan',
                'kicker' => 'Senin, 08.00',
                'headline' => 'Masuk 07.58 · Tepat waktu',
                'rows' => [['Selasa', 'Hadir', 'done'], ['Rabu', 'Hadir', 'done'], ['Kamis', 'Izin', 'warn'], ['Jumat', '—', 'todo']],
                'action' => 'Absen pulang',
            ],
            'stok' => [
                'label' => __('Inventaris/Stok Barang'),
                'app' => 'Stok Gudang',
                'kicker' => 'Perlu dicek',
                'headline' => '2 barang hampir habis',
                'rows' => [['Kardus besar', '12', 'warn'], ['Lakban bening', '8', 'warn'], ['Plastik wrap', '140', 'done'], ['Label pengiriman', '560', 'done']],
                'action' => 'Tambah stok',
            ],
            'booking' => [
                'label' => __('Pemesanan & Booking'),
                'app' => 'Booking Studio',
                'kicker' => 'Sabtu',
                'headline' => 'Pilih jam sesi',
                'rows' => [['09.00 – 10.00', 'Penuh', 'done'], ['10.00 – 11.00', 'Tersedia', 'todo'], ['13.00 – 14.00', 'Tersedia', 'todo'], ['15.00 – 16.00', 'Penuh', 'done']],
                'action' => 'Pesan sekarang',
            ],
            'sekolah' => [
                'label' => __('Sekolah/E-Learning'),
                'app' => 'Kelas Online',
                'kicker' => 'Matematika · Kelas 8',
                'headline' => 'Tugas 3 dikumpulkan besok',
                'rows' => [['Modul 1 · Aljabar', 'Selesai', 'done'], ['Modul 2 · Persamaan', 'Selesai', 'done'], ['Modul 3 · Grafik', '60%', 'warn'], ['Kuis akhir', 'Terkunci', 'todo']],
                'action' => 'Lanjut belajar',
            ],
            'skripsi' => [
                'label' => __('Aplikasi Tugas Kuliah/Skripsi'),
                'app' => 'Sistem Pakar',
                'kicker' => 'Diagnosa',
                'headline' => 'Jawab 5 pertanyaan',
                'rows' => [['Gejala 1', 'Ya', 'done'], ['Gejala 2', 'Tidak', 'done'], ['Gejala 3', '—', 'todo'], ['Hasil', '—', 'todo']],
                'action' => 'Lanjut',
            ],
            'profil' => [
                'label' => 'Company Profile',
                'app' => 'PT Bisnismu',
                'kicker' => 'Sejak 2015',
                'headline' => 'Solusi logistik antar pulau',
                'rows' => [['Layanan', '›', 'done'], ['Armada', '›', 'done'], ['Klien kami', '›', 'todo'], ['Hubungi kami', '›', 'todo']],
                'action' => 'Minta penawaran',
            ],
            'konversi' => [
                'label' => __('Konversi Website ke Aplikasi'),
                'app' => 'bisnismu.com',
                'kicker' => 'Website Anda',
                'headline' => 'Sekarang ada di Play Store',
                'rows' => [['Login pelanggan', 'Aktif', 'done'], ['Notifikasi', 'Aktif', 'done'], ['Mode offline', 'Opsional', 'todo'], ['Menu website', 'Tersinkron', 'done']],
                'action' => 'Buka aplikasi',
            ],
            'lainnya' => [
                'label' => __('Lainnya (Konsultasi Dulu)'),
                'app' => 'Aplikasi Anda',
                'kicker' => 'Belum ada di daftar?',
                'headline' => 'Ceritakan idenya',
                'rows' => [['Siapa penggunanya?', '?', 'todo'], ['Masalah apa yang diselesaikan?', '?', 'todo'], ['Fitur wajib', '?', 'todo'], ['Target selesai', '?', 'todo']],
                'action' => 'Konsultasi gratis',
            ],
        ];
    }
}
