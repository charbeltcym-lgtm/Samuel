@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#172554] to-[#2c3e50] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-[#172554]">Connexion à votre compte</h2>
            @auth
                <a href="{{ route('logout') }}" class="text-[#6B7280] hover:text-gray-300 transition-colors" onclick="event.preventDefault(); document.querySelector('form').submit()">Déconnexion</a>
            @else
                <a href="/inscription" class="text-[#D4A72C] font-medium">Créer un compte</a>
            @endauth
        </div>
        
        @if ($errors->any())
            <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label for="email" class="block text-sm font-medium text-[#4A5568] mb-2">Email</label>
                <input type="email" name="email" id="email" required class="w-full rounded-xl border border-[#CBD5E1] px-4 py-3 focus:outline-none focus:border-[#172554] transition-colors">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-[#4A5568] mb-2">Mot de passe</label>
                <input type="password" name="password" id="password" required class="w-full rounded-xl border border-[#CBD5E1] px-4 py-3 focus:outline-none focus:border-[#172554] transition-colors">
            </div>
            
            <div class="flex flex-wrap items-center justify-between gap-y-2 text-sm">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" name="remember" id="remember" class="rounded border-gray-300 w-4 h-4 focus:ring-2 focus:ring-[#172554]">
                    Se souvenir de moi
                </label>
                <a href="{{ route('password.request') }}" class="text-[#6B7280] hover:text-[#172554] transition-colors">Mot de passe oublié ?</a>
            </div>
            
            <button type="submit" class="w-full rounded-xl bg-gradient-to-r from-[#D4A72C] to-[#F59E0B] px-6 py-3 text-lg font-medium text-[#172554] hover:opacity-90 transition-opacity">
                Se connecter
            </button>
        </form>
    </div>
</div>