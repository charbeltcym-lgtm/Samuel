<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos — Samuel App</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; color: #172554; }
        a { text-decoration: none; }

        /* En-tête */
        .header { background: #172554; padding: 14px 0; }
        .header-inner { max-width: 1100px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
        .brand { display: flex; align-items: center; gap: 10px; }
        .brand img { width: 45px; height: 45px; border-radius: 10px; object-fit: cover; }
        .brand span { color: #fff; font-size: 20px; font-weight: bold; }
        .nav { display: flex; gap: 16px; align-items: center; flex-wrap: wrap; }
        .nav a { color: #ccc; }
        .nav a:hover { color: #D4A72C; }
        .nav a.active { color: #D4A72C; }
        .nav a.cta { background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; font-weight: bold; }

        /* Bandeau */
        .hero { background: linear-gradient(180deg, #172554, #1e3a5f); color: #fff; text-align: center; padding: 70px 20px; }
        .hero img { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 4px solid #D4A72C; box-shadow: 0 10px 30px rgba(0,0,0,.35); margin: 0 auto 22px; display: block; }
        .hero h1 { font-size: 38px; margin-bottom: 14px; }
        .hero p { font-size: 17px; color: #cbd5e1; max-width: 660px; margin: 0 auto; line-height: 1.7; }
        .hero .motto { margin-top: 24px; color: #D4A72C; font-size: 13px; letter-spacing: 3px; }

        /* Sections */
        .section { padding: 60px 20px; }
        .section-inner { max-width: 1000px; margin: 0 auto; }
        .bg-gray { background: #F3F4F6; }
        .bg-white { background: #fff; }
        .bg-blue { background: #172554; color: #fff; }
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 22px; }
        .card { background: #fff; border-radius: 16px; padding: 32px; box-shadow: 0 6px 24px rgba(23,37,84,.06); }
        .card.gold { border-top: 4px solid #D4A72C; }
        .card.blue { border-top: 4px solid #172554; }
        .card h2 { font-size: 20px; margin-bottom: 12px; }
        .card p { color: #4A5568; line-height: 1.8; }
        .card .ico { font-size: 28px; margin-bottom: 14px; }

        .section-title { text-align: center; font-size: 28px; margin-bottom: 10px; }
        .section-sub { text-align: center; color: #6B7280; margin-bottom: 40px; }

        .grid-obj { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; }
        .obj { display: flex; align-items: flex-start; gap: 14px; background: #F3F4F6; border-radius: 14px; padding: 20px 22px; }
        .obj .check { flex: 0 0 auto; width: 30px; height: 30px; border-radius: 50%; background: #15803D; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 15px; font-weight: bold; }
        .obj p { color: #374151; line-height: 1.6; font-size: 15px; }

        /* Contact */
        .contact-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .contact-btn { display: flex; align-items: center; gap: 14px; padding: 14px 24px; border-radius: 12px; font-weight: bold; text-align: left; min-width: 260px; }
        .contact-btn .circle { display: flex; align-items: center; justify-content: center; width: 44px; height: 44px; border-radius: 50%; flex: 0 0 auto; }
        .contact-btn small { display: block; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; opacity: .85; }
        .contact-btn .val { font-size: 16px; }
        .wa { background: #25D366; color: #fff; }
        .wa .circle { background: rgba(255,255,255,.22); }
        .mail { background: #D4A72C; color: #172554; }
        .mail .circle { background: rgba(23,37,84,.12); }
        .cta-link { margin-top: 30px; }
        .cta-link a { color: #D4A72C; font-weight: bold; }

        /* Pied de page */
        .footer { background: #0f1a30; color: #ccc; text-align: center; padding: 26px 20px; }
        .footer .name { color: #fff; font-weight: bold; font-size: 16px; }
        .footer .motto { color: #D4A72C; font-size: 12px; letter-spacing: 2px; margin: 6px 0; }

        @media (max-width: 640px) {
            .header-inner { justify-content: center; text-align: center; }
            .nav { justify-content: center; gap: 12px; }
            .brand span { font-size: 18px; }
            .hero { padding: 48px 18px; }
            .hero h1 { font-size: 27px; }
            .hero p { font-size: 15px; }
            .hero img { width: 92px; height: 92px; }
            .section { padding: 42px 16px; }
            .card { padding: 24px; }
            .contact-btns { flex-direction: column; }
            .contact-btn { min-width: 0; width: 100%; }
        }
    </style>
</head>
<body>

    <!-- En-tête -->
    <header class="header">
        <div class="header-inner">
            <div class="brand">
                <img src="{{ asset('images/logo.png') }}" alt="Samuel App">
                <span>Samuel App</span>
            </div>
            <nav class="nav">
                <a href="/">Accueil</a>
                <a href="/a-propos" class="active">À propos</a>
                <a href="/chapitres">Chapitres</a>
                <a href="/connexion">Connexion</a>
                <a href="/inscription" class="cta">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- Bandeau -->
    <section class="hero">
        <img src="{{ asset('images/logo.png') }}" alt="Samuel App">
        <h1>À propos de Samuel App</h1>
        <p>Une plateforme numérique au service de la formation et de l'organisation des Samuels, les servants d'autel de l'Église catholique.</p>
        <div class="motto">FOI &nbsp;•&nbsp; SERVICE &nbsp;•&nbsp; ENGAGEMENT</div>
    </section>

    <!-- Mission & Vision -->
    <section class="section bg-gray">
        <div class="section-inner grid-2">
            <div class="card gold">
                <div class="ico">🎯</div>
                <h2>Notre mission</h2>
                <p>Moderniser l'organisation et le suivi des activités des Samuels (servants d'autel) au sein de l'Église catholique. Offrir une plateforme numérique intuitive qui facilite la formation, la consultation des cours et le suivi des présences.</p>
            </div>
            <div class="card blue">
                <div class="ico">🌅</div>
                <h2>Notre vision</h2>
                <p>Devenir la référence numérique pour la formation des servants d'autel, en rendant les ressources liturgiques accessibles à tout moment et en simplifiant la gestion administrative au service de l'Église.</p>
            </div>
        </div>
    </section>

    <!-- Objectifs -->
    <section class="section bg-white">
        <div class="section-inner">
            <h2 class="section-title">Nos objectifs</h2>
            <p class="section-sub">Ce que Samuel App vous permet de faire au quotidien</p>

            <div class="grid-obj">
                @foreach ([
                    'Centraliser les cours et documents de formation des Samuels',
                    'Faciliter l\'apprentissage des fonctions liturgiques',
                    'Permettre la consultation des cours même sans connexion Internet',
                    'Simplifier l\'enregistrement et le suivi des présences',
                    'Moderniser l\'organisation des activités ecclésiales',
                    'Sécuriser les comptes et contrôler les autorisations selon les rôles',
                ] as $objectif)
                    <div class="obj">
                        <div class="check">✓</div>
                        <p>{{ $objectif }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section class="section bg-blue">
        <div class="section-inner">
            <h2 class="section-title" style="color:#fff;">Contactez l'administrateur</h2>
            <p class="section-sub" style="color:#cbd5e1;">Une question, une demande d'information ? Nous vous répondrons avec plaisir.</p>

            <div class="contact-btns">
                <a href="https://wa.me/2250150034446" target="_blank" rel="noopener" class="contact-btn wa">
                    <span class="circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </span>
                    <span>
                        <small>WhatsApp</small>
                        <span class="val">0150034446</span>
                    </span>
                </a>

                <a href="mailto:charbeltcym@gmail.com" class="contact-btn mail">
                    <span class="circle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="2" y="4" width="20" height="16" rx="3"/>
                            <path d="M3 7l9 6 9-6"/>
                        </svg>
                    </span>
                    <span>
                        <small>Email</small>
                        <span class="val">charbeltcym@gmail.com</span>
                    </span>
                </a>
            </div>

            <div class="cta-link" style="text-align:center;">
                <a href="/inscription">Créer mon compte →</a>
            </div>
        </div>
    </section>

    <!-- Pied de page -->
    <footer class="footer">
        <div class="name">Samuel App</div>
        <div class="motto">Foi &bull; Service &bull; Engagement</div>
        <p style="font-size:13px;">&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
