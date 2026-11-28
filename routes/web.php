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
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\FinancialReportController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\InvoicePaymentController;
use App\Http\Controllers\Admin\InvoiceSettlementController;
use App\Http\Controllers\Admin\InvoiceShareController;
use App\Http\Controllers\Admin\OrderClientController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SimImportController;
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
// Client-facing invoice PDF behind a secret 40-character link (see Invoice::shareUrl()).
Route::get('/invoice/{token}', [InvoiceShareController::class, 'show'])
    ->where('token', '[A-Za-z0-9]{40}')->middleware('throttle:30,1')->name('invoices.public');
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

    // Client project management (ported from SIM-Proyek)
    Route::get('/search', SearchController::class)->name('search');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->whereNumber('order')->name('orders.status');
    Route::resource('orders', OrderController::class)->except(['show']);
    Route::resource('order-clients', OrderClientController::class)->except(['show']);
    Route::get('/invoices/settings', [InvoiceController::class, 'settingsForm'])->name('invoices.settings');
    Route::post('/invoices/settings', [InvoiceController::class, 'updateSettings'])->name('invoices.settings.update');
    Route::get('/invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->whereNumber('invoice')->name('invoices.pdf');
    Route::get('/invoices/pairs', [InvoiceSettlementController::class, 'pairs'])->name('invoices.pairs');
    Route::post('/invoices/pairs', [InvoiceSettlementController::class, 'linkPairs'])->name('invoices.pairs.link');
    Route::post('/invoices/{invoice}/unlink', [InvoiceSettlementController::class, 'unlink'])->whereNumber('invoice')->name('invoices.settlement.unlink');
    Route::post('/invoices/{invoice}/settlement',[InvoiceSettlementController::class, 'store'])->whereNumber('invoice')->name('invoices.settlement.store');
    Route::post('/invoices/{invoice}/payments',[InvoicePaymentController::class, 'store'])->whereNumber('invoice')->name('invoices.payments.store');
    Route::delete('/invoices/{invoice}/payments/{payment}', [InvoicePaymentController::class, 'destroy'])->whereNumber(['invoice', 'payment'])->name('invoices.payments.destroy');
    Route::get('/invoices/{invoice}/payments/{payment}/receipt', [InvoicePaymentController::class, 'receipt'])->whereNumber(['invoice', 'payment'])->name('invoices.payments.receipt');
    Route::post('/invoices/{invoice}/whatsapp', [InvoiceShareController::class, 'whatsapp'])->whereNumber('invoice')->name('invoices.share.whatsapp');
    Route::post('/invoices/{invoice}/email', [InvoiceShareController::class, 'email'])->whereNumber('invoice')->middleware('throttle:10,1')->name('invoices.share.email');
    Route::post('/invoices/{invoice}/reset-link', [InvoiceShareController::class, 'resetLink'])->whereNumber('invoice')->name('invoices.share.reset');
    Route::resource('invoices', InvoiceController::class)->except(['show']);
    Route::get('/financial', [FinancialReportController::class, 'index'])->name('financial.index');
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/import/sim-proyek', [SimImportController::class, 'create'])->name('sim-import.create');
    Route::post('/import/sim-proyek', [SimImportController::class, 'store'])->middleware('throttle:5,1')->name('sim-import.store');
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
