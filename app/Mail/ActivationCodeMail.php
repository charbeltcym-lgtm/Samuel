<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nom,
        public string $code,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue chez Samuel App — Voici votre code d\'activation',
            replyTo: [
                new Address('charbeltcym@gmail.com', 'Administration Samuel App'),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activation-code',
        );
    }
}
