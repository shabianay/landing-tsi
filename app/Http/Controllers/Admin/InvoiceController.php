<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\OrderClient;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

/** Invoices with PDF output, ported from SIM-Proyek. */
class InvoiceController extends Controller
{
    /** Company details printed on invoices (setting key => label). */
    public const SETTINGS = [
        'invoice_company_name' => 'Nama usaha',
        'invoice_company_address' => 'Alamat',
        'invoice_company_phone' => 'Telepon',
        'invoice_company_email' => 'Email',
        'invoice_payment_info' => 'Info pembayaran (rekening)',
        'invoice_footer' => 'Catatan kaki invoice',
    ];

    public function index(Request $request): View
    {
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(['paid', 'partial', 'unpaid'])],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        $query = Invoice::with('items')->orderByDesc('invoice_date')->orderByDesc('id');

        if (! empty($filters['q'])) {
            $term = '%' . addcslashes($filters['q'], '%_\\') . '%';
            $query->where(fn ($q) => $q->where('client_name', 'like', $term)->orWhere('invoice_number', 'like', $term));
        }

        // Status is computed from items, so filter in PHP on the (small) result set.
        $all = $query->get();
        if (! empty($filters['status'])) {
            $all = $all->filter(fn (Invoice $i) => $i->status === $filters['status'])->values();
        }

        $page = max(1, $request->integer('page', 1));
        $perPage = 15;
        $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(), $all->count(), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.invoices.index', [
            'invoices' => $invoices,
            'filters' => $filters,
            'totals' => [
                'total' => $all->sum->total,
                'paid' => $all->sum(fn ($i) => (float) $i->paid_amount),
                'remaining' => $all->sum->remaining,
            ],
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.invoices.form', [
            'invoice' => new Invoice([
                'invoice_number' => Invoice::nextNumber(),
                'invoice_date' => today(),
                'discount_type' => 'fixed',
                'paid_amount' => 0,
            ]),
            'clients' => OrderClient::orderBy('name')->get(),
            'selectedClient' => $request->integer('client') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $invoice = DB::transaction(function () use ($data) {
            $invoice = Invoice::create($this->invoiceAttributes($data) + ['invoice_number' => $data['invoice_number']]);
            $this->syncItems($invoice, $data);

            return $invoice;
        });

        ActivityLog::record('created', $invoice, "Membuat invoice {$invoice->invoice_number}");

        return redirect()->route('admin.invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} berhasil dibuat.")
            ->with('pdf_url', route('admin.invoices.pdf', $invoice));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('admin.invoices.form', [
            'invoice' => $invoice,
            'clients' => OrderClient::orderBy('name')->get(),
            'selectedClient' => $invoice->order_client_id,
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $this->validated($request, $invoice);

        DB::transaction(function () use ($invoice, $data) {
            $invoice->update($this->invoiceAttributes($data));
            $this->syncItems($invoice, $data);
        });

        ActivityLog::record('updated', $invoice, "Mengubah invoice {$invoice->invoice_number}");

        return redirect()->route('admin.invoices.index')
            ->with('success', "Invoice {$invoice->invoice_number} berhasil diperbarui.")
            ->with('pdf_url', route('admin.invoices.pdf', $invoice));
    }

    /** Record a payment: the amount paid so far. */
    public function updatePaid(Request $request, Invoice $invoice): RedirectResponse
    {
        $data = $request->validate(['paid_amount' => ['required', 'numeric', 'min:0', 'max:9999999999999']], [], ['paid_amount' => 'jumlah dibayar']);

        $invoice->update(['paid_amount' => $data['paid_amount']]);
        ActivityLog::record('updated', $invoice, "Mencatat pembayaran invoice {$invoice->invoice_number}: Rp" . number_format((float) $data['paid_amount'], 0, ',', '.'));

        return back()->with('success', "Pembayaran invoice {$invoice->invoice_number} disimpan ({$invoice->fresh('items')->status_label}).");
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        ActivityLog::record('deleted', $invoice, "Menghapus invoice {$invoice->invoice_number}");
        $invoice->delete();

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice berhasil dihapus.');
    }

    public function pdf(Request $request, Invoice $invoice): Response
    {
        $invoice->load('items');
        $settings = $this->settings();
        $logo = $this->logoDataUri($settings['invoice_logo'] ?? null);

        $pdf = Pdf::loadView('admin.invoices.pdf', compact('invoice', 'settings', 'logo'))->setPaper('a4');
        $filename = 'invoice-' . str_replace('/', '-', $invoice->invoice_number) . '.pdf';

        return $request->boolean('download') ? $pdf->download($filename) : $pdf->stream($filename);
    }

    public function settingsForm(): View
    {
        return view('admin.invoices.settings', ['settings' => $this->settings(), 'fields' => self::SETTINGS]);
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'invoice_company_name' => ['required', 'string', 'max:255'],
            'invoice_company_address' => ['nullable', 'string', 'max:1000'],
            'invoice_company_phone' => ['nullable', 'string', 'max:50'],
            'invoice_company_email' => ['nullable', 'email', 'max:255'],
            'invoice_payment_info' => ['nullable', 'string', 'max:1000'],
            'invoice_footer' => ['nullable', 'string', 'max:500'],
            // PNG/JPG only: the PDF renderer cannot embed WebP or SVG.
            'invoice_logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        foreach (array_keys(self::SETTINGS) as $key) {
            Setting::updateOrCreate(['key' => $key], ['value' => $data[$key] ?? '', 'group' => 'invoice', 'label' => self::SETTINGS[$key]]);
        }

        if ($request->hasFile('invoice_logo')) {
            $old = Setting::get('invoice_logo');
            $path = $request->file('invoice_logo')->store('invoice', 'public');
            Setting::updateOrCreate(['key' => 'invoice_logo'], ['value' => $path, 'group' => 'invoice', 'label' => 'Logo invoice']);
            if ($old && $old !== $path) {
                Storage::disk('public')->delete($old);
            }
        }

        ActivityLog::record('updated', 'Setting', 'Mengubah pengaturan invoice');

        return redirect()->route('admin.invoices.settings')->with('success', 'Pengaturan invoice disimpan.');
    }

    private function settings(): array
    {
        $saved = Setting::whereIn('key', [...array_keys(self::SETTINGS), 'invoice_logo'])->pluck('value', 'key')->all();

        return $saved + [
            'invoice_company_name' => Setting::get('site_name', 'The Solution ID'),
            'invoice_company_address' => '',
            'invoice_company_phone' => Setting::get('contact_phone', ''),
            'invoice_company_email' => Setting::get('contact_email', ''),
            'invoice_payment_info' => '',
            'invoice_footer' => 'Terima kasih atas kepercayaan Anda.',
        ];
    }

    private function logoDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }
        $mime = Storage::disk('public')->mimeType($path);
        if (! in_array($mime, ['image/png', 'image/jpeg'], true)) {
            return null;
        }

