<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscriptions - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
    <style>
        .inscription-actions { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
        .inscription-form { display: flex; gap: 6px; align-items: center; }
        @media (max-width: 600px) {
            .inscription-actions, .inscription-form { flex-direction: column; align-items: stretch; width: 100%; }
            .inscription-actions .btn, .inscription-form .btn, .inscription-form select { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Inscriptions</div>
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
                <div class="page-head">
                    <h3>Demandes d'inscription ({{ $inscriptions->count() }})</h3>
                    <a href="/nouveau-samuel" class="btn btn-gold">+ Nouveau Samuel</a>
                </div>
                @if ($inscriptions->isEmpty())
                    <div class="empty-state"><div class="icon">📝</div><p>Aucune demande d'inscription pour le moment</p></div>
                @else
                    <div class="table-wrap">
                    <table>
                        <thead><tr><th>Nom</th><th>Email</th><th>Date</th><th>Statut</th><th>Actions</th></tr></thead>
                        <tbody>
                            @foreach ($inscriptions as $inscription)
                            <tr>
                                <td><strong>{{ $inscription->name }}</strong></td>
                                <td style="word-break:break-all;">{{ $inscription->email }}</td>
                                <td>{{ $inscription->created_at->format('d/m/Y') }}</td>
                                <td><span class="badge badge-pending">En attente</span></td>
                                <td>
                                    <div class="inscription-actions">
                                        <form action="{{ route('inscriptions.valider', $inscription->id) }}" method="POST" onsubmit="return confirm('Valider l\'inscription de {{ $inscription->name }} avec le role selectionne et generer le code d\'activation ?');" class="inscription-form">
                                            @csrf
                                            <select name="role" style="padding:5px 8px; border:1px solid #E5E7EB; border-radius:8px; font-size:13px; background:#fff;">
                                                <option value="user">Simple Samuel</option>
                                                <option value="admin">Administrateur</option>
                                            </select>
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
                    </div>
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
