<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicantRequest;
use App\Models\Applicant;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    public function index()
    {
        return view('jobs.index');
    }

    public function store(StoreApplicantRequest $request)
    {
        if ($request->filled('website')) {
            return redirect()->route('jobs.thanks');
        }

        try {
            DB::transaction(function () use ($request) {
                $data = $request->safe()->except('website');
                $hidden = Applicant::onlyTrashed()
                    ->where('whatsapp', $data['whatsapp'])
                    ->lockForUpdate()
                    ->first();

                if (! $hidden) {
                    $applicant = Applicant::create($data + ['status' => 'baru']);
                    $applicant->statusLogs()->create(['from_status' => null, 'to_status' => 'baru']);

                    return;
                }

                // Re-application by a previously hidden applicant: reuse the record so the
                // history (status log, partner code, agreement) stays attached to one person.
                $previousStatus = $hidden->status;
                $hidden->restore();
                $hidden->fill($data + ['status' => 'baru']);
                $hidden->admin_notes = trim(($hidden->admin_notes ? $hidden->admin_notes."\n\n" : '')
                    .'['.now()->format('d/m/Y H:i').'] Mendaftar ulang setelah sebelumnya disembunyikan.');
                $hidden->save();
                $hidden->statusLogs()->create(['from_status' => $previousStatus, 'to_status' => 'baru']);
            });
        } catch (UniqueConstraintViolationException) {
            return back()->withInput()->withErrors([
                'whatsapp' => 'Nomor WhatsApp ini sudah terdaftar.',
            ]);
        }

        return redirect()->route('jobs.thanks');
    }

    public function thanks()
    {
        return view('jobs.thanks');
    }
}
