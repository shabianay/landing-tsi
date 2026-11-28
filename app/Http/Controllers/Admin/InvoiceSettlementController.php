<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Order;
use App\Support\InvoiceSettlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** DP + pelunasan invoices: issue a pelunasan invoice, and link pairs made before this existed. */
class InvoiceSettlementController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->load('items');
        if (! InvoiceSettlement::canSettle($invoice)) {
            return back()->withErrors(['settlement' => 'Invoice pelunasan hanya bisa dibuat untuk invoice berstatus DP yang belum dilunasi.']);
        }
        $remaining = round($invoice->remaining, 2);

        $data = $request->validate([
            'invoice_date' => ['required', 'date'],
            'paid_now' => ['nullable', 'numeric', 'min:0', 'max:' . $remaining],
            'method' => ['nullable', Rule::in(Order::PAYMENT_METHODS)],
        ], [
            'paid_now.max' => 'Jumlah melebihi sisa tagihan (Rp' . number_format($remaining, 0, ',', '.') . ').',
        ], ['invoice_date' => 'tanggal invoice', 'paid_now' => 'jumlah dibayar', 'method' => 'metode']);

        $settlement = InvoiceSettlement::create($invoice, Carbon::parse($data['invoice_date']), (float) ($data['paid_now'] ?? 0), $data['method'] ?? null);
        ActivityLog::record('created', $settlement, "Membuat invoice pelunasan {$settlement->invoice_number} untuk {$invoice->invoice_number}");

        return redirect()->route('admin.invoices.edit', $settlement)
            ->with('success', "Invoice pelunasan {$settlement->invoice_number} dibuat.")
            ->with('pdf_url', route('admin.invoices.pdf', $settlement));
    }

    /** Preview of the existing pairs that would be linked (nothing changes yet). */
    public function pairs(): View
    {
        return view('admin.invoices.pairs', ['pairs' => InvoiceSettlement::candidatePairs()]);
    }

    public function linkPairs(Request $request): RedirectResponse
    {
        $data = $request->validate(['pairs' => ['required', 'array', 'min:1'], 'pairs.*' => ['string', 'regex:/^\d+-\d+$/']], ['pairs.required' => 'Pilih minimal satu pasangan.']);

        // Only pairs the matcher still proposes can be linked.
        $candidates = InvoiceSettlement::candidatePairs()->keyBy(fn ($p) => $p['dp']->id . '-' . $p['settlement']->id);
        $linked = 0;
        foreach (array_unique($data['pairs']) as $key) {
            if ($pair = $candidates->get($key)) {
                InvoiceSettlement::link($pair['dp'], $pair['settlement']);
                ActivityLog::record('updated', $pair['settlement'], "Menyambungkan {$pair['settlement']->invoice_number} sebagai pelunasan {$pair['dp']->invoice_number}");
                $linked++;
            }
        }

        return redirect()->route('admin.invoices.index')->with('success', "{$linked} pasangan DP–pelunasan disambungkan.");
    }
}
