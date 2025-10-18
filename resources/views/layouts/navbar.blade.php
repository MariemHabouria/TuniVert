<!-- Navbar start -->
<div class="container-fluid fixed-top px-0">
    <div class="container px-0">
        <!-- Topbar -->
        <div class="topbar">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8">
                    <div class="topbar-info d-flex flex-wrap">
                        <a href="mailto:Tunivert@gmail.tn" class="text-light me-4">
                            <i class="fas fa-envelope text-white me-2"></i>Tunivert@gmail.tn
                        </a>
                        <a href="tel:+21612345678" class="text-light">
                            <i class="fas fa-phone-alt text-white me-2"></i>+216 12 345 678
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="topbar-icon d-flex align-items-center justify-content-end">
                        <a href="#" class="btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
                        <a href="#" class="btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-light bg-light navbar-expand-xl">
            <a href="{{ route('home') }}" class="navbar-brand ms-3">
                <h1 class="text-primary display-5">Tunivert</h1>
            </a>
            <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                <div class="navbar-nav ms-auto">
                    <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a>
                    <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->routeIs('about') ? 'active' : '' }}">À propos</a>
                    <a href="{{ route('events.browse') }}" class="nav-item nav-link {{ request()->routeIs('events.browse') ? 'active' : '' }}">Événements</a>
                    <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->routeIs('service') ? 'active' : '' }}">Formations</a>
                    <a href="{{ route('causes') }}" class="nav-item nav-link {{ request()->routeIs('causes') ? 'active' : '' }}">Donations</a>
                    <a href="{{ route('forums.index') }}" 
                       class="nav-item nav-link {{ request()->is('forums*') ? 'active' : '' }}">
                       Forums
                    </a>
                    <a href="{{ route('alertes.index') }}" 
                       class="nav-item nav-link {{ request()->is('alertes*') ? 'active' : '' }}">
                       Alertes
                    </a>
                    <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>

                    <!-- Partie Challenge - Toujours visible -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('challenges*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            Challenges
                        </a>
                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                            @auth
                                @if(Auth::user()->role === 'association')
                                    <a href="{{ route('challenges.create') }}" class="dropdown-item {{ request()->routeIs('challenges.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus me-2"></i>Créer un Challenge
                                    </a>
                                    <a href="{{ route('challenges.crud') }}" class="dropdown-item {{ request()->routeIs('challenges.crud') ? 'active' : '' }}">
                                        <i class="fas fa-cog me-2"></i>Gérer mes Challenges
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('scores.classement', ['challenge' => 'current']) }}" class="dropdown-item">
                                        <i class="fas fa-chart-bar me-2"></i>Statistiques
                                    </a>
                                @else
                                    <a href="{{ route('challenges.index') }}" class="dropdown-item">
                                        <i class="fas fa-trophy me-2"></i>Voir les Challenges
                                    </a>
                                    <a href="{{ route('challenges.profil') }}" class="dropdown-item">
                                        <i class="fas fa-user-check me-2"></i>Mes Participations
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('challenges.index') }}" class="dropdown-item">
                                    <i class="fas fa-trophy me-2"></i>Voir les Challenges
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Partie Formations -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('formations*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            Formations
                        </a>
                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                            <a href="{{ route('formations.index') }}" class="dropdown-item">Catalogue</a>
                            @auth
                                <a href="{{ route('formations.create') }}" class="dropdown-item {{ request()->routeIs('formations.create') ? 'active' : '' }}">Créer une formation</a>
                                <a href="{{ route('formations.dashboard') }}" class="dropdown-item {{ request()->routeIs('formations.dashboard') ? 'active' : '' }}">Mes formations</a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Authentification -->
                <div class="d-flex align-items-center flex-nowrap pt-xl-0 ms-3">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Connexion</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Inscription</a>
                    @endguest

                    @auth
                        <!-- 🔔 NOTIFICATIONS DROPDOWN -->
                        <div class="nav-item dropdown me-3">
                            <a class="nav-link dropdown-toggle p-0 position-relative" href="#" id="notificationsMenu" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" title="Notifications">
                                <i class="fas fa-bell fa-lg text-primary"></i>
                                @php
                                    $notificationCount = Auth::user()->unreadNotifications->count();
                                @endphp
                                @if($notificationCount > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                          style="font-size: 0.6rem;">
                                        {{ $notificationCount }}
                                    </span>
                                @endif
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="notificationsMenu" style="min-width: 320px;">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">🔔 Notifications</h6>
                                        @if($notificationCount > 0)
                                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary py-0" style="font-size: 0.7rem;">
                                                    Tout marquer comme lu
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </li>
                                
                                @forelse(Auth::user()->unreadNotifications->take(5) as $notification)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2" 
                                           href="{{ $notification->data['url'] ?? '#' }}"
                                           onclick="markNotificationAsRead('{{ $notification->id }}')">
                                            <div class="me-3 fs-6">
                                                {!! $notification->data['icon'] ?? '🔔' !!}
                                            </div>
                                            <div class="flex-grow-1">
                                                <small class="d-block text-dark">{{ $notification->data['message'] ?? 'Nouvelle notification' }}</small>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                @empty
                                    <li class="px-3 py-2 text-center">
                                        <small class="text-muted">Aucune notification</small>
                                    </li>
                                @endforelse
                                
                                @if($notificationCount > 5)
                                    <li class="text-center">
                                        <a class="dropdown-item small py-2" href="{{ route('notifications.index') }}">
                                            Voir toutes les notifications ({{ $notificationCount }})
                                        </a>
                                    </li>
                                @elseif($notificationCount > 0)
                                    <li class="text-center">
                                        <a class="dropdown-item small py-2" href="{{ route('notifications.index') }}">
                                            Voir toutes les notifications
                                        </a>
                                    </li>
                                @else
                                    <li class="text-center">
                                        <a class="dropdown-item small py-2" href="{{ route('notifications.index') }}">
                                            Historique des notifications
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- PROFIL UTILISATEUR -->
                        <div class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle p-0" href="#" id="userMenu" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" title="Mon compte">
                                <span class="avatar bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                      style="width:38px;height:38px;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                                @if(Auth::user()->role === 'association')
                                    <small class="text-muted d-block" style="font-size: 0.7rem;">Association</small>
                                @endif
                            </a>

                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu" style="min-width: 240px;">
                                <li class="px-3 py-2">
                                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                    <div class="small text-muted">{{ Auth::user()->email }}</div>
                                    @if(Auth::user()->role === 'association')
                                        <span class="badge bg-primary mt-1">Association</span>
                                    @endif
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile') }}">
                                        <i class="fas fa-user"></i>
                                        Profil
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('notifications.index') }}">
                                        <i class="fas fa-bell"></i>
                                        Mes Notifications
                                        @if($notificationCount > 0)
                                            <span class="badge bg-danger ms-auto">{{ $notificationCount }}</span>
                                        @endif
                                    </a>
                                </li>

                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('alertes.create') }}">
                                        <i class="fas fa-plus-circle"></i>
                                        Créer une alerte
                                    </a>
                                </li>

                                @if(Auth::user()->role === 'association')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('challenges.create') }}">
                                        <i class="fas fa-plus"></i>
                                        Créer un Challenge
                                    </a>
                                </li>
                                @else
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('challenges.profil') }}">
                                        <i class="fas fa-trophy"></i>
                                        Mes Participations
                                    </a>
                                </li>
                                @endif

                                <li><hr class="dropdown-divider"></li>

                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100">
                                            <i class="fas fa-sign-out-alt"></i>
                                            Se déconnecter
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- Script pour les notifications -->
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
            // Recharger pour mettre à jour le compteur
            setTimeout(() => location.reload(), 300);
        }
    });
}

// Actualiser le compteur de notifications toutes les 60 secondes
setInterval(() => {
    if (document.querySelector('.navbar-nav')) {
        // Rechargement simple pour mettre à jour le compteur
        // Vous pouvez implémenter une actualisation AJAX plus tard
        const currentPath = window.location.pathname;
        if (!currentPath.includes('notifications')) {
            // Ne pas recharger si on est sur la page des notifications
            // location.reload();
        }
    }
}, 60000);
</script>