@extends('samuel.espace.layout')

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between;">
    <a href="{{ route('espace') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#172554; font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
    <form method="GET" action="{{ route('espace.presence') }}" style="display:flex; gap:8px; align-items:center;">
        <input type="date" name="date" value="{{ $dateIso }}" class="input" style="width:auto;">
        <button class="btn btn-light" style="padding:10px 14px;">Afficher</button>
    </form>
</div>

<div class="card">
    <div class="card-pad">
        <div style="font-weight:700; color:#172554; font-size:18px;">Présence du {{ $date->translatedFormat('l j F Y') }}</div>
        <div style="color:#6b7280; font-size:13px;">Cochez le statut pour chaque Samuel</div>
    </div>
    <form method="POST" action="{{ route('espace.presence.store') }}">
        @csrf
        <input type="hidden" name="date_repetition" value="{{ $dateIso }}">
        <div class="table-row header">
            <div>Samuel</div>
            <div style="text-align:center;">Présent</div>
            <div style="text-align:center;">Absent</div>
            <div style="text-align:center;">Retard</div>
            <div style="text-align:center;">Permissionnaire</div>
        </div>
        @foreach($samuels as $samuel)
            @php $statut = $presences[$samuel->id]->statut ?? null; @endphp
            <div class="table-row">
                <div>
                    <div style="font-weight:700; text-transform:uppercase;">{{ $samuel->nom }}</div>
                    <div style="color:#6b7280; font-size:13px;">{{ $samuel->prenoms }}</div>
                </div>
                @foreach(['present','absent','retard','permissionnaire'] as $code)
                    <label style="display:flex; justify-content:center; cursor:pointer;">
                        <input type="radio" name="statuts[{{ $samuel->id }}]" value="{{ $code }}" {{ $statut === $code ? 'checked' : '' }} style="display:none;">
                        @php
                            $icons = ['present'=>'M9 11l3 3L22 4M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11','absent'=>'M18 6L6 18M6 6l12 12','retard'=>'M12 6v6l4 2M12 22a10 10 0 1 0 0-20 10 10 0 0 0 0 20z','permissionnaire'=>'M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z'];
                        @endphp
                        <span class="pill {{ $statut === $code ? 'active-'.$code : '' }}">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                @if($code==='present')<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>
                                @elseif($code==='absent')<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                @elseif($code==='retard')<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                @else<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                @endif
                            </svg>
                        </span>
                    </label>
                @endforeach
            </div>
        @endforeach
        <div style="padding:16px;">
            <button class="btn btn-green" style="width:100%;">Enregistrer la présence</button>
        </div>
    </form>
</div>
@endsection
