@extends('layouts.master')

@section('title', __('Pendaftaran diterima — The Solution ID'))

@section('content')
<section class="py-16 md:py-28">
    <div class="wrap max-w-2xl">
        <x-blockmark size="1.5rem" />
        <p class="mt-8 text-sm font-semibold text-primary-700">{{ __('Pendaftaran diterima') }}</p>
        <h1 class="display-2 mt-2">{{ __('Terima kasih sudah mendaftar!') }}</h1>
        <p class="lede mt-5">{{ __('Tim The Solution ID akan meninjau pendaftaranmu dan menghubungi melalui WhatsApp jika sesuai dengan kebutuhan kami.') }}</p>
        <a href="{{ route('home') }}" class="btn-primary mt-10">{{ __('Kembali ke beranda') }}</a>
    </div>
</section>
@endsection
