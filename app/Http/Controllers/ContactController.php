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
        // Bots fill the hidden honeypot field; pretend success without storing anything.
        if ($request->filled('website')) {
            return redirect()->route('contact')->with('success', 'Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');
        }

        $validated = $request->safe()->except('website');

        // Save contact
        \App\Models\Contact::create($validated);

        // Send email notification to both recipients (queued)
        foreach (config('services.contact.recipients', []) as $recipient) {
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
