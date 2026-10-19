<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel administrateur - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Nouvel administrateur</div>
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
                <h3>Ajouter un administrateur</h3>
                @if (session('success'))
                    <div style="background:#dcfce7; color:#15803D; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">{{ session('success') }}</div>
                @endif
                <form action="/nouvel-admin" method="POST">
                    @csrf
                    <div class="form-group"><label>Nom complet</label><input type="text" name="name" value="{{ old('name') }}" required placeholder="Ex : Marie Claire"></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required placeholder="Ex : marie@example.com"></div>
                    <div class="form-group"><label>Mot de passe</label><input type="password" name="password" required placeholder="Mot de passe initial"></div>
                    <div style="display:flex; gap:10px; margin-top:25px;">
                        <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Ajouter</button>
                        <a href="/administrateurs" class="btn btn-light" style="padding:12px 28px; font-size:15px;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
