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
                        <a href="#" class="btn-square text-white me-2"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navbar -->
        <nav class="navbar navbar-light bg-light navbar-expand-xl py-0">
            <div class="container-fluid">
                <a href="{{ route('home') }}" class="navbar-brand ms-2">
                    <h1 class="text-primary display-5 mb-0">Tunivert</h1>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>

                <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                    <div class="navbar-nav mx-auto flex-grow-1 justify-content-center">
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
<!-- Donations Dropdown -->
<div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle {{ request()->is('donations*') || request()->is('donation') ? 'active' : '' }}" data-bs-toggle="dropdown">
        Donations
    </a>
    <div class="dropdown-menu m-0 bg-secondary rounded-0">
        @auth
            @if(Auth::user()->role === 'association')
                <!-- Association Dashboard - Only for association users -->
                <a href="{{ route('donations.dashboard') }}" class="dropdown-item bg-primary text-white {{ request()->routeIs('donations.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i><strong>Dashboard Association</strong>
                    <small class="d-block text-light">Gestion & Analytics</small>
                </a>
            @else
                <!-- Regular user options -->
                <!-- Page publique -->
                <a href="{{ route('donation') }}" class="dropdown-item {{ request()->routeIs('donation') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-heart me-2"></i>Faire un Don
                </a>
                
                <!-- Créer un don -->
                <a href="{{ route('donations.create') }}" class="dropdown-item {{ request()->routeIs('donations.create') ? 'active' : '' }}">
                    <i class="fas fa-plus-circle me-2"></i>Nouvelle Donation
                </a>

                <!-- Historique des dons -->
                <a href="{{ route('donations.history') }}" class="dropdown-item {{ request()->routeIs('donations.history') ? 'active' : '' }}">
                    <i class="fas fa-history me-2"></i>Historique
                </a>
            @endif
        @else
            <!-- Guest user options -->
            <!-- Page publique -->
            <a href="{{ route('donation') }}" class="dropdown-item {{ request()->routeIs('donation') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-heart me-2"></i>Faire un Don
            </a>
            <div class="dropdown-divider"></div>
            <span class="dropdown-item text-muted">
                <i class="fas fa-lock me-2"></i>Connectez-vous pour plus d'options
            </span>
        @endauth
    </div>
                    </div>

                    <!-- Authentification -->
                    <div class="d-flex align-items-center flex-nowrap ms-auto">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm me-2">Connexion</a>
                            <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Inscription</a>
                        @endguest

                    @auth
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
            </div>
        </nav>
    </div>
</div>
<!-- Navbar End -->