<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Présences - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Présences</div>
            <div class="topbar-right">
                <div class="bell">🔔<span class="dot"></span></div>
                <div class="profile">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">Historique des présences</h3>
                    <a href="/nouvelle-seance" class="btn btn-gold">+ Nouvelle séance</a>
                </div>
                @if ($presences->isEmpty())
                    <div class="empty-state"><div class="icon">✅</div><p>Aucune présence enregistrée pour le moment</p><p style="font-size:13px;">Les répétitions ont lieu tous les samedis</p></div>
                @else
                    <table>
                        <thead><tr><th>Samuel</th><th>Date (samedi)</th><th>Statut</th></tr></thead>
                        <tbody>
                            @foreach ($presences as $presence)
                            <tr>
                                <td><strong>{{ $presence->user->name ?? '—' }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($presence->date_repetition)->format('d/m/Y') }}</td>
                                <td>
                                    @if ($presence->statut === 'present') <span class="badge badge-present">Présent</span>
                                    @elseif ($presence->statut === 'absent') <span class="badge badge-absent">Absent</span>
                                    @elseif ($presence->statut === 'retard') <span class="badge badge-retard">Retard</span>
                                    @else <span class="badge badge-permission">Permissionnaire</span> @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <footer class="footer"><div class="brand">Samuel App • Administration principale</div><div class="motto">Foi • Service • Engagement</div></footer>
    </div>
</body>
</html>
