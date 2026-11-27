@extends('errors.layout')

@section('code', '419')
@section('title', 'Sesi kedaluwarsa')
@section('message', 'Halaman terlalu lama dibiarkan terbuka. Muat ulang halaman, lalu kirim lagi.')
@section('actions')
    <a href="{{ url()->previous() }}" class="btn-primary">Kembali</a>
    <a href="{{ url('/') }}" class="btn-secondary">Ke beranda</a>
@endsection
