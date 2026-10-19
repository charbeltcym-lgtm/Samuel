<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique et sécurité - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Historique et sécurité</div>
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
                <h3>Journal d'activité</h3>
                @if ($activites->isEmpty())
                    <div class="empty-state"><div class="icon">🔒</div><p>Aucune activité enregistrée</p></div>
                @else
                    @foreach ($activites as $activite)
                    <div style="display:flex; gap:12px; padding:12px 0; border-bottom:1px solid #F3F4F6;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">📌</div>
                        <div>
                            <div style="font-size:14px; color:#374151;">{{ $activite->description }}</div>
                            <div style="font-size:12px; color:#9CA3AF; margin-top:2px;">{{ $activite->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
