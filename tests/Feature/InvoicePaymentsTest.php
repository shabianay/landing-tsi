<?php

namespace Tests\Feature;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderClient;
use App\Models\PageView;
use App\Models\User;
use App\Support\Terbilang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/** Payment history (7), sending invoices (8) and invoice ↔ project links (9). */
class InvoicePaymentsTest extends TestCase
{
    use RefreshDatabase;

    private OrderClient $client;

    private int $seq = 0;

    protected function setUp(): void
    {
        parent::setUp();
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->client = OrderClient::create(['name' => 'Budi', 'phone' => '0812-3456-7890', 'email' => 'budi@example.com', 'address' => 'Jombang']);
    }

    private function invoice(float $price = 1000000, array $extra = []): Invoice
    {
        $invoice = Invoice::create($extra + ['invoice_number' => 'TSI/11/2026/' . str_pad((string) ++$this->seq, 3, '0', STR_PAD_LEFT), 'invoice_date' => today(), 'order_client_id' => $this->client->id, 'client_name' => 'Budi']);
        $invoice->items()->create(['item_name' => 'Website', 'item_qty' => 1, 'item_price' => $price]);

        return $invoice;
    }

    private function order(): Order
    {
        return Order::create(['order_client_id' => $this->client->id, 'client_name' => 'Budi', 'task_type' => 'Website toko', 'deadline' => today()->addWeek(), 'price' => 1000000, 'status' => 'Pending', 'payment_method' => 'QRIS']);
    }

