<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Notifications</div>
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
                <div style="background:#dcfce7; color:#15803D; padding:14px 18px; border-radius:10px; margin-bottom:22px; font-size:14px; border:1px solid #bbf7d0;">{{ session('success') }}</div>
            @endif

            <div class="panel">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; flex-wrap:wrap; gap:10px;">
                    <h3 style="margin:0;">Toutes les notifications ({{ $notifications->count() }})</h3>
                    <form action="/notifications/read-all" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-light">Marquer tout comme lu</button>
                    </form>
                </div>

                @if ($notifications->isEmpty())
                    <div class="empty-state"><div class="icon">🔔</div><p>Aucune notification pour le moment</p><p style="font-size:13px;">Les actions effectuées depuis l'application mobile apparaîtront ici.</p></div>
                @else
                    @foreach ($notifications as $notif)
                    <div style="display:flex; gap:14px; padding:16px; border-radius:12px; margin-bottom:10px; background:{{ $notif->read_at ? '#FFFFFF' : '#FFFBEB' }}; border:1px solid {{ $notif->read_at ? '#F3F4F6' : '#FDE68A' }};">
                        <div style="width:42px; height:42px; border-radius:12px; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px;
                            @if ($notif->type === 'success') background:#dcfce7;
                            @elseif ($notif->type === 'warning') background:#FEF3C7;
                            @elseif ($notif->type === 'error') background:#fee2e2;
                            @else background:#dbeafe; @endif">
                            @if ($notif->type === 'success') ✅ @elseif ($notif->type === 'warning') ⚠️ @elseif ($notif->type === 'error') ❌ @else 📱 @endif
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; justify-content:space-between; align-items:center; gap:10px;">
                                <strong style="color:#172554; font-size:15px;">{{ $notif->titre }}</strong>
                                @if (!$notif->read_at)
                                    <span style="background:#D4A72C; color:#172554; font-size:11px; font-weight:bold; padding:2px 10px; border-radius:20px;">Nouveau</span>
                                @endif
                            </div>
                            <p style="color:#4A5568; font-size:14px; margin-top:4px;">{{ $notif->message }}</p>
                            <div style="display:flex; gap:15px; align-items:center; margin-top:8px;">
                                <span style="color:#9CA3AF; font-size:12px;">{{ $notif->created_at->format('d/m/Y H:i') }} · {{ $notif->created_at->diffForHumans() }}</span>
                                @if ($notif->source)
                                    <span style="background:#F3F4F6; color:#6B7280; font-size:11px; padding:2px 8px; border-radius:6px;">{{ $notif->source }}</span>
                                @endif
                                @if (!$notif->read_at)
                                    <form action="/notifications/{{ $notif->id }}/read" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" style="background:none; border:none; color:#D4A72C; font-size:12px; font-weight:bold; cursor:pointer;">Marquer comme lu</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        <footer class="footer"><div class="brand">Samuel App • Administration principale</div><div class="motto">Foi • Service • Engagement</div></footer>
    </div>
</body>
</html>
