<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactNotification;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(ContactRequest $request)
    {
        $validated = $request->validated();

        // Save contact
        \App\Models\Contact::create($validated);

        // Send email notification to both recipients (queued)
        foreach (['shabianarsyl@gmail.com', 'thesolutionid@gmail.com'] as $recipient) {
            Mail::to($recipient)->queue(new ContactNotification(
                $validated['name'],
                $validated['email'],
                $validated['subject'] ?? null,
                $validated['message']
            ));
        }

        return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
    }
}
