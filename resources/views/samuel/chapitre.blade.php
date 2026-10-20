<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titre }} - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #F3F4F6; }

        .chapitre-contenu h2 {
            color: #172554;
            font-size: 22px;
            font-weight: bold;
            text-decoration: underline;
            text-underline-offset: 4px;
            margin: 30px 0 14px;
            padding-bottom: 8px;
            border-bottom: 2px solid #D4A72C;
        }
        .chapitre-contenu h2:first-child { margin-top: 0; }
        .chapitre-contenu h3 {
            color: #172554;
            font-size: 18px;
            font-weight: bold;
            text-decoration: underline;
            text-underline-offset: 4px;
            margin: 24px 0 10px;
        }
        .chapitre-contenu h4 {
            color: #1D4ED8;
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            text-underline-offset: 4px;
            margin: 22px 0 10px;
        }
        .chapitre-contenu p { margin: 0 0 14px; text-align: justify; }
        .chapitre-contenu strong { font-weight: bold; color: #172554; }
        .chapitre-contenu u { text-decoration: underline; text-underline-offset: 3px; }
        .chapitre-contenu ul { margin: 0 0 16px 22px; }
        .chapitre-contenu li { margin-bottom: 6px; }
        .chapitre-contenu blockquote {
            background: #F3F4F6;
            border-left: 4px solid #D4A72C;
            margin: 0 0 16px;
            padding: 12px 18px;
            border-radius: 0 8px 8px 0;
            color: #1f2937;
            font-style: italic;
        }
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
                <a href="/" style="color: #ccc; text-decoration: none;">Accueil</a>
                <a href="/a-propos" style="color: #ccc; text-decoration: none;">À propos</a>
                <a href="/chapitres" style="color: #D4A72C; text-decoration: none;">Chapitres</a>
                <a href="/connexion" style="color: #ccc; text-decoration: none;">Connexion</a>
                <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- Contenu du chapitre -->
    <section style="max-width: 800px; margin: 0 auto; padding: 50px 20px;">
        <a href="/chapitres" style="color: #D4A72C; text-decoration: none; font-weight: bold;">&larr; Retour aux chapitres</a>
        <div style="background: white; border-radius: 12px; padding: 35px; margin-top: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0;">
            <div style="width: 50px; height: 50px; background: #D4A72C; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <span style="color: #172554; font-weight: bold; font-size: 22px;">{{ $numero }}</span>
            </div>
            <h1 style="color: #172554; font-size: 28px; margin-bottom: 20px;">{{ $titre }}</h1>
            @if (!empty($contenu) && auth()->check() && auth()->user()->role === 'super_admin')
                <div class="chapitre-contenu" style="color: #2D3748; font-size: 16px; line-height: 1.9; word-wrap: break-word;">
                    {!! $contenu !!}
                </div>
            @elseif (!empty($contenu))
                <div style="background: #fffbeb; border: 1px dashed #D4A72C; border-radius: 12px; padding: 22px; text-align: center;">
                    <p style="color: #92400E; font-size: 15px; line-height: 1.7; margin: 0;">
                        Le contenu détaillé de ce chapitre est réservé à l'administration.
                        Activez votre compte puis connectez-vous pour y accéder grâce à l'application mobile.
                    </p>
                </div>
            @else
                <p style="color: #4A5568; font-size: 16px; line-height: 1.8;">
                    Le contenu de ce chapitre sera prochainement disponible. Il présentera en détail les enseignements liés à : {{ $titre }}.
                </p>
            @endif
        </div>
    </section>

    <!-- Pied de page -->
    <footer style="background: #0f1a30; color: #ccc; text-align: center; padding: 20px;">
        <p>&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
