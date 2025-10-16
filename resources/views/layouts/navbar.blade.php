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

            <!-- Hamburger -->
            <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                <!-- ✅ Liens alignés -->
                <ul class="navbar-nav ms-auto d-flex flex-row align-items-center gap-3">
                    <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a></li>
                    <li class="nav-item"><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">À propos</a></li>
                    <li class="nav-item"><a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}">Événements</a></li>
                    <li class="nav-item"><a href="{{ route('donations.history') }}" class="nav-link {{ request()->routeIs('donations.history') ? 'active' : '' }}">Donations</a></li>
                    <li class="nav-item"><a href="{{ route('forums.index') }}" class="nav-link {{ request()->is('forums*') ? 'active' : '' }}">Forums</a></li>
                    <li class="nav-item"><a href="{{ route('alertes.index') }}" class="nav-link {{ request()->is('alertes*') ? 'active' : '' }}">Alertes</a></li>
                    <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>

                    <!-- Dropdown Challenges -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('challenges*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            Challenges
                        </a>
                        <ul class="dropdown-menu m-0 bg-secondary rounded-0">
                            @auth
                                @if(Auth::user()->role === 'association')
                                    <li><a href="{{ route('challenges.create') }}" class="dropdown-item">Créer un Challenge</a></li>
                                    <li><a href="{{ route('challenges.crud') }}" class="dropdown-item">Gérer mes Challenges</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a href="{{ route('scores.classement', ['challenge' => 'current']) }}" class="dropdown-item">Statistiques</a></li>
                                @else
                                    <li><a href="{{ route('challenges.index') }}" class="dropdown-item">Voir les Challenges</a></li>
                                    <li><a href="{{ route('challenges.profil') }}" class="dropdown-item">Mes Participations</a></li>
                                @endif
                            @else
                                <li><a href="{{ route('challenges.index') }}" class="dropdown-item">Voir les Challenges</a></li>
                            @endauth
                        </ul>
                    </li>

                    <!-- Dropdown Formations -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('formations*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            Formations
                        </a>
                        <ul class="dropdown-menu m-0 bg-secondary rounded-0">
                            <li><a href="{{ route('formations.index') }}" class="dropdown-item">Catalogue</a></li>
                            @auth
                                @if(Auth::user()->role === 'association')
                                    <li><a href="{{ route('formations.create') }}" class="dropdown-item">Créer une formation</a></li>
                                    <li><a href="{{ route('formations.dashboard') }}" class="dropdown-item">Mes formations</a></li>
                                @endif
                            @endauth
                        </ul>
                    </li>
                </ul>

                <!-- ✅ Partie utilisateur -->
                <div class="d-flex align-items-center flex-nowrap pt-xl-0 ms-3">
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
                                        <i class="fas fa-user"></i> Profil
                                    </a>
                                </li>

                                @if(Auth::user()->role === 'association')
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('challenges.create') }}">
                                            <i class="fas fa-plus"></i> Créer un Challenge
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('challenges.profil') }}">
                                            <i class="fas fa-trophy"></i> Mes Participations
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2 w-100">
                                            <i class="fas fa-sign-out-alt"></i> Se déconnecter
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

<!-- ✅ CSS rapide -->
<style>
.navbar-nav .nav-link {
    white-space: nowrap; /* empêche retour à la ligne */
}
</style>
