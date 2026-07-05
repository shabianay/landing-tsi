<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\Popup;
use App\Models\ProcessStep;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $popups = Popup::active()->get();

        $services = Service::with('translations')->where('is_active', true)->orderBy('sort_order')->get();
        $processSteps = ProcessStep::with('translations')->where('is_active', true)->orderBy('step_number')->get();
        $partners = Partner::with('translations')->where('is_active', true)->orderBy('sort_order')->get();
        return view('home', compact('popups', 'services', 'processSteps', 'partners'));
    }

    public function about()
    {
        $partners = Partner::with('translations')->where('is_active', true)->orderBy('sort_order')->get();
        return view('about', compact('partners'));
    }
}
