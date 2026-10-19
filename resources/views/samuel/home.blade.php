<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; }
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
                <a href="/" style="color: #D4A72C; text-decoration: none;">Accueil</a>
                <a href="/a-propos" style=" color: #ccc; text-decoration: none;">À propos</a>
                <a href="/chapitres" style="color: #ccc; text-decoration: none;">Chapitres</a>
                <a href="/connexion" style="color: #ccc; text-decoration: none;">Connexion</a>
                <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 8px 18px; border-radius: 8px; text-decoration: none; font-weight: bold;">Inscription</a>
            </nav>
        </div>
    </header>

    <!-- Section principale -->
    <section style="background: linear-gradient(180deg, #172554, #1e3a5f); color: white; text-align: center; padding: 80px 20px;">
        <h1 style="font-size: 40px; margin-bottom: 15px;">Bienvenue dans l'espace des Samuels</h1>
        <p style="font-size: 18px; color: #ccc; max-width: 600px; margin: 0 auto 30px;">Une plateforme dédiée aux servants d'autel de l'Église catholique. Découvrez vos cours, gérez vos présences et rejoignez une communauté de foi.</p>
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="/chapitres" style="background: #D4A72C; color: #172554; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 16px;">Découvrir les chapitres</a>
            <a href="/inscription" style="background: white; color: #172554; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 16px;">Créer un compte</a>
        </div>
    </section>

    <!-- Section À propos -->
    <section style="background: #F3F4F6; padding: 60px 20px;">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">
            <h2 style="color: #172554; font-size: 28px; margin-bottom: 15px;">À propos de Samuel App</h2>
            <p style="color: #555; font-size: 16px; line-height: 1.8;">Samuel App a été créé pour moderniser l'organisation et le suivi des activités des Samuels au sein de l'Église catholique. La plateforme facilite la formation liturgique, la consultation des cours et le suivi des présences.</p>
        </div>
    </section>

    <!-- Section fonctionnalités -->
    <section style="background: white; padding: 60px 20px;">
        <div style="max-width: 1000px; margin: 0 auto;">
            <h2 style="color: #172554; font-size: 28px; text-align: center; margin-bottom: 40px;">Nos fonctionnalités</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px;">
                <div style="background: #F3F4F6; border-radius: 12px; padding: 25px; text-align: center;">
                    <div style="width: 50px; height: 50px; background: #D4A72C; border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: #172554; font-size: 22px;">📖</span>
                    </div>
                    <h3 style="color: #172554; margin-bottom: 8px;">Formation</h3>
                    <p style="color: #666; font-size: 14px;">Accédez à tous vos cours et chapitres de formation</p>
                </div>
                <div style="background: #F3F4F6; border-radius: 12px; padding: 25px; text-align: center;">
                    <div style="width: 50px; height: 50px; background: #15803D; border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-size: 22px;">✅</span>
                    </div>
                    <h3 style="color: #172554; margin-bottom: 8px;">Présences</h3>
                    <p style="color: #666; font-size: 14px;">Enregistrez et suivez vos présences de séance</p>
                </div>
                <div style="background: #F3F4F6; border-radius: 12px; padding: 25px; text-align: center;">
                    <div style="width: 50px; height: 50px; background: #172554; border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-size: 22px;">📱</span>
                    </div>
                    <h3 style="color: #172554; margin-bottom: 8px;">Hors ligne</h3>
                    <p style="color: #666; font-size: 14px;">Consultez vos formations même sans connexion</p>
                </div>
                <div style="background: #F3F4F6; border-radius: 12px; padding: 25px; text-align: center;">
                    <div style="width: 50px; height: 50px; background: #EC4899; border-radius: 10px; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                        <span style="color: white; font-size: 22px;">📊</span>
                    </div>
                    <h3 style="color: #172554; margin-bottom: 8px;">Statistiques</h3>
                    <p style="color: #666; font-size: 14px;">Consultez votre participation et historique</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section inscription -->
    <section style="background: #172554; color: white; text-align: center; padding: 60px 20px;">
        <h2 style="font-size: 28px; margin-bottom: 10px;">Rejoignez Samuel App</h2>
        <p style="color: #ccc; max-width: 500px; margin: 0 auto 25px;">Créez votre compte et commencez votre formation dès aujourd'hui.</p>
        <a href="/inscription" style="background: #D4A72C; color: #172554; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 16px;">Créer mon compte</a>
    </section>

    <!-- Pied de page -->
    <footer style="background: #0f1a30; color: #ccc; text-align: center; padding: 20px;">
        <p>&copy; 2026 Samuel App — Tous droits réservés</p>
    </footer>

</body>
</html>
