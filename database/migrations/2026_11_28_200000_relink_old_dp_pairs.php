<?php

use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Setting;
use App\Support\InvoiceSettlement;
use Illuminate\Database\Migrations\Migration;

/**
 * One-off, requested by the owner after seeing the list: the pairs undone by
 * 2026_11_28_180000 are linked again. The DP invoice keeps showing "DP" and the
 * pelunasan invoice shows the remainder as paid ("Lunas"); see Invoice::status_label.
 * Only pairs recorded in the ignore list by that undo are touched.
 */
return new class extends Migration
{
    public function up(): void
    {
        $value = Setting::where('key', 'invoice_pairs_ignored')->value('value');
        $keys = is_string($value) ? (json_decode($value, true) ?: []) : [];

        foreach ($keys as $key) {
            if (! is_string($key) || ! preg_match('/^(\d+)-(\d+)$/', $key, $m)) {
                continue;
            }
            $dp = Invoice::with(['items', 'payments'])->find((int) $m[1]);
            $settlement = Invoice::with(['items', 'payments'])->find((int) $m[2]);

            // Link only if both still exist and are still the same unlinked DP + paid pair.
            if (! $dp || ! $settlement || $settlement->settles_invoice_id || $dp->is_settled
                || $dp->status !== 'partial' || $settlement->status !== 'paid'
                || abs($dp->document_total - $settlement->document_total) >= 0.01) {
                continue;
            }

            InvoiceSettlement::link($dp, $settlement);
            ActivityLog::record('updated', $settlement, "Menyambungkan {$settlement->invoice_number} sebagai pelunasan {$dp->invoice_number}");
        }

        Setting::where('key', 'invoice_pairs_ignored')->delete();
    }

    public function down(): void
    {
        // Use "Lepas sambungan" on an invoice to undo a link.
    }
};
