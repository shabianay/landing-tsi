<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderClient;
use App\Models\User;
use App\Support\InvoiceSettlement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** DP invoice + pelunasan invoice (the studio's way of billing a deal). */
class InvoiceSettlementTest extends TestCase
{
    use RefreshDatabase;

    private OrderClient $client;

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->client = OrderClient::create(['name' => 'Resha', 'address' => 'Jombang']);
    }

    /** An invoice for a deal of $price with $paid already received. */
    private function invoice(float $price, float $paid, ?string $date = null, array $extra = []): Invoice
    {
        $invoice = Invoice::create($extra + [
            'invoice_number' => 'TSI/01/2026/' . str_pad((string) ++$this->seq, 3, '0', STR_PAD_LEFT),
            'invoice_date' => $date ?? today()->subMonth()->toDateString(),
            'order_client_id' => $this->client->id, 'client_name' => 'Resha',
        ]);
        $invoice->items()->create(['item_name' => 'Website', 'item_qty' => 1, 'item_price' => $price]);
        if ($paid > 0) {
            $invoice->payments()->create(['amount' => $paid, 'paid_at' => $invoice->invoice_date]);
        }
        $invoice->syncPaidAmount();

        return $invoice->fresh();
    }

    public function test_pelunasan_invoice_bills_the_rest_and_the_deal_is_counted_once(): void
    {
        $dp = $this->invoice(2000000, 1000000);
        $this->assertSame('partial', $dp->status);

        $this->post(route('admin.invoices.settlement.store', $dp), ['invoice_date' => today()->toDateString(), 'paid_now' => 1000000, 'method' => 'BCA'])
            ->assertSessionHasNoErrors()->assertRedirect();

        $settlement = Invoice::where('settles_invoice_id', $dp->id)->with('items')->firstOrFail();
        $this->assertNotSame($dp->invoice_number, $settlement->invoice_number);
        $this->assertEquals(2000000, $settlement->document_total);
        $this->assertEquals(1000000, (float) $settlement->credit_amount);
        $this->assertEquals(1000000, $settlement->amount_due);
        $this->assertSame('paid', $settlement->status);

        $dp = $dp->fresh('items');
        $this->assertTrue($dp->is_settled);
        $this->assertSame('Dilunasi', $dp->status_label);
        $this->assertSame('partial', $dp->document_status, 'the DP document itself still reads DP');

        $this->assertSame(['total' => 2000000.0, 'paid' => 2000000.0, 'unpaid' => ['total' => 0.0, 'count' => 0]], Invoice::summarize(Invoice::query()));

        foreach ([$dp, $settlement] as $invoice) {
            $pdf = $this->get(route('admin.invoices.pdf', $invoice));
            $pdf->assertOk();
            $this->assertStringStartsWith('%PDF', $pdf->getContent());
        }
        $this->get(route('admin.invoices.edit', $settlement))->assertOk()->assertSee('invoice pelunasan');
        $this->get(route('admin.invoices.edit', $dp))->assertOk()->assertSee('dilunasi');
        $this->get(route('admin.invoices.index'))->assertOk()->assertSee('Dilunasi')->assertSee('lewat ' . $settlement->invoice_number);
    }

    public function test_pelunasan_can_be_issued_before_the_client_pays(): void
    {
        $dp = $this->invoice(2000000, 1000000);
        $this->post(route('admin.invoices.settlement.store', $dp), ['invoice_date' => today()->toDateString(), 'paid_now' => 0]);
        $settlement = Invoice::where('settles_invoice_id', $dp->id)->firstOrFail();

        $this->assertSame('unpaid', $settlement->fresh('items')->status);
        $this->assertSame(['total' => 2000000.0, 'paid' => 1000000.0, 'unpaid' => ['total' => 1000000.0, 'count' => 1]], Invoice::summarize(Invoice::query()));

        $this->post(route('admin.invoices.payments.store', $settlement), ['amount' => 1500000, 'paid_at' => today()->toDateString()])->assertSessionHasErrors('amount');
        $this->post(route('admin.invoices.payments.store', $settlement), ['amount' => 1000000, 'paid_at' => today()->toDateString()])->assertSessionHasNoErrors();
        $this->assertSame('paid', $settlement->fresh('items')->status);
    }

    public function test_only_open_dp_invoices_can_be_settled_once(): void
    {
        $unpaid = $this->invoice(1000000, 0);
        $paid = $this->invoice(1000000, 1000000);
        $dp = $this->invoice(1000000, 400000);

        $this->post(route('admin.invoices.settlement.store', $unpaid), ['invoice_date' => today()->toDateString()])->assertSessionHasErrors('settlement');
        $this->post(route('admin.invoices.settlement.store', $paid), ['invoice_date' => today()->toDateString()])->assertSessionHasErrors('settlement');
        $this->post(route('admin.invoices.settlement.store', $dp), ['invoice_date' => today()->toDateString(), 'paid_now' => 700000])->assertSessionHasErrors('paid_now');
        $this->post(route('admin.invoices.settlement.store', $dp), ['invoice_date' => today()->toDateString(), 'paid_now' => 600000])->assertSessionHasNoErrors();
        $this->post(route('admin.invoices.settlement.store', $dp), ['invoice_date' => today()->toDateString()])->assertSessionHasErrors('settlement');
        $this->assertSame(1, Invoice::whereNotNull('settles_invoice_id')->count());
    }

    public function test_settled_dp_is_locked_and_deleting_the_pelunasan_reopens_it(): void
    {
        $dp = $this->invoice(2000000, 1000000);
        $settlement = InvoiceSettlement::create($dp, today(), 1000000);

        $this->post(route('admin.invoices.payments.store', $dp), ['amount' => 1, 'paid_at' => today()->toDateString()])->assertSessionHasErrors('amount');
        $this->delete(route('admin.invoices.payments.destroy', [$dp, $dp->payments()->first()]))->assertSessionHasErrors('amount');
        $this->assertSame(1, $dp->payments()->count());

        $this->delete(route('admin.invoices.destroy', $settlement))->assertRedirect();
        $dp = $dp->fresh('items');
        $this->assertFalse($dp->is_settled);
        $this->assertSame('partial', $dp->status);
    }

    public function test_project_is_completed_when_the_pelunasan_is_paid(): void
    {
        $order = Order::create(['order_client_id' => $this->client->id, 'client_name' => 'Resha', 'task_type' => 'Website', 'deadline' => today(), 'price' => 2000000, 'status' => 'Pending', 'payment_method' => 'QRIS']);
        $dp = $this->invoice(2000000, 0, null, ['order_id' => $order->id]);
        $this->post(route('admin.invoices.payments.store', $dp), ['amount' => 1000000, 'paid_at' => today()->subDays(5)->toDateString()]);
        $this->assertSame('Ongoing', $order->fresh()->status);

        $settlement = InvoiceSettlement::create($dp->fresh(), today(), 1000000);
        $this->assertSame($order->id, $settlement->order_id);
        $this->assertSame('Completed', $order->fresh()->status);
        $this->assertSame(today()->toDateString(), $order->fresh()->payment_lunas_date->toDateString());
    }

    public function test_unlinking_an_old_pair_restores_the_original_data(): void
    {
        $dp = $this->invoice(1000000, 500000, '2025-08-08');
        $later = $this->invoice(1000000, 1000000, '2025-08-20');
        $originalNote = $later->payments()->first()->note;
        $before = Invoice::summarize(Invoice::query());

        $this->post(route('admin.invoices.pairs.link'), ['pairs' => ["{$dp->id}-{$later->id}"]]);
        $this->assertEquals(500000, (float) $later->fresh()->paid_amount);

        $this->get(route('admin.invoices.edit', $later))->assertSee('Lepas sambungan');
        $this->post(route('admin.invoices.settlement.unlink', $later))->assertSessionHasNoErrors();

        $later = $later->fresh(['items', 'payments']);
        $this->assertNull($later->settles_invoice_id);
        $this->assertEquals(0, (float) $later->credit_amount);
        $this->assertEquals(1000000, (float) $later->paid_amount);
        $this->assertCount(1, $later->payments);
        $this->assertEquals(1000000, (float) $later->payments->first()->amount);
        $this->assertSame($originalNote, $later->payments->first()->note);
        $this->assertFalse($dp->fresh()->is_settled);
        $this->assertSame($before, Invoice::summarize(Invoice::query()));
        // The pair the admin undid is not proposed again.
        $this->assertCount(0, InvoiceSettlement::candidatePairs());
    }

    public function test_revert_migration_only_touches_pairs_linked_from_the_pairs_page(): void
    {
        $dp = $this->invoice(1000000, 500000, '2025-08-08');
        $later = $this->invoice(1000000, 1000000, '2025-08-20');
        $this->post(route('admin.invoices.pairs.link'), ['pairs' => ["{$dp->id}-{$later->id}"]]);

        $dp2 = $this->invoice(2000000, 1000000, '2026-10-01');
        $made = InvoiceSettlement::create($dp2, today(), 1000000);

        (require database_path('migrations/2026_11_28_180000_unlink_old_dp_pairs.php'))->up();

        $this->assertNull($later->fresh()->settles_invoice_id);
        $this->assertEquals(1000000, (float) $later->fresh()->paid_amount);
        $this->assertSame($dp2->id, $made->fresh()->settles_invoice_id, 'button-made pelunasan stays');
        $this->post(route('admin.invoices.settlement.unlink', $made))->assertSessionHasErrors('settlement');
    }

    public function test_existing_pairs_are_previewed_then_linked(): void
    {
        // A deal of 1.000.000: DP 500.000, then a second invoice that recorded the full price as paid.
        $dp = $this->invoice(1000000, 500000, '2025-08-08');
        $later = $this->invoice(1000000, 1000000, '2025-08-20');
        // Not a pair: different amount.
        $this->invoice(750000, 300000, '2025-09-01');
        $this->invoice(900000, 900000, '2025-09-10');

        $this->assertSame(['total' => 3650000.0, 'paid' => 2700000.0, 'unpaid' => ['total' => 0.0, 'count' => 0]], Invoice::summarize(Invoice::query()));

        $this->get(route('admin.invoices.index'))->assertSee('1 pasangan invoice DP + pelunasan');
        $this->get(route('admin.invoices.pairs'))->assertOk()
            ->assertSee($dp->invoice_number)->assertSee($later->invoice_number)->assertSee('Rp500.000');

        $this->post(route('admin.invoices.pairs.link'), ['pairs' => ["{$dp->id}-{$later->id}", '999-998']])->assertRedirect(route('admin.invoices.index'));

        $later = $later->fresh(['items', 'payments']);
        $this->assertSame($dp->id, $later->settles_invoice_id);
        $this->assertEquals(500000, (float) $later->paid_amount);
        $this->assertEquals(500000, $later->amount_due);
        $this->assertSame('paid', $later->status);
        $this->assertTrue($dp->fresh()->is_settled);

        // The 1.000.000 deal now counts once: 1.000.000 + 750.000 + 900.000.
        $this->assertSame(['total' => 2650000.0, 'paid' => 2200000.0, 'unpaid' => ['total' => 0.0, 'count' => 0]], Invoice::summarize(Invoice::query()));
        $this->assertCount(0, InvoiceSettlement::candidatePairs());
    }
}
