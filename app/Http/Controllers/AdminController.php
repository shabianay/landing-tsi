<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Applicant;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Faq;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\PageView;
use App\Models\Partner;
use App\Models\Popup;
use App\Models\ProcessStep;
use App\Models\Project;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $clientCount = Client::count();
        $projectCount = Project::count();
        $contactCount = Contact::count();
        $testimonialCount = Testimonial::count();
        $articleCount = Article::count();
        $popupCount = Popup::count();
        $faqCount = Faq::count();
        $serviceCount = Service::count();
        $partnerCount = Partner::count();
        $stepCount = ProcessStep::count();
        $applicantCount = Applicant::count();
        $newApplicantCount = Applicant::where('created_at', '>=', now()->subDays(7))->count();
        $applicantStatusCounts = Applicant::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
        $latestContacts = Contact::latest()->take(5)->get();
        $latestArticles = Article::latest()->take(5)->get();

        // Stats
        $viewsToday = PageView::whereDate('created_at', today())->count();
        $viewsWeek = PageView::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $viewsMonth = PageView::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $topPages = PageView::select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        // Client projects & money (ported from SIM-Proyek)
        $orderCounts = Order::query()->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $dueOrders = Order::whereNotIn('status', ['Completed', 'Cancelled'])
            ->whereDate('deadline', '<=', today()->addDays(7))
            ->orderBy('deadline')
            ->take(6)
            ->get();
        $year = Invoice::summarize(Invoice::query()->whereYear('invoice_date', now()->year));
        $money = [
            'billed' => $year['total'],
            'paid' => $year['paid'],
            'outstanding' => Invoice::summarize(Invoice::query())['unpaid']['total'],
        ];
        $yearInvoices = Invoice::withAmounts()->whereYear('invoice_date', now()->year)->get();
        $paidByMonth = collect(range(1, 12))->map(fn ($m) => (float) $yearInvoices
            ->filter(fn ($i) => (int) $i->invoice_date->format('n') === $m)
            ->sum->paid_capped);

        return view('admin.dashboard', compact(
            'orderCounts', 'dueOrders', 'money', 'paidByMonth',
            'clientCount', 'projectCount', 'contactCount', 'testimonialCount',
            'articleCount', 'popupCount', 'faqCount',
            'serviceCount', 'partnerCount', 'stepCount',
            'latestContacts', 'latestArticles',
            'applicantCount', 'newApplicantCount', 'applicantStatusCounts',
            'viewsToday', 'viewsWeek', 'viewsMonth', 'topPages'
        ));
    }
}
