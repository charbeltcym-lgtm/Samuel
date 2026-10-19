<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: #F3F4F6; }

        /* ---------- Barre latérale ---------- */
        .sidebar { position: fixed; left: 0; top: 0; width: 260px; height: 100vh; background: #172554; display: flex; flex-direction: column; z-index: 50; }
        .sidebar-logo { display: flex; align-items: center; gap: 12px; padding: 22px 20px; border-bottom: 1px solid #374151; }
        .sidebar-logo img { width: 46px; height: 46px; border-radius: 12px; object-fit: cover; }
        .sidebar-logo .brand { color: white; font-size: 18px; font-weight: bold; line-height: 1.2; }
        .sidebar-logo .motto { color: #D4A72C; font-size: 11px; font-style: italic; }
        .nav { flex: 1; padding: 18px 14px; overflow-y: auto; }
        .nav a { display: flex; align-items: center; gap: 12px; color: #CBD5E1; text-decoration: none; padding: 12px 15px; border-radius: 10px; font-size: 14px; margin-bottom: 4px; transition: all 0.2s; }
        .nav a:hover { background: #2D3748; color: white; }
        .nav a.active { background: #D4A72C; color: #172554; font-weight: bold; }
        .nav .section-title { color: #6B7280; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; padding: 15px 15px 6px; }
        .sidebar-footer { padding: 18px 20px; border-top: 1px solid #374151; color: #9CA3AF; font-size: 13px; }

        /* ---------- Zone principale ---------- */
        .main { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: white; padding: 16px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; position: sticky; top: 0; z-index: 40; }
        .topbar .page-title { color: #172554; font-size: 20px; font-weight: bold; }
        .topbar-right { display: flex; align-items: center; gap: 18px; }
        .notif-bell { position: relative; display: inline-flex; align-items: center; justify-content: center; width: 42px; height: 42px; border-radius: 50%; background: #F3F4F6; color: #172554; text-decoration: none; border: 1px solid #E5E7EB; transition: background .15s ease, transform .15s ease, box-shadow .15s ease; }
        .notif-bell:hover { background: #E8ECF3; transform: translateY(-1px); box-shadow: 0 4px 10px rgba(23,37,84,0.12); }
        .notif-bell:active { transform: translateY(0); }
        .notif-badge { position: absolute; top: -4px; right: -4px; min-width: 20px; height: 20px; padding: 0 6px; background: #DC2626; color: #fff; font-size: 11px; font-weight: bold; border-radius: 20px; border: 2px solid #fff; display: flex; align-items: center; justify-content: center; line-height: 1; }
        .profile { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .profile .avatar { width: 40px; height: 40px; border-radius: 50%; background: #D4A72C; color: #172554; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; }
        .profile img.avatar { object-fit: cover; background: #E5E7EB; }
        .profile .info { line-height: 1.2; }
        .profile .info .name { color: #172554; font-size: 14px; font-weight: bold; }
        .profile .info .role { color: #6B7280; font-size: 12px; }

        .content { flex: 1; padding: 30px; }

        /* ---------- Accueil ---------- */
        .welcome { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; flex-wrap: wrap; gap: 15px; }
        .welcome h1 { color: #172554; font-size: 26px; }
        .welcome p { color: #6B7280; font-size: 15px; margin-top: 4px; }
        .clock { background: white; padding: 14px 24px; border-radius: 14px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; text-align: right; }
        .clock .time { color: #172554; font-size: 28px; font-weight: bold; }
        .clock .date { color: #6B7280; font-size: 13px; }

        /* ---------- Statistiques ---------- */
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; border-radius: 16px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-start; }
        .stat-card .label { color: #6B7280; font-size: 14px; margin-bottom: 8px; }
        .stat-card .value { font-size: 34px; font-weight: bold; color: #172554; }
        .stat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; }

        /* ---------- Grilles ---------- */
        .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px; }
        .grid-2-equal { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .panel { background: white; border-radius: 16px; padding: 25px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); border: 1px solid #e2e8f0; }
        .panel h3 { color: #172554; font-size: 18px; margin-bottom: 18px; }

        /* ---------- Tableaux ---------- */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; color: #6B7280; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; padding: 10px 12px; border-bottom: 2px solid #F3F4F6; }
        td { padding: 13px 12px; font-size: 14px; color: #374151; border-bottom: 1px solid #F3F4F6; }
        tr:hover td { background: #F9FAFB; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .badge-active { background: #dcfce7; color: #15803D; }
        .badge-pending { background: #FEF3C7; color: #EA580C; }
        .badge-rejected { background: #fee2e2; color: #DC2626; }
        .badge-present { background: #dcfce7; color: #15803D; }
        .badge-absent { background: #fee2e2; color: #DC2626; }
        .badge-retard { background: #FEF3C7; color: #EA580C; }
        .badge-permission { background: #dbeafe; color: #1D4ED8; }

        .btn { display: inline-block; padding: 7px 16px; border-radius: 8px; font-size: 13px; font-weight: bold; text-decoration: none; cursor: pointer; border: none; transition: opacity 0.2s; }
        .btn:hover { opacity: 0.85; }
        .btn-gold { background: #D4A72C; color: #172554; }
        .btn-green { background: #15803D; color: white; }
        .btn-red { background: #DC2626; color: white; }
        .btn-navy { background: #172554; color: white; }
        .btn-light { background: #F3F4F6; color: #172554; }

        /* ---------- Actions rapides ---------- */
        .quick-action { display: flex; align-items: center; gap: 12px; padding: 14px; border-radius: 12px; background: #F3F4F6; color: #172554; font-size: 14px; font-weight: 500; margin-bottom: 10px; text-decoration: none; transition: all 0.2s; border: 1px solid transparent; }
        .quick-action:hover { background: white; border-color: #D4A72C; }
        .quick-action .qa-icon { width: 38px; height: 38px; background: #D4A72C; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #172554; font-weight: bold; font-size: 18px; }

        /* ---------- Graphique anneau ---------- */
        .donut-wrap { display: flex; align-items: center; gap: 25px; }
        .donut { width: 150px; height: 150px; border-radius: 50%; position: relative; }
        .donut .center { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; }
        .donut .center .num { font-size: 26px; font-weight: bold; color: #172554; }
        .donut .center .lbl { font-size: 11px; color: #6B7280; }
        .legend { list-style: none; }
        .legend li { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; font-size: 14px; color: #374151; }
        .legend .dotc { width: 12px; height: 12px; border-radius: 3px; }

        /* ---------- Activité ---------- */
        .activity-item { display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid #F3F4F6; }
        .activity-item:last-child { border-bottom: none; }
        .activity-icon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .activity-text { font-size: 14px; color: #374151; }
        .activity-time { font-size: 12px; color: #9CA3AF; margin-top: 2px; }

        .empty-state { text-align: center; padding: 30px; color: #9CA3AF; }
        .empty-state .icon { font-size: 40px; margin-bottom: 10px; }

        /* ---------- Pied de page ---------- */
        .footer { background: #172554; color: #9CA3AF; text-align: center; padding: 22px; font-size: 14px; }
        .footer .brand { color: white; font-weight: bold; }
        .footer .motto { color: #D4A72C; font-size: 12px; margin-top: 4px; letter-spacing: 2px; }

        /* ---------- Responsive ---------- */
        .nav-toggle { display: none; }
        .hamburger { display: none; }
        @media (max-width: 1024px) { .grid-2, .grid-2-equal { grid-template-columns: 1fr; } }
        @media (max-width: 900px) {
            .main { margin-left: 0; }
            .sidebar { transform: translateX(-100%); transition: transform 0.25s ease; box-shadow: 4px 0 24px rgba(0,0,0,0.35); }
            .nav-toggle:checked ~ .sidebar { transform: translateX(0); }
            .hamburger { display: flex; align-items: center; justify-content: center; position: fixed; top: 12px; left: 12px; z-index: 60; width: 42px; height: 42px; border-radius: 10px; background: #172554; color: #fff; font-size: 20px; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.25); }
            .topbar { padding: 12px 16px 12px 64px; }
            .topbar .page-title { font-size: 17px; }
            .topbar .profile .info { display: none; }
            .content { padding: 16px; }
            .panel { padding: 16px; overflow-x: auto; }
            .panel table { min-width: 520px; }
            .welcome h1 { font-size: 20px; }
            .stat-card .value { font-size: 28px; }
        }
        @media (max-width: 600px) {
            .topbar-right { gap: 12px; }
            .profile .avatar { width: 34px; height: 34px; font-size: 14px; }
            .stats { grid-template-columns: 1fr; }
            .donut-wrap { flex-direction: column; align-items: flex-start; }
            .clock { text-align: left; }
        }
    </style>
</head>
<body>

    <!-- Barre latérale -->
    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <aside class="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Samuel App">
            <div>
                <div class="brand">Samuel App</div>
                <div class="motto">Ensemble pour un même service</div>
            </div>
        </div>
        <nav class="nav">
            <a href="/dashboard" class="active">📊 Vue d'ensemble</a>
            <div class="section-title">Gestion</div>
            <a href="/inscriptions">📝 Inscriptions</a>
            <a href="/samuels">👥 Gestion des Samuels</a>
            <a href="/administrateurs">🛡️ Administrateurs</a>
            <div class="section-title">Formation</div>
            <a href="/chapitres">📖 Chapitres</a>
            <div class="section-title">Activités</div>
            <a href="/presences">✅ Présences</a>
            <a href="/historique">🔒 Historique et sécurité</a>
            <div class="section-title">Compte</div>
            <a href="/parametres">⚙️ Paramètres du compte</a>
            <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
                @csrf
                <button type="submit" style="width:100%; display:flex; align-items:center; gap:12px; color:#CBD5E1; background:none; border:none; padding:12px 15px; border-radius:10px; font-size:14px; cursor:pointer; font-family:inherit; text-align:left;">🚪 Déconnexion</button>
            </form>
        </nav>
        <div class="sidebar-footer">
            Connecté en tant que<br><strong style="color:#D4A72C">Super administrateur</strong>
        </div>
    </aside>
    <label for="nav-toggle" class="hamburger">☰</label>

    <!-- Zone principale -->
    <div class="main">

        <!-- Barre supérieure -->
        <header class="topbar">
            <div class="page-title">Vue d'ensemble</div>
            <div class="topbar-right">
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">Administrateur principal</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">

            @if (session('success'))
                <div style="background:#dcfce7; color:#15803D; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px; word-break:break-word;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Accueil + horloge -->
            <div class="welcome">
                <div>
                    <h1>Bonjour, {{ auth()->user()->name }} 👋</h1>
                    <p>Bienvenue sur votre tableau de bord de supervision</p>
                </div>
                <div class="clock">
                    <div class="time" id="liveTime">--:--:--</div>
                    <div class="date" id="liveDate">Chargement...</div>
                </div>
            </div>

            <!-- Statistiques générales -->
            <div class="stats">
                <div class="stat-card">
                    <div>
                        <div class="label">Nombre total de Samuels</div>
                        <div class="value">{{ $stats['total_samuels'] }}</div>
                    </div>
                    <div class="stat-icon" style="background:#EEF2FF; color:#172554;">👥</div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="label">Nombre d'administrateurs</div>
                        <div class="value" style="color:#15803D;">{{ $stats['total_administrateurs'] }}</div>
                    </div>
                    <div class="stat-icon" style="background:#dcfce7; color:#15803D;">🛡️</div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="label">Demandes en attente</div>
                        <div class="value" style="color:#EA580C;">{{ $stats['inscriptions_en_attente'] }}</div>
                    </div>
                    <div class="stat-icon" style="background:#FEF3C7; color:#EA580C;">⏳</div>
                </div>
                <div class="stat-card">
                    <div>
                        <div class="label">Présents dernière répétition</div>
                        <div class="value" style="color:#15803D;">{{ $stats['presents'] }}</div>
                    </div>
                    <div class="stat-icon" style="background:#dcfce7; color:#15803D;">✅</div>
                </div>
            </div>

            <!-- Inscriptions + Actions rapides -->
            <div class="grid-2">
                <div class="panel">
                    <h3>Demandes d'inscription récentes</h3>
                    @if ($inscriptions_en_attente->isEmpty())
                        <div class="empty-state">
                            <div class="icon">📭</div>
                            <p>Aucune demande d'inscription pour le moment</p>
                        </div>
                    @else
                        <table>
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inscriptions_en_attente as $inscription)
                                <tr>
                                    <td><strong>{{ $inscription->name }}</strong></td>
                                    <td>{{ $inscription->email }}</td>
                                    <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                                    <td><span class="badge badge-pending">En attente</span></td>
                                    <td>
                                        <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                            <form action="{{ route('inscriptions.valider', $inscription->id) }}" method="POST" onsubmit="return confirm('Valider l\'inscription de {{ $inscription->name }} et générer le code d\'activation ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-green" style="padding:5px 10px;">Valider</button>
                                            </form>
                                            <form action="{{ route('inscriptions.rejeter', $inscription->id) }}" method="POST" onsubmit="return confirm('Rejeter l\'inscription de {{ $inscription->name }} ?');">
                                                @csrf
                                                <button type="submit" class="btn btn-red" style="padding:5px 10px;">Rejeter</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                <div class="panel">
                    <h3>Actions rapides</h3>
                    <a href="/nouveau-samuel" class="quick-action"><div class="qa-icon">+</div> Nouveau Samuel</a>
                    <a href="/nouvel-admin" class="quick-action"><div class="qa-icon">+</div> Nouvel administrateur</a>
                    <a href="/nouvelle-seance" class="quick-action"><div class="qa-icon">+</div> Nouvelle séance</a>
                    <a href="/presences" class="quick-action"><div class="qa-icon">✓</div> Gérer les présences</a>
                </div>
            </div>

            <!-- Présences dernière répétition + graphique -->
            <div class="grid-2-equal">
                <div class="panel">
                    <h3>Présences — Dernière répétition</h3>
                    @if ($stats['total_derniere'] > 0)
                        <div class="donut-wrap">
                            <div class="donut" style="background: conic-gradient(
                                #15803D 0deg {{ ($stats['presents'] / $stats['total_derniere']) * 360 }}deg,
                                #DC2626 {{ ($stats['presents'] / $stats['total_derniere']) * 360 }}deg {{ (($stats['presents'] + $stats['absents']) / $stats['total_derniere']) * 360 }}deg,
                                #EA580C {{ (($stats['presents'] + $stats['absents']) / $stats['total_derniere']) * 360 }}deg {{ (($stats['presents'] + $stats['absents'] + $stats['retards']) / $stats['total_derniere']) * 360 }}deg,
                                #1D4ED8 {{ (($stats['presents'] + $stats['absents'] + $stats['retards']) / $stats['total_derniere']) * 360 }}deg 360deg
                            );">
                                <div class="center">
                                    <div class="num">{{ $stats['taux_participation'] }}%</div>
                                    <div class="lbl">Participation</div>
                                </div>
                            </div>
                            <ul class="legend">
                                <li><span class="dotc" style="background:#15803D;"></span> Présents : {{ $stats['presents'] }}</li>
                                <li><span class="dotc" style="background:#DC2626;"></span> Absents : {{ $stats['absents'] }}</li>
                                <li><span class="dotc" style="background:#EA580C;"></span> Retards : {{ $stats['retards'] }}</li>
                                <li><span class="dotc" style="background:#1D4ED8;"></span> Permissionnaires : {{ $stats['permissionnaires'] }}</li>
                            </ul>
                        </div>
                        <p style="color:#9CA3AF; font-size:12px; margin-top:15px;">Répétition du {{ \Carbon\Carbon::parse($stats['derniere_repetition'])->format('d/m/Y') }}</p>
                    @else
                        <div class="empty-state">
                            <div class="icon">📅</div>
                            <p>Aucune répétition enregistrée pour le moment</p>
                        </div>
                    @endif
                </div>

                <div class="panel">
                    <h3>Activité récente</h3>
                    @if ($activites->isEmpty())
                        <div class="empty-state">
                            <div class="icon">📋</div>
                            <p>Aucune activité enregistrée</p>
                        </div>
                    @else
                        @foreach ($activites as $activite)
                        <div class="activity-item">
                            <div class="activity-icon" style="background:#EEF2FF;">📌</div>
                            <div>
                                <div class="activity-text">{{ $activite->description }}</div>
                                <div class="activity-time">{{ $activite->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Chapitres -->
            <div class="panel" style="margin-bottom:30px;">
                <h3>Chapitres de formation</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(250px,1fr)); gap:12px;">
                    @php
                        $chapitres = [
                            1 => 'Histoire du Samuel et prière du servant',
                            2 => 'Comportements, qualités et attributs des Samuels',
                            3 => 'L\'Assemblée liturgique',
                            4 => 'Étude des objets sacrés',
                            5 => 'Rôle du polyvalent dans la messe',
                            6 => 'Porte-croix',
                            7 => 'Rôle du porte-croix dans les différents types de messes',
                            8 => 'Importance du Samuel dans l\'Église et surtout la nature',
                            9 => 'Pratiques',
                        ];
                    @endphp
                    @foreach ($chapitres as $numero => $titre)
                    <a href="/chapitres/{{ $numero }}" style="display:flex; align-items:center; gap:12px; padding:14px; background:#F3F4F6; border-radius:10px; text-decoration:none; border:1px solid transparent; transition:all 0.2s;" onmouseover="this.style.borderColor='#D4A72C'" onmouseout="this.style.borderColor='transparent'">
                        <div style="width:36px; height:36px; background:#D4A72C; border-radius:8px; display:flex; align-items:center; justify-content:center; color:#172554; font-weight:bold;">{{ $numero }}</div>
                        <span style="color:#172554; font-size:13px; font-weight:500;">{{ $titre }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Pied de page -->
        @include('samuel.dashboards.partials.footer')

    </div>

    <script>
        function updateClock() {
            var now = new Date();
            document.getElementById('liveTime').textContent = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            document.getElementById('liveDate').textContent = now.toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
        }
        updateClock();
        setInterval(updateClock, 1000);
    </script>

</body>
</html>
