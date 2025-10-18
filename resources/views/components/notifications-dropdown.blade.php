@php
    $notifications = Auth::user()->unreadNotifications ?? collect();
    $notificationCount = $notifications->count();
@endphp

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        🔔 
        @if($notificationCount > 0)
            <span class="badge bg-danger">{{ $notificationCount }}</span>
        @endif
    </a>
    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
        <li><h6 class="dropdown-header">Notifications</h6></li>
        
        @forelse($notifications->take(5) as $notification)
            <li>
                <a class="dropdown-item d-flex align-items-center py-2" 
                   href="{{ $notification->data['url'] ?? '#' }}"
                   onclick="markNotificationAsRead('{{ $notification->id }}')">
                    <div class="me-2">{!! $notification->data['icon'] ?? '🔔' !!}</div>
                    <div>
                        <small class="d-block">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</small>
                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
        @empty
            <li><a class="dropdown-item text-muted">Aucune notification</a></li>
        @endforelse
        
        @if($notificationCount > 5)
            <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">Voir toutes ({{ $notificationCount }})</a></li>
        @elseif($notificationCount > 0)
            <li><a class="dropdown-item text-center small" href="{{ route('notifications.index') }}">Voir toutes</a></li>
        @endif
    </ul>
</li>

<script>
function markNotificationAsRead(notificationId) {
    fetch('/notifications/' + notificationId + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(response => {
        // La notification sera marquée comme lue
    });
}
</script>