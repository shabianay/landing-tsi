<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\PartnerDeal;
use App\Models\PartnerLead;
use App\Models\PartnerMonthlyBonus;
use App\Models\PartnerPayment;
use Carbon\Carbon;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PartnerSalesController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'month' => ['nullable', 'date_format:Y-m'],
        ]);
        $month = Carbon::createFromFormat('!Y-m', $filters['month'] ?? now()->format('Y-m'));
        $monthStart = $month->copy()->startOfMonth();

        return view('admin.partner-sales.index', [
            'leads' => PartnerLead::with('partner')->withCount('deals')->latest('reported_at')->paginate(20)->withQueryString(),
            'month' => $month,
            'monthlyBonuses' => PartnerMonthlyBonus::with('partner')
                ->whereDate('month', $monthStart->toDateString())
                ->orderByDesc('amount')
                ->get(),
            'leadCount' => PartnerLead::count(),
            'paymentsReceived' => PartnerPayment::whereBetween('received_at', [
                $monthStart,
                $monthStart->copy()->endOfMonth(),
            ])->sum('amount'),
            'commissionOutstanding' => PartnerPayment::whereNull('commission_paid_at')->sum('commission_amount'),
            'commissionPaid' => PartnerPayment::whereNotNull('commission_paid_at')->sum('commission_amount'),
        ]);
    }

    public function create()
    {
        $partners = Applicant::query()
            ->where('status', 'partner_aktif')
            ->whereNotNull('partner_code')
            ->orderBy('partner_code')
            ->get(['id', 'full_name', 'partner_code']);

        return view('admin.partner-sales.create', compact('partners'));
    }

    public function store(Request $request)
    {
        $whatsapp = $request->input('whatsapp');
        if (is_string($whatsapp)) {
            $digits = preg_replace('/\D+/', '', $whatsapp);
            if (str_starts_with($digits, '08')) {
                $digits = '62'.substr($digits, 1);
            }
            $request->merge(['whatsapp' => $digits]);
        }

        $data = $request->validate([
            'applicant_id' => [
                'required',
                Rule::exists('applicants', 'id')->where(fn ($query) => $query
                    ->where('status', 'partner_aktif')
                    ->whereNotNull('partner_code')),
            ],
            'full_name' => ['required', 'string', 'max:150'],
            'whatsapp' => ['bail', 'required', 'string', 'regex:/^628[0-9]{8,11}$/', Rule::unique('partner_leads', 'whatsapp')],
            'service' => ['required', 'string', 'max:150'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'reported_at' => ['required', 'date', 'before_or_equal:now'],
        ], [
            'whatsapp.regex' => 'Masukkan nomor WhatsApp Indonesia yang diawali 08 atau 628.',
            'whatsapp.unique' => 'Nomor klien ini sudah tercatat. Periksa daftar pencatatan klien.',
        ]);

        try {
            $lead = PartnerLead::create($data);
        } catch (UniqueConstraintViolationException) {
            return back()->withInput()->withErrors([
                'whatsapp' => 'Nomor klien ini sudah tercatat. Periksa daftar pencatatan klien.',
            ]);
        }

        return redirect()->route('admin.partner-sales.show', $lead)->with('success', 'Calon klien berhasil dicatat.');
    }

    public function show(PartnerLead $lead)
    {
        $lead->load([
            'partner',
            'deals.payments' => fn ($query) => $query->orderBy('received_at'),
        ]);

        return view('admin.partner-sales.show', compact('lead'));
    }

    public function storeDeal(Request $request, PartnerLead $lead)
    {
        $data = $request->validate([
            'description' => ['required', 'string', 'max:150'],
            'deal_amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'discount_amount' => ['required', 'integer', 'min:0', 'lte:deal_amount', 'max:999999999999'],
        ]);

        $finalAmount = $data['deal_amount'] - $data['discount_amount'];
        if ($finalAmount < 1) {
            throw ValidationException::withMessages([
                'discount_amount' => 'Diskon harus lebih kecil dari harga deal.',
            ]);
        }

        $lead->deals()->create($data + ['final_amount' => $finalAmount]);

        return back()->with('success', 'Deal berhasil dicatat.');
    }

    public function storePayment(Request $request, PartnerDeal $deal)
    {
        $data = $request->validate([
            'payment_type' => ['required', Rule::in(['dp', 'cicilan', 'pelunasan'])],
            'amount' => ['required', 'integer', 'min:1', 'max:999999999999'],
            'received_at' => ['required', 'date', 'before_or_equal:now'],
        ]);
        $data['amount'] = (int) $data['amount'];

        DB::transaction(function () use ($deal, $data) {
            $deal = PartnerDeal::query()->lockForUpdate()->findOrFail($deal->id);
            $payments = $deal->payments()->lockForUpdate();
            $amountPaid = (clone $payments)->sum('amount');
            $remaining = $deal->final_amount - $amountPaid;

            if ($data['amount'] > $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Pembayaran melebihi sisa deal sebesar Rp'.number_format(max($remaining, 0), 0, ',', '.').'.',
                ]);
            }

            if ($data['payment_type'] === 'dp' && (clone $payments)->where('payment_type', 'dp')->exists()) {
                throw ValidationException::withMessages([
                    'payment_type' => 'DP sudah tercatat untuk deal ini. Gunakan Cicilan untuk pembayaran berikutnya.',
                ]);
            }

            if ($data['payment_type'] === 'pelunasan' && $data['amount'] !== $remaining) {
                throw ValidationException::withMessages([
                    'amount' => 'Nilai pelunasan harus sama dengan sisa deal sebesar Rp'.number_format(max($remaining, 0), 0, ',', '.').'.',
                ]);
            }

            $payment = $deal->payments()->create([
                'payment_type' => $data['payment_type'],
                'amount' => $data['amount'],
                'commission_amount' => intdiv($data['amount'] + 5, 10),
                'received_at' => Carbon::parse($data['received_at']),
            ]);

            if ($payment->payment_type === 'dp') {
                $lead = $deal->lead()->firstOrFail();
                $this->syncMonthlyBonus($lead->applicant_id, $payment->received_at->copy()->startOfMonth());
            }
        });

        return back()->with('success', 'Pembayaran dan komisi 10% berhasil dicatat.');
    }

    public function destroyDeal(PartnerDeal $deal)
    {
        DB::transaction(function () use ($deal) {
            $deal = PartnerDeal::query()->lockForUpdate()->findOrFail($deal->id);

            if ($deal->payments()->exists()) {
                throw ValidationException::withMessages([
                    'deal' => 'Deal yang sudah memiliki pembayaran tidak bisa dihapus. Hapus pembayarannya terlebih dahulu.',
                ]);
            }

            $deal->delete();
        });

        return back()->with('success', 'Deal berhasil dihapus.');
    }

    public function destroyPayment(PartnerPayment $payment)
    {
        DB::transaction(function () use ($payment) {
            $payment = PartnerPayment::query()->lockForUpdate()->findOrFail($payment->id);

            if ($payment->commission_paid_at) {
                throw ValidationException::withMessages([
                    'payment' => 'Komisi pembayaran ini sudah dibayarkan, sehingga pembayaran tidak bisa dihapus.',
                ]);
            }

            $applicantId = null;
            $monthStart = null;

            if ($payment->payment_type === 'dp') {
                $applicantId = $payment->deal()->firstOrFail()->lead()->firstOrFail()->applicant_id;
                $monthStart = $payment->received_at->copy()->startOfMonth();

                $bonusAlreadyPaid = PartnerMonthlyBonus::query()
                    ->where('applicant_id', $applicantId)
                    ->whereDate('month', $monthStart->toDateString())
                    ->where('paid_amount', '>', 0)
                    ->exists();

                if ($bonusAlreadyPaid) {
                    throw ValidationException::withMessages([
                        'payment' => 'Bonus bulanan partner untuk bulan DP ini sudah dibayarkan, sehingga DP tidak bisa dihapus.',
                    ]);
                }
            }

            $payment->delete();

            if ($applicantId) {
                $this->syncMonthlyBonus($applicantId, $monthStart);
            }
        });

        return back()->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function markCommissionPaid(PartnerPayment $payment)
    {
        $payment->update(['commission_paid_at' => $payment->commission_paid_at ?? now()]);

        return back()->with('success', 'Komisi pembayaran ditandai sudah dibayarkan.');
    }

    public function markBonusPaid(PartnerMonthlyBonus $bonus)
    {
        if ($bonus->amount <= $bonus->paid_amount) {
            throw ValidationException::withMessages([
                'bonus' => 'Bonus belum memenuhi syarat untuk dibayarkan.',
            ]);
        }

        $bonus->update([
            'paid_amount' => $bonus->amount,
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Bonus bulanan ditandai sudah dibayarkan.');
    }

    private function syncMonthlyBonus(int $applicantId, Carbon $monthStart): void
    {
        Applicant::query()->lockForUpdate()->findOrFail($applicantId);
        $monthEnd = $monthStart->copy()->endOfMonth();
        $eligibleClients = PartnerLead::query()
            ->where('applicant_id', $applicantId)
            ->whereHas('deals', fn ($query) => $query
                ->where('final_amount', '>=', 2_000_000)
                ->whereHas('payments', fn ($query) => $query
                    ->where('payment_type', 'dp')
                    ->whereBetween('received_at', [$monthStart, $monthEnd])))
            ->count();

        $bonus = PartnerMonthlyBonus::query()
            ->where('applicant_id', $applicantId)
            ->whereDate('month', $monthStart->toDateString())
            ->first();
        $paidAmount = min((int) ($bonus?->paid_amount ?? 0), intdiv($eligibleClients, 3) * 500_000);
        $amount = intdiv($eligibleClients, 3) * 500_000;
        $now = now();
        DB::table('partner_monthly_bonuses')->upsert(
            [[
                'applicant_id' => $applicantId,
                'month' => $monthStart->toDateString(),
                'eligible_client_count' => $eligibleClients,
                'amount' => $amount,
                'paid_amount' => $paidAmount,
                'paid_at' => $paidAmount >= $amount && $bonus?->paid_at
                    ? $bonus->paid_at
                    : null,
                'created_at' => $now,
                'updated_at' => $now,
            ]],
            ['applicant_id', 'month'],
            ['eligible_client_count', 'amount', 'paid_amount', 'paid_at', 'updated_at'],
        );
    }
}
