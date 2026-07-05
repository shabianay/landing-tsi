<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $name,
        public string $email,
        public ?string $subjectText,
        public string $messageText
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Baru dari ' . $this->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.contact',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subjectText,
                'messageText' => $this->messageText,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
