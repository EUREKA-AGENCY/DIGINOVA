<?php

namespace App\Mail;

use App\Models\QuoteRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteRequestReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public QuoteRequest $quote)
    {
    }

    public function build(): self
    {
        return $this->subject('Nouvelle demande de devis')
            ->markdown('emails.quote_request_md', [
                'quote' => $this->quote,
            ]);
    }
}
