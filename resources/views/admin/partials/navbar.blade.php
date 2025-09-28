<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex align-items-top flex-row">
    <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
        <div class="me-3">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-bs-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
        </div>
        <div>
            <a class="navbar-brand brand-logo" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('admin/images/logo.svg') }}" alt="logo" />
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('admin/images/logo-mini.svg') }}" alt="logo" />
            </a>
        </div>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-top">
        <ul class="navbar-nav">
            <li class="nav-item fw-semibold d-none d-lg-block ms-0">
                <h1 class="welcome-text">Bonjour, 
                    <span class="text-black fw-bold">
                        @auth
                            {{ Auth::user()->name }}
                        @else
                            Admin
                        @endauth
                    </span>
                </h1>
                <h3 class="welcome-sub-text">Panel d'administration</h3>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <!-- Date et heure en temps réel -->
            <li class="nav-item d-none d-md-block">
                <div class="nav-link">
                    <i class="mdi mdi-clock me-1"></i>
                    <span id="live-time">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </li>

            <!-- Notifications -->
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count-symbol bg-danger"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0">
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 fw-medium float-start">Notifications</p>
                        <span class="badge badge-pill badge-primary float-end">Voir tout</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-success">
                                <i class="mdi mdi-calendar"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Nouvel événement</h6>
                            <p class="text-gray ellipsis mb-0">Un événement a été créé</p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-warning">
                                <i class="mdi mdi-account-multiple"></i>
                            </div>
                        </div>
                        <div class="preview-item-content d-flex align-items-start flex-column justify-content-center">
                            <h6 class="preview-subject font-weight-normal mb-1">Nouvel utilisateur</h6>
                            <p class="text-gray ellipsis mb-0">Inscription d'un nouvel utilisateur</p>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- User Menu avec authentification -->
            <li class="nav-item dropdown d-none d-lg-block user-dropdown">
                <a class="nav-link" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        @if(Auth::user()->avatar)
                            <img class="img-xs rounded-circle" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile image">
                        @else
                            <div class="avatar-placeholder bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                <span class="text-white fw-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                    @else
                        <img class="img-xs rounded-circle" src="{{ asset('admin/images/faces/face8.jpg') }}" alt="Profile image">
                    @endauth
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown">
                    @auth
                    <div class="dropdown-header text-center">
                        @if(Auth::user()->avatar)
                            <img class="img-md rounded-circle" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile image">
                        @else
                            <div class="avatar-placeholder-lg bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 60px; height: 60px;">
                                <span class="text-white fw-bold fs-4">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <p class="mb-1 mt-3 fw-semibold">{{ Auth::user()->name }}</p>
                        <p class="fw-light text-muted mb-0">{{ Auth::user()->email }}</p>
                        <span class="badge badge-success mt-1">{{ Auth::user()->role }}</span>
                    </div>
                    
                    <a class="dropdown-item" href="#">
                        <i class="dropdown-item-icon mdi mdi-account-outline text-primary me-2"></i> Mon Profil
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="dropdown-item-icon mdi mdi-settings text-primary me-2"></i> Paramètres
                    </a>
                    <div class="dropdown-divider"></div>
                    
                    <!-- Formulaire de déconnexion -->
                    <form method="POST" action="{{ route('admin.logout') }}" class="dropdown-item p-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none p-0 w-100 text-start">
                            <i class="dropdown-item-icon mdi mdi-power text-danger me-2"></i> Déconnexion
                        </button>
                    </form>
                    @else
                    <div class="dropdown-header text-center">
                        <p class="mb-1 mt-3 fw-semibold">Non connecté</p>
                        <a href="{{ route('admin.login') }}" class="btn btn-primary btn-sm mt-2">Se connecter</a>
                    </div>
                    @endauth
                </div>
            </li>

            <!-- Bouton de déconnexion visible sur mobile -->
            <li class="nav-item d-lg-none">
                @auth
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="nav-link btn btn-link text-danger">
                        <i class="mdi mdi-power"></i>
                    </button>
                </form>
                @else
                <a href="{{ route('admin.login') }}" class="nav-link">
                    <i class="mdi mdi-login"></i>
                </a>
                @endauth
            </li>
        </ul>
    </div>
</nav>

<!-- Script pour l'heure en temps réel -->
<script>
    function updateTime() {
        const now = new Date();
        const options = { 
            day: '2-digit', 
            month: '2-digit', 
            year: 'numeric',
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        };
        document.getElementById('live-time').textContent = now.toLocaleDateString('fr-FR', options);
    }
    
    // Mettre à jour toutes les secondes
    setInterval(updateTime, 1000);
    updateTime(); // Initialisation
</script>

<style>
.avatar-placeholder {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
}

.avatar-placeholder-lg {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: white;
    font-size: 1.5rem;
}

.dropdown-item button {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    padding: 0.5rem 1rem;
}

.dropdown-item button:hover {
    background-color: #f8f9fa;
}
</style>