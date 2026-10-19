<x-guest-layout>
    <h2 class="text-2xl font-bold text-[#172554]">Mot de passe oublié</h2>
    <p class="mt-1 text-sm text-gray-500">
        Indiquez votre adresse email : nous vous enverrons un lien pour choisir un nouveau mot de passe.
    </p>

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

    <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700">Adresse email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   placeholder="exemple@email.com"
                   class="mt-1 block w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 placeholder-gray-400 focus:border-[#D4A72C] focus:outline-none focus:ring-2 focus:ring-[#D4A72C]/40">
        </div>

        <button type="submit"
                class="w-full rounded-xl bg-[#D4A72C] py-3 text-base font-bold text-[#172554] shadow-lg shadow-[#D4A72C]/30 transition hover:bg-[#c9991f]">
            Envoyer le lien
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500">
        <a href="{{ url('/connexion') }}" class="font-semibold text-[#172554] underline hover:text-[#D4A72C]">
            &larr; Retour à la connexion
        </a>
    </p>
</x-guest-layout>
