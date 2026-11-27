<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\Article;
use App\Models\Contact;
use App\Models\PageView;
use App\Models\PartnerDeal;
use App\Models\PartnerLead;
use App\Models\PartnerMonthlyBonus;
use App\Models\PartnerPayment;
use App\Models\Popup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

/**
 * Regression tests for the findings of the 2026-11-27 security audit.
 */
class SecurityRemediationTest extends TestCase
{
    use RefreshDatabase;

    // M-01
    public function test_article_index_rejects_array_query_parameters_without_server_error(): void
    {
        $this->get('/artikel?q[]=x')->assertOk();
        $this->get('/artikel?category[]=x')->assertOk();
        $this->get('/artikel?q=%25')->assertOk();
    }

    // L-01
    public function test_article_json_ld_is_valid_json(): void
    {
        $article = Article::create([
            'title' => 'Judul "kutip" </script>',
            'slug' => 'judul-uji',
            'content' => 'Isi artikel',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        $html = $this->get(route('articles.show', $article->slug))->assertOk()->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $matches);
        $schemas = array_map(fn ($json) => json_decode(trim($json), true), $matches[1]);
        $articleSchema = collect($schemas)->firstWhere('@type', 'Article');

        $this->assertNotNull($articleSchema, 'Article JSON-LD must decode to valid JSON');
        $this->assertSame('https://schema.org', $articleSchema['@context']);
        $this->assertSame('Judul "kutip" </script>', $articleSchema['headline']);
        $this->assertStringNotContainsString('</script>"', $html);
    }

    // I-03
    public function test_article_views_are_counted_once_per_session(): void
    {
        $article = Article::create([
            'title' => 'Artikel', 'slug' => 'artikel-views', 'content' => 'Isi',
            'is_published' => true, 'published_at' => now()->subDay(),
        ]);

        $this->get(route('articles.show', $article->slug));
        $this->get(route('articles.show', $article->slug));

        $this->assertSame(1, (int) $article->fresh()->views);
    }

    // M-02
    public function test_partner_sales_pages_still_load_after_partner_is_soft_deleted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();
        $lead = PartnerLead::create([
            'applicant_id' => $partner->id, 'full_name' => 'Klien', 'whatsapp' => '6281200000002',
            'service' => 'Website', 'reported_at' => now(),
        ]);
        $partner->delete();

        $this->actingAs($admin)->get(route('admin.partner-sales.index'))->assertOk()->assertSee('TSI-001');
        $this->get(route('admin.partner-sales.show', $lead))->assertOk()->assertSee('TSI-001');
    }

    // M-03
    public function test_soft_delete_keeps_translations_and_force_delete_removes_them(): void
    {
        $popup = Popup::create(['title' => 'Judul', 'content' => 'Isi', 'is_active' => true]);
        $popup->setTranslation('title', 'en', 'Title');

        $popup->delete();
        $popup->restore();
        $this->assertSame(1, $popup->translations()->count());

        $popup->forceDelete();
        $this->assertSame(0, \App\Models\Translation::count());
    }

    // M-04
    public function test_contact_form_limits_message_length_and_drops_honeypot_submissions(): void
    {
        Mail::fake();
        config(['services.google.recaptcha_secret_key' => null]);

        $this->post(route('contact.store'), [
            'name' => 'Tester', 'email' => 'tester@example.com', 'message' => str_repeat('a', 5001),
        ])->assertSessionHasErrors('message');

        $this->post(route('contact.store'), [
            'name' => 'Bot', 'email' => 'bot@example.com', 'message' => 'spam', 'website' => 'http://spam.test',
        ])->assertRedirect(route('contact'));
        $this->assertSame(0, Contact::count());
        Mail::assertNothingQueued();

        config(['services.contact.recipients' => ['inbox@example.com']]);
        $this->post(route('contact.store'), [
            'name' => 'Tester', 'email' => 'tester@example.com', 'message' => 'Halo',
        ])->assertRedirect(route('contact'));
        $this->assertSame(1, Contact::count());
        Mail::assertQueued(\App\Mail\ContactNotification::class, fn ($mail) => $mail->hasTo('inbox@example.com'));
    }

    // L-02
    public function test_login_errors_are_translated_in_indonesian(): void
    {
        $this->post('/login', ['email' => 'nobody@example.com', 'password' => 'wrong'])
            ->assertSessionHasErrors(['email' => 'Email atau kata sandi tidak cocok.']);
    }

    // L-03
    public function test_dashboard_monthly_views_ignore_previous_years(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $old = PageView::create(['url' => '/', 'ip' => '127.0.0.1']);
        $old->forceFill(['created_at' => now()->subYear()])->save();
        PageView::create(['url' => '/', 'ip' => '127.0.0.1']);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('viewsMonth', 1);
    }

