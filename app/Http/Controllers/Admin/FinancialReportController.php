<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Revenue and payments per year/month, from invoices (ported from SIM-Proyek). */
class FinancialReportController extends Controller
{
    public function index(Request $request): View
    {
        $data = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);
        $year = (int) ($data['year'] ?? now()->year);
        $month = isset($data['month']) ? (int) $data['month'] : null;

        $yearInvoices = Invoice::with('items')->whereYear('invoice_date', $year)->orderByDesc('invoice_date')->get();
        $invoices = $month ? $yearInvoices->filter(fn ($i) => (int) $i->invoice_date->format('n') === $month)->values() : $yearInvoices;

        $months = collect(range(1, 12))->map(function (int $m) use ($yearInvoices) {
            $items = $yearInvoices->filter(fn ($i) => (int) $i->invoice_date->format('n') === $m);

            return (object) [
                'month' => $m,
                'count' => $items->count(),
                'total' => (float) $items->sum->total,
                'paid' => (float) $items->sum(fn ($i) => (float) $i->paid_amount),
            ];
        });

        $years = Invoice::query()->pluck('invoice_date')->map(fn ($d) => (int) $d->format('Y'))
            ->push(now()->year)->push($year)->unique()->sortDesc()->values();

        $totalRevenue = (float) $invoices->sum->total;
        $totalPaid = (float) $invoices->sum(fn ($i) => (float) $i->paid_amount);

        return view('admin.financial.index', [
            'year' => $year,
            'month' => $month,
            'years' => $years,
            'invoices' => $invoices,
            'months' => $months,
            'maxMonth' => max(1, $months->max('total')),
            'totalRevenue' => $totalRevenue,
            'totalPaid' => $totalPaid,
            'unpaid' => Invoice::unpaidSummary($invoices),
        ]);
    }
}