        return 'data:' . $mime . ';base64,' . base64_encode(Storage::disk('public')->get($path));
    }

    private function validated(Request $request, ?Invoice $invoice = null): array
    {
        return $request->validate([
            'invoice_number' => [$invoice ? 'prohibited' : 'required', 'string', 'max:50', Rule::unique('invoices', 'invoice_number')],
            'invoice_date' => ['required', 'date'],
            'order_client_id' => ['required', 'exists:order_clients,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'paid_amount' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
            'use_ppn' => ['nullable', 'boolean'],
            'discount_amount' => ['nullable', 'numeric', 'min:0', 'max:9999999999999'],
            'discount_type' => ['required', Rule::in(['fixed', 'percent'])],
            'items' => ['required', 'array', 'min:1', 'max:50'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:100000'],
            'items.*.price' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
        ], [
            'items.required' => 'Tambahkan minimal satu item.',
        ], [
            'invoice_number' => 'nomor invoice',
            'invoice_date' => 'tanggal invoice',
            'order_client_id' => 'klien',
            'paid_amount' => 'jumlah dibayar',
            'discount_amount' => 'diskon',
            'items.*.name' => 'nama item',
            'items.*.qty' => 'jumlah',
            'items.*.price' => 'harga',
        ]);
    }

    private function invoiceAttributes(array $data): array
    {
        $client = OrderClient::findOrFail($data['order_client_id']);
        $subtotal = collect($data['items'])->sum(fn ($i) => $i['qty'] * $i['price']);
        $discount = (float) ($data['discount_amount'] ?? 0);
        if ($data['discount_type'] === 'percent') {
            $discount = min($discount, 100);
        }

        return [
            'invoice_date' => $data['invoice_date'],
            'order_client_id' => $client->id,
            'client_name' => $client->name,
            'client_address' => $client->address,
            'notes' => $data['notes'] ?? null,
            'paid_amount' => $data['paid_amount'],
            'ppn_amount' => ! empty($data['use_ppn']) ? round($subtotal * Invoice::PPN_RATE, 2) : 0,
            'discount_amount' => $discount,
            'discount_type' => $data['discount_type'],
        ];
    }

    private function syncItems(Invoice $invoice, array $data): void
    {
        $invoice->items()->delete();
        foreach (array_values($data['items']) as $item) {
            $invoice->items()->create([
                'item_name' => $item['name'],
                'item_qty' => $item['qty'],
                'item_price' => $item['price'],
            ]);
        }
    }
}
