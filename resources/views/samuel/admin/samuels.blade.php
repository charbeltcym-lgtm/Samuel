<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Samuels - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Gestion des Samuels</div>
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
                    <h3 style="margin:0;">Liste des Samuels ({{ $samuels->count() }})</h3>
                    <a href="/nouveau-samuel" class="btn btn-gold">+ Nouveau Samuel</a>
                </div>
                @if ($samuels->isEmpty())
                    <div class="empty-state"><div class="icon">👥</div><p>Aucun Samuel inscrit pour le moment</p></div>
                @else
                    <table>
                        <thead><tr><th>Nom</th><th>Email</th><th>Statut</th><th>Inscrit le</th></tr></thead>
                        <tbody>
                            @foreach ($samuels as $samuel)
                            <tr>
                                <td><strong>{{ $samuel->name }}</strong></td>
                                <td>{{ $samuel->email }}</td>
                                <td><span class="badge badge-{{ $samuel->status === 'active' ? 'active' : ($samuel->status === 'pending' ? 'pending' : 'rejected') }}">{{ $samuel->status === 'active' ? 'Actif' : ($samuel->status === 'pending' ? 'En attente' : 'Désactivé') }}</span></td>
                                <td>{{ $samuel->created_at->format('d/m/Y') }}</td>
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
