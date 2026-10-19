<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Forcer HTTPS en production sur Render.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Conserver la configuration Brevo.
        Mail::extend('brevo', function (array $config) {
            return Transport::fromDsn($config['dsn']);
        });
    }
}
