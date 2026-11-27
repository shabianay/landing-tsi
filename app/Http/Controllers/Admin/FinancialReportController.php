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

        // Amounts come from SQL (withAmounts), so no invoice items are loaded.
        $yearInvoices = Invoice::withAmounts()->whereYear('invoice_date', $year)->orderByDesc('invoice_date')->get();
        $invoices = $month ? $yearInvoices->filter(fn ($i) => (int) $i->invoice_date->format('n') === $month)->values() : $yearInvoices;

        $months = collect(range(1, 12))->map(function (int $m) use ($yearInvoices) {
            $items = $yearInvoices->filter(fn ($i) => (int) $i->invoice_date->format('n') === $m);

            return (object) [
                'month' => $m,
                'count' => $items->count(),
                'total' => (float) $items->sum->total,
                'paid' => (float) $items->sum->paid_capped,
            ];
        });

        $years = Invoice::query()->pluck('invoice_date')->map(fn ($d) => (int) $d->format('Y'))
            ->push(now()->year)->push($year)->unique()->sortDesc()->values();

        $periodQuery = Invoice::query()->whereYear('invoice_date', $year)
            ->when($month, fn ($q) => $q->whereMonth('invoice_date', $month));
        $summary = Invoice::summarize($periodQuery);
        $totalRevenue = $summary['total'];
        $totalPaid = $summary['paid'];

        return view('admin.financial.index', [
            'year' => $year,
            'month' => $month,
            'years' => $years,
            'invoices' => $invoices,
            'months' => $months,
            'maxMonth' => max(1, $months->max('total')),
            'totalRevenue' => $totalRevenue,
            'totalPaid' => $totalPaid,
            'unpaid' => $summary['unpaid'],
        ]);
    }
}
