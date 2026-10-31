@extends('samuel.espace.layout')

@section('content')
<div style="display:flex; align-items:center; gap:8px;">
    <a href="{{ route('espace') }}" style="display:flex; align-items:center; gap:8px; text-decoration:none; color:#172554; font-weight:600;">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="15 18 9 12 15 6"/></svg>
        Retour
    </a>
</div>

<div class="card">
    <div class="card-pad" style="display:flex; align-items:center; gap:10px;">
        <div style="width:44px; height:44px; border-radius:14px; background:#172554; color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">{{ $numero }}</div>
        <div>
            <div style="font-weight:700; color:#172554; font-size:18px;">Chapitre {{ $numero }}</div>
            <div style="color:#6b7280;">{{ $titre }}</div>
        </div>
    </div>
    <div class="card-pad" style="line-height:1.7; color:#1f2937;">
        @if($contenu)
            {!! $contenu !!}
        @else
            <p style="color:#6b7280;">Contenu du chapitre {{ $numero }} en cours de rédaction. Ce chapitre sera accessible dès qu'il sera disponible.</p>
        @endif
    </div>
</div>
@endsection
