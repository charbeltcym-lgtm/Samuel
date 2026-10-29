<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Samuel - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Modifier un Samuel</div>
            <div class="topbar-right">
                @include('samuel.dashboards.partials.notification-bell')
                <div class="profile">
                    @include('samuel.dashboards.partials.avatar')
                    <div class="info"><div class="name">{{ auth()->user()->name }}</div><div class="role">Administrateur principal</div></div>
                </div>
            </div>
        </header>
        <div class="content">
            <div class="panel" style="max-width:600px;">
                <h3>Modifier : {{ $samuel->nom_complet }}</h3>
                <p style="color:#6B7280; font-size:14px; margin-bottom:20px;">
                    Corrigez le nom ou les prénoms du Samuel. Ses présences sont conservées.
                </p>

                @if ($errors->any())
                    <div style="background:#fee2e2; color:#DC2626; padding:12px 16px; border-radius:10px; margin-bottom:20px; font-size:14px;">
                        @foreach ($errors->all() as $erreur)
                            <div>{{ $erreur }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('samuels.update', $samuel->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="{{ old('nom', $samuel->nom) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="prenoms">Prénoms</label>
                        <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms', $samuel->prenoms) }}" required>
                    </div>

                    <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:24px;">
                        <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Enregistrer les modifications</button>
                        <a href="/samuels" class="btn btn-light" style="padding:12px 20px;">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
        @include('samuel.dashboards.partials.footer')
    </div>
</body>
</html>
