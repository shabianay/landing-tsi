<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi {{ $payment->receipt_number }}</title>
    @php
        // Same visual language as the invoice PDF (pdf.blade.php), A5 landscape.
        $rp = fn ($n) => 'Rp. ' . number_format((float) $n, 0, ',', '.');
        $forWhat = $invoice->items->pluck('item_name')->implode(', ');
    @endphp
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1C2630; margin: 0; padding: 34px 44px 0; line-height: 1.45; }
        table { border-collapse: collapse; width: 100%; }
        td { vertical-align: top; padding: 0; }
        .muted { color: #415060; }
        .title { font-size: 26px; font-weight: bold; color: #3498DB; letter-spacing: .5px; line-height: 1; }
        .rows td { padding: 6px 0; border-bottom: 1px solid #DDE2E7; }
        .rows .k { width: 30%; font-weight: bold; text-transform: uppercase; font-size: 9px; color: #0F1720; }
        .amount { background: #3498DB; color: #fff; font-weight: bold; font-size: 15px; padding: 10px 16px; }
        .footer { position: fixed; left: 44px; right: 44px; bottom: 26px; }
    </style>
</head>
<body>
    <table style="margin-bottom: 18px;">
        <tr>
            <td style="width: 50%;">
                @if ($logo)
                    <img src="{{ $logo }}" alt="" style="height: 42px; width: auto;">
                @else
                    <div style="font-size: 14px; font-weight: bold;">{{ $settings['invoice_company_name'] }}</div>
                @endif
            </td>
            <td style="width: 50%; text-align: right;">
                <div class="title">KWITANSI</div>
                <div class="muted" style="margin-top: 6px;">No. {{ $payment->receipt_number }}</div>
            </td>
        </tr>
    </table>

    <table class="rows">
        <tr><td class="k">Telah diterima dari</td><td><strong>{{ $invoice->client_name }}</strong></td></tr>
        <tr><td class="k">Terbilang</td><td style="font-style: italic;">{{ ucfirst($words) }}</td></tr>
        <tr><td class="k">Untuk pembayaran</td><td>Invoice {{ $invoice->invoice_number }}{{ $forWhat ? ' — ' . $forWhat : '' }}@if ($payment->note)<br><span class="muted">{{ $payment->note }}</span>@endif</td></tr>
        <tr><td class="k">Tanggal / metode</td><td>{{ $payment->paid_at->locale('id')->translatedFormat('j F Y') }}{{ $payment->method ? ' · ' . $payment->method : '' }}</td></tr>
        <tr><td class="k">Sisa tagihan</td><td>{{ $remainingAfter > 0 ? $rp($remainingAfter) . ' dari total ' . $rp($invoice->amount_due) : 'Lunas' }}</td></tr>
    </table>

    <table style="margin-top: 18px;">
        <tr>
            <td style="width: 55%;"><div class="amount">{{ $rp($payment->amount) }}</div></td>
            <td style="width: 45%; text-align: center;" class="muted">
                {{ $settings['invoice_company_name'] }}
            </td>
        </tr>
    </table>

    <div class="footer">
        @if ($settings['invoice_footer'])
            <div style="font-weight: bold; color: #415060; margin-bottom: 6px;">{{ $settings['invoice_footer'] }}</div>
        @endif
        <div style="height: 6px; background: #3498DB;"></div>
    </div>
</body>
</html>
