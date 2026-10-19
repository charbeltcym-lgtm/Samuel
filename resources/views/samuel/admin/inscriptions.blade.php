<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Inscriptions</div>
            <div class="topbar-right">
                <div class="bell">🔔<span class="dot"></span></div>
                <div class="profile">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            @if (session('success'))
                <div style="background:#dcfce7; color:#15803D; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px; word-break:break-word;">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div style="background:#fee2e2; color:#DC2626; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif
            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">Demandes d'inscription ({{ $inscriptions->count() }})</h3>
                    <a href="/nouveau-samuel" class="btn btn-gold">+ Nouveau Samuel</a>
                </div>
                @if ($inscriptions->isEmpty())
                    <div class="empty-state"><div class="icon">📝</div><p>Aucune demande d'inscription pour le moment</p></div>
                @else
                    <table>
                        <thead><tr><th>Nom</th><th>Email</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach ($inscriptions as $inscription)
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
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
