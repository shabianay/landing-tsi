@extends('layouts.master')

@section('title', __('Pendaftaran diterima — The Solution ID'))

@section('content')
<section class="relative flex min-h-[70vh] items-center justify-center overflow-hidden px-4 pb-20 pt-36 lg:pt-40">
    <div class="glow top-0 -left-40 h-[400px] w-[400px] bg-primary-200/30" aria-hidden="true"></div>
    <div class="glow bottom-0 -right-40 h-[400px] w-[400px] bg-blue-200/30" aria-hidden="true"></div>
    <div class="card relative z-10 w-full max-w-2xl !p-8 text-center sm:!p-12">
        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-primary-50 to-primary-100 text-primary-600" aria-hidden="true">
            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 13 4 4L19 7"/></svg>
        </div>
        <span class="section-label mt-6">{{ __('Pendaftaran diterima') }}</span>
        <h1 class="section-title mt-5 !text-3xl sm:!text-4xl">{{ __('Terima kasih sudah mendaftar!') }}</h1>
        <p class="section-subtitle mx-auto mt-4 max-w-xl !text-base">{{ __('Tim The Solution ID akan meninjau pendaftaranmu dan menghubungi melalui WhatsApp jika sesuai dengan kebutuhan kami.') }}</p>
        <a href="{{ route('home') }}" class="btn-primary mt-8">{{ __('Kembali ke beranda') }}</a>
    </div>
</section>
@endsection
