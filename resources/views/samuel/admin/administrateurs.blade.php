<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateurs - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Gestion des administrateurs</div>
            <div class="topbar-right">
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">Liste des administrateurs ({{ $administrateurs->count() }})</h3>
                    <a href="/nouvel-admin" class="btn btn-gold">+ Nouvel administrateur</a>
                </div>
                @if ($administrateurs->isEmpty())
                    <div class="empty-state"><div class="icon">🛡️</div><p>Aucun administrateur désigné pour le moment</p></div>
                @else
                    <table>
                        <thead><tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Statut</th></tr></thead>
                        <tbody>
                            @foreach ($administrateurs as $admin)
                            <tr>
                                <td><strong>{{ $admin->name }}</strong></td>
                                <td>{{ $admin->email }}</td>
                                <td>{{ $admin->role === 'super_admin' ? 'Super admin' : 'Administrateur' }}</td>
                                <td><span class="badge badge-{{ $admin->status === 'active' ? 'active' : 'pending' }}">{{ $admin->status === 'active' ? 'Actif' : 'En attente' }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
