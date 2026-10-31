@extends('samuel.espace.layout')

@section('content')
<div class="card card-pad">
    <div style="display:flex; align-items:center; gap:12px;">
        <div style="width:54px; height:54px; border-radius:999px; background:linear-gradient(135deg,#1E3A8A,#172554); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">
            {{ strtoupper(mb_substr($user->name,0,1)) }}
        </div>
        <div>
            <div style="color:#6b7280; font-size:13px;">Bienvenue</div>
            <div style="font-size:18px; font-weight:700; color:#172554;">{{ $user->name }}</div>
            <div style="font-size:12px; color:#6b7280;">{{ $user->role === 'super_admin' ? 'Super administrateur' : 'Administrateur' }}</div>
        </div>
    </div>
</div>

<div class="grid-3">
    <a class="action" href="{{ route('espace.samuels.create') }}">
        <div class="action-icon" style="background:rgba(212,167,44,.18);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#172554" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg></div>
        <span>Ajouter<br>un Samuel</span>
    </a>
    <a class="action" href="{{ route('espace.samuels') }}">
        <div class="action-icon" style="background:rgba(23,37,84,.12);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#172554" stroke-width="1.8"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <span>Liste des<br>Samuels</span>
    </a>
    <a class="action" href="{{ route('espace.presence') }}">
        <div class="action-icon" style="background:rgba(21,128,61,.16);"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#166534" stroke-width="1.8"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg></div>
        <span>Faire la<br>présence</span>
    </a>
</div>

<div class="card">
    <div class="card-pad" style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:22px; background:var(--gold); border-radius:2px;"></div>
        <div style="font-weight:700; color:#172554; font-size:17px;">Chapitres</div>
    </div>
    @foreach([1=>'Histoire du Samuel et prière du servant',2=>'Comportements, qualités et attributs des Samuels',3=>'L\'Assemblée liturgique',4=>'Étude des objets sacrés',5=>'Rôle du polyvalent dans la messe',6=>'Porte-croix',7=>'Rôle du porte-croix dans les différents types de messes',8=>'Importance du Samuel dans l\'Église et surtout la nature',9=>'Pratiques'] as $num => $titre)
        <a class="list-item" href="{{ route('espace.chapitre', $num) }}">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:44px; height:44px; border-radius:14px; background:#172554; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">{{ $num }}</div>
                <div>
                    <div style="font-weight:600;">Chapitre {{ $num }}</div>
                    <div style="font-size:13px; color:#6b7280;">{{ $titre }}</div>
                </div>
            </div>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    @endforeach
</div>
@endsection
