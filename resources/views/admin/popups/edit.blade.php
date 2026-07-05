@extends('layouts.admin')

@section('title', __('Edit Popup — Admin TSI'))

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.popups.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Edit Popup') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Perbarui popup marketing') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('admin.popups.update', $popup) }}">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <x-admin.locale-tabs :model="$popup" field="title" label="{{ __('Judul') }}" required="true" placeholder="{{ __('Dapatkan Diskon 20%!') }}" />
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <x-admin.locale-tabs :model="$popup" field="content" label="{{ __('Konten') }}" type="textarea" :rows="4" required="true" placeholder="{{ __('Isi pesan marketing di sini...') }}" />
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <x-admin.locale-tabs :model="$popup" field="button_text" label="{{ __('Teks Tombol') }}" placeholder="{{ __('Hubungi Kami') }}" />
                        @error('button_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="button_url" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('URL Tombol') }}</label>
                        <input type="url" name="button_url" id="button_url" value="{{ old('button_url', $popup->button_url) }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30">
                        @error('button_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="display_delay" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Delay Tampil (detik)') }}</label>
                        <input type="number" name="display_delay" id="display_delay" value="{{ old('display_delay', $popup->display_delay) }}" min="0" max="30" class="w-24 px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium">
                        @error('display_delay') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-3 pt-8">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $popup->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-dark-100/20 text-primary-500 focus:ring-primary-500/20">
                        <label for="is_active" class="text-sm font-semibold text-dark-300">{{ __('Aktif') }}</label>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_at" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Mulai Tampil') }}</label>
                        <input type="datetime-local" name="start_at" id="start_at" value="{{ old('start_at', $popup->start_at?->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium">
                        @error('start_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="end_at" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Selesai Tampil') }}</label>
                        <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at', $popup->end_at?->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium">
                        @error('end_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-dark-100/5">
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl transition-all duration-200 text-sm">{{ __('Simpan') }}</button>
                <a href="{{ route('admin.popups.index') }}" class="px-6 py-3 text-dark-100/50 font-medium rounded-xl hover:bg-dark-50/80 transition-all duration-200 text-sm">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
