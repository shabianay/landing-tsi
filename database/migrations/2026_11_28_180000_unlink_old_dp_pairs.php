<?php

use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Support\InvoiceSettlement;
use Illuminate\Database\Migrations\Migration;

/**
 * One-off, requested by the owner: undo the DP–pelunasan pairs linked on the
 * "Sambungkan DP & pelunasan" page. Each later invoice gets its original payments
 * back and stands on its own again; the pairs are no longer proposed.
 * Pelunasan invoices made with the "Buat invoice pelunasan" button are not touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        Invoice::whereNotNull('settles_invoice_id')->orderBy('id')->get()
            ->filter(fn (Invoice $invoice) => InvoiceSettlement::linkedFromOldPair($invoice))
            ->each(function (Invoice $invoice) {
                $dpNumber = $invoice->settles?->invoice_number;
                InvoiceSettlement::unlink($invoice);
                ActivityLog::record('updated', $invoice, "Melepas sambungan {$invoice->invoice_number} dari {$dpNumber} (data dikembalikan)");
            });
    }

    public function down(): void
    {
        // Not reversible automatically: link the pairs again from the admin page if needed.
    }
};
