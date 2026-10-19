@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#f8fafc]" x-data="{ open: false }">

    <!-- Overlay (mobile) -->
    <div x-show="open" x-transition.opacity @click="open = false"
         class="lg:hidden fixed inset-0 bg-black/50 z-40"></div>

    <!-- Barre latérale -->
    <aside class="fixed left-0 top-0 h-full w-64 bg-[#172554] flex flex-col border-r border-[#374151] shadow-2xl z-50 transition-transform duration-300 -translate-x-full lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex h-16 items-center justify-between px-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#D4A72C] to-[#F59E0B] flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#172554]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path class="icon" d="M17 9V5a2 2 0 0-1 2-2h1a2 2 0 0 1 2 2v4m0 4V5a2 2 0 0-1 2-2h1a2 2 0 0 1 2 2v4m-6-6h6m5-12l3 8l7 2l-3 8l-7-2L21 7l-7-2z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-[#F9FAFC]">Samuel App</h2>
            </div>
            <button @click="open = false" class="lg:hidden p-2 rounded-md hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-6 py-4 space-y-1 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 6l2 2m-2-2v-4a1 1 0 011-1h4a1 1 0 011 1v4z" />
                </svg>
                Vue d'ensemble
            </a>

            <a href="/inscriptions" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 6v6l4 2v6h-4v-2.5L17 15.5V6zM2 9l8 8 2-2M2 13l8 8 2-2" />
                </svg>
                Inscriptions
            </a>

            <a href="/samuels" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M3 3v2h20v2H3V3zm0 6h18v2H3v-2zm0 6h18v2H3v-2z" />
                </svg>
                Gestion des Samuels
            </a>

            <a href="/administrateurs" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M13 16l-4-4 4-4M21 21l-2-2-2-2M4 22l2-2 2-2" />
                </svg>
                Gestion des administrateurs
            </a>

            <a href="/chapitres" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 6v6l4 2v6h-4v-2.5L17 15.5V6zM2 9l8 8 2-2M2 13l8 8 2-2" />
                </svg>
                Chapitres
            </a>

            <a href="/presences" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Présences
            </a>

            <a href="/historique" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 15h.01M8 12h.01M16 12h.01M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                Historique et sécurité
            </a>

            <a href="/parametres" class="flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-[#CBD5E1] hover:text-white hover:bg-[#2D3748] transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path class="icon" d="M12 15h.01M8 12h.01M16 12h.01M12 8a4 4 0 100 8 4 4 0 000-8z" />
                </svg>
                Paramètres du compte
            </a>
        </nav>

        <div class="px-6 py-4 border-t border-[#374151] text-sm text-[#6B7280]">
            Connecté en tant que Super administrateur
        </div>
    </aside>

    <!-- Zone principale -->
    <main class="p-4 sm:p-6 lg:p-8 lg:ml-64">
        <button @click="open = true" class="lg:hidden mb-4 inline-flex items-center gap-2 rounded-xl bg-[#172554] px-4 py-2 text-sm font-medium text-white shadow">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path class="icon" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            Menu
        </button>
        @yield('content')
    </main>
</div>
@endsection
