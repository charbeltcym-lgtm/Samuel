@extends('samuel.espace.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap;">
    <a href="{{ route('espace') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#172554; font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
    <div style="display:flex; gap:8px; flex-wrap:wrap;">
        <a href="{{ route('samuels.liste.pdf') }}" class="btn btn-light">Télécharger la liste</a>
        <a href="{{ route('espace.samuels.create') }}" class="btn btn-gold">+ Ajouter</a>
    </div>
</div>

<div class="card">
    <div class="card-pad" style="display:flex; align-items:center; gap:10px;">
        <div style="width:4px; height:22px; background:var(--gold); border-radius:2px;"></div>
        <div style="font-weight:700; color:#172554; font-size:17px;">Liste des Samuels</div>
    </div>
    @forelse($samuels as $samuel)
        <div class="list-item">
            <div>
                <div style="font-weight:700; text-transform:uppercase;">{{ $samuel->nom }}</div>
                <div style="color:#6b7280;">{{ $samuel->prenoms }}</div>
            </div>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('espace.samuels.edit', $samuel->id) }}" class="btn btn-light" style="padding:8px 12px;">Modifier</a>
                <form method="POST" action="{{ route('espace.samuels.destroy', $samuel->id) }}" onsubmit="return confirm('Supprimer {{ $samuel->nom_complet }} ?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn" style="background:#fee2e2; color:#b91c1c; padding:8px 12px;">Supprimer</button>
                </form>
            </div>
        </div>
    @empty
        <div class="card-pad" style="text-align:center; color:#6b7280;">Aucun Samuel enregistré</div>
    @endforelse
</div>
@endsection
