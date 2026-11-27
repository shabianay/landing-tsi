@extends('errors.layout')

@section('code', '503')
@section('title', 'Sedang diperbarui')
@section('message', 'Kami sedang memperbarui situs. Biasanya hanya beberapa menit, silakan kembali sebentar lagi.')
@section('actions')
    <a href="{{ url()->current() }}" class="btn-primary">Muat ulang</a>
@endsection
