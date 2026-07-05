<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Faq;
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
        $latestContacts = Contact::latest()->take(5)->get();
        $latestArticles = Article::latest()->take(5)->get();

        // Stats
        $viewsToday = PageView::whereDate('created_at', today())->count();
        $viewsWeek = PageView::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $viewsMonth = PageView::whereMonth('created_at', now()->month)->count();
        $topPages = PageView::select('url', DB::raw('count(*) as total'))
            ->groupBy('url')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'clientCount', 'projectCount', 'contactCount', 'testimonialCount',
            'articleCount', 'popupCount', 'faqCount',
            'serviceCount', 'partnerCount', 'stepCount',
            'latestContacts', 'latestArticles',
            'viewsToday', 'viewsWeek', 'viewsMonth', 'topPages'
        ));
    }
}
