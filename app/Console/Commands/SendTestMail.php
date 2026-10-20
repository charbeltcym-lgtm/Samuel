<?php

namespace App\Console\Commands;

use App\Mail\ActivationCodeMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    protected $signature = 'mail:test {email : Adresse email du destinataire}';

    protected $description = "Envoie un email de test (meme modele que le code d'activation)";

    public function handle(): int
    {
        $email = (string) $this->argument('email');
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $motDePasse = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(4)).random_int(100000, 999999);

        $this->info('Mailer actif : '.config('mail.default'));
        $this->info('Expediteur    : '.config('mail.from.address'));

        try {
            Mail::to($email)->send(new ActivationCodeMail('Candidat de test', $code, $motDePasse));
        } catch (\Throwable $e) {
            $this->error("Echec de l'envoi : ".$e->getMessage());

            return self::FAILURE;
        }

        $this->info("Email de test envoye a {$email} (code {$code}, mot de passe {$motDePasse}).");

        return self::SUCCESS;
    }
}
