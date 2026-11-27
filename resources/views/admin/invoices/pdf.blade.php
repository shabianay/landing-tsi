<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    @php
        // Mirrors the studio's own invoice design (archive in "THE SOLUTION ID/Invoice").
        $rp = fn ($n) => 'Rp. ' . number_format((float) $n, 0, ',', '.');
        $badge = ['paid' => 'PAID', 'partial' => 'DP', 'unpaid' => 'UNPAID'][$invoice->status];
    @endphp
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1C2630; margin: 0; padding: 56px 62px 0; line-height: 1.45; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; padding: 0; }
        .blue { color: #3498DB; }
        .muted { color: #415060; }
        .label { font-size: 9.5px; font-weight: bold; text-transform: uppercase; color: #0F1720; margin-bottom: 2px; }
        .title { font-size: 34px; font-weight: bold; color: #3498DB; letter-spacing: .5px; line-height: 1; }
        .badge { display: inline-block; margin-top: 10px; width: 120px; padding: 4px 0; text-align: center; font-size: 12px; color: #1F78B4; background: #A8D8FA; }
        .items th { background: #3498DB; color: #fff; font-weight: bold; text-transform: uppercase; font-size: 10.5px; padding: 12px 10px; }
        .items td { padding: 12px 10px; font-size: 10px; }
        .items tbody tr:last-child td { border-bottom: 2px solid #3498DB; }
        .item-name { font-weight: bold; text-transform: uppercase; color: #0F1720; }
        .c { text-align: center; }
        .r { text-align: right; }
        .sum td { padding: 3px 0; font-size: 10.5px; }
        .sum .k { font-weight: bold; text-transform: uppercase; color: #0F1720; width: 44%; }
        .sum .colon { width: 12%; text-align: center; font-weight: bold; }
        .sum .v { text-align: center; }
        .total td { background: #3498DB; color: #fff; font-weight: bold; font-size: 11.5px; padding: 13px 0; }
        .footer { position: fixed; left: 62px; right: 62px; bottom: 46px; }
    </style>
</head>
<body>
    {{-- Logo + INVOICE / status --}}
    <table style="margin-bottom: 30px;">
        <tr>
            <td style="width: 55%;">
                @if ($logo)
                    <img src="{{ $logo }}" alt="" style="height: 60px; width: auto;">
                @else
                    <div style="font-size: 16px; font-weight: bold;">{{ $settings['invoice_company_name'] }}</div>
                @endif
            </td>
            <td style="width: 45%; text-align: right;">
                <div class="title">INVOICE</div>
                <span class="badge">{{ $badge }}</span>
            </td>
        </tr>
    </table>

    {{-- Sender / client / date + number --}}
    <table style="margin-bottom: 26px;">
        <tr>
            <td style="width: {{ $invoice->client_name ? '33%' : '55%' }}; padding-right: 12px;">
                @if ($invoice->client_name)
                    <div class="label">Pengirim</div>
                @endif
                <div class="muted">
                    @if ($settings['invoice_company_address']){!! nl2br(e($settings['invoice_company_address'])) !!}<br>@endif
                    @if ($settings['invoice_company_email']){{ $settings['invoice_company_email'] }}<br>@endif
                    @if ($settings['invoice_company_phone']){{ $settings['invoice_company_phone'] }}@endif
                </div>
            </td>
            @if ($invoice->client_name)
                <td style="width: 34%; padding-right: 12px;">
                    <div class="label">Kepada</div>
                    <div style="font-weight: bold; color: #0F1720;">{{ $invoice->client_name }}</div>
                    @if ($invoice->client_address)
                        <div class="muted">{!! nl2br(e($invoice->client_address)) !!}</div>
                    @endif
                </td>
            @endif
            <td style="width: {{ $invoice->client_name ? '33%' : '45%' }};">
                <div class="label">Tanggal</div>
                <div class="muted" style="margin-bottom: 14px;">{{ $invoice->invoice_date->locale('id')->translatedFormat('l, j F Y') }}</div>
                <div class="label">No. Invoice</div>
                <div class="muted">{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    {{-- Items --}}
    <table class="items">
        <thead>
            <tr>
                <th class="c" style="width: 38%;">Keterangan</th>
                <th class="c" style="width: 22%;">Harga</th>
                <th class="c" style="width: 16%;">Kuantitas</th>
                <th class="c" style="width: 24%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td class="item-name">{{ $item->item_name }}</td>
                    <td class="c muted">{{ $rp($item->item_price) }}</td>
                    <td class="c muted">{{ $item->item_qty }}</td>
                    <td class="c muted">{{ $rp($item->item_qty * (float) $item->item_price) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Payment + summary --}}
    <table style="margin-top: 150px;">
        <tr>
            <td style="width: 42%; padding-right: 30px;">
                @if ($settings['invoice_payment_info'])
                    <div class="label" style="font-size: 10.5px;">Metode Pembayaran</div>
                    <div class="muted">{!! nl2br(e($settings['invoice_payment_info'])) !!}</div>
                @endif
                @if ($settings['invoice_payment_note'] && $invoice->status !== 'paid')
                    <div class="muted" style="margin-top: 8px; text-align: justify;">{{ $settings['invoice_payment_note'] }}</div>
                @endif
                @if ($invoice->notes)
                    <div class="muted" style="margin-top: 8px;">{!! nl2br(e($invoice->notes)) !!}</div>
                @endif
            </td>
            <td style="width: 58%;">
                <table class="sum" style="padding: 0 18px;">
                    <tr><td class="k">Sub Total</td><td class="colon">:</td><td class="v muted">{{ $rp($invoice->subtotal) }}</td></tr>
                    <tr><td class="k">Diskon{{ $invoice->discount_type === 'percent' && (float) $invoice->discount_amount > 0 ? ' ' . rtrim(rtrim(number_format((float) $invoice->discount_amount, 2, ',', '.'), '0'), ',') . '%' : '' }}</td><td class="colon">:</td><td class="v muted">{{ $invoice->calculated_discount_value > 0 ? '- ' . $rp($invoice->calculated_discount_value) : '-' }}</td></tr>
                    <tr><td class="k">PPN {{ $invoice->ppn_rate_label }}%</td><td class="colon">:</td><td class="v muted">{{ (float) $invoice->ppn_amount > 0 ? $rp($invoice->ppn_amount) : '-' }}</td></tr>
                </table>
                <table class="total" style="margin-top: 14px;">
                    <tr>
                        <td style="width: 44%; padding-left: 18px;">TOTAL</td>
                        <td style="width: 12%; text-align: center;">:</td>
                        <td style="text-align: center;">{{ $rp($invoice->total) }}</td>
                    </tr>
                </table>
                @if ($invoice->status === 'partial')
                    <table class="sum" style="margin-top: 8px; padding: 0 18px;">
                        <tr><td class="k">Dibayar</td><td class="colon">:</td><td class="v muted">{{ $rp($invoice->paid_amount) }}</td></tr>
                        <tr><td class="k">Sisa</td><td class="colon">:</td><td class="v" style="font-weight: bold;">{{ $rp($invoice->remaining) }}</td></tr>
                    </table>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer">
        @if ($settings['invoice_footer'])
            <div style="font-weight: bold; color: #415060; font-size: 11px; margin-bottom: 10px;">{{ $settings['invoice_footer'] }}</div>
        @endif
        <div style="height: 9px; background: #3498DB;"></div>
    </div>
</body>
</html>
