<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Connexion' }} — {{ config('app.name', 'Samuel App') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-[#172554] via-[#1c2b4a] to-[#0d1733] px-4 py-10">

            <div class="w-full max-w-md">
                <!-- En-tete / marque -->
                <div class="flex flex-col items-center mb-6">
                    <div class="rounded-full border-2 border-[#D4A72C] p-1 shadow-lg shadow-[#D4A72C]/30">
                        <img src="{{ asset('images/logo.png') }}" alt="Samuel" class="h-24 w-24 rounded-full object-cover">
                    </div>
                    <h1 class="mt-4 text-3xl font-extrabold tracking-[0.35em] text-[#D4A72C]">SAMUEL</h1>
                    <p class="mt-1 text-xs tracking-[0.3em] text-white/70">FOI • SERVICE • ENGAGEMENT</p>
                </div>

                <!-- Carte -->
                <div class="rounded-2xl bg-white p-8 shadow-2xl">
                    {{ $slot }}
                </div>

                <!-- Pied de page -->
                <p class="mt-6 text-center text-xs text-white/50">
                    &copy; {{ date('Y') }} Samuel App — Tous droits réservés
                </p>
            </div>
        </div>
    </body>
</html>
