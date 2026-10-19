<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos — Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; color: #172554; }
        a { text-decoration: none; }
        .nav-link { color: #ccc; }
        .nav-link:hover { color: #D4A72C; }
    </style>
</head>
<body>

    <!-- En-tête -->
    <header style="background: #172554; padding: 15px 0;">
        <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" alt="Samuel App" style="width: 45px; height: 45px; border-radius: 10px; object-fit: cover;">
                <span style="color: white; font-size: 20px; font-weight: bold;">Samuel App</span>
            </div>
            <nav style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                <a href="/" class="nav-link">Accueil</a>
                <a href="/a-propos" style="color: #D4A72C;">À propos</a>
                <a href="/chapitres" class="nav-link">Chapitres</a>
                <a href="/connexion" class="nav-link">Connexion</a>
                <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; font-weight: bold;">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- En-tête de page -->
    <section style="background: linear-gradient(180deg, #172554, #1e3a5f); color: white; text-align: center; padding: 70px 20px;">
        <div style="width: 76px; height: 76px; border-radius: 20px; background: linear-gradient(135deg, #D4A72C, #F59E0B); display: flex; align-items: center; justify-content: center; margin: 0 auto 22px; font-size: 36px;">🕊️</div>
        <h1 style="font-size: 38px; margin-bottom: 14px;">À propos de Samuel App</h1>
        <p style="font-size: 17px; color: #cbd5e1; max-width: 660px; margin: 0 auto; line-height: 1.7;">
            Une plateforme numérique au service de la formation et de l'organisation des Samuels,
            les servants d'autel de l'Église catholique.
        </p>
        <div style="margin-top: 24px; color: #D4A72C; font-size: 13px; letter-spacing: 3px;">FOI &nbsp;•&nbsp; SERVICE &nbsp;•&nbsp; ENGAGEMENT</div>
    </section>

    <!-- Mission & Vision -->
    <section style="background: #F3F4F6; padding: 60px 20px;">
        <div style="max-width: 1000px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 22px;">
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 6px 24px rgba(23,37,84,0.06); border-top: 4px solid #D4A72C;">
                <div style="font-size: 28px; margin-bottom: 14px;">🎯</div>
                <h2 style="font-size: 20px; margin-bottom: 12px;">Notre mission</h2>
                <p style="color: #4A5568; line-height: 1.8;">
                    Moderniser l'organisation et le suivi des activités des Samuels (servants d'autel) au sein de
                    l'Église catholique. Offrir une plateforme numérique intuitive qui facilite la formation,
                    la consultation des cours et le suivi des présences.
                </p>
            </div>
            <div style="background: white; border-radius: 16px; padding: 32px; box-shadow: 0 6px 24px rgba(23,37,84,0.06); border-top: 4px solid #172554;">
                <div style="font-size: 28px; margin-bottom: 14px;">🌅</div>
                <h2 style="font-size: 20px; margin-bottom: 12px;">Notre vision</h2>
                <p style="color: #4A5568; line-height: 1.8;">
                    Devenir la référence numérique pour la formation des servants d'autel, en rendant les ressources
                    liturgiques accessibles à tout moment et en simplifiant la gestion administrative au service de l'Église.
                </p>
            </div>
        </div>
    </section>

    <!-- Objectifs -->
    <section style="background: white; padding: 60px 20px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h2 style="text-align: center; font-size: 28px; margin-bottom: 10px;">Nos objectifs</h2>
            <p style="text-align: center; color: #6B7280; margin-bottom: 40px;">Ce que Samuel App vous permet de faire au quotidien</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px;">
                @php
                    $objectifs = [
                        'Centraliser les cours et documents de formation des Samuels',
                        'Faciliter l\'apprentissage des fonctions liturgiques',
                        'Permettre la consultation des cours même sans connexion Internet',
                        'Simplifier l\'enregistrement et le suivi des présences',
                        'Moderniser l\'organisation des activités ecclésiales',
                        'Sécuriser les comptes et contrôler les autorisations selon les rôles',
                    ];
                @endphp

                @foreach ($objectifs as $objectif)
                    <div style="display: flex; align-items: flex-start; gap: 14px; background: #F3F4F6; border-radius: 14px; padding: 20px 22px;">
                        <div style="flex: 0 0 auto; width: 30px; height: 30px; border-radius: 50%; background: #15803D; color: white; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: bold;">✓</div>
                        <p style="color: #374151; line-height: 1.6; font-size: 15px;">{{ $objectif }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact / appel à l'action -->
    <section style="background: #172554; color: white; text-align: center; padding: 60px 20px;">
        <h2 style="font-size: 26px; margin-bottom: 10px;">Une question ?</h2>
        <p style="color: #cbd5e1; max-width: 520px; margin: 0 auto 26px; line-height: 1.7;">
            Pour toute demande d'information, veuillez contacter l'administrateur de la plateforme.
        </p>
        <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
            <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 12px 30px; border-radius: 10px; font-weight: bold;">Créer mon compte</a>
            <a href="/chapitres" style="background: rgba(255,255,255,0.12); color: white; padding: 12px 30px; border-radius: 10px; font-weight: bold;">Voir les chapitres</a>
        </div>
    </section>

    <!-- Pied de page -->
    <footer style="background: #0f1a30; color: #ccc; text-align: center; padding: 26px 20px;">
        <div style="color: white; font-weight: bold; font-size: 16px;">Samuel App</div>
        <div style="color: #D4A72C; font-size: 12px; letter-spacing: 2px; margin: 6px 0;">Foi &bull; Service &bull; Engagement</div>
        <p style="font-size: 13px;">&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
