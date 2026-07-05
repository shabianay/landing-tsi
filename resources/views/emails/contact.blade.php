@component('mail::message')
# <span style="color: #1e40af;">{{ __('Pesan Baru dari :name', ['name' => $name]) }}</span>

{{ __('Seseorang telah mengirim pesan melalui formulir kontak website. Berikut detailnya:') }}

@component('mail::panel')
**{{ __('Nama') }}:** {{ $name }}
**{{ __('Email') }}:** [{{ $email }}](mailto:{{ $email }})
**{{ __('Subjek') }}:** {{ $subject ?? __('Tidak ada subjek') }}
@endcomponent

**{{ __('Pesan:') }}**

> {{ $messageText }}

@component('mail::button', ['url' => 'mailto:' . $email, 'color' => 'primary'])
{{ __('Balas Pesan Ini') }}
@endcomponent

{{ __('Terima kasih') }},<br>
**{{ config('app.name') }}**
@endcomponent
