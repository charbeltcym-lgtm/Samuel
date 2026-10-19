@php
    $__notifCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
@endphp
<a href="/notifications" class="notif-bell" title="Notifications" aria-label="Notifications">
    <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
    </svg>
    @if ($__notifCount > 0)
        <span class="notif-badge">{{ $__notifCount > 99 ? '99+' : $__notifCount }}</span>
    @endif
</a>
