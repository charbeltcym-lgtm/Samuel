<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle séance - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Nouvelle séance</div>
            <div class="topbar-right">
                <div class="bell">🔔<span class="dot"></span></div>
                <div class="profile">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="panel" style="max-width:600px;">
                <h3>Créer une séance de répétition</h3>
                <p style="color:#6B7280; font-size:14px; margin-bottom:20px;">Les répétitions ont lieu tous les samedis.</p>
                @if (session('success'))
                    <div style="background:#dcfce7; color:#15803D; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('success') }}</div>
                @endif
                <form action="/nouvelle-seance" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Date du samedi</label>
                        <input type="date" name="date_repetition" value="{{ old('date_repetition') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Thème / Observation</label>
                        <textarea name="remarque" rows="3" placeholder="Ex : Répétition générale">{{ old('remarque') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Créer la séance</button>
                </form>
            </div>
        </div>
        <footer class="footer"><div class="brand">Samuel App • Administration principale</div><div class="motto">Foi • Service • Engagement</div></footer>
    </div>
</body>
</html>
