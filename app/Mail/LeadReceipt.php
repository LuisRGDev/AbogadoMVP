<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class LeadReceipt extends Mailable
{
    public function __construct(public Contact $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Recibimos su solicitud — '.site()->name());
    }

    public function content(): Content
    {
        return new Content(view: 'mail.lead-receipt');
    }
}
