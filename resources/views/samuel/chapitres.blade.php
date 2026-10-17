<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapitres - Samuel App</title>
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
                <a href="/chapitres" style="color: #D4A72C; text-decoration: none;">Chapitres</a>
                <a href="/connexion" style="color: #ccc; text-decoration: none;">Connexion</a>
                <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- Titre -->
    <section style="background: linear-gradient(180deg, #172554, #1e3a5f); color: white; text-align: center; padding: 50px 20px;">
        <h1 style="font-size: 34px; margin-bottom: 10px;">Chapitres de formation</h1>
        <p style="font-size: 16px; color: #ccc;">Les neuf chapitres du programme Samuel</p>
    </section>

    <!-- Liste des chapitres -->
    <section style="padding: 50px 20px;">
        <div style="max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px;">
            @foreach ($chapitres as $numero => $titre)
            <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
                <div style="width: 45px; height: 45px; background: #D4A72C; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                    <span style="color: #172554; font-weight: bold; font-size: 20px;">{{ $numero }}</span>
                </div>
                <h3 style="color: #172554; font-size: 18px; margin-bottom: 8px;">{{ $titre }}</h3>
                <a href="/chapitres/{{ $numero }}" style="color: #D4A72C; text-decoration: none; font-weight: bold; font-size: 14px;">Consulter le chapitre &rarr;</a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Pied de page -->
    <footer style="background: #0f1a30; color: #ccc; text-align: center; padding: 20px;">
        <p>&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
