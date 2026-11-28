<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/** Invoice sent to a client, with the PDF attached. Sent right away (not queued). */
class InvoiceMail extends Mailable
{
    /** @param array<string, string> $settings invoice settings (company name, email, ...) */
    public function __construct(
        public Invoice $invoice,
        private string $pdf,
        public array $settings,
        public ?string $personalMessage = null,
    ) {}

    public function envelope(): Envelope
    {
        $replyTo = filter_var($this->settings['invoice_company_email'] ?? '', FILTER_VALIDATE_EMAIL);

        return new Envelope(
            subject: "Invoice {$this->invoice->invoice_number} — {$this->settings['invoice_company_name']}",
            replyTo: $replyTo ? [new Address($replyTo, $this->settings['invoice_company_name'])] : [],
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.invoice', with: [
            'invoice' => $this->invoice,
            'company' => $this->settings['invoice_company_name'],
            'paymentInfo' => $this->settings['invoice_payment_info'] ?? '',
            'personalMessage' => $this->personalMessage,
            'link' => $this->invoice->shareUrl(),
        ]);
    }

    public function attachments(): array
    {
        $filename = 'invoice-' . str_replace('/', '-', $this->invoice->invoice_number) . '.pdf';

        return [Attachment::fromData(fn () => $this->pdf, $filename)->withMime('application/pdf')];
    }
}
