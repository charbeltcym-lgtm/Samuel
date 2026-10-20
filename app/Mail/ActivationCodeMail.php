<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ActivationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $nom,
        public string $code,
        public string $motDePasse = '',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue chez Samuel App — Voici vos identifiants d\'accès',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.activation-code',
        );
    }
}
