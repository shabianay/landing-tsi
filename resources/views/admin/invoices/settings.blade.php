@extends('layouts.admin')

@section('title', 'Pengaturan invoice — Admin TSI')

@section('content')
@include('admin.partials.pm-head', [
    'title' => 'Pengaturan invoice',
    'subtitle' => 'Data usaha yang dicetak di setiap invoice PDF.',
    'actions' => '<a href="' . route('admin.invoices.index') . '" class="btn-secondary !min-h-11 !px-4 text-sm">Kembali ke Invoice</a>',
])

<form method="POST" action="{{ route('admin.invoices.settings.update') }}" enctype="multipart/form-data" class="max-w-3xl space-y-6 border border-line bg-white p-5 sm:p-8">
    @csrf

    <div class="grid gap-6 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="invoice_company_name" class="field-label">Nama usaha *</label>
            <input id="invoice_company_name" name="invoice_company_name" value="{{ old('invoice_company_name', $settings['invoice_company_name']) }}" required maxlength="255" class="field">
        </div>
        <div>
            <label for="invoice_company_phone" class="field-label">Telepon</label>
            <input id="invoice_company_phone" name="invoice_company_phone" value="{{ old('invoice_company_phone', $settings['invoice_company_phone']) }}" maxlength="50" class="field num">
        </div>
        <div>
            <label for="invoice_company_email" class="field-label">Email</label>
            <input id="invoice_company_email" type="email" name="invoice_company_email" value="{{ old('invoice_company_email', $settings['invoice_company_email']) }}" maxlength="255" class="field">
        </div>
    </div>
    <div>
        <label for="invoice_company_address" class="field-label">Alamat</label>
        <textarea id="invoice_company_address" name="invoice_company_address" rows="2" maxlength="1000" class="field">{{ old('invoice_company_address', $settings['invoice_company_address']) }}</textarea>
    </div>
    <div>
        <label for="invoice_payment_info" class="field-label">Info pembayaran</label>
        <textarea id="invoice_payment_info" name="invoice_payment_info" rows="3" maxlength="1000" class="field" placeholder="Contoh: BCA 1234567890 a.n. Nama Pemilik">{{ old('invoice_payment_info', $settings['invoice_payment_info']) }}</textarea>
        <p class="field-hint">Nomor rekening atau e-wallet tujuan transfer. Dicetak di kiri bawah invoice.</p>
    </div>
    <div>
        <label for="invoice_payment_note" class="field-label">Catatan pembayaran</label>
        <textarea id="invoice_payment_note" name="invoice_payment_note" rows="3" maxlength="1000" class="field">{{ old('invoice_payment_note', $settings['invoice_payment_note']) }}</textarea>
        <p class="field-hint">Dicetak di bawah info pembayaran, misalnya ketentuan DP. Kosongkan bila tidak perlu.</p>
    </div>
    <div class="max-w-xs">
        <label for="invoice_ppn_rate" class="field-label">Tarif PPN (%)</label>
        <input id="invoice_ppn_rate" type="number" name="invoice_ppn_rate" min="0" max="100" step="0.01" value="{{ old('invoice_ppn_rate', $ppnRate) }}" class="field num" @error('invoice_ppn_rate') aria-invalid="true" @enderror>
        <p class="field-hint">Dipakai untuk invoice baru yang dicentang "Kenakan PPN", dihitung dari harga setelah diskon. Invoice lama tetap memakai tarif saat dibuat.</p>
    </div>
    <div>
        <label for="invoice_footer" class="field-label">Catatan kaki</label>
        <input id="invoice_footer" name="invoice_footer" value="{{ old('invoice_footer', $settings['invoice_footer']) }}" maxlength="500" class="field">
    </div>
    <div>
        <label for="invoice_logo" class="field-label">Logo invoice (PNG/JPG, maks. 2 MB)</label>
        <input id="invoice_logo" type="file" name="invoice_logo" accept="image/png,image/jpeg" class="field !py-2">
        <p class="field-hint">{{ ! empty($settings['invoice_logo']) ? 'Logo sudah diunggah. Unggah lagi untuk mengganti.' : 'Kosongkan untuk memakai logo website.' }}</p>
    </div>

    <div class="flex flex-wrap gap-3 border-t border-line pt-6">
        <button type="submit" class="btn-primary">Simpan pengaturan</button>
        <a href="{{ route('admin.sim-import.create') }}" class="ml-auto self-center text-sm font-semibold text-primary-700 underline">Impor data dari SIM-Proyek</a>
    </div>
</form>
@endsection
