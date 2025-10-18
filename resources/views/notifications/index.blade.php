@extends('layouts.app')

@section('title', 'Mes Notifications')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">🔔 Mes Notifications</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Notifications</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            <!-- Actions globales -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <a href="{{ route('notifications.test') }}" class="btn btn-outline-info btn-sm">
                        🧪 Tester les notifications
                    </a>
                </div>
                <div>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                    <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            📪 Tout marquer comme lu
                        </button>
                    </form>
                    @endif
                    
                    @if(auth()->user()->notifications->count() > 0)
                    <form action="{{ route('notifications.destroy-all') }}" method="POST" class="d-inline" 
                          onsubmit="return confirm('Supprimer toutes les notifications ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            🗑️ Tout supprimer
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Statistiques -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body py-3">
                            <h3 class="mb-0">{{ auth()->user()->notifications->count() }}</h3>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-warning text-dark">
                        <div class="card-body py-3">
                            <h3 class="mb-0">{{ auth()->user()->unreadNotifications->count() }}</h3>
                            <small>Non lues</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center bg-success text-white">
                        <div class="card-body py-3">
                            <h3 class="mb-0">{{ auth()->user()->readNotifications->count() }}</h3>
                            <small>Lues</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liste des notifications -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">📬 Historique des notifications</h5>
                </div>
                
                <div class="card-body p-0">
                    @forelse($notifications as $notification)
                        <div class="border-bottom p-4 {{ $notification->read() ? '' : 'bg-light' }}" 
                             id="notification-{{ $notification->id }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-2">
                                        <span class="me-3 fs-5">
                                            {!! $notification->data['icon'] ?? '🔔' !!}
                                        </span>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 {{ $notification->read() ? 'text-muted' : 'text-dark fw-bold' }}">
                                                {{ $notification->data['message'] ?? 'Notification' }}
                                            </h6>
                                            @if(isset($notification->data['details']))
                                                <p class="mb-1 text-muted small">{{ $notification->data['details'] }}</p>
                                            @endif
                                            <small class="text-muted">
                                                {{ $notification->created_at->format('d/m/Y à H:i') }}
                                                ({{ $notification->created_at->diffForHumans() }})
                                            </small>
                                        </div>
                                    </div>
                                    
                                    @if(isset($notification->data['url']))
                                    <div class="mt-2">
                                        <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary me-2">
                                            👁️ Voir les détails
                                        </a>
                                        
                                        @if(!$notification->read())
                                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                ✓ Marquer comme lu
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Supprimer cette notification ?')">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <span class="display-1 text-muted">🔔</span>
                            </div>
                            <h5 class="text-muted">Aucune notification</h5>
                            <p class="text-muted">Vous serez notifié lorsqu'il y aura de nouvelles activités.</p>
                            <div class="mt-3">
                                <a href="{{ route('alertes.index') }}" class="btn btn-primary me-2">
                                    📢 Voir les alertes
                                </a>
                                <a href="{{ route('notifications.test') }}" class="btn btn-outline-secondary">
                                    🧪 Tester les notifications
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                @if($notifications->hasPages())
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                Affichage de {{ $notifications->firstItem() }} à {{ $notifications->lastItem() }} 
                                sur {{ $notifications->total() }} notifications
                            </small>
                        </div>
                        <div>
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function markNotificationAsRead(notificationId) {
    fetch('/notifications/' + notificationId + '/read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            // Mettre à jour l'affichage sans recharger
            const notificationElement = document.getElementById('notification-' + notificationId);
            if (notificationElement) {
                notificationElement.classList.remove('bg-light');
                notificationElement.querySelector('h6').classList.remove('fw-bold');
                notificationElement.querySelector('h6').classList.add('text-muted');
                
                // Cacher le bouton "Marquer comme lu"
                const markAsReadBtn = notificationElement.querySelector('form[action*="/read"]');
                if (markAsReadBtn) {
                    markAsReadBtn.style.display = 'none';
                }
            }
        }
    });
}

// Mettre à jour les statistiques toutes les 30 secondes
setInterval(() => {
    // Vous pouvez implémenter une actualisation AJAX ici
}, 30000);
</script>
@endsection