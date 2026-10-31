<x-guest-layout>
    <h2 class="text-2xl font-bold text-[#172554]">Première connexion</h2>
    <p class="mt-1 text-sm text-gray-500">Entrez votre email, le code d'activation reçu par email, puis choisissez votre mot de passe.</p>

    @if (session('status'))
        <div class="mt-4 rounded-xl border border-green-200 bg-green-50 px-4 py-2.5 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mt-4 rounded-xl border border-red-200 bg-red-50 px-4 py-2.5 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('premiere-connexion') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <div>
            <label for="code" class="block text-sm font-semibold text-gray-700">Code d'activation</label>
            <input id="code" name="code" type="text" value="{{ old('code') }}" required maxlength="10" inputmode="numeric" pattern="[0-9]*"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-center text-lg font-semibold tracking-[0.6em] text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">Nouveau mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="new-password"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Confirmer le mot de passe</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <button type="submit" class="w-full rounded-xl bg-[#172554] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1e3a8a]">Activer mon compte et me connecter</button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ route('login') }}" class="font-semibold text-[#172554] hover:text-[#D4A72C]">Retour à la connexion</a>
    </div>
</x-guest-layout>
