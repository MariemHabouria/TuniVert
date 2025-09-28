<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Tunivert')</title>

  {{-- Polices et icônes --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Styles locaux --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  @stack('styles')
</head>
<body>

{{-- ========= TOPBAR ========= --}}
<div class="container-fluid fixed-top px-0">
  <div class="container px-0">
    <div class="topbar bg-dark py-1">
      <div class="row align-items-center justify-content-center">
        <div class="col-md-8 d-flex flex-wrap align-items-center">
          <a href="mailto:Tunivert@gmail.tn" class="text-light me-4">
            <i class="fas fa-envelope me-2"></i>Tunivert@gmail.tn
          </a>
          <a href="tel:+21612345678" class="text-light">
            <i class="fas fa-phone-alt me-2"></i>+216 12 345 678
          </a>
        </div>
        <div class="col-md-4 d-flex justify-content-end">
          <a href="#" class="btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
          <a href="#" class="btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
          <a href="#" class="btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
          <a href="#" class="btn-square text-white"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
    </div>

    {{-- ========= NAVBAR ========= --}}
    <nav class="navbar navbar-light bg-light navbar-expand-xl">
      <a href="{{ route('home') }}" class="navbar-brand ms-3">
        <h1 class="text-primary display-5 m-0">Tunivert</h1>
      </a>
      <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="fa fa-bars text-primary"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ms-auto align-items-center">

          {{-- Pages principales --}}
          <li class="nav-item"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Accueil</a></li>
          <li class="nav-item"><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}">À propos</a></li>
          <li class="nav-item"><a href="{{ route('events.browse') }}" class="nav-link {{ request()->routeIs('events.browse') ? 'active' : '' }}">Événements</a></li>
          <li class="nav-item"><a href="{{ route('service') }}" class="nav-link {{ request()->routeIs('service') ? 'active' : '' }}">Formations</a></li>
          <li class="nav-item"><a href="{{ route('causes') }}" class="nav-link {{ request()->routeIs('causes') ? 'active' : '' }}">Donations</a></li>
          <li class="nav-item"><a href="{{ route('blog') }}" class="nav-link {{ request()->routeIs('blog') ? 'active' : '' }}">Forums</a></li>

          {{-- Challenges Dropdown --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('challenges*') ? 'active' : '' }}" href="#" id="challengesDropdown" role="button" data-bs-toggle="dropdown">
              Challenges
            </a>
            <ul class="dropdown-menu" aria-labelledby="challengesDropdown">
              <a href="{{ route('challenges.index') }}" class="dropdown-item">Voir les Challenges</a>
              @auth
                @if(auth()->user()->role === 'association')
                  <a href="{{ route('challenges.create') }}" class="dropdown-item">Créer un Challenge</a>
                  <a href="{{ route('challenges.crud') }}" class="dropdown-item">Gérer mes Challenges</a>
                  <a href="{{ route('scores.classement', ['challenge' => 'current']) }}" class="dropdown-item">Statistiques</a>
                @else
                  <a href="{{ route('challenges.profil') }}" class="dropdown-item">Mes participations</a>
                @endif
              @endauth
            </ul>
          </li>

          {{-- Formations Dropdown --}}
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('formations*') ? 'active' : '' }}" href="#" id="formationsDropdown" role="button" data-bs-toggle="dropdown">
              Formations
            </a>
            <ul class="dropdown-menu" aria-labelledby="formationsDropdown">
              <a href="{{ route('formations.index') }}" class="dropdown-item">Catalogue</a>
              @auth
                @if(auth()->user()->role === 'association')
                  <a href="{{ route('formations.create') }}" class="dropdown-item">Créer une formation</a>
                  <a href="{{ route('formations.dashboard') }}" class="dropdown-item">Mes formations</a>
                @endif
              @endauth
            </ul>
          </li>

          {{-- Contact --}}
          <li class="nav-item"><a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
        </ul>

        {{-- Auth --}}
        <div class="d-flex align-items-center ms-3">
          @guest
            <a href="{{ route('login') }}" class="btn btn-primary text-white py-2 px-4 me-2">Connexion</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary py-2 px-4">Inscription</a>
          @endguest

          @auth
            <div class="dropdown">
              <a class="nav-link dropdown-toggle p-0" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width:38px;height:38px;">
                  {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu" style="min-width:220px;">
                <li class="px-3 py-2">
                  <div class="fw-semibold">{{ Auth::user()->name }}</div>
                  <div class="small text-muted">{{ Auth::user()->email }}</div>
                  @if(auth()->user()->role === 'association')
                    <span class="badge bg-primary mt-1">Association</span>
                  @endif
                </li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile') }}"><i class="bi bi-person-circle"></i> Profil</a></li>
                <li>
                  <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                      <i class="bi bi-power"></i> Se déconnecter
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

{{-- Espace pour navbar fixe --}}
<div style="height:120px"></div>

{{-- Contenu --}}
@yield('content')

{{-- Footer --}}
<div class="container-fluid footer bg-dark text-body py-5 mt-5">
  <div class="container py-5">
    <div class="row g-5">
      <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="footer-item">
          <h4 class="mb-4 text-white">Newsletter</h4>
          <p class="mb-4">Recevez nos actualités.</p>
          <div class="position-relative mx-auto">
            <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Votre email">
            <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid copyright py-4">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-4 text-center text-md-start mb-md-0">
        <span class="text-body"><i class="fas fa-copyright text-light me-2"></i>Tunivert, tous droits réservés.</span>
      </div>
      <div class="col-md-4 text-center">
        <div class="d-flex align-items-center justify-content-center">
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-4 text-center text-md-end text-body">
        Designed by Tunivert
      </div>
    </div>
  </div>
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
