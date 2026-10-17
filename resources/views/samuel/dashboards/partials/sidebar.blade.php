<aside class="sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('images/logo.png') }}" alt="Samuel App">
        <div>
            <div class="brand">Samuel App</div>
            <div class="motto">Ensemble pour un même service</div>
        </div>
    </div>
    <nav class="nav">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">📊 Vue d'ensemble</a>
        @php $unread = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count(); @endphp
        <a href="/notifications" class="{{ request()->is('notifications') ? 'active' : '' }}" style="display:flex; justify-content:space-between;">
            <span>🔔 Notifications</span>
            @if ($unread > 0)<span style="background:#DC2626; color:white; font-size:11px; font-weight:bold; padding:2px 8px; border-radius:20px;">{{ $unread }}</span>@endif
        </a>
        <div class="section-title">Gestion</div>
        <a href="/inscriptions" class="{{ request()->is('inscriptions') || request()->is('nouveau-samuel') ? 'active' : '' }}">📝 Inscriptions</a>
        <a href="/samuels" class="{{ request()->is('samuels') ? 'active' : '' }}">👥 Gestion des Samuels</a>
        <a href="/administrateurs" class="{{ request()->is('administrateurs') || request()->is('nouvel-admin') ? 'active' : '' }}">🛡️ Administrateurs</a>
        <div class="section-title">Formation</div>
        <a href="/chapitres" class="{{ request()->is('chapitres*') ? 'active' : '' }}">📖 Chapitres</a>
        <div class="section-title">Activités</div>
        <a href="/presences" class="{{ request()->is('presences') || request()->is('nouvelle-seance') ? 'active' : '' }}">✅ Présences</a>
        <a href="/historique" class="{{ request()->is('historique') ? 'active' : '' }}">🔒 Historique et sécurité</a>
        <div class="section-title">Compte</div>
        <a href="/parametres" class="{{ request()->is('parametres') ? 'active' : '' }}">⚙️ Paramètres du compte</a>
        <form action="{{ route('logout') }}" method="POST" style="margin-top:10px;">
            @csrf
            <button type="submit" style="width:100%; display:flex; align-items:center; gap:12px; color:#CBD5E1; background:none; border:none; padding:12px 15px; border-radius:10px; font-size:14px; cursor:pointer; font-family:inherit; text-align:left;">🚪 Déconnexion</button>
        </form>
    </nav>
    <div class="sidebar-footer">
        Connecté en tant que<br><strong style="color:#D4A72C">Super administrateur</strong>
    </div>
</aside>
