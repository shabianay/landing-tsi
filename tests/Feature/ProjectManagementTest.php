<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderClient;
use App\Models\Setting;
use App\Models\User;
use App\Support\SimProyekImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProjectManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    private function client(array $attributes = []): OrderClient
    {
        return OrderClient::create(array_merge(['name' => 'Budi Santoso', 'address' => 'Jl. Melati 1, Jombang', 'phone' => '0812'], $attributes));
    }

    public function test_pages_are_admin_only(): void
    {
        $this->get(route('admin.orders.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create(['is_admin' => false]))->get(route('admin.invoices.index'))->assertForbidden();
    }

    public function test_all_pages_render_for_admin(): void
    {
        $this->actingAs($this->admin());
        $client = $this->client();
        $order = Order::create(['order_client_id' => $client->id, 'client_name' => $client->name, 'task_type' => 'Website', 'deadline' => today(), 'price' => 1500000, 'status' => 'Ongoing']);

        foreach ([
            route('admin.dashboard'), route('admin.orders.index'), route('admin.orders.index', ['view' => 'list']),
            route('admin.orders.create'), route('admin.orders.edit', $order), route('admin.order-clients.index'),
            route('admin.order-clients.create'), route('admin.invoices.index'), route('admin.invoices.create'),
            route('admin.invoices.settings'), route('admin.financial.index'), route('admin.activity-logs.index'),
            route('admin.search', ['q' => 'Budi']), route('admin.sim-import.create'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_order_status_follows_payment_dates_and_board_moves_are_logged(): void
    {
        $this->actingAs($this->admin());
        $client = $this->client();

        $this->post(route('admin.orders.store'), [
            'order_client_id' => $client->id, 'task_type' => 'Aplikasi Android', 'deadline' => today()->addWeek()->toDateString(),
            'price' => 2500000, 'payment_method' => 'QRIS', 'payment_date' => today()->toDateString(),
        ])->assertRedirect(route('admin.orders.index'));

        $order = Order::firstOrFail();
        $this->assertSame('Ongoing', $order->status);
        $this->assertSame('Budi Santoso', $order->client_name);

        $this->patchJson(route('admin.orders.status', $order), ['status' => 'Completed'])->assertOk()->assertJson(['status' => 'Completed']);
        $this->assertSame('Completed', $order->fresh()->status);
        $this->patchJson(route('admin.orders.status', $order), ['status' => 'Unknown'])->assertUnprocessable();

        $this->assertSame(2, ActivityLog::count());
    }

    public function test_invoice_totals_number_payment_and_pdf(): void
    {
        $this->actingAs($this->admin());
        $client = $this->client();
        $number = Invoice::nextNumber();
        $this->assertMatchesRegularExpression('#^TSI/\d{2}/\d{4}/001$#', $number);

        $this->post(route('admin.invoices.store'), [
            'invoice_number' => $number, 'invoice_date' => today()->toDateString(), 'order_client_id' => $client->id,
            'paid_amount' => 500000, 'use_ppn' => 1, 'discount_amount' => 10, 'discount_type' => 'percent',
            'items' => [['name' => 'Desain UI', 'qty' => 1, 'price' => 1000000], ['name' => 'Hosting', 'qty' => 2, 'price' => 250000]],
        ])->assertRedirect(route('admin.invoices.index'));

        $invoice = Invoice::with('items')->firstOrFail();
        // subtotal 1.500.000 + PPN 150.000 - 10% (150.000) = 1.500.000
        $this->assertEquals(1500000, $invoice->subtotal);
        $this->assertEquals(1500000, $invoice->total);
        $this->assertSame('partial', $invoice->status);
        $this->assertSame('Jl. Melati 1, Jombang', $invoice->client_address);
        $this->assertStringEndsWith('/002', Invoice::nextNumber());

        $this->patch(route('admin.invoices.paid', $invoice), ['paid_amount' => 1500000])->assertRedirect();
        $this->assertSame('paid', $invoice->fresh('items')->status);

        $pdf = $this->get(route('admin.invoices.pdf', $invoice));
        $pdf->assertOk();
        $this->assertSame('application/pdf', $pdf->headers->get('content-type'));
        $this->assertStringStartsWith('%PDF', $pdf->getContent());
    }

    public function test_invoice_requires_items_and_unique_number(): void
    {
        $this->actingAs($this->admin());
        $client = $this->client();
        Invoice::create(['invoice_number' => 'INV/TSI/01/2026/001', 'invoice_date' => today(), 'client_name' => 'X']);

        $this->post(route('admin.invoices.store'), [
            'invoice_number' => 'INV/TSI/01/2026/001', 'invoice_date' => today()->toDateString(), 'order_client_id' => $client->id,
            'paid_amount' => 0, 'discount_type' => 'fixed', 'items' => [],
        ])->assertSessionHasErrors(['invoice_number', 'items']);
    }

    public function test_financial_report_sums_by_period(): void
    {
        $this->actingAs($this->admin());
        $invoice = Invoice::create(['invoice_number' => 'A1', 'invoice_date' => now()->startOfYear()->addMonths(2), 'client_name' => 'X', 'paid_amount' => 100000]);
        $invoice->items()->create(['item_name' => 'Item', 'item_qty' => 1, 'item_price' => 300000]);
        $unpaid = Invoice::create(['invoice_number' => 'A2', 'invoice_date' => now()->startOfYear()->addMonths(3), 'client_name' => 'Y', 'paid_amount' => 0]);
        $unpaid->items()->create(['item_name' => 'Item', 'item_qty' => 1, 'item_price' => 450000]);

        // Billed 750.000, received 100.000; "belum dibayar" counts only A2 (the DP invoice A1 is excluded).
        $this->get(route('admin.financial.index', ['year' => now()->year]))
            ->assertOk()
            ->assertSee('Rp750.000')
            ->assertSee('Rp100.000')
            ->assertSee('Rp450.000')
            ->assertSee('1 invoice tanpa pembayaran');

        $summary = Invoice::unpaidSummary(Invoice::with('items')->get());
        $this->assertSame(['total' => 450000.0, 'count' => 1], $summary);
    }

    public function test_invoice_settings_are_saved_and_hidden_from_generic_settings(): void
    {
        $this->actingAs($this->admin());

        $this->post(route('admin.invoices.settings.update'), [
            'invoice_company_name' => 'The Solution ID', 'invoice_payment_info' => 'BCA 123 a.n. Test',
            'invoice_logo' => UploadedFile::fake()->image('logo.png', 120, 60),
        ])->assertRedirect(route('admin.invoices.settings'));

        $this->assertSame('BCA 123 a.n. Test', Setting::get('invoice_payment_info'));
        $this->assertNotNull(Setting::get('invoice_logo'));
        $this->get(route('admin.settings.index'))->assertOk()->assertDontSee('BCA 123 a.n. Test');
    }

    public function test_sim_proyek_import_is_idempotent_and_keeps_dates(): void
    {
        $admin = $this->admin();
        $payload = [
            'source' => 'SIM-Proyek',
            'clients' => [['id' => 7, 'name' => 'Sari', 'email' => 'sari@example.com', 'phone' => '0813', 'address' => 'Surabaya', 'company' => null, 'notes' => null, 'created_at' => '2026-07-22 10:00:00', 'updated_at' => '2026-07-22 10:00:00']],
            'projects' => [['client_id' => 7, 'client_name' => 'Sari', 'task_type' => 'Website', 'description' => null, 'deadline' => '2026-08-01', 'price' => '1500000.00', 'status' => 'Ongoing', 'payment_method' => 'QRIS', 'payment_date' => '2026-07-23', 'payment_lunas_date' => null, 'created_at' => '2026-07-22 11:00:00', 'updated_at' => '2026-07-23 09:00:00']],
            'invoices' => [['client_id' => 7, 'invoice_number' => 'INV/TSI/07/2026/001', 'invoice_date' => '2026-07-23', 'client_name' => 'Sari', 'client_address' => 'Surabaya', 'notes' => null, 'paid_amount' => '750000.00', 'ppn_amount' => '0.00', 'discount_amount' => '0.00', 'discount_type' => 'fixed', 'created_at' => '2026-07-23 09:00:00', 'updated_at' => '2026-07-23 09:00:00',
                'items' => [['item_name' => 'Website', 'item_qty' => 1, 'item_price' => '1500000.00']]]],
            'activity_logs' => [['action' => 'created', 'model_type' => 'Project', 'description' => 'Membuat proyek Sari', 'created_at' => '2026-07-22 11:00:00', 'updated_at' => '2026-07-22 11:00:00']],
            'settings' => ['company_name' => 'The Solution ID', 'payment_info' => 'BLU 0062 a.n. Test'],
        ];

        $importer = new SimProyekImporter();
        $first = $importer->import($payload, $admin->id);
        $second = $importer->import($payload, $admin->id);

        $this->assertSame([1, 1, 1, 1, 1], [$first['clients'], $first['orders'], $first['invoices'], $first['items'], $first['logs']]);
        $this->assertSame(0, $second['clients'] + $second['orders'] + $second['invoices'] + $second['logs']);
        $this->assertSame(1, OrderClient::count());
        $this->assertSame('2026-07-22 11:00:00', Order::first()->created_at->format('Y-m-d H:i:s'));
        $this->assertSame(OrderClient::first()->id, Invoice::first()->order_client_id);
        $this->assertSame('partial', Invoice::with('items')->first()->status);
        $this->assertSame('BLU 0062 a.n. Test', Setting::get('invoice_payment_info'));
    }

    public function test_import_upload_rejects_other_files(): void
    {
        $this->actingAs($this->admin());
        $file = UploadedFile::fake()->createWithContent('x.json', json_encode(['source' => 'Other']));

        $this->post(route('admin.sim-import.store'), ['file' => $file])->assertSessionHasErrors('file');
    }
}
