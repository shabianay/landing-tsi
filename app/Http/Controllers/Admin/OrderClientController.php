<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\OrderClient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Clients who order projects ("Data Klien"). */
class OrderClientController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $clients = OrderClient::withCount(['orders', 'invoices'])
            ->when($q !== '', function ($query) use ($q) {
                $term = '%' . addcslashes(mb_substr($q, 0, 100), '%_\\') . '%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('company', 'like', $term)->orWhere('phone', 'like', $term));
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.order-clients.index', compact('clients', 'q'));
    }

    public function create(): View
    {
        return view('admin.order-clients.form', ['client' => new OrderClient()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $client = OrderClient::create($this->validated($request));
        ActivityLog::record('created', $client, "Menambahkan klien {$client->name}");

        if ($request->query('return') === 'order') {
            return redirect()->route('admin.orders.create', ['client' => $client->id])->with('success', 'Klien ditambahkan. Lanjutkan isi proyek.');
        }

        return redirect()->route('admin.order-clients.index')->with('success', 'Klien berhasil ditambahkan.');
    }

    public function edit(OrderClient $orderClient): View
    {
        return view('admin.order-clients.form', ['client' => $orderClient]);
    }

    public function update(Request $request, OrderClient $orderClient): RedirectResponse
    {
        $orderClient->update($this->validated($request));
        ActivityLog::record('updated', $orderClient, "Mengubah data klien {$orderClient->name}");

        return redirect()->route('admin.order-clients.index')->with('success', 'Data klien berhasil diperbarui.');
    }

    public function destroy(OrderClient $orderClient): RedirectResponse
    {
        ActivityLog::record('deleted', $orderClient, "Menghapus klien {$orderClient->name}");
        // Projects and invoices keep the client's name; only the link is cleared (nullOnDelete).
        $orderClient->delete();

        return redirect()->route('admin.order-clients.index')->with('success', 'Klien berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:2000'],
            'company' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ], [], ['name' => 'nama', 'phone' => 'nomor HP', 'address' => 'alamat', 'company' => 'perusahaan', 'notes' => 'catatan']);
    }
}
