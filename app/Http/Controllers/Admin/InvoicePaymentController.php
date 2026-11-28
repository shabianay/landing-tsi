<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Support\Terbilang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

/** Payment history of an invoice: record a payment, remove a wrong one, print a receipt. */
class InvoicePaymentController extends Controller
{
    public function store(Request $request, Invoice $invoice): RedirectResponse
    {
        $invoice->load(['items', 'settledBy']);
        if ($invoice->settledBy) {
            return back()->withErrors(['amount' => "Invoice {$invoice->invoice_number} sudah dilunasi lewat {$invoice->settledBy->invoice_number}. Catat pembayaran di invoice itu."]);
        }
        $remaining = round($invoice->remaining, 2);
        if ($remaining <= 0) {
            return back()->withErrors(['amount' => "Invoice {$invoice->invoice_number} sudah lunas."]);
        }

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'max:' . $remaining],
            'paid_at' => ['required', 'date', 'before_or_equal:' . today()->addDay()->toDateString()],
            'method' => ['nullable', Rule::in(Order::PAYMENT_METHODS)],
            'note' => ['nullable', 'string', 'max:255'],
        ], [
            'amount.max' => 'Jumlah melebihi sisa tagihan (Rp' . number_format($remaining, 0, ',', '.') . ').',
            'paid_at.before_or_equal' => 'Tanggal bayar tidak boleh di masa depan.',
        ], ['amount' => 'jumlah', 'paid_at' => 'tanggal bayar', 'method' => 'metode', 'note' => 'catatan']);

        DB::transaction(function () use ($invoice, $data) {
            $invoice->payments()->create($data);
            $invoice->syncPaidAmount();
        });

        $invoice->refresh()->load('items');
        ActivityLog::record('updated', $invoice, "Mencatat pembayaran invoice {$invoice->invoice_number}: Rp" . number_format((float) $data['amount'], 0, ',', '.'));

        return back()->with('success', "Pembayaran Rp" . number_format((float) $data['amount'], 0, ',', '.') . " untuk {$invoice->invoice_number} dicatat ({$invoice->status_label}).");
    }

    public function destroy(Invoice $invoice, InvoicePayment $payment): RedirectResponse
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);
        // The DP on a settled invoice is deducted on its pelunasan invoice; keep them consistent.
        if ($settledBy = $invoice->settledBy) {
            return back()->withErrors(['amount' => "Pembayaran ini sudah dipotongkan di invoice pelunasan {$settledBy->invoice_number}. Hapus invoice pelunasan itu dulu bila DP-nya salah."]);
        }

        DB::transaction(function () use ($invoice, $payment) {
            $payment->delete();
            $invoice->syncPaidAmount();
        });
        ActivityLog::record('deleted', $invoice, "Menghapus pembayaran Rp" . number_format((float) $payment->amount, 0, ',', '.') . " dari invoice {$invoice->invoice_number}");

        return back()->with('success', 'Pembayaran dihapus.');
    }

    /** Kwitansi (receipt) for one payment. */
    public function receipt(Request $request, Invoice $invoice, InvoicePayment $payment, InvoiceController $invoices): Response
    {
        abort_unless($payment->invoice_id === $invoice->id, 404);
        $invoice->load('items');

        // Balance right after this payment (payments are ordered by date).
        $paidUpTo = (float) $invoice->payments()
            ->where(fn ($q) => $q->where('paid_at', '<', $payment->paid_at)
                ->orWhere(fn ($q) => $q->where('paid_at', $payment->paid_at)->where('id', '<=', $payment->id)))
            ->sum('amount');

        $pdf = Pdf::loadView('admin.invoices.receipt', [
            'invoice' => $invoice,
            'payment' => $payment,
            'settings' => $invoices->pdfSettings(),
            'logo' => $invoices->pdfLogo(),
            'words' => Terbilang::rupiah($payment->amount),
            'remainingAfter' => max(0, $invoice->amount_due - $paidUpTo),
        ])->setPaper('a5', 'landscape');

        $filename = 'kwitansi-' . str_replace('/', '-', $payment->receipt_number) . '.pdf';

        return $request->boolean('download') ? $pdf->download($filename) : $pdf->stream($filename);
    }
}
