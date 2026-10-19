@php($utilisateur = auth()->user())
@if ($utilisateur && $utilisateur->photo)
    <img class="avatar" src="{{ asset('storage/' . $utilisateur->photo) }}" alt="{{ $utilisateur->name }}">
@else
    <div class="avatar">{{ strtoupper(substr($utilisateur->name ?? '?', 0, 1)) }}</div>
@endif
