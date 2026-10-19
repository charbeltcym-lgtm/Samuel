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
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            @if (session('success'))
                <div style="background:#dcfce7; color:#15803D; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">
                    {{ session('success') }}
                </div>
            @endif
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">Liste des Samuels ({{ $samuels->count() }})</h3>
                    <a href="/nouveau-samuel" class="btn btn-gold">+ Nouveau Samuel</a>
                </div>
                @if ($samuels->isEmpty())
                    <div class="empty-state"><div class="icon">👥</div><p>Aucun Samuel inscrit pour le moment</p></div>
                @else
                    <table>
                        <thead><tr><th>Nom</th><th>Prénoms</th><th>Ajouté le</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach ($samuels as $samuel)
                            <tr>
                                <td><strong>{{ $samuel->nom }}</strong></td>
                                <td>{{ $samuel->prenoms }}</td>
                                <td>{{ $samuel->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <form action="{{ route('samuels.destroy', $samuel->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement {{ $samuel->nom_complet }} et ses présences ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-red" style="padding:5px 10px;">Supprimer</button>
                                    </form>
                                </td>
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
