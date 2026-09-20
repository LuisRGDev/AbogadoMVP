<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class NewLeadReceived extends Mailable
{
    public function __construct(public Contact $contact) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->contact->email, $this->contact->name)],
            subject: sprintf('Nueva %s web: %s', $this->contact->type->value === 'appointment' ? 'solicitud de cita' : 'consulta', $this->contact->name),
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.lead-received');
    }
}
