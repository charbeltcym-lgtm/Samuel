@php($utilisateur = auth()->user())
@if ($utilisateur && $utilisateur->photo_url)
    <img class="avatar" src="{{ $utilisateur->photo_url }}" alt="{{ $utilisateur->name }}">
@else
    <div class="avatar">{{ strtoupper(substr($utilisateur->name ?? '?', 0, 1)) }}</div>
@endif
