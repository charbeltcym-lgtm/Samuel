<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue chez Samuel App</title>
</head>
<body style="margin:0; padding:0; background:#F3F4F6; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#F3F4F6; padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.08);">

                    <!-- En-tête -->
                    <tr>
                        <td style="background:#172554; padding:30px 24px; text-align:center;">
                            <img src="{{ asset('images/logo.png') }}" alt="Samuel App" width="110" style="width:110px; height:110px; border-radius:50%; border:3px solid #D4A72C; object-fit:cover; display:block; margin:0 auto 14px; background:#ffffff;">
                            <div style="color:#ffffff; font-size:24px; font-weight:bold; letter-spacing:3px;">SAMUEL APP</div>
                            <div style="color:#D4A72C; font-size:12px; letter-spacing:2px; margin-top:6px;">Foi &nbsp;•&nbsp; Service &nbsp;•&nbsp; Engagement</div>
                        </td>
                    </tr>

                    <!-- Corps -->
                    <tr>
                        <td style="padding:34px 32px 10px;">
                            <h1 style="color:#172554; font-size:22px; margin:0 0 16px;">Bienvenue, {{ $nom }} ! 🕊️</h1>
                            <p style="color:#374151; font-size:15px; line-height:1.7; margin:0 0 16px;">
                                Béni soit le Seigneur ! Que la joie soit dans ton cœur :
                                <strong>ta demande d'inscription a été validée</strong> par l'administration de Samuel App.
                            </p>
                            <p style="color:#374151; font-size:15px; line-height:1.7; margin:0 0 24px;">
                                Nous sommes heureux de t'accueillir dans la famille des Samuels, les serviteurs d'autel au service de la sainte Église.
                            </p>

                            <!-- Code -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#fffbeb; border:1px dashed #D4A72C; border-radius:14px;">
                                <tr>
                                    <td style="padding:22px; text-align:center;">
                                        <div style="color:#6B7280; font-size:13px; text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">Voici ton code d'activation</div>
                                        <div style="color:#172554; font-size:36px; font-weight:bold; letter-spacing:10px; font-family:'Courier New', monospace;">{{ $code }}</div>
                                    </td>
                                </tr>
                            </table>

                            <p style="color:#172554; font-size:15px; font-weight:bold; margin:28px 0 12px;">Pour activer ton compte :</p>
                            <ol style="color:#374151; font-size:15px; line-height:1.9; margin:0 0 22px; padding-left:20px;">
                                <li>Ouvre l'application mobile <strong>Samuel</strong>.</li>
                                <li>Sur l'écran de connexion, appuie sur <strong>« Première connexion »</strong>.</li>
                                <li>Saisis ton <strong>code d'activation</strong>, ton <strong>email</strong> et choisis ton <strong>mot de passe</strong>.</li>
                                <li>C'est fait ! Tu pourras ensuite te connecter normalement et accéder à tes chapitres.</li>
                            </ol>

                            <p style="color:#9CA3AF; font-size:13px; line-height:1.6; margin:0 0 8px; font-style:italic;">
                                Si tu n'es pas à l'origine de cette inscription, tu peux ignorer ce message.
                            </p>
                        </td>
                    </tr>

                    <!-- Signature -->
                    <tr>
                        <td style="padding:8px 32px 32px;">
                            <p style="color:#374151; font-size:15px; line-height:1.7; margin:0 0 6px;">
                                Que le Seigneur te garde et te bénisse dans ce service.
                            </p>
                            <p style="color:#172554; font-size:15px; font-weight:bold; margin:0;">
                                L'administration de Samuel App
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background:#0f1a30; padding:22px 24px; text-align:center;">
                            <div style="color:#D4A72C; font-size:13px; font-style:italic;">« Parle Seigneur, ton serviteur t'écoute » — 1 Samuel 3,10</div>
                            <div style="color:#9CA3AF; font-size:12px; margin-top:10px;">&copy; {{ date('Y') }} Samuel App — Tous droits réservés</div>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
