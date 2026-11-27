<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\ProjectController as AdminProjectController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ArticleController as AdminArticleController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProcessStepController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Admin\ApplicantController;
use App\Http\Controllers\Admin\PartnerSalesController;
use App\Http\Controllers\Admin\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/portfolio/{id}', [PortfolioController::class, 'show'])->name('portfolio.show');
Route::get('/artikel', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/artikel/{slug}', [ArticleController::class, 'show'])->name('articles.show');
// The Android service now lives in the home "Mau dibuatkan apa?" section.
Route::redirect('/aplikasi-android', '/#jasa', 301)->name('services.android');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::post('/jobs/apply', [JobController::class, 'store'])->middleware('throttle:5,1')->name('jobs.apply');
Route::get('/jobs/thanks', [JobController::class, 'thanks'])->name('jobs.thanks');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:3,1')->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account/profile', [AccountController::class, 'updateProfile'])->middleware('throttle:6,1')->name('account.profile.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->middleware('throttle:6,1')->name('account.password.update');
    Route::get('/applicants/export', [ApplicantController::class, 'export'])->name('applicants.export');
    Route::get('/applicants', [ApplicantController::class, 'index'])->name('applicants.index');
    Route::get('/applicants/{applicant}', [ApplicantController::class, 'show'])->whereNumber('applicant')->name('applicants.show');
    Route::put('/applicants/{applicant}', [ApplicantController::class, 'update'])->whereNumber('applicant')->name('applicants.update');
    Route::delete('/applicants/{applicant}', [ApplicantController::class, 'destroy'])->whereNumber('applicant')->name('applicants.destroy');
    Route::get('/partner-sales', [PartnerSalesController::class, 'index'])->name('partner-sales.index');
    Route::get('/partner-sales/create', [PartnerSalesController::class, 'create'])->name('partner-sales.create');
    Route::post('/partner-sales', [PartnerSalesController::class, 'store'])->name('partner-sales.store');
    Route::get('/partner-sales/{lead}', [PartnerSalesController::class, 'show'])->whereNumber('lead')->name('partner-sales.show');
    Route::post('/partner-sales/{lead}/deals', [PartnerSalesController::class, 'storeDeal'])->whereNumber('lead')->name('partner-sales.deals.store');
    Route::post('/partner-deals/{deal}/payments', [PartnerSalesController::class, 'storePayment'])->whereNumber('deal')->name('partner-sales.payments.store');
    Route::delete('/partner-deals/{deal}', [PartnerSalesController::class, 'destroyDeal'])->whereNumber('deal')->name('partner-sales.deals.destroy');
    Route::delete('/partner-payments/{payment}', [PartnerSalesController::class, 'destroyPayment'])->whereNumber('payment')->name('partner-sales.payments.destroy');
    Route::patch('/partner-payments/{payment}/commission-paid', [PartnerSalesController::class, 'markCommissionPaid'])->whereNumber('payment')->name('partner-sales.commissions.paid');
    Route::patch('/partner-bonuses/{bonus}/paid', [PartnerSalesController::class, 'markBonusPaid'])->whereNumber('bonus')->name('partner-sales.bonuses.paid');
    Route::resource('clients', ClientController::class)->except(['show']);
    Route::resource('projects', AdminProjectController::class)->except(['show']);
    Route::resource('contacts', AdminContactController::class)->only(['index', 'show', 'destroy']);
    Route::resource('testimonials', TestimonialController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('articles', AdminArticleController::class)->except(['show']);
    Route::resource('popups', PopupController::class)->except(['show']);
    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('settings', SettingController::class)->except(['show']);
    Route::resource('partners', PartnerController::class)->except(['show']);
    Route::resource('process-steps', ProcessStepController::class)->except(['show']);
});

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        Session::put('locale', $locale);
        App::setLocale($locale);
    }
    return redirect()->back();
})->name('lang.switch');

Route::get('/robots.txt', function () {
    $disallow = config('app.env') !== 'production' ? '/' : '';
    return response(trim('
User-agent: *
Disallow: ' . $disallow . '
Allow: /
Sitemap: ' . url('sitemap.xml') . '
'))->header('Content-Type', 'text/plain');
})->name('robots');

Route::get('/sitemap.xml', function () {
    $projects = \App\Models\Project::all();
    $articles = \App\Models\Article::published()->get();
    return response()->view('sitemap', compact('projects', 'articles'))->header('Content-Type', 'application/xml');
})->name('sitemap');

require __DIR__.'/auth.php';