    public function test_payments_build_the_paid_amount_and_can_be_removed(): void
    {
        $invoice = $this->invoice();

        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 400000, 'paid_at' => today()->subDays(3)->toDateString(), 'method' => 'QRIS'])->assertSessionHasNoErrors();
        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 600000, 'paid_at' => today()->toDateString()])->assertSessionHasNoErrors();
        $this->assertSame('paid', $invoice->fresh('items')->status);
        $this->assertEquals(1000000, (float) $invoice->fresh()->paid_amount);

        // Already settled: nothing more can be recorded.
        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 1, 'paid_at' => today()->toDateString()])->assertSessionHasErrors('amount');

        $last = $invoice->payments()->reorder()->latest('id')->first();
        $this->delete(route('admin.invoices.payments.destroy', [$invoice, $last]))->assertRedirect();
        $this->assertEquals(400000, (float) $invoice->fresh()->paid_amount);
        $this->assertSame('partial', $invoice->fresh('items')->status);
    }

    public function test_payment_validation(): void
    {
        $invoice = $this->invoice();
        $other = $this->invoice();
        $payment = $other->payments()->create(['amount' => 1000, 'paid_at' => today()]);

        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 0, 'paid_at' => today()->toDateString()])->assertSessionHasErrors('amount');
        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 1000, 'paid_at' => today()->addWeek()->toDateString()])->assertSessionHasErrors('paid_at');
        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 1000, 'paid_at' => today()->toDateString(), 'method' => 'Bitcoin'])->assertSessionHasErrors('method');
        // A payment of another invoice cannot be removed through this invoice's URL.
        $this->delete(route('admin.invoices.payments.destroy', [$invoice, $payment]))->assertNotFound();
    }

    public function test_receipt_pdf_and_receipt_number(): void
    {
        $invoice = $this->invoice(1500000);
        $first = $invoice->payments()->create(['amount' => 500000, 'paid_at' => today()->subDay()]);
        $second = $invoice->payments()->create(['amount' => 1000000, 'paid_at' => today()]);

        $this->assertSame('KW/' . $invoice->invoice_number . '/1', $first->receipt_number);
        $this->assertSame('KW/' . $invoice->invoice_number . '/2', $second->receipt_number);

        $response = $this->get(route('admin.invoices.payments.receipt', [$invoice, $second]));
        $response->assertOk();
        $this->assertStringStartsWith('%PDF', $response->getContent());
    }

    public function test_terbilang(): void
    {
        $this->assertSame('satu juta lima ratus ribu rupiah', Terbilang::rupiah(1500000));
        $this->assertSame('seribu dua ratus tiga puluh empat rupiah', Terbilang::rupiah(1234));
        $this->assertSame('sebelas juta seratus sebelas ribu rupiah', Terbilang::rupiah(11111000));
        $this->assertSame('nol rupiah', Terbilang::rupiah(0));
    }

    public function test_edit_cannot_drop_the_total_below_what_was_paid(): void
    {
        $invoice = $this->invoice(1000000);
        $invoice->payments()->create(['amount' => 800000, 'paid_at' => today()]);
        $invoice->syncPaidAmount();

        $this->put(route('admin.invoices.update', $invoice), [
            'invoice_date' => today()->toDateString(), 'order_client_id' => $this->client->id, 'discount_type' => 'fixed',
            'items' => [['name' => 'Website', 'qty' => 1, 'price' => 500000]],
        ])->assertSessionHasErrors('items');

        $this->get(route('admin.invoices.edit', $invoice))->assertOk()->assertSee('Riwayat pembayaran')->assertSee('Kirim lewat WhatsApp');
    }

    public function test_whatsapp_opens_a_message_with_the_client_link(): void
    {
        $invoice = $this->invoice();

        $response = $this->post(route('admin.invoices.share.whatsapp', $invoice));
        $target = $response->headers->get('Location');

        $this->assertStringStartsWith('https://wa.me/6281234567890?text=', $target);
        $this->assertNotNull($invoice->fresh()->share_token);
        $this->assertStringContainsString(rawurlencode(route('invoices.public', $invoice->fresh()->share_token)), $target);
    }

    public function test_public_link_serves_the_pdf_until_it_is_replaced(): void
    {
        $invoice = $this->invoice();
        $url = $invoice->shareUrl();
        auth()->logout();

        $response = $this->get($url);
        $response->assertOk();
        $this->assertSame('application/pdf', $response->headers->get('content-type'));
        $this->assertSame('noindex, nofollow', $response->headers->get('x-robots-tag'));
        $this->assertSame(0, PageView::count(), 'the secret link must not be stored in page views');

        $this->get('/invoice/' . str_repeat('a', 40))->assertNotFound();

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        $this->post(route('admin.invoices.share.reset', $invoice))->assertRedirect();
        auth()->logout();
        $this->get($url)->assertNotFound();
    }

    public function test_email_sends_the_pdf(): void
    {
        Mail::fake();
        $invoice = $this->invoice();

        $this->post(route('admin.invoices.share.email', $invoice), ['email' => 'budi@example.com', 'message' => 'Terlampir invoice.'])
            ->assertSessionHasNoErrors();

        Mail::assertSent(InvoiceMail::class, function (InvoiceMail $mail) use ($invoice) {
            return $mail->hasTo('budi@example.com')
                && $mail->invoice->is($invoice)
                && count($mail->attachments()) === 1;
        });
        $this->post(route('admin.invoices.share.email', $invoice), ['email' => 'bukan-email'])->assertSessionHasErrors('email');
    }

    public function test_invoice_from_a_project_and_project_follows_payments(): void
    {
        $order = $this->order();

        $this->get(route('admin.invoices.create', ['order' => $order->id]))->assertOk()->assertSee('Website toko');

        $this->post(route('admin.invoices.store'), [
            'invoice_date' => today()->toDateString(), 'order_client_id' => $this->client->id, 'order_id' => $order->id,
            'discount_type' => 'fixed', 'paid_amount' => 500000, 'payment_method' => 'QRIS',
            'items' => [['name' => 'Website toko', 'qty' => 1, 'price' => 1000000]],
        ])->assertSessionHasNoErrors();
        $invoice = Invoice::firstOrFail();

        // DP recorded → project has a DP date and is in progress.
        $order->refresh();
        $this->assertSame(today()->toDateString(), $order->payment_date->toDateString());
        $this->assertNull($order->payment_lunas_date);
        $this->assertSame('Ongoing', $order->status);
        $this->assertSame('partial', $order->invoicePaymentStatus());

        // Paid off → lunas date and Completed; removing that payment undoes it.
        $this->post(route('admin.invoices.payments.store', $invoice), ['amount' => 500000, 'paid_at' => today()->toDateString()]);
        $this->assertSame('Completed', $order->fresh()->status);
        $this->assertNotNull($order->fresh()->payment_lunas_date);

        $this->delete(route('admin.invoices.payments.destroy', [$invoice, $invoice->payments()->reorder()->latest('id')->first()]));
        $this->assertNull($order->fresh()->payment_lunas_date);

        $this->get(route('admin.orders.edit', $order))->assertOk()->assertSee($invoice->invoice_number)->assertSee('+ Buat invoice');
        $this->get(route('admin.orders.index'))->assertOk()->assertSee('Invoice DP');
    }

    public function test_project_must_belong_to_the_billed_client(): void
    {
        $other = OrderClient::create(['name' => 'Sari']);
        $order = Order::create(['order_client_id' => $other->id, 'client_name' => 'Sari', 'task_type' => 'App', 'deadline' => today(), 'price' => 1, 'status' => 'Pending', 'payment_method' => 'QRIS']);

        $this->post(route('admin.invoices.store'), [
            'invoice_date' => today()->toDateString(), 'order_client_id' => $this->client->id, 'order_id' => $order->id,
            'discount_type' => 'fixed', 'items' => [['name' => 'X', 'qty' => 1, 'price' => 1000]],
        ])->assertSessionHasErrors('order_id');
    }

    public function test_money_in_follows_payment_dates(): void
    {
        $invoice = $this->invoice(1000000, ['invoice_date' => now()->setDate(now()->year, 1, 15)]);
        $invoice->payments()->create(['amount' => 300000, 'paid_at' => now()->setDate(now()->year, 3, 2)]);
        $invoice->syncPaidAmount();

        // March has money in but no invoice; January has the invoice but no money in.
        $this->get(route('admin.financial.index', ['year' => now()->year, 'month' => 3]))->assertOk()->assertSee('Rp300.000');
        $this->get(route('admin.financial.index', ['year' => now()->year, 'month' => 1]))->assertOk()->assertSee('Rp1.000.000');
    }
}
