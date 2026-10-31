@extends('samuel.espace.layout')

@section('content')
<div style="display:flex; align-items:center; gap:8px;">
    <a href="{{ route('espace.samuels') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#172554; font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
</div>

<div class="card card-pad">
    <div style="font-weight:700; color:#172554; font-size:18px; margin-bottom:14px;">Modifier un Samuel</div>
    <form method="POST" action="{{ route('espace.samuels.update', $samuel->id) }}">
        @csrf
        @method('PUT')
        <div style="display:flex; flex-direction:column; gap:12px;">
            <div>
                <label style="font-size:13px; color:#374151;">Nom</label>
                <input class="input" name="nom" value="{{ old('nom', $samuel->nom) }}" required>
            </div>
            <div>
                <label style="font-size:13px; color:#374151;">Prénoms</label>
                <input class="input" name="prenoms" value="{{ old('prenoms', $samuel->prenoms) }}" required>
            </div>
            <button class="btn btn-navy">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
