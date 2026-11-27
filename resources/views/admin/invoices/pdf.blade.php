<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1C2630; margin: 0; padding: 46px 50px; line-height: 1.5; }
        table { border-collapse: collapse; width: 100%; }
        td, th { vertical-align: top; padding: 0; }
        .muted { color: #415060; }
        .label { font-size: 8.5px; font-weight: bold; letter-spacing: .6px; text-transform: uppercase; color: #415060; margin-bottom: 3px; }
        .title { font-size: 30px; font-weight: bold; color: #0F1720; letter-spacing: 1px; }
        .badge { display: inline-block; margin-top: 8px; padding: 3px 12px; font-size: 9px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background: #E3F5EA; color: #1E6B3A; }
        .badge-partial { background: #FFF4D6; color: #7A5300; }
        .badge-unpaid { background: #FDE7E7; color: #9B1C1C; }
        .items th { background: #0F1720; color: #fff; font-size: 8.5px; text-transform: uppercase; letter-spacing: .5px; padding: 9px 12px; text-align: left; }
        .items td { padding: 10px 12px; border-bottom: 1px solid #DDE2E7; }
        .r { text-align: right; }
        .c { text-align: center; }
        .sum td { padding: 4px 0; }
        .total td { background: #3498DB; color: #0F1720; font-weight: bold; font-size: 12px; padding: 10px 12px; }
        .block { display: inline-block; width: 9px; height: 9px; margin: 0 1px 1px 0; }
    </style>
</head>
<body>
    <table style="margin-bottom: 26px;">
        <tr>
            <td style="width: 55%;">
                @if ($logo)
                    <img src="{{ $logo }}" alt="" style="height: 42px; width: auto; margin-bottom: 8px;">
                @else
                    {{-- The logo's block mark, drawn in CSS --}}
                    <div style="margin-bottom: 8px; line-height: 0;">
                        <span class="block" style="background:#415060"></span><br>
                        <span class="block" style="background:#415060"></span><span class="block" style="background:#415060"></span><br>
                        <span class="block" style="background:#3498DB"></span>
                    </div>
                @endif
                <div style="font-size: 14px; font-weight: bold; color: #0F1720;">{{ $settings['invoice_company_name'] }}</div>
                <div class="muted" style="font-size: 9.5px; margin-top: 3px;">
                    @if ($settings['invoice_company_address']){!! nl2br(e($settings['invoice_company_address'])) !!}<br>@endif
                    @if ($settings['invoice_company_email']){{ $settings['invoice_company_email'] }}<br>@endif
                    @if ($settings['invoice_company_phone']){{ $settings['invoice_company_phone'] }}@endif
                </div>
            </td>
            <td style="width: 45%; text-align: right;">
                <div class="title">INVOICE</div>
                <span class="badge badge-{{ $invoice->status }}">{{ $invoice->status_label }}</span>
            </td>
        </tr>
    </table>

    <table style="border-top: 1px solid #DDE2E7; margin-bottom: 22px;">
        <tr>
            <td style="width: 55%; padding-top: 14px;">
                <div class="label">Ditagihkan kepada</div>
                <div style="font-weight: bold; font-size: 11px;">{{ $invoice->client_name }}</div>
                @if ($invoice->client_address)
                    <div class="muted" style="font-size: 9.5px;">{!! nl2br(e($invoice->client_address)) !!}</div>
                @endif
            </td>
            <td style="width: 45%; padding-top: 14px; text-align: right;">
                <div class="label">Tanggal</div>
                <div style="font-weight: bold; margin-bottom: 10px;">{{ $invoice->invoice_date->locale('id')->translatedFormat('l, d F Y') }}</div>
                <div class="label">No. invoice</div>
                <div style="font-weight: bold; font-size: 11.5px;">{{ $invoice->invoice_number }}</div>
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="r" style="width: 19%;">Harga</th>
                <th class="c" style="width: 12%;">Jumlah</th>
                <th class="r" style="width: 19%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->items as $item)
                <tr>
                    <td style="font-weight: bold;">{{ $item->item_name }}</td>
                    <td class="r">Rp{{ number_format((float) $item->item_price, 0, ',', '.') }}</td>
                    <td class="c">{{ $item->item_qty }}</td>
                    <td class="r">Rp{{ number_format($item->item_qty * (float) $item->item_price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top: 22px;">
        <tr>
            <td style="width: 52%; padding-right: 24px;">
                @if ($settings['invoice_payment_info'])
                    <div class="label">Pembayaran</div>
                    <div style="font-size: 10px;">{!! nl2br(e($settings['invoice_payment_info'])) !!}</div>
                @endif
                @if ($invoice->notes)
                    <div style="margin-top: 14px; padding-top: 10px; border-top: 1px solid #DDE2E7;">
                        <div class="label">Catatan</div>
                        <div class="muted" style="font-size: 9.5px;">{!! nl2br(e($invoice->notes)) !!}</div>
                    </div>
                @endif
            </td>
            <td style="width: 48%;">
                <table class="sum">
                    <tr><td class="muted">Subtotal</td><td class="r">Rp{{ number_format($invoice->subtotal, 0, ',', '.') }}</td></tr>
                    @if ((float) $invoice->ppn_amount > 0)
                        <tr><td class="muted">PPN 10%</td><td class="r">Rp{{ number_format((float) $invoice->ppn_amount, 0, ',', '.') }}</td></tr>
                    @endif
                    @if ($invoice->calculated_discount_value > 0)
                        <tr><td class="muted">Diskon{{ $invoice->discount_type === 'percent' ? ' (' . rtrim(rtrim(number_format((float) $invoice->discount_amount, 2, ',', '.'), '0'), ',') . '%)' : '' }}</td><td class="r">−Rp{{ number_format($invoice->calculated_discount_value, 0, ',', '.') }}</td></tr>
                    @endif
                </table>
                <table class="total" style="margin-top: 8px;">
                    <tr><td>TOTAL</td><td class="r">Rp{{ number_format($invoice->total, 0, ',', '.') }}</td></tr>
                </table>
                <table class="sum" style="margin-top: 8px;">
                    <tr><td class="muted">Sudah dibayar</td><td class="r">Rp{{ number_format((float) $invoice->paid_amount, 0, ',', '.') }}</td></tr>
                    <tr><td style="font-weight: bold;">Sisa tagihan</td><td class="r" style="font-weight: bold;">Rp{{ number_format($invoice->remaining, 0, ',', '.') }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if ($settings['invoice_footer'])
        <div class="muted" style="margin-top: 40px; padding-top: 14px; border-top: 1px solid #DDE2E7; text-align: center; font-size: 9.5px;">{{ $settings['invoice_footer'] }}</div>
    @endif
</body>
</html>
