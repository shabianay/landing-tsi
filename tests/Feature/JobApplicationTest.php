<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JobApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_applicant_can_submit_and_phone_number_is_normalized(): void
    {
        $response = $this->post(route('jobs.apply'), $this->validApplication());

        $response->assertRedirect(route('jobs.thanks'));
        $this->assertDatabaseHas('applicants', [
            'full_name' => 'Calon Partner',
            'whatsapp' => '6281234567890',
            'status' => 'baru',
        ]);
        $this->assertSame(now()->subYears(25)->toDateString(), Applicant::firstOrFail()->birth_date->toDateString());
        $this->assertDatabaseHas('applicant_status_logs', [
            'from_status' => null,
            'to_status' => 'baru',
        ]);
    }

    public function test_job_page_is_public_and_included_in_the_sitemap(): void
    {
        $this->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('Freelance Sales &amp; Marketing', false)
            ->assertSee('Jika berhasil membawa 3 klien dalam 1 bulan')
            ->assertSee('6 klien = Rp1.000.000')
            ->assertSee('name="_token"', false);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertSee(route('jobs.index'));
    }

    public function test_job_pages_follow_the_selected_language(): void
    {
        $this->withSession(['locale' => 'en'])
            ->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('Application form')
            ->assertSee('Tell us about yourself')
            ->assertSee('Date of birth')
            ->assertSee('Student')
            ->assertSee('Bring 3 clients in one month')
            ->assertSee('Submit application')
            ->assertDontSee('Formulir pendaftaran')
            ->assertDontSee('Ceritakan tentang dirimu');

        $this->withSession(['locale' => 'en'])
            ->get(route('jobs.thanks'))
            ->assertOk()
            ->assertSee('Application received')
            ->assertSee('Thank you for applying!')
            ->assertSee('Back to home');
    }

    public function test_applicant_must_be_at_least_eighteen_years_old(): void
    {
        $this->post(route('jobs.apply'), $this->validApplication([
            'birth_date' => now()->subYears(18)->addDay()->toDateString(),
        ]))->assertSessionHasErrors('birth_date');

        $this->post(route('jobs.apply'), $this->validApplication([
            'birth_date' => now()->subYears(18)->toDateString(),
        ]))->assertRedirect(route('jobs.thanks'));

        $this->assertDatabaseCount('applicants', 1);
    }

    public function test_duplicate_whatsapp_is_rejected_after_normalization(): void
    {
        Applicant::create(array_replace($this->validApplication(), [
            'whatsapp' => '6281234567890',
            'status' => 'baru',
        ]));

        $response = $this->from(route('jobs.index'))->post(route('jobs.apply'), $this->validApplication());

        $response->assertSessionHasErrors('whatsapp');
        $this->assertDatabaseCount('applicants', 1);
    }

    public function test_untrusted_fields_cannot_inject_sql_or_set_admin_status(): void
    {
        $name = "Robert'); DROP TABLE applicants;--";

        $this->post(route('jobs.apply'), $this->validApplication([
            'full_name' => $name,
            'status' => 'partner_aktif',
            'admin_notes' => 'Injected note',
        ]))->assertRedirect(route('jobs.thanks'));

        $this->assertDatabaseHas('applicants', [
            'full_name' => $name,
            'status' => 'baru',
            'admin_notes' => null,
        ]);
        $this->assertDatabaseHas('applicant_status_logs', [
            'to_status' => 'baru',
        ]);
    }

    public function test_invalid_application_and_honeypot_are_not_saved(): void
    {
        $this->post(route('jobs.apply'), $this->validApplication([
            'motivation' => 'Singkat',
            'social_link' => 'https://example.com/profile',
            'agreed_commission' => '',
        ]))->assertSessionHasErrors(['motivation', 'social_link', 'agreed_commission']);

        $this->post(route('jobs.apply'), $this->validApplication(['website' => 'bot']))
            ->assertRedirect(route('jobs.thanks'));

        $this->assertDatabaseCount('applicants', 0);
    }

    public function test_motivation_limit_has_a_clear_message_and_character_hint(): void
    {
        $this->from(route('jobs.index'))
            ->post(route('jobs.apply'), $this->validApplication([
                'motivation' => str_repeat('a', 2001),
            ]))
            ->assertSessionHasErrors([
                'motivation' => 'Alasan ketertarikan maksimal 2.000 karakter.',
            ]);

        $this->get(route('jobs.index'))
            ->assertOk()
            ->assertSee('Maksimal 2.000 karakter.');
    }

    public function test_malformed_phone_payload_is_rejected_without_saving(): void
    {
        $this->post(route('jobs.apply'), $this->validApplication([
            'whatsapp' => ['6281234567890'],
        ]))->assertSessionHasErrors('whatsapp');

        $this->assertDatabaseCount('applicants', 0);
    }

    public function test_public_form_is_limited_to_five_submissions_per_minute(): void
    {
        for ($number = 1; $number <= 5; $number++) {
            $this->post(route('jobs.apply'), $this->validApplication([
                'whatsapp' => '62812345678'.str_pad((string) $number, 2, '0', STR_PAD_LEFT),
            ]))->assertRedirect(route('jobs.thanks'));
        }

        $this->post(route('jobs.apply'), $this->validApplication([
            'whatsapp' => '6281234567806',
        ]))->assertTooManyRequests();

        $this->assertDatabaseCount('applicants', 5);
    }

    public function test_applicant_dashboard_requires_an_admin(): void
    {
        $this->get(route('admin.applicants.index'))->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.applicants.index'))
            ->assertForbidden();
    }

    public function test_admin_can_filter_update_export_and_soft_delete_applicants(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $applicant = Applicant::create($this->validApplication() + ['status' => 'baru']);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Rekrutmen sales');

        $this->actingAs($admin)
            ->get(route('admin.applicants.index', ['status' => 'baru', 'q' => 'Calon']))
            ->assertOk()
            ->assertSee('Calon Partner');

        $this->put(route('admin.applicants.update', $applicant), [
            'status' => 'dihubungi',
            'admin_notes' => 'Sudah dihubungi.',
        ])->assertRedirect();

        $this->assertDatabaseHas('applicant_status_logs', [
            'applicant_id' => $applicant->id,
            'from_status' => 'baru',
            'to_status' => 'dihubungi',
        ]);

        $export = $this->get(route('admin.applicants.export', ['status' => 'dihubungi']));
        $export->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('Calon Partner', $export->streamedContent());

        $this->delete(route('admin.applicants.destroy', $applicant))->assertRedirect();
        $this->assertSoftDeleted('applicants', ['id' => $applicant->id]);
    }

    public function test_agreement_must_be_recorded_before_trial_or_partner_activation(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $applicant = Applicant::create($this->validApplication() + ['status' => 'baru']);

        $this->actingAs($admin)
            ->put(route('admin.applicants.update', $applicant), [
                'status' => 'perjanjian_ditandatangani',
                'admin_notes' => '',
            ])
            ->assertSessionHasErrors('agreement_version');

        $this->put(route('admin.applicants.update', $applicant), [
            'status' => 'partner_aktif',
            'agreement_version' => '',
            'admin_notes' => '',
        ])->assertSessionHasErrors('status');

        $this->put(route('admin.applicants.update', $applicant), [
            'status' => 'perjanjian_ditandatangani',
            'agreement_version' => 'v1.2',
            'admin_notes' => '',
        ])->assertRedirect();

        $applicant->refresh();
        $this->assertSame('v1.2', $applicant->agreement_version);
        $this->assertNotNull($applicant->agreement_signed_at);

        $this->put(route('admin.applicants.update', $applicant), [
            'status' => 'partner_aktif',
            'agreement_version' => 'v1.2',
            'admin_notes' => '',
            'partner_code' => 'ATTACKER-CODE',
            'agreement_signed_at' => '2000-01-01',
        ])->assertRedirect();

        $applicant->refresh();
        $this->assertSame('TSI-001', $applicant->partner_code);
        $this->assertSame('v1.2', $applicant->agreement_version);
        $this->assertNotSame('2000-01-01', $applicant->agreement_signed_at->toDateString());
        $this->actingAs($admin)
            ->get(route('admin.applicants.show', $applicant))
            ->assertOk()
            ->assertSee('TSI-001')
            ->assertSee('Disetujui')
            ->assertSee('Kode partner');
    }

    public function test_partner_codes_are_unique_and_not_reallocated(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $first = Applicant::create($this->validApplication() + [
            'status' => 'perjanjian_ditandatangani',
        ]);
        $first->agreement_version = 'v1';
        $first->agreement_signed_at = now();
        $first->save();
        $second = Applicant::create(array_replace($this->validApplication(), [
            'whatsapp' => '6281234567899',
            'status' => 'perjanjian_ditandatangani',
        ]));
        $second->agreement_version = 'v1';
        $second->agreement_signed_at = now();
        $second->save();

        $this->actingAs($admin)->put(route('admin.applicants.update', $first), [
            'status' => 'partner_aktif',
            'agreement_version' => 'v1',
        ])->assertRedirect();
        $this->put(route('admin.applicants.update', $second), [
            'status' => 'partner_aktif',
            'agreement_version' => 'v1',
        ])->assertRedirect();
        $this->put(route('admin.applicants.update', $first), [
            'status' => 'masa_percobaan',
            'agreement_version' => 'v1',
        ])->assertRedirect();
        $this->put(route('admin.applicants.update', $first), [
            'status' => 'partner_aktif',
            'agreement_version' => 'v1',
        ])->assertRedirect();

        $this->assertSame('TSI-001', $first->fresh()->partner_code);
        $this->assertSame('TSI-002', $second->fresh()->partner_code);
    }

    public function test_admin_output_escapes_applicant_html_and_csv_formulas(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $applicant = Applicant::create(array_replace($this->validApplication(), [
            'full_name' => '<script>alert(1)</script>',
            'status' => 'baru',
        ]));
        Applicant::create(array_replace($this->validApplication(), [
            'full_name' => '=HYPERLINK("https://example.com","click")',
            'whatsapp' => '6281234567899',
            'status' => 'baru',
        ]));

        $this->actingAs($admin)
            ->get(route('admin.applicants.show', $applicant))
            ->assertOk()
            ->assertSee($applicant->full_name)
            ->assertDontSee($applicant->full_name, false);

        $response = $this->get(route('admin.applicants.export'));

        $response->assertOk();
        $this->assertStringContainsString("'=HYPERLINK", $response->streamedContent());
    }

    private function validApplication(array $overrides = []): array
    {
        return array_replace([
            'full_name' => 'Calon Partner',
            'whatsapp' => '081234567890',
            'domicile' => 'Jakarta Selatan',
            'birth_date' => now()->subYears(25)->toDateString(),
            'current_activity' => 'freelance',
            'experience' => 'Pernah membantu usaha keluarga.',
            'motivation' => 'Saya tertarik membantu bisnis bertumbuh.',
            'social_link' => 'https://www.instagram.com/calonsales',
            'agreed_commission' => '1',
        ], $overrides);
    }
}