    // L-04
    public function test_page_views_older_than_retention_are_pruned_and_bots_are_not_tracked(): void
    {
        $old = PageView::create(['url' => '/', 'ip' => '127.0.0.1']);
        $old->forceFill(['created_at' => now()->subDays(PageView::RETENTION_DAYS + 1)])->save();
        PageView::create(['url' => '/', 'ip' => '127.0.0.1']);

        $this->artisan('model:prune', ['--model' => [PageView::class]])->assertSuccessful();
        $this->assertSame(1, PageView::count());

        $this->withHeader('User-Agent', 'Googlebot/2.1')->get('/about');
        $this->get('/halaman-tidak-ada');
        $this->assertSame(1, PageView::count());
    }

    // L-05
    public function test_popup_button_url_rejects_javascript_scheme(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post(route('admin.popups.store'), [
            'title' => ['id' => 'Promo'], 'content' => ['id' => 'Isi'], 'button_url' => 'javascript:alert(1)',
        ])->assertSessionHasErrors('button_url');
    }

    // M-06
    public function test_logo_setting_rejects_svg_upload(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $setting = \App\Models\Setting::firstOrCreate(['key' => 'logo'], ['value' => 'logo.webp', 'group' => 'general']);
        $svg = \Illuminate\Http\UploadedFile::fake()->createWithContent('logo.svg', '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>');

        $this->actingAs($admin)->put(route('admin.settings.update', $setting), [
            'key' => 'logo', 'group' => 'general', 'value' => $svg,
        ])->assertSessionHasErrors('value');
    }

    // L-07
    public function test_signed_applicant_cannot_move_back_to_recruitment_stage(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();

        $this->actingAs($admin)->put(route('admin.applicants.update', $partner), ['status' => 'wawancara'])
            ->assertSessionHasErrors('status');
        $this->assertSame('partner_aktif', $partner->fresh()->status);

        $this->put(route('admin.applicants.update', $partner), ['status' => 'ditolak'])
            ->assertSessionHasNoErrors();
        $this->assertSame('ditolak', $partner->fresh()->status);
    }

    // L-07
    public function test_admin_can_correct_wrong_payments_and_deals(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();
        $this->actingAs($admin);

        $deals = [];
        for ($number = 1; $number <= 3; $number++) {
            $lead = PartnerLead::create([
                'applicant_id' => $partner->id, 'full_name' => 'Klien '.$number,
                'whatsapp' => '62812000000'.$number.'0', 'service' => 'Website', 'reported_at' => now(),
            ]);
            $deal = $lead->deals()->create(['description' => 'Web', 'deal_amount' => 3_000_000, 'discount_amount' => 0, 'final_amount' => 3_000_000]);
            $this->post(route('admin.partner-sales.payments.store', $deal), [
                'payment_type' => 'dp', 'amount' => 1_000_000, 'received_at' => now()->format('Y-m-d\TH:i'),
            ])->assertSessionHasNoErrors();
            $deals[] = $deal;
        }
        $this->assertSame(500_000, PartnerMonthlyBonus::firstOrFail()->amount);

        // Deleting a DP recalculates the monthly bonus.
        $payment = $deals[0]->payments()->firstOrFail();
        $this->delete(route('admin.partner-sales.payments.destroy', $payment))->assertSessionHasNoErrors();
        $this->assertSame(0, PartnerMonthlyBonus::firstOrFail()->amount);
        $this->assertSame(2, PartnerPayment::count());

        // A deal without payments can be removed; a deal with payments cannot.
        $this->delete(route('admin.partner-sales.deals.destroy', $deals[0]))->assertSessionHasNoErrors();
        $this->delete(route('admin.partner-sales.deals.destroy', $deals[1]))->assertSessionHasErrors('deal');
        $this->assertSame(2, PartnerDeal::count());

        // Payments whose commission was already paid out are locked.
        $paid = $deals[1]->payments()->firstOrFail();
        $this->patch(route('admin.partner-sales.commissions.paid', $paid));
        $this->delete(route('admin.partner-sales.payments.destroy', $paid))->assertSessionHasErrors('payment');
        $this->assertNotNull($paid->fresh());
    }

    // I-01
    public function test_is_admin_cannot_be_mass_assigned(): void
    {
        $user = User::create(['name' => 'X', 'email' => 'x@example.com', 'password' => 'secret-password', 'is_admin' => true]);

        $this->assertFalse((bool) $user->fresh()->is_admin);
    }

    // I-02
    public function test_last_admin_cannot_delete_own_account(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->delete('/profile', ['password' => 'password'])
            ->assertSessionHasErrorsIn('userDeletion', 'password');
        $this->assertNotNull($admin->fresh());
    }

    // L-08
    public function test_security_headers_are_sent(): void
    {
        $this->get('/')
            ->assertHeader('Permissions-Policy')
            ->assertHeader('Content-Security-Policy')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
            ->assertHeaderMissing('X-Powered-By');
    }

