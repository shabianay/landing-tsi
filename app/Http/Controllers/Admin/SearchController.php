<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\OrderClient;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** One search box for client projects, invoices and clients. */
class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $q = trim(mb_substr((string) $request->query('q', ''), 0, 100));
        $results = ['orders' => collect(), 'invoices' => collect(), 'clients' => collect()];

        if (mb_strlen($q) >= 2) {
            $term = '%' . addcslashes($q, '%_\\') . '%';
            $results['orders'] = Order::where('client_name', 'like', $term)->orWhere('task_type', 'like', $term)->latest()->limit(10)->get();
            $results['invoices'] = Invoice::with('items')->where('client_name', 'like', $term)->orWhere('invoice_number', 'like', $term)->latest('invoice_date')->limit(10)->get();
            $results['clients'] = OrderClient::where('name', 'like', $term)->orWhere('company', 'like', $term)->orWhere('phone', 'like', $term)->orderBy('name')->limit(10)->get();
        }

        return view('admin.search', compact('q', 'results'));
    }
}
