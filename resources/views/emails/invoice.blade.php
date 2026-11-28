@php($rp = fn ($n) => 'Rp' . number_format((float) $n, 0, ',', '.'))
<x-mail::message>
# Invoice {{ $invoice->invoice_number }}

Halo {{ $invoice->client_name }},

@if ($personalMessage)
{{ $personalMessage }}

@else
berikut invoice dari {{ $company }}. File PDF-nya kami lampirkan di email ini.

@endif
<x-mail::table>
| | |
|:--|--:|
| Tanggal | {{ $invoice->invoice_date->locale('id')->translatedFormat('j F Y') }} |
| Total | **{{ $rp($invoice->total) }}** |
@if ($invoice->status === 'partial')
| Sudah dibayar | {{ $rp($invoice->paid_amount) }} |
| Sisa | **{{ $rp($invoice->remaining) }}** |
@endif
| Status | {{ $invoice->status_label }} |
</x-mail::table>

@if ($invoice->status !== 'paid' && $paymentInfo)
**Pembayaran ke:**<br>
{!! nl2br(e($paymentInfo)) !!}

@endif
<x-mail::button :url="$link">
Lihat invoice
</x-mail::button>

Terima kasih,<br>
{{ $company }}
</x-mail::message>
