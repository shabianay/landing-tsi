<?php

namespace App\Support;

use App\Models\Invoice;
use Illuminate\Support\Collection;

/**
 * DP + pelunasan as two invoices: the pelunasan invoice repeats the items, deducts the DP
 * already paid ("credit") and closes the DP invoice, so the deal is billed only once.
 */
class InvoiceSettlement
{
    /** Can a pelunasan invoice be made for this one? */
    public static function canSettle(Invoice $invoice): bool
    {
        return $invoice->exists
            && ! $invoice->settles_invoice_id
            && ! $invoice->is_settled
            && $invoice->status === 'partial';
    }

    /** Create the pelunasan invoice, optionally with the payment received now. */
    public static function create(Invoice $dp, \DateTimeInterface $date, float $paidNow = 0, ?string $method = null): Invoice
    {
        $dp->loadMissing('items');

        $settlement = Invoice::createNumbered($date, null, function (string $number) use ($dp, $date, $paidNow, $method) {
            $settlement = Invoice::create([
                'invoice_number' => $number,
                'invoice_date' => $date,
                'order_client_id' => $dp->order_client_id,
                'order_id' => $dp->order_id,
                'client_name' => $dp->client_name,
                'client_address' => $dp->client_address,
                'ppn_rate' => $dp->ppn_rate,
                'ppn_amount' => $dp->ppn_amount,
                'discount_amount' => $dp->discount_amount,
                'discount_type' => $dp->discount_type,
                'paid_amount' => 0,
                'settles_invoice_id' => $dp->id,
                'credit_amount' => $dp->paid_amount,
            ]);
            foreach ($dp->items as $item) {
                $settlement->items()->create($item->only(['item_name', 'item_qty', 'item_price']));
            }
            if ($paidNow > 0) {
                $settlement->payments()->create([
                    'amount' => $paidNow,
                    'paid_at' => $date,
                    'method' => $method,
                    'note' => 'Pelunasan ' . $dp->invoice_number,
                ]);
            }
            $settlement->syncPaidAmount();

            return $settlement;
        });

        $dp->unsetRelation('settledBy');
        $dp->order?->syncPaymentDatesFromInvoices();

        return $settlement;
    }

    /**
     * Existing pairs entered before this feature: same client, same document total, an earlier
     * DP invoice and a later invoice paid in full. Each DP invoice is matched at most once.
     *
     * @return Collection<int, array{dp: Invoice, settlement: Invoice, paid_before: float, paid_after: float}>
     */
    public static function candidatePairs(): Collection
    {
        $invoices = Invoice::with(['items', 'payments'])
            ->whereNull('settles_invoice_id')
            ->orderBy('invoice_date')->orderBy('id')
            ->get()
            ->reject(fn (Invoice $i) => $i->is_settled);

        $used = [];
        $pairs = collect();
        foreach ($invoices->filter(fn (Invoice $i) => $i->status === 'partial') as $dp) {
            if (in_array($dp->id, $used, true)) {
                continue;
            }
            $match =$invoices->first(fn (Invoice $later) => ! in_array($later->id, $used, true)
                && $later->id !== $dp->id
                && self::sameClient($dp, $later)
                && abs($later->document_total - $dp->document_total) < 0.01
                && ($later->invoice_date->gt($dp->invoice_date) || ($later->invoice_date->eq($dp->invoice_date) && $later->id > $dp->id))
                && $later->status === 'paid');

            if ($match) {
                $used[] = $match->id;
                $used[] = $dp->id;
                $due = max(0, $match->document_total - (float) $dp->paid_amount);
                $pairs->push([
                    'dp' => $dp,
                    'settlement' => $match,
                    'paid_before' => (float) $match->paid_amount,
                    'paid_after' => min((float) $match->paid_amount, $due),
                ]);
            }
        }

        return $pairs;
    }

    /**
     * Link an existing pair. The later invoice used to record the full price as paid;
     * it now asks only for the remainder, so its payments are trimmed to that amount.
     */
    public static function link(Invoice $dp, Invoice $settlement): void
    {
        $settlement->loadMissing(['items', 'payments']);
        $due = max(0, $settlement->document_total - (float) $dp->paid_amount);

        \Illuminate\Support\Facades\DB::transaction(function () use ($dp, $settlement, $due) {
            $settlement->forceFill([
                'settles_invoice_id' => $dp->id,
                'credit_amount' => $dp->paid_amount,
                'order_id' => $settlement->order_id ?? $dp->order_id,
            ])->save();

            // Trim the newest payments first until the total equals what is now due.
            $excess = round((float) $settlement->payments->sum('amount') - $due, 2);
            foreach ($settlement->payments->sortByDesc(fn ($p) => $p->paid_at->format('Ymd') . str_pad((string) $p->id, 12, '0', STR_PAD_LEFT)) as $payment) {
                if ($excess <= 0) {
                    break;
                }
                $cut = min($excess, (float) $payment->amount);
                $left = round((float) $payment->amount - $cut, 2);
                $left > 0
                    ? $payment->update(['amount' => $left, 'note' => trim(($payment->note ? $payment->note . ' ' : '') . '(disesuaikan: dikurangi DP ' . $dp->invoice_number . ')')])
                    : $payment->delete();
                $excess = round($excess - $cut, 2);
            }

            $settlement->unsetRelation('payments');
            $settlement->syncPaidAmount();
        });

        $dp->order?->syncPaymentDatesFromInvoices();
    }

    private static function sameClient(Invoice $a, Invoice $b): bool
    {
        return $a->order_client_id && $b->order_client_id
            ? $a->order_client_id === $b->order_client_id
            : mb_strtolower(trim($a->client_name)) === mb_strtolower(trim($b->client_name));
    }
}
