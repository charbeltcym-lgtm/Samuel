<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres du compte - Samuel App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('samuel.dashboards.partials.styles')
</head>
<body>
    @include('samuel.dashboards.partials.sidebar')
    <div class="main">
        <header class="topbar">
            <div class="page-title">Paramètres du compte</div>
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
                <div style="background:#dcfce7; color:#15803D; padding:14px 18px; border-radius:10px; margin-bottom:22px; font-size:14px; max-width:900px; border:1px solid #bbf7d0;">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div style="background:#fee2e2; color:#DC2626; padding:14px 18px; border-radius:10px; margin-bottom:22px; font-size:14px; max-width:900px; border:1px solid #fecaca;">
                    <ul style="margin-left:18px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Photo de profil -->
            <div class="panel" style="max-width:900px;">
                <h3>📷 Photo de profil</h3>
                <div style="display:flex; align-items:center; gap:30px; flex-wrap:wrap;">
                    <div style="text-align:center;">
                        @if (auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" alt="Photo de profil" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border:4px solid #D4A72C;">
                        @else
                            <div style="width:120px; height:120px; border-radius:50%; background:#D4A72C; color:#172554; display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:bold; border:4px solid #e2e8f0;">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <p style="color:#9CA3AF; font-size:12px; margin-top:8px;">Photo actuelle</p>
                    </div>
                    <form action="/parametres/photo" method="POST" enctype="multipart/form-data" style="flex:1; min-width:250px;">
                        @csrf
                        <div class="form-group">
                            <label>Choisir une nouvelle photo</label>
                            <input type="file" name="photo" accept="image/*" required style="padding:10px; background:#F9FAFB;">
                            <p style="color:#9CA3AF; font-size:12px; margin-top:6px;">Formats acceptés : JPG, PNG, GIF. Taille max : 2 Mo.</p>
                        </div>
                        <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Téléverser la photo</button>
                    </form>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:25px; max-width:900px; align-items:start;">

                <!-- Informations personnelles -->
                <div class="panel" style="margin-bottom:0;">
                    <h3>👤 Informations personnelles</h3>
                    <form action="/parametres" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Rôle</label>
                            <input type="text" value="{{ auth()->user()->role === 'super_admin' ? 'Super administrateur' : (auth()->user()->role === 'admin' ? 'Administrateur' : 'Samuel') }}" disabled style="background:#F3F4F6; color:#6B7280;">
                        </div>
                        <button type="submit" class="btn btn-gold" style="padding:12px 28px; font-size:15px;">Enregistrer les modifications</button>
                    </form>
                </div>

                <!-- Mot de passe -->
                <div class="panel" style="margin-bottom:0;">
                    <h3>🔒 Changer le mot de passe</h3>
                    <form action="/parametres" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" name="current_password" required placeholder="Votre mot de passe actuel">
                        </div>
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" required placeholder="Minimum 8 caractères">
                        </div>
                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" required placeholder="Retapez le nouveau mot de passe">
                        </div>
                        <button type="submit" class="btn btn-navy" style="padding:12px 28px; font-size:15px;">Changer le mot de passe</button>
                    </form>
                </div>

            </div>

        </div>

        @include('samuel.dashboards.partials.footer')
    </div>

    <script>
        document.querySelectorAll('form').forEach(function(form) {
            if (form.querySelector('input[name="current_password"]')) {
                form.addEventListener('submit', function(e) {
                    if (!confirm('Voulez-vous vraiment changer votre mot de passe ?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>

</body>
</html>
