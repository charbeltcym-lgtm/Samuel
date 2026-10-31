<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Samuel - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
    <style>
        .form-actions { display: flex; gap: 10px; margin-top: 25px; flex-wrap: wrap; }
        @media (max-width: 600px) {
            .form-actions { flex-direction: column; }
            .form-actions .btn { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    @include('samuel.dashboards.partials.sidebar')

    <div class="main">
        <header class="topbar">
            <div class="page-title">Nouveau Samuel</div>
            <div class="topbar-right">
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info">
                        <div class="name">{{ auth()->user()->name }}</div>
                        <div class="role">Administrateur principal</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="content">
            <div class="panel" style="max-width: 600px;">
                <h3>Ajouter un Samuel</h3>

                @if (session('success'))
                    <div style="background:#dcfce7; color:#15803D; padding:12px; border-radius:8px; margin-bottom:20px; font-size:14px;">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="/nouveau-samuel" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" name="nom" id="nom" value="{{ old('nom') }}" required placeholder="Ex : TONOU">
                    </div>
                    <div class="form-group">
                        <label for="prenoms">Prénoms</label>
                        <input type="text" name="prenoms" id="prenoms" value="{{ old('prenoms') }}" required placeholder="Ex : Charbel">
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Ajouter le Samuel</button>
                        <a href="/samuels" class="btn btn-light" style="padding:12px 28px; font-size:15px;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

        @include('samuel.dashboards.partials.footer')
    </div>

</body>
</html>
