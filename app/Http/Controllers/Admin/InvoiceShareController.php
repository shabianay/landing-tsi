<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\InvoiceMail;
use App\Models\ActivityLog;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

/** Getting an invoice to the client: secret PDF link, WhatsApp message, email. */
class InvoiceShareController extends Controller
{
    /** Opens WhatsApp with the message ready (creates the link on first use). */
    public function whatsapp(Invoice $invoice, InvoiceController $invoices): RedirectResponse
    {
        $invoice->load(['items', 'client']);
        ActivityLog::record('updated', $invoice, "Membagikan invoice {$invoice->invoice_number} lewat WhatsApp");

        return redirect()->away($invoice->whatsappUrl($invoice->client?->phone, $invoices->pdfSettings()['invoice_company_name']));
    }

    public function email(Request $request, Invoice $invoice, InvoiceController $invoices): RedirectResponse
    {
        $data = $request->validate(
            ['email' => ['required', 'email', 'max:255'], 'message' => ['nullable', 'string', 'max:2000']],
            [],
            ['email' => 'email penerima', 'message' => 'pesan'],
        );
        $invoice->load(['items', 'client']);

        try {
            Mail::to($data['email'])->send(new InvoiceMail($invoice, $invoices->renderPdf($invoice), $invoices->pdfSettings(), $data['message'] ?? null));
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['email' => 'Email gagal dikirim. Periksa pengaturan email server, atau kirim lewat WhatsApp.'])->withInput();
        }

        ActivityLog::record('updated', $invoice, "Mengirim invoice {$invoice->invoice_number} ke {$data['email']}");

        return back()->with('success', "Invoice {$invoice->invoice_number} terkirim ke {$data['email']}.");
    }

    /** New secret link; the old one stops working (e.g. it was sent to the wrong person). */
    public function resetLink(Invoice $invoice): RedirectResponse
    {
        $invoice->resetShareToken();
        ActivityLog::record('updated', $invoice, "Mengganti link invoice {$invoice->invoice_number}");

        return back()->with('success', 'Link baru dibuat. Link lama tidak bisa dibuka lagi.');
    }

    /** Public: the client opens the PDF from the secret link. */
    public function show(string $token, InvoiceController $invoices): Response
    {
        $invoice = Invoice::where('share_token', $token)->firstOrFail();
        $invoice->load('items');

        return response($invoices->renderPdf($invoice), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $invoices->pdfFilename($invoice) . '"',
            'X-Robots-Tag' => 'noindex, nofollow',
            'Cache-Control' => 'private, no-store',
            'Referrer-Policy' => 'no-referrer',
        ]);
    }
}
