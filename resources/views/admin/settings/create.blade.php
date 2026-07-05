@extends('layouts.admin')

@section('title', __('Tambah Setting — Admin TSI'))

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Tambah Setting') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Buat pengaturan baru') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8">
        <form method="POST" action="{{ route('admin.settings.store') }}">
            @csrf
            <div class="space-y-6">
                <div>
                    <label for="key" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Key') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="key" id="key" value="{{ old('key') }}" required class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30" placeholder="site_name">
                    @error('key') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="label" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Label') }}</label>
                    <input type="text" name="label" id="label" value="{{ old('label') }}" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30" placeholder="Nama Situs">
                    @error('label') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="value" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Value') }}</label>
                    <textarea name="value" id="value" rows="3" class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30">{{ old('value') }}</textarea>
                    @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="group" class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Grup') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="group" id="group" value="{{ old('group') }}" required class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30" placeholder="general">
                    @error('group') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 rounded border-dark-100/20 text-primary-500 focus:ring-primary-500/20">
                    <label for="is_active" class="text-sm font-semibold text-dark-300">{{ __('Aktif') }}</label>
                </div>
            </div>
            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-dark-100/5">
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl transition-all duration-200 text-sm">{{ __('Simpan') }}</button>
                <a href="{{ route('admin.settings.index') }}" class="px-6 py-3 text-dark-100/50 font-medium rounded-xl hover:bg-dark-50/80 transition-all duration-200 text-sm">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection