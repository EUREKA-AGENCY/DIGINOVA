<?php

namespace App\Mail;

use App\Models\ContactRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactRequest $contact)
    {
    }

    public function build(): self
    {
        return $this->subject('Nouvelle demande de contact')
            ->markdown('emails.contact_request_md', [
                'contact' => $this->contact,
            ]);
    }
}
