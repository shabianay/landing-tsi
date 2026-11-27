<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\PartnerDeal;
use App\Models\PartnerLead;
use App\Models\PartnerMonthlyBonus;
use App\Models\PartnerPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerSalesTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_records_leads_deals_payments_commission_and_monthly_bonus(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();
        $this->actingAs($admin);
        $this->get(route('admin.partner-sales.create'))
            ->assertOk()
            ->assertSee($partner->partner_code);

        for ($number = 1; $number <= 3; $number++) {
            $lead = $this->recordLead($partner, $number);
            if ($number === 1) {
                $this->get(route('admin.partner-sales.show', $lead))
                    ->assertOk()
                    ->assertSee('Data calon klien')
                    ->assertSee('Catat deal baru');
            }
            $deal = $this->recordDeal($lead, 2_500_000, 500_000);
            $this->recordPayment($deal, 'dp', 500_000);
        }

        $bonus = PartnerMonthlyBonus::firstOrFail();
        $this->patch(route('admin.partner-sales.bonuses.paid', $bonus))->assertRedirect();
        $this->assertSame(500_000, $bonus->fresh()->paid_amount);

        for ($number = 4; $number <= 6; $number++) {
            $lead = $this->recordLead($partner, $number);
            $deal = $this->recordDeal($lead, 2_500_000, 500_000);
            $this->recordPayment($deal, 'dp', 500_000);
        }

        $belowThreshold = $this->recordLead($partner, 7);
        $smallDeal = $this->recordDeal($belowThreshold, 2_499_999, 500_001);
        $this->recordPayment($smallDeal, 'dp', 500_000);

        $this->assertSame(6, PartnerMonthlyBonus::firstOrFail()->eligible_client_count);
        $this->assertSame(1_000_000, PartnerMonthlyBonus::firstOrFail()->amount);
        $this->assertSame(500_000, PartnerMonthlyBonus::firstOrFail()->paid_amount);
        $this->assertNull(PartnerMonthlyBonus::firstOrFail()->paid_at);
        $this->assertSame(50_000, PartnerPayment::firstOrFail()->commission_amount);

        $this->actingAs($admin)
            ->get(route('admin.partner-sales.index', ['month' => now()->format('Y-m')]))
            ->assertOk()
            ->assertSee($partner->partner_code)
            ->assertSee('1.000.000');

        $bonus = PartnerMonthlyBonus::firstOrFail();
        $this->patch(route('admin.partner-sales.bonuses.paid', $bonus))->assertRedirect();
        $this->assertSame(1_000_000, $bonus->fresh()->paid_amount);
        $this->assertNotNull($bonus->fresh()->paid_at);

        $payment = PartnerPayment::firstOrFail();
        $this->patch(route('admin.partner-sales.commissions.paid', $payment))->assertRedirect();
        $this->assertNotNull($payment->fresh()->commission_paid_at);
    }

    public function test_lead_requires_active_partner_and_duplicate_client_phone_is_rejected(): void
    {
        $this->get(route('admin.partner-sales.index'))->assertRedirect(route('login'));
        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get(route('admin.partner-sales.index'))
            ->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();
        $inactivePartner = $this->createActivePartner('dihubungi', 'TSI-999');
        $this->actingAs($admin);

        $this->post(route('admin.partner-sales.store'), $this->leadData($partner, 1))
            ->assertRedirect();
        $this->post(route('admin.partner-sales.store'), $this->leadData($partner, 1))
            ->assertSessionHasErrors('whatsapp');
        $this->post(route('admin.partner-sales.store'), $this->leadData($inactivePartner, 2))
            ->assertSessionHasErrors('applicant_id');

        $this->assertDatabaseCount('partner_leads', 1);
        $this->assertDatabaseHas('partner_leads', ['whatsapp' => '6281234567801']);
    }

    public function test_payments_cannot_exceed_deal_and_dp_is_recorded_once(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $partner = $this->createActivePartner();
        $this->actingAs($admin);
        $lead = $this->recordLead($partner, 1);
        $deal = $this->recordDeal($lead, 2_000_000, 0);

        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData('dp', 500_000))
            ->assertRedirect();
        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData('dp', 500_000))
            ->assertSessionHasErrors('payment_type');
        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData('cicilan', 1_500_001))
            ->assertSessionHasErrors('amount');
        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData('pelunasan', 1_000_000))
            ->assertSessionHasErrors('amount');
        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData('pelunasan', '1500000'))
            ->assertRedirect();

        $this->assertDatabaseCount('partner_payments', 2);
        $this->assertSame(200_000, PartnerPayment::sum('commission_amount'));
    }

    private function createActivePartner(string $status = 'partner_aktif', string $code = 'TSI-001'): Applicant
    {
        $phoneEnding = substr($code, -2);
        $partner = Applicant::create([
            'full_name' => 'Partner Sales',
            'whatsapp' => '62812345678'.$phoneEnding,
            'domicile' => 'Jakarta',
            'birth_date' => now()->subYears(25)->toDateString(),
            'current_activity' => 'freelance',
            'motivation' => 'Saya tertarik membantu bisnis bertumbuh.',
            'social_link' => 'https://instagram.com/partner',
            'agreed_commission' => true,
            'status' => $status,
        ]);
        $partner->partner_code = $code;
        $partner->save();

        return $partner;
    }

    private function leadData(Applicant $partner, int $number): array
    {
        return [
            'applicant_id' => $partner->id,
            'full_name' => 'Calon Klien '.$number,
            'whatsapp' => '0812345678'.str_pad((string) $number, 2, '0', STR_PAD_LEFT),
            'service' => 'Website company profile',
            'reported_at' => now()->format('Y-m-d\TH:i'),
        ];
    }

    private function recordLead(Applicant $partner, int $number): PartnerLead
    {
        $this->post(route('admin.partner-sales.store'), $this->leadData($partner, $number))
            ->assertRedirect();

        return PartnerLead::latest('id')->firstOrFail();
    }

    private function recordDeal(PartnerLead $lead, int $dealAmount, int $discountAmount): PartnerDeal
    {
        $this->post(route('admin.partner-sales.deals.store', $lead), [
            'description' => 'Website company profile',
            'deal_amount' => $dealAmount,
            'discount_amount' => $discountAmount,
        ])->assertRedirect();

        return $lead->deals()->latest('id')->firstOrFail();
    }

    private function paymentData(string $type, int|string $amount): array
    {
        return [
            'payment_type' => $type,
            'amount' => $amount,
            'received_at' => now()->format('Y-m-d\TH:i'),
        ];
    }

    private function recordPayment(PartnerDeal $deal, string $type, int $amount): void
    {
        $this->post(route('admin.partner-sales.payments.store', $deal), $this->paymentData($type, $amount))
            ->assertRedirect();
    }
}