    // L-08: nonce-based script CSP; every script tag on the page carries the nonce.
    public function test_csp_uses_a_per_request_nonce_matching_rendered_scripts(): void
    {
        $first = $this->get('/');
        $csp = $first->headers->get('Content-Security-Policy');

        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[A-Za-z0-9]+'/", $csp);
        $this->assertStringNotContainsString("script-src 'self' 'unsafe-inline'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);

        preg_match("/'nonce-([A-Za-z0-9]+)'/", $csp, $match);
        preg_match_all('/<script(?![^>]*type="application\/ld\+json")([^>]*)>/', $first->getContent(), $scripts);
        foreach ($scripts[1] as $attributes) {
            $this->assertStringContainsString('nonce="'.$match[1].'"', $attributes);
        }

        $second = $this->get('/')->headers->get('Content-Security-Policy');
        $this->assertNotSame($csp, $second);
    }

    // L-08: no inline event handlers remain (they would be blocked by the CSP).
    public function test_views_have_no_inline_event_handlers_or_inline_scripts(): void
    {
        $offenders = [];
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(resource_path('views'))) as $file) {
            if (! str_ends_with($file->getFilename(), '.blade.php') || str_contains($file->getPathname(), 'vendor')) {
                continue;
            }
            $source = file_get_contents($file->getPathname());
            if (preg_match('/\son(click|submit|load|change|input|error)\s*=/i', $source)
                || preg_match('/<script(?![^>]*(src=|application\/ld\+json))[^>]*>/i', $source)) {
                $offenders[] = $file->getFilename();
            }
        }

        $this->assertSame([], $offenders);
    }

    // L-06
    public function test_hidden_applicant_can_apply_again_and_keeps_history(): void
    {
        $partner = $this->createActivePartner();
        $partner->delete();

        $this->post(route('jobs.apply'), [
            'full_name' => 'Partner Kembali',
            'whatsapp' => '081234567801',
            'domicile' => 'Bandung',
            'birth_date' => now()->subYears(26)->toDateString(),
            'current_activity' => 'bekerja',
            'motivation' => 'Saya ingin bergabung kembali sebagai partner.',
            'social_link' => 'https://instagram.com/kembali',
            'agreed_commission' => '1',
        ])->assertRedirect(route('jobs.thanks'));

        $this->assertSame(1, Applicant::withTrashed()->count());
        $applicant = Applicant::firstOrFail();
        $this->assertSame($partner->id, $applicant->id);
        $this->assertSame('baru', $applicant->status);
        $this->assertSame('Partner Kembali', $applicant->full_name);
        $this->assertSame('TSI-001', $applicant->partner_code);
        $this->assertStringContainsString('Mendaftar ulang', $applicant->admin_notes);
        $this->assertTrue($applicant->statusLogs()->where('from_status', 'partner_aktif')->where('to_status', 'baru')->exists());

        // A visible applicant with the same number is still rejected.
        $this->post(route('jobs.apply'), [
            'full_name' => 'Duplikat', 'whatsapp' => '081234567801', 'domicile' => 'Bandung',
            'birth_date' => now()->subYears(26)->toDateString(), 'current_activity' => 'bekerja',
            'motivation' => 'Saya ingin bergabung kembali sebagai partner.',
            'social_link' => 'https://instagram.com/kembali', 'agreed_commission' => '1',
        ])->assertSessionHasErrors('whatsapp');
    }

    // C-01
    public function test_admin_password_command_rejects_weak_password_and_updates_strong_one(): void
    {
        $admin = User::factory()->create(['is_admin' => true, 'email' => 'admin@example.com']);

        $this->artisan('admin:password', ['email' => 'admin@example.com'])
            ->expectsQuestion('Password baru (minimal 12 karakter)', 'password')
            ->assertFailed();

        $this->artisan('admin:password', ['email' => 'admin@example.com'])
            ->expectsQuestion('Password baru (minimal 12 karakter)', 'Kuat-Sekali-2026!')
            ->expectsQuestion('Ulangi password baru', 'Kuat-Sekali-2026!')
            ->assertSuccessful();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Kuat-Sekali-2026!', $admin->fresh()->password));
    }

    private function createActivePartner(): Applicant
    {
        $partner = Applicant::create([
            'full_name' => 'Partner Sales',
            'whatsapp' => '6281234567801',
            'domicile' => 'Jakarta',
            'birth_date' => now()->subYears(25)->toDateString(),
            'current_activity' => 'freelance',
            'motivation' => 'Saya tertarik membantu bisnis bertumbuh.',
            'social_link' => 'https://instagram.com/partner',
            'agreed_commission' => true,
            'status' => 'partner_aktif',
        ]);
        $partner->forceFill([
            'partner_code' => 'TSI-001',
            'agreement_version' => 'v1',
            'agreement_signed_at' => now(),
        ])->save();

        return $partner;
    }
}
