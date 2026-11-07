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
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            @if (session('success'))
                <div style="background:#dcfce7; color:#15803D; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">{{ session('success') }}</div>
            @endif
            @if ($errors->any())
                <div style="background:#fee2e2; color:#DC2626; padding:14px 16px; border-radius:10px; margin-bottom:18px; font-size:14px;">
                    @foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
            @endif

            <div class="panel" style="margin-bottom:18px;">
                <h3 style="margin-bottom:14px;">Supprimer l'historique</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:14px;">
                    <form method="POST" action="{{ route('historique.vider-periode') }}" onsubmit="return confirm('Supprimer les entrées de la période sélectionnée ?');" style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:12px; padding:14px;">
                        @csrf
                        <div style="font-weight:600; color:#172554; margin-bottom:10px;">Par période</div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            <label style="flex:1; min-width:120px; font-size:12px; color:#6B7280;">Du
                                <input type="date" name="date_debut" value="{{ old('date_debut') }}" style="width:100%; margin-top:4px; padding:8px 10px; border:1px solid #D1D5DB; border-radius:8px;">
                            </label>
                            <label style="flex:1; min-width:120px; font-size:12px; color:#6B7280;">Au
                                <input type="date" name="date_fin" value="{{ old('date_fin') }}" style="width:100%; margin-top:4px; padding:8px 10px; border:1px solid #D1D5DB; border-radius:8px;">
                            </label>
                        </div>
                        <button type="submit" class="btn" style="margin-top:10px; background:#EA580C; color:#fff; border:none; padding:8px 14px; border-radius:8px; font-weight:600; cursor:pointer;">Supprimer la période</button>
                    </form>
                    <form method="POST" action="{{ route('historique.vider-tout') }}" onsubmit="return confirm('Vider TOUT l\\'historique ? Cette action est irréversible.');" style="background:#FEF2F2; border:1px solid #FECACA; border-radius:12px; padding:14px;">
                        @csrf
                        <div style="font-weight:600; color:#991B1B; margin-bottom:6px;">Tout supprimer</div>
                        <div style="font-size:13px; color:#7F1D1D; margin-bottom:10px;">Supprime toutes les entrées du journal d'activité.</div>
                        <button type="submit" class="btn" style="background:#DC2626; color:#fff; border:none; padding:8px 14px; border-radius:8px; font-weight:600; cursor:pointer;">Vider tout l'historique</button>
                    </form>
                </div>
            </div>

            <div class="panel">
                <h3>Journal d'activité ({{ $activites->count() }})</h3>
                @if ($activites->isEmpty())
                    <div class="empty-state"><div class="icon">🔒</div><p>Aucune activité enregistrée</p></div>
                @else
                    @foreach ($activites as $activite)
                    <div style="display:flex; gap:12px; padding:12px 0; border-bottom:1px solid #F3F4F6; align-items:flex-start;">
                        <div style="width:36px; height:36px; border-radius:10px; background:#EEF2FF; display:flex; align-items:center; justify-content:center; flex-shrink:0;">📌</div>
                        <div style="flex:1; min-width:0;">
                            <div style="font-size:14px; color:#374151;">{{ $activite->description }}</div>
                            <div style="font-size:12px; color:#9CA3AF; margin-top:2px;">{{ $activite->created_at->format('d/m/Y H:i') }}@if($activite->user) · {{ $activite->user->name }}@endif</div>
                        </div>
                        <form method="POST" action="{{ route('historique.destroy', $activite->id) }}" onsubmit="return confirm('Supprimer cette entrée ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Supprimer" style="background:#FEE2E2; color:#B91C1C; border:none; width:34px; height:34px; border-radius:8px; cursor:pointer; font-size:16px; line-height:1;">×</button>
                        </form>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
