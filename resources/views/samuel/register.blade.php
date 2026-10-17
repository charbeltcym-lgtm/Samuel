<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #F3F4F6; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <header style="background: #172554; padding: 15px 0;">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="Samuel App" style="width: 45px; height: 45px; border-radius: 10px; object-fit: cover;">
                <span style="color: white; font-size: 20px; font-weight: bold;">Samuel App</span>
            </div>
            <nav style="display: flex; gap: 20px; align-items: center;">
                <a href="/" style="color: #ccc; text-decoration: none;">Accueil</a>
                <a href="/a-propos" style="color: #ccc; text-decoration: none;">À propos</a>
                <a href="/chapitres" style="color: #ccc; text-decoration: none;">Chapitres</a>
                <a href="/connexion" style="color: #ccc; text-decoration: none;">Connexion</a>
                <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- Formulaire d'inscription -->
    <section style="max-width: 450px; margin: 50px auto; padding: 0 20px;">
        <div style="background: white; border-radius: 16px; padding: 35px; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border: 1px solid #e2e8f0;">
            <h1 style="color: #172554; font-size: 26px; text-align: center; margin-bottom: 8px;">Créer un compte</h1>
            <p style="color: #6B7280; font-size: 14px; text-align: center; margin-bottom: 25px;">Rejoignez la communauté des Samuels</p>

            @if (session('success'))
                <div style="background: #dcfce7; color: #15803D; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background: #fee2e2; color: #DC2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px;">
                    <ul style="margin-left: 18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('registration.store') }}" method="POST">
                @csrf

                <div style="margin-bottom: 18px;">
                    <label for="name" style="display: block; color: #374151; font-size: 14px; font-weight: bold; margin-bottom: 6px;">Nom complet</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        style="width: 100%; padding: 12px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 15px;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="email" style="display: block; color: #374151; font-size: 14px; font-weight: bold; margin-bottom: 6px;">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        style="width: 100%; padding: 12px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 15px;">
                </div>

                <div style="margin-bottom: 18px;">
                    <label for="password" style="display: block; color: #374151; font-size: 14px; font-weight: bold; margin-bottom: 6px;">Mot de passe</label>
                    <input type="password" name="password" id="password" required
                        style="width: 100%; padding: 12px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 15px;">
                </div>

                <div style="margin-bottom: 25px;">
                    <label for="password_confirmation" style="display: block; color: #374151; font-size: 14px; font-weight: bold; margin-bottom: 6px;">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        style="width: 100%; padding: 12px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 15px;">
                </div>

                <button type="submit" style="width: 100%; background: #D4A72C; color: #172554; border: none; padding: 14px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer;">
                    S'inscrire
                </button>

                <p style="text-align: center; color: #6B7280; font-size: 14px; margin-top: 20px;">
                    Déjà un compte ? <a href="/connexion" style="color: #D4A72C; font-weight: bold; text-decoration: none;">Se connecter</a>
                </p>
            </form>
        </div>
    </section>

    <!-- Pied de page -->
    <footer style="background: #0f1a30; color: #ccc; text-align: center; padding: 20px; margin-top: 50px;">
        <p>&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
