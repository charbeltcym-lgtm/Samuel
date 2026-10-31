<?php

use App\Http\Middleware\ApiRole;
use App\Http\Middleware\ApiTokenAuth;
use App\Http\Middleware\EnsureAdmin;
use App\Http\Middleware\EnsureSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Derriere le proxy de Render : faire confiance aux en-tetes X-Forwarded-*
        // pour detecter le HTTPS et generer les bonnes URLs.
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'api.token' => ApiTokenAuth::class,
            'api.role' => ApiRole::class,
            'web.super' => EnsureSuperAdmin::class,
            'web.admin' => EnsureAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
