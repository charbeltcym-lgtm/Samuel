@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-md mx-auto px-4">
        <div class="bg-white p-8 rounded-xl shadow-lg max-w-lg mx-auto border border-[#e2e8f0]">
            <h2 class="text-2xl font-bold text-center text-[#172554] mb-6">Activation de votre compte</h2>
            
            <p class="text-base text-center text-[#4a4a4a] mb-8">
                Entrez votre code d'activation reçu par email pour activer votre compte.
            </p>
            
            <form class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#374151] mb-2">Code d'activation</label>
                    <input type="text" name="activation_code" required 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                           placeholder="Votre code d'activation" maxlength="10">
                </div>
                
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#15803D] to-[#1e4d32] text-white font-medium rounded-xl hover:opacity-90 transition-opacity">
                    Activer mon compte
                </button>
            </form>
            
            <p class="mt-6 text-center text-sm text-[#6b7280]">
                Vous avez déjà un compte ? 
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">Connectez-vous ici</a>
            </p>
        </div>
    </div>
</div>
@endsection