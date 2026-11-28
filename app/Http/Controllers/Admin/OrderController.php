<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\OrderClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/** Client projects ("Proyek Klien"), ported from SIM-Proyek: Kanban board + filterable list. */
class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $view = $request->query('view') === 'list' ? 'list' : 'board';
        $filters = $request->validate([
            'status' => ['nullable', Rule::in(Order::STATUSES)],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'q' => ['nullable', 'string', 'max:100'],
        ]);

        // Linked invoices (with SQL amounts) give each project its payment badge.
        $query = Order::query()->with(['invoices' => fn ($q) => $q->withAmounts()])->latest();

        if (! empty($filters['q'])) {
            $term = '%' . addcslashes($filters['q'], '%_\\') . '%';
            $query->where(fn ($q) => $q->where('client_name', 'like', $term)->orWhere('task_type', 'like', $term));
        }

        if ($view === 'list') {
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            if (! empty($filters['start_date']) && ! empty($filters['end_date'])) {
                $query->whereBetween('created_at', [$filters['start_date'] . ' 00:00:00', $filters['end_date'] . ' 23:59:59']);
            }

            return view('admin.orders.index', [
                'view' => $view,
                'filters' => $filters,
                'orders' => $query->paginate(15)->withQueryString(),
            ]);
        }

        return view('admin.orders.index', [
            'view' => $view,
            'filters' => $filters,
            'columns' => $query->get()->groupBy('status'),
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.orders.form', [
            'order' => new Order(['status' => 'Pending', 'deadline' => now()->addWeeks(2)]),
            'clients' => OrderClient::orderBy('name')->get(),
            'selectedClient' => $request->integer('client') ?: null,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['status'] = Order::statusFromPayments($data);

        $order = Order::create($data);
        ActivityLog::record('created', $order, "Membuat proyek {$order->client_name} ({$order->task_type})");

        return redirect()->route('admin.orders.index')->with('success', 'Proyek berhasil ditambahkan.');
    }

    public function edit(Order $order): View
    {
        $order->load(['invoices' => fn ($q) => $q->withAmounts()->orderBy('invoice_date')]);

        return view('admin.orders.form', [
            'order' => $order,
            'clients' => OrderClient::orderBy('name')->get(),
            'selectedClient' => $order->order_client_id,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $data = $this->validated($request);
        if ($order->invoices()->exists()) {
            // Payment dates of a project with invoices come from those invoices (see Order::syncPaymentDatesFromInvoices).
            $data['payment_date'] = $order->payment_date?->toDateString();
            $data['payment_lunas_date'] = $order->payment_lunas_date?->toDateString();
        }
        $requested = $request->validate(['status' => ['nullable', Rule::in(Order::STATUSES)]])['status'] ?? null;
        $data['status'] = $order->statusAfterEdit($data, $requested);

        $order->update($data);
        ActivityLog::record('updated', $order, "Mengubah proyek {$order->client_name} ({$order->task_type})");

        return redirect()->route('admin.orders.index', $request->query('return') === 'list' ? ['view' => 'list'] : [])
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    /** Move a card on the board (drag and drop sends JSON; the no-JS select posts a form). */
    public function updateStatus(Request $request, Order $order): JsonResponse|RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(Order::STATUSES)]]);

        if ($order->status !== $data['status']) {
            $order->update(['status' => $data['status']]);
            ActivityLog::record('updated', $order, "Memindahkan proyek {$order->client_name} ke {$order->status_label}");
        }

        if ($request->expectsJson()) {
            return response()->json(['ok' => true, 'status' => $order->status, 'label' => $order->status_label]);
        }

        return back()->with('success', "Proyek dipindahkan ke {$order->status_label}.");
    }

    public function destroy(Order $order): RedirectResponse
    {
        ActivityLog::record('deleted', $order, "Menghapus proyek {$order->client_name} ({$order->task_type})");
        $order->delete();

        return redirect()->route('admin.orders.index', url()->previous() === route('admin.orders.index', ['view' => 'list']) ? ['view' => 'list'] : [])
            ->with('success', 'Proyek berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'order_client_id' => ['required', 'exists:order_clients,id'],
            'task_type' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'deadline' => ['required', 'date'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999'],
            'payment_method' => ['required', Rule::in(Order::PAYMENT_METHODS)],
            'payment_date' => ['nullable', 'date'],
            'payment_lunas_date' => ['nullable', 'date'],
        ], [], [
            'order_client_id' => 'klien',
            'task_type' => 'jenis pekerjaan',
            'description' => 'deskripsi',
            'price' => 'harga',
            'payment_method' => 'metode pembayaran',
            'payment_date' => 'tanggal DP',
            'payment_lunas_date' => 'tanggal lunas',
        ]);

        $data['client_name'] = OrderClient::findOrFail($data['order_client_id'])->name;

        return $data;
    }
}
