<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:'.implode(',', array_keys(Applicant::STATUSES))],
            'domicile' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $applicants = $this->filteredApplicants($filters)->latest()->paginate(15)->withQueryString();

        return view('admin.applicants.index', [
            'applicants' => $applicants,
            'filters' => $filters,
            'totalApplicants' => Applicant::count(),
            'newThisWeek' => Applicant::where('created_at', '>=', now()->subDays(7))->count(),
            'statusCounts' => Applicant::query()
                ->select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->pluck('total', 'status'),
        ]);
    }

    public function show(Applicant $applicant)
    {
        return view('admin.applicants.show', [
            'applicant' => $applicant->load('statusLogs'),
        ]);
    }

    public function update(Request $request, Applicant $applicant)
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Applicant::STATUSES))],
            'agreement_version' => ['nullable', 'string', 'max:20', 'required_if:status,perjanjian_ditandatangani'],
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        DB::transaction(function () use ($applicant, $data) {
            $applicant = Applicant::query()->lockForUpdate()->findOrFail($applicant->id);
            $previousStatus = $applicant->status;

            if (in_array($previousStatus, Applicant::POST_AGREEMENT_STATUSES, true)
                && in_array($data['status'], Applicant::PRE_AGREEMENT_STATUSES, true)) {
                throw ValidationException::withMessages([
                    'status' => 'Pelamar yang sudah menandatangani perjanjian tidak bisa dikembalikan ke tahap seleksi. Gunakan status Ditolak untuk menonaktifkan.',
                ]);
            }

            if (in_array($data['status'], ['masa_percobaan', 'partner_aktif'], true)
                && (! $applicant->agreement_signed_at || ! $applicant->agreement_version)) {
                throw ValidationException::withMessages([
                    'status' => 'Perjanjian harus ditandatangani sebelum masa percobaan atau menjadi partner aktif.',
                ]);
            }

            $applicant->admin_notes = $data['admin_notes'] ?? null;
            $applicant->status = $data['status'];

            if ($applicant->status === 'perjanjian_ditandatangani') {
                $agreementChanged = $applicant->agreement_version !== $data['agreement_version']
                    || ! $applicant->agreement_signed_at;
                $applicant->agreement_version = $data['agreement_version'];

                if ($agreementChanged) {
                    $applicant->agreement_signed_at = now();
                }
            }

            if ($applicant->status === 'partner_aktif' && ! $applicant->partner_code) {
                $sequence = DB::table('partner_code_sequences')
                    ->where('id', 1)
                    ->lockForUpdate()
                    ->first();

                if (! $sequence) {
                    throw new \RuntimeException('Partner code sequence has not been initialized.');
                }

                $nextNumber = $sequence->next_number + 1;
                DB::table('partner_code_sequences')
                    ->where('id', 1)
                    ->update(['next_number' => $nextNumber]);
                $applicant->partner_code = 'TSI-'.str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);
            }

            $applicant->save();

            if ($previousStatus !== $applicant->status) {
                $applicant->statusLogs()->create([
                    'from_status' => $previousStatus,
                    'to_status' => $applicant->status,
                ]);
            }
        });

        return back()->with('success', 'Data pelamar berhasil diperbarui.');
    }

    public function destroy(Applicant $applicant)
    {
        $applicant->delete();

        return redirect()->route('admin.applicants.index')->with('success', 'Pelamar disembunyikan.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:'.implode(',', array_keys(Applicant::STATUSES))],
            'domicile' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);
        $filename = 'pelamar-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($filters) {
            $output = fopen('php://output', 'w');
            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, ['Nama', 'WhatsApp', 'Domisili', 'Tanggal lahir', 'Kegiatan', 'Pengalaman', 'Alasan', 'Media sosial', 'Status', 'Kode partner', 'Versi perjanjian', 'Waktu tanda tangan', 'Tanggal daftar', 'Catatan admin']);

            $this->filteredApplicants($filters)->latest()->chunk(500, function ($applicants) use ($output) {
                foreach ($applicants as $applicant) {
                    fputcsv($output, [
                        $this->csvSafe($applicant->full_name),
                        $applicant->whatsapp,
                        $this->csvSafe($applicant->domicile),
                        $applicant->birth_date?->format('Y-m-d'),
                        Applicant::ACTIVITIES[$applicant->current_activity],
                        $this->csvSafe($applicant->experience),
                        $this->csvSafe($applicant->motivation),
                        $this->csvSafe($applicant->social_link),
                        Applicant::STATUSES[$applicant->status],
                        $applicant->partner_code,
                        $this->csvSafe($applicant->agreement_version),
                        $applicant->agreement_signed_at?->format('Y-m-d H:i:s'),
                        $applicant->created_at->format('Y-m-d H:i:s'),
                        $this->csvSafe($applicant->admin_notes),
                    ]);
                }
            });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function filteredApplicants(array $filters): Builder
    {
        return Applicant::query()
            ->when($filters['q'] ?? null, fn (Builder $query, string $search) => $query
                ->where(fn (Builder $query) => $query
                    ->where('full_name', 'like', "%{$search}%")
                    ->orWhere('whatsapp', 'like', "%{$search}%")))
            ->when($filters['status'] ?? null, fn (Builder $query, string $status) => $query->where('status', $status))
            ->when($filters['domicile'] ?? null, fn (Builder $query, string $domicile) => $query->where('domicile', 'like', "%{$domicile}%"))
            ->when($filters['from'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '>=', $date))
            ->when($filters['to'] ?? null, fn (Builder $query, string $date) => $query->whereDate('created_at', '<=', $date));
    }

    private function csvSafe(?string $value): string
    {
        return preg_match('/^[\s]*[=+\-@]/', $value ?? '') ? "'".$value : ($value ?? '');
    }
}
