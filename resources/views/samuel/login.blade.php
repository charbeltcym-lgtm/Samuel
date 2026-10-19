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
                   autocomplete="username" placeholder="exemple@email.com"
                   class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-[#D4A72C] focus:outline-none focus:ring-2 focus:ring-[#D4A72C]/40">
        </div>

        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700">Mot de passe</label>
            <div class="relative mt-1">
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       placeholder="••••••••"
                       class="block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-900 placeholder-gray-400 focus:border-[#D4A72C] focus:outline-none focus:ring-2 focus:ring-[#D4A72C]/40">
                <button type="button" onclick="togglePassword()" tabindex="-1"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 transition hover:text-gray-600"
                        aria-label="Afficher le mot de passe">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-2 pt-1">
            <label class="inline-flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-[#172554] focus:ring-[#D4A72C]">
                Se souvenir de moi
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-[#172554] underline hover:text-[#D4A72C]">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-[#D4A72C] py-3 text-base font-bold text-[#172554] shadow-lg shadow-[#D4A72C]/30 transition hover:bg-[#c9991f]">
            Se connecter
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        Pas encore de compte ?
        <a href="{{ url('/inscription') }}" class="font-semibold text-[#172554] underline hover:text-[#D4A72C]">S'inscrire</a>
    </p>

    <script>
        function togglePassword() {
            var input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-guest-layout>
