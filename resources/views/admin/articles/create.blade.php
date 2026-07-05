@extends('layouts.admin')

@section('title', __('Tambah Artikel — Admin TSI'))

@section('content')
<div class="max-w-4xl">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('admin.articles.index') }}" class="flex items-center justify-center w-10 h-10 rounded-xl text-dark-100/40 bg-white border border-dark-100/10 hover:border-primary-500/30 hover:text-primary-600 transition-all duration-200 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-dark-300 tracking-tight">{{ __('Tambah Artikel') }}</h1>
            <p class="text-dark-100/50 mt-1 font-medium text-sm">{{ __('Buat artikel blog baru') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-dark-100/5 shadow-sm p-6 md:p-8">
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <x-admin.locale-tabs :model="null" field="title" label="{{ __('Judul Artikel') }}" required="true" placeholder="{{ __('Masukkan judul artikel') }}" />

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Slug') }}</label>
                    <input type="text" name="slug" value="{{ old('slug') }}"
                           class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30 @error('slug') border-red-500 @enderror"
                           placeholder="judul-artikel">
                    @error('slug') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <x-admin.locale-tabs :model="null" field="category" label="{{ __('Kategori') }}" placeholder="{{ __('Website, SEO, Digital Marketing') }}" />
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Penulis') }}</label>
                    <input type="text" name="author" value="{{ old('author', 'The Solution ID') }}"
                           class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Tags (dipisah koma)') }}</label>
                    <input type="text" name="tags" value="{{ old('tags') }}"
                           class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30"
                           placeholder="web, seo, digital">
                </div>
            </div>

            <x-admin.locale-tabs :model="null" field="content" type="textarea" rows="15" label="{{ __('Konten') }}" required="true" placeholder="{{ __('Tulis konten artikel di sini...') }}" />

            <x-admin.locale-tabs :model="null" field="excerpt" type="textarea" rows="3" label="{{ __('Excerpt (ringkasan)') }}" placeholder="{{ __('Ringkasan artikel') }}" />

            <div>
                <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Gambar') }}</label>
                <input type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"
                       class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                <p class="text-xs text-dark-100/40 mt-1.5">{{ __('Format: JPG, PNG, WebP. Maks 5MB. Otomatis dikonversi ke WebP.') }}</p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('Alt Text (deskripsi gambar)') }}</label>
                <input type="text" name="alt_text" value="{{ old('alt_text') }}"
                       class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30"
                       placeholder="{{ __('Deskripsi singkat gambar untuk SEO & aksesibilitas') }}">
            </div>

            <div>
                <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('OG Image (social sharing)') }}</label>
                <input type="file" name="og_image" accept="image/jpeg,image/png,image/gif,image/webp"
                       class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-600 hover:file:bg-primary-100">
                <p class="text-xs text-dark-100/40 mt-1.5">{{ __('Khusus untuk thumbnail pas dishare ke WhatsApp/Facebook. 1200x630px ideal.') }}</p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                <x-admin.locale-tabs :model="null" field="seo_title" label="{{ __('SEO Title') }}" placeholder="{{ __('Judul untuk SEO (optional)') }}" />

                <div>
                    <label class="block text-sm font-semibold text-dark-300 mb-2">{{ __('SEO Keywords') }}</label>
                    <input type="text" name="seo_keywords" value="{{ old('seo_keywords') }}"
                           class="w-full px-4 py-3 bg-dark-50/50 border border-dark-100/10 rounded-xl focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 outline-none transition-all font-medium placeholder-dark-100/30"
                           placeholder="keyword1, keyword2, keyword3">
                </div>
            </div>

            <x-admin.locale-tabs :model="null" field="seo_description" type="textarea" rows="2" label="{{ __('SEO Description') }}" placeholder="{{ __('Meta deskripsi') }}" />

            <div class="flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_published" value="1" class="sr-only peer" {{ old('is_published') ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-dark-100/20 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500/20 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-500"></div>
                </label>
                <span class="text-sm font-semibold text-dark-300">{{ __('Publikasikan langsung') }}</span>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-dark-100/5">
                <button type="submit" class="px-6 py-3 bg-primary-500 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/25 hover:bg-primary-600 hover:shadow-xl transition-all duration-200 text-sm">{{ __('Simpan') }}</button>
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-3 text-dark-100/50 font-medium rounded-xl hover:bg-dark-50/80 transition-all duration-200 text-sm">{{ __('Batal') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
