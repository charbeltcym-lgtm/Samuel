<x-guest-layout>
    <h2 class="text-2xl font-bold text-[#172554]">Connexion</h2>
    <p class="mt-1 text-sm text-gray-500">Accédez à votre espace administrateur.</p>

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

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">Mot de passe</label>
            <input id="password" name="password" type="password" required autocomplete="current-password"
                   class="mt-2 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm text-gray-900 shadow-sm focus:border-[#172554] focus:outline-none focus:ring-2 focus:ring-[#172554]/10">
        </div>

        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2 text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-[#172554] focus:ring-[#172554]">
                <span>Se souvenir de moi</span>
            </label>
            <a href="{{ route('password.request') }}" class="font-semibold text-[#172554] hover:text-[#D4A72C]">Mot de passe oublié ?</a>
        </div>

        <button type="submit" class="w-full rounded-xl bg-[#172554] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#1e3a8a]">Se connecter</button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-500">
        <p>Première connexion ? <a href="{{ route('premiere-connexion') }}" class="font-semibold text-[#172554] hover:text-[#D4A72C]">Définir votre mot de passe</a></p>
    </div>
</x-guest-layout>
