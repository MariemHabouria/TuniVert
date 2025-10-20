<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>TuniVert - {{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    @include('layouts.navbar')

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="text-white display-3 mb-4">{{ $event->title }}</h3>
            <p class="fs-5 text-white mb-4">Rejoignez-nous pour cet événement environnemental exceptionnel organisé par TuniVert</p>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.index') }}">Événements</a></li>
                <li class="breadcrumb-item active text-white">{{ $event->title }}</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Main Content Start -->
    <div class="container py-5">
            --success-color: #198754;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #0dcaf0;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
            --muted-text: #6c757d;
            --border-radius: 1rem;
            --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --shadow-md: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            --shadow-lg: 0 1rem 3rem rgba(0, 0, 0, 0.175);
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.9), rgba(25, 135, 84, 0.9));
            padding: 4rem 0;
            margin-top: 120px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><polygon fill="rgba(255,255,255,0.1)" points="1000,100 1000,0 0,100"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Main Event Card */
        .event-main-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            margin-top: -2rem;
            position: relative;
            z-index: 3;
        }

        .event-image {
            height: 400px;
            object-fit: cover;
            border-radius: var(--border-radius) var(--border-radius) 0 0;
        }

        /* Info Cards */
        .info-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        /* Action Buttons */
        .action-btn {
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .action-btn:hover::before {
            left: 100%;
        }

        .btn-primary-gradient {
            background: linear-gradient(45deg, var(--primary-color), #6610f2);
            color: white;
        }

        .btn-success-gradient {
            background: linear-gradient(45deg, var(--success-color), #20c997);
            color: white;
        }

        .btn-warning-gradient {
            background: linear-gradient(45deg, var(--warning-color), #fd7e14);
            color: white;
        }

        /* Statistics Cards */
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            text-align: center;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            display: block;
        }

        /* AI Suggestion Card */
        .ai-suggestion-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .ai-suggestion-card::before {
            content: '🤖';
            position: absolute;
            top: -10px;
            right: -10px;
            font-size: 4rem;
            opacity: 0.1;
        }

        .suggestion-btn {
            background: rgba(255, 255, 255, 0.2);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            margin: 0.5rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .suggestion-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        /* Comments Section */
        .comments-section {
            background: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: var(--shadow-md);
        }

        .comment-card {
            background: var(--light-bg);
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .comment-card:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--success-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.25rem;
        }

        /* Chatbot */
        .chatbot-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
        }

        .chat-toggle-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(45deg, var(--primary-color), var(--success-color));
            border: none;
            color: white;
            font-size: 1.5rem;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
        }

        .chat-toggle-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .chat-window {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            width: 350px;
            max-height: 500px;
            overflow: hidden;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: 2rem 0;
            }
            
            .event-image {
                height: 250px;
            }
            
            .action-btn {
                padding: 0.5rem 1.5rem;
                font-size: 0.9rem;
            }
            
            .chatbot-container {
                bottom: 20px;
                right: 20px;
            }
        }

        /* Loading Animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
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
                    <a href="{{ route('events.index') }}" class="nav-item nav-link {{ request()->routeIs('events.index') ? 'active' : '' }}">Événements</a>
                    <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->routeIs('service') ? 'active' : '' }}">Formations</a>
                    <a href="{{ route('causes') }}" class="nav-item nav-link {{ request()->routeIs('causes') ? 'active' : '' }}">Donations</a>
                    <!-- ✅ Forums -->
                    <a href="{{ route('forums.index') }}" class="nav-item nav-link {{ request()->is('forums*') ? 'active' : '' }}">Forums</a>
                    <!-- ✅ Alertes -->
                    <a href="{{ route('alertes.index') }}" class="nav-item nav-link {{ request()->is('alertes*') ? 'active' : '' }}">Alertes</a>
                    <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>

                    <!-- Challenge Dropdown -->
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

                    <!-- Formation Dropdown -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle {{ request()->is('formations*') ? 'active' : '' }}" data-bs-toggle="dropdown">
                            Formations
                        </a>
                        <div class="dropdown-menu m-0 bg-secondary rounded-0">
                            <a href="{{ route('formations.index') }}" class="dropdown-item">Catalogue</a>
                            @auth
                                @if(Auth::user()->role === 'association')
                                    <a href="{{ route('formations.create') }}" class="dropdown-item {{ request()->routeIs('formations.create') ? 'active' : '' }}">Créer une formation</a>
                                    <a href="{{ route('formations.dashboard') }}" class="dropdown-item {{ request()->routeIs('formations.dashboard') ? 'active' : '' }}">Mes formations</a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Auth pour guest -->
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
                                    <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('donations.history') }}">
                                        <i class="fas fa-heart"></i>
                                        Mes Donations
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
        </nav>
    </div>
</div>
<!-- Navbar End -->

<!-- Hero Section -->
<div class="hero-section">
    <div class="container hero-content">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-3 fade-in-up">{{ $event->title }}</h1>
                <p class="lead mb-4 fade-in-up" style="animation-delay: 0.2s;">
                    Rejoignez-nous pour cet événement environnemental exceptionnel organisé par TuniVert
                </p>
                @if($event->category)
                    <span class="badge bg-light text-primary px-3 py-2 rounded-pill fs-6 fade-in-up" style="animation-delay: 0.4s;">
                        <i class="bi bi-tags-fill me-2"></i>{{ $event->category }}
                    </span>
                @endif
            </div>
            <div class="col-lg-4 text-center">
                <div class="stat-card fade-in-up" style="animation-delay: 0.6s;">
                    <span class="stat-number">{{ $event->participants->count() }}</span>
                    <div class="mt-2">Participants Inscrits</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container py-5">
    <!-- Event Main Card -->
    <div class="event-main-card fade-in-up">
        <div class="row g-0">
            <!-- Event Image -->
            <div class="col-lg-6">
                @if($event->image)
                    <img src="{{ Storage::url($event->image) }}" class="event-image w-100" alt="{{ $event->title }}">
                @else
                    <div class="event-image w-100 d-flex align-items-center justify-content-center bg-gradient" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        <div class="text-center text-white">
                            <i class="bi bi-calendar-event" style="font-size: 4rem;"></i>
                            <h4 class="mt-3">Événement TuniVert</h4>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Event Details -->
            <div class="col-lg-6">
                <div class="p-4 p-lg-5">
                    <!-- Event Info Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-danger text-white">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <h6 class="fw-bold text-danger mb-1">Lieu</h6>
                                <p class="mb-0 text-muted">{{ $event->location }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-success text-white">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <h6 class="fw-bold text-success mb-1">Date</h6>
                                <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-primary text-white">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <h6 class="fw-bold text-primary mb-1">Organisateur</h6>
                                <p class="mb-0 text-muted">{{ $event->organizer->name ?? 'TuniVert' }}</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-card">
                                <div class="info-icon bg-info text-white">
                                    <i class="bi bi-people-fill"></i>
                                </div>
                                <h6 class="fw-bold text-info mb-1">Participants</h6>
                                <p class="mb-0 text-muted">{{ $event->participants->count() }} inscrits</p>
                            </div>
                        </div>
                    </div>

                    <!-- Event Description -->
                    @if($event->details)
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="bi bi-info-circle-fill text-primary me-2"></i>
                                Description de l'événement
                            </h5>
                            <div class="text-muted lh-lg">
                                {!! nl2br(e($event->details)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('events.index') }}" class="action-btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour aux événements
                        </a>

                        @auth
                            <a href="{{ route('donations.create', ['event_id' => $event->id]) }}" class="action-btn btn-success-gradient pulse-animation">
                                <i class="bi bi-heart-fill me-2"></i>Faire un Don
                            </a>

                            @if(Auth::id() === $event->organizer_id)
                                <a href="{{ route('events.edit', $event->id) }}" class="action-btn btn-warning-gradient">
                                    <i class="bi bi-pencil-square me-2"></i>Modifier
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="action-btn btn-primary-gradient">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter pour participer
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

  {{-- ====== DONATION AI SUGGESTIONS ====== --}}
  @if(isset($donationSuggestion) && $donationSuggestion && auth()->check())
  <div class="card shadow-sm border-0 rounded-4 mb-5">
    <div class="card-body p-4">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h4 class="text-primary mb-2">
            <i class="bi bi-robot me-2"></i>
            Suggestions IA de Don
          </h4>
          <p class="text-muted mb-3">
            Notre IA suggère un montant personnalisé basé sur votre historique et cet événement.
            <strong>Probabilité de don: {{ number_format($donationSuggestion['propensity'] * 100, 1) }}%</strong>
          </p>
          
          <div class="d-flex gap-2 flex-wrap">
            @foreach($donationSuggestion['amounts'] as $level => $amount)
              @php
                $badgeClass = match($level) {
                  'low' => 'bg-success',
                  'mid' => 'bg-warning text-dark',
                  'high' => 'bg-danger',
                  default => 'bg-secondary'
                };
                $levelText = match($level) {
                  'low' => 'Modeste',
                  'mid' => 'Recommandé',
                  'high' => 'Généreux',
                  default => ucfirst($level)
                };
              @endphp
              <button type="button" 
                      class="btn {{ $badgeClass }} suggestion-amount-btn"
                      data-amount="{{ $amount }}"
                      data-level="{{ $level }}"
                      data-event-id="{{ $event->id }}">
                {{ $levelText }}: {{ $amount }} TND
              </button>
            @endforeach
          </div>
        </div>
        
        <div class="col-md-4 text-center">
          <div class="text-center">
            <i class="bi bi-heart-fill text-danger fs-1 mb-2"></i>
            <p class="small text-muted mb-0">Soutenez cet événement</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  {{-- ====== Section Commentaires Modernisée ====== --}}
    <!-- Comments Section -->
    <div class="section-card fade-in-up mt-5" style="animation-delay: 0.2s;">
        <h3 class="section-title">
            <i class="bi bi-chat-dots-fill text-primary me-2"></i>
            Commentaires & Discussions
            <span class="badge bg-primary rounded-pill ms-2">{{ $event->comments->count() }}</span>
        </h3>

  {{-- Formulaire d’ajout --}}
  @auth
  <form action="{{ route('comments.store', $event->id) }}" method="POST" class="cmt-editor card border-0 shadow-sm">
    @csrf
    <div class="card-body p-3 p-sm-4">
      <div class="d-flex align-items-start gap-3">
        {{-- Avatar user connecté --}}
        <div class="cmt-avatar cmt-avatar--lg">
          <span class="cmt-avatar__initial">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
          </span>
        </div>

        <div class="flex-grow-1">
          <label for="comment_content" class="form-label fw-semibold mb-1">Ajouter un commentaire</label>
          <textarea id="comment_content"
                    name="content"
                    class="form-control cmt-textarea"
                    rows="3"
                    maxlength="1000"
                    placeholder="Partagez votre avis…"
                    required></textarea>

          <div class="d-flex justify-content-between align-items-center mt-2">
            <small class="text-muted"><span id="charCount">0</span>/1000</small>
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-chat-left-dots"></i> Publier
            </button>
          </div>
        </div>
      </div>
    </div>
  </form>
  @endauth

  {{-- Liste des commentaires modernisée --}}
  <div class="comments-list mt-4">
    @if($event->comments->count() > 0)
      @foreach($event->comments as $comment)
        <div class="comment-item">
          <div class="comment-avatar">
            <div class="avatar-circle">
              {{ strtoupper(substr($comment->user->name, 0, 1)) }}
            </div>
          </div>
          <div class="comment-content">
            <div class="comment-header">
              <h6 class="comment-author">{{ $comment->user->name }}</h6>
              @if($comment->user_id === $event->organizer_id)
                <span class="badge bg-primary rounded-pill text-xs me-2">Organisateur</span>
              @endif
              <span class="comment-date">
                {{ $comment->created_at->diffForHumans() }}
              </span>
            </div>
            <div class="comment-text">
              {{ $comment->content }}
            </div>
          </div>
        </div>
      @endforeach
    @else
      <div class="text-center py-5">
        <i class="bi bi-chat-quote text-muted mb-3" style="font-size: 3rem;"></i>
        <h5 class="text-muted">Aucun commentaire pour le moment</h5>
        <p class="text-muted">Soyez le premier à partager vos pensées !</p>
      </div>
    @endif
  </div>



            

              {{-- Zone d’édition repliable --}}
              @auth
                @if(Auth::id() === $comment->user_id)
                  <div class="collapse mt-3" id="edit-{{ $comment->id }}">
                    <form action="{{ route('comments.update', $comment->id) }}" method="POST" class="cmt-edit d-flex flex-column flex-sm-row gap-2">
                      @csrf
                      @method('PUT')
                      <input type="text" name="content" value="{{ $comment->content }}" class="form-control" placeholder="Modifier votre commentaire..." required>
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                      </button>
                    </form>
                  </div>
                @endif
              @endauth
            </div>
          </div>
        </div>
  </div>

{{-- Compteur caractères (léger) --}}
<script>
document.addEventListener('DOMContentLoaded', function(){
  const txt = document.getElementById('comment_content');
  if (!txt) return;
  const counter = document.getElementById('charCount');
  const update = () => { counter.textContent = (txt.value || '').length; };
  txt.addEventListener('input', update);
  update();
});
</script>
<!-- Chatbot spécifique à la page événement -->
<div id="chatbot" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; width: 320px; font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Apple Color Emoji', 'Segoe UI Emoji';">
  <button id="chat-toggle" class="btn btn-primary w-100 mb-2">💬 Chat avec le bot</button>

  <div id="chat-window" class="card shadow-lg" style="display:none; max-height:420px; overflow-y:auto;">
    <div class="card-body" id="chat-log"></div>
  </div>

  <div id="chat-input-container" style="display:none; margin-top:6px;">
    <div class="input-group">
      <input type="text" id="chat-input" class="form-control" placeholder="Pose ta question sur l'événement..." />
      <button id="chat-send" class="btn btn-primary">Envoyer</button>
    </div>
  </div>
</div>

<!-- CSS additionnel (auto-contenu) -->
<style>
/* ====== Événement ====== */
.object-cover { object-fit: cover; }
.event-card .card-body .prose { line-height: 1.7; }


/* Chips soft */
.bg-soft-primary { background-color: rgba(13,110,253,.12) !important; }
.bg-soft-success { background-color: rgba(25,135,84,.12) !important; }
.bg-soft-danger  { background-color: rgba(220,53,69,.12) !important; }
.bg-soft-secondary { background-color: rgba(108,117,125,.12) !important; }

.text-primary-emphasis   { color: #0a58ca !important; }
.text-success-emphasis   { color: #0f5132 !important; }
.text-danger-emphasis    { color: #842029 !important; }
.text-secondary-emphasis { color: #41464b !important; }

/* ====== Commentaires (look pro) ====== */
.cmt-list { display: grid; gap: 0.75rem; }

/* Carte */
.cmt-card {
  border-radius: 16px;
  transition: box-shadow .2s ease, transform .12s ease;
  will-change: transform, box-shadow;
}
.cmt-card:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 28px rgba(0,0,0,.08);
}

/* Avatar */
.cmt-avatar {
  width: 48px;
  height: 48px;
  border-radius: 999px;
  overflow: hidden;
  position: relative;
  flex: 0 0 auto;
  background: linear-gradient(135deg, #0d6efd 0%, #6c63ff 100%);
  display: grid;
  place-items: center;
}
.cmt-avatar--lg { width: 56px; height: 56px; }
.cmt-avatar__initial {
  color: #fff;
  font-weight: 700;
  font-size: 1rem;
  letter-spacing: .5px;
}

/* En-tête */
.cmt-header { position: relative; }
.cmt-author { color: #111827; } /* near-black */
.cmt-dot { color: #9ca3af; }    /* muted gray */

/* Badges */
.cmt-badge {
  display: inline-flex;
  align-items: center;
  gap: .35rem;
  padding: .25rem .6rem;
  border-radius: 999px;
  font-size: .75rem;
  font-weight: 600;
  line-height: 1;
  border: 1px solid transparent;
  user-select: none;
}
.cmt-badge--role {
  color: #0f5132;
  background: rgba(25,135,84,.12);
  border-color: rgba(25,135,84,.25);
}
.cmt-badge--sentiment { letter-spacing: .1px; }
.cmt-badge--positif {
  color: #0f5132;
  background: rgba(25,135,84,.12);
  border-color: rgba(25,135,84,.25);
}
.cmt-badge--negatif {
  color: #842029;
  background: rgba(220,53,69,.12);
  border-color: rgba(220,53,69,.25);
}
.cmt-badge--neutre {
  color: #41464b;
  background: rgba(108,117,125,.14);
  border-color: rgba(108,117,125,.28);
}
.cmt-badge--pending {
  color: #525252;
  background: rgba(0,0,0,.06);
  border-color: rgba(0,0,0,.08);
}

/* Texte */
.cmt-text {
  color: #1f2937;
  font-size: .975rem;
  line-height: 1.7;
  word-break: break-word;
  white-space: pre-wrap;
}

/* Footer d’actions (liens discrets) */
.cmt-footer .cmt-action-link {
  font-size: .9rem;
  text-decoration: none;
  color: #0d6efd;
  opacity: .85;
}
.cmt-footer .cmt-action-link:hover {
  opacity: 1;
  text-decoration: underline;
}

/* Actions (modifier/supprimer) visibles au survol de la carte */
.cmt-actions { opacity: 0; transition: opacity .15s ease; }
.cmt-card:hover .cmt-actions { opacity: 1; }

/* Éditeur */
.cmt-editor { border-radius: 16px; }
.cmt-textarea {
  resize: vertical;
  min-height: 92px;
  border-radius: 12px;
}

/* Responsif */
@media (max-width: 575.98px) {
  .cmt-avatar--lg { width: 52px; height: 52px; }
  .cmt-text { font-size: .95rem; }
}


/* Chatbot small polishing */
#chatbot .card { border: none; border-radius: 12px; }
#chatbot #chat-log { max-height: 360px; overflow-y: auto; }

/* Section Styles */
.section-card {
    background: white;
    border-radius: var(--border-radius);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    margin: 2rem 0;
}

.section-title {
    color: var(--dark-text);
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #e9ecef;
}

/* Comment Item Styles */
.comment-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 1rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.comment-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.comment-avatar {
    flex-shrink: 0;
}

.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 1.1rem;
}

.comment-content {
    flex: 1;
}

.comment-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.comment-author {
    font-weight: 600;
    color: var(--dark-text);
    margin: 0;
}

.comment-date {
    color: var(--muted-text);
    font-size: 0.875rem;
}

.comment-text {
    color: var(--dark-text);
    line-height: 1.6;
}
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggleBtn = document.getElementById('chat-toggle');
  const chatWindow = document.getElementById('chat-window');
  const chatLog = document.getElementById('chat-log');
  const inputContainer = document.getElementById('chat-input-container');
  const sendBtn = document.getElementById('chat-send');
  const chatInput = document.getElementById('chat-input');

  // autosize textarea + compteur
  const txt = document.getElementById('comment_content');
  if (txt) {
    const counter = document.getElementById('charCount');
    const update = () => {
      txt.style.height = 'auto';
      txt.style.height = Math.min(txt.scrollHeight, 240) + 'px';
      if (counter) counter.textContent = String(txt.value.length);
    };
    txt.addEventListener('input', update);
    update();
  }

  toggleBtn.addEventListener('click', () => {
    const isVisible = chatWindow.style.display === 'block';
    chatWindow.style.display = isVisible ? 'none' : 'block';
    inputContainer.style.display = isVisible ? 'none' : 'block';
    if (!isVisible) chatInput.focus();
  });

  function appendMessage(who, html) {
    const wrap = document.createElement('div');
    wrap.className = 'mb-2';
    wrap.innerHTML = <strong>${who}:</strong> ${html};
    chatLog.appendChild(wrap);
    chatWindow.scrollTop = chatWindow.scrollHeight;
  }

  function setTyping(isTyping) {
    let el = document.getElementById('typing');
    if (isTyping) {
      if (!el) {
        el = document.createElement('div');
        el.id = 'typing';
        el.className = 'text-muted small fst-italic';
        el.textContent = 'Le bot écrit…';
        chatLog.appendChild(el);
      }
    } else if (el) {
      el.remove();
    }
  }

  async function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    appendMessage('Vous', escapeHtml(message));
    chatInput.value = '';
    setTyping(true);
    sendBtn.disabled = true;

    try {
      const res = await fetch("{{ route('chatbot.ask') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message })
      });

      const data = await res.json();
      setTyping(false);
      sendBtn.disabled = false;

      const reply = data?.reply ?? "Désolé, je n'ai pas compris.";
      appendMessage('Bot', escapeHtml(reply));
    } catch (e) {
      setTyping(false);
      sendBtn.disabled = false;
      appendMessage('Bot', "Une erreur est survenue. Réessaie.");
    }
  }

  sendBtn.addEventListener('click', sendMessage);
  chatInput.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') sendMessage();
  });

  function escapeHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
  }
});
</script>

@if(isset($donationSuggestion) && $donationSuggestion && auth()->check())
<script>
// Donation AI Suggestions Click Tracking
document.addEventListener('DOMContentLoaded', function() {
  const suggestionBtns = document.querySelectorAll('.suggestion-amount-btn');
  
  suggestionBtns.forEach(btn => {
    btn.addEventListener('click', function() {
      const amount = this.dataset.amount;
      const level = this.dataset.level;
      const eventId = this.dataset.eventId;
      
      // Log the click for analytics
      fetch('/donations/suggestion-click', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        },
        body: JSON.stringify({
          event_id: eventId,
          amount: amount,
          level: level
        })
      }).catch(console.error);
      
      // Redirect to donation page with the suggested amount
      const donationUrl = `/donations/create?event_id=${eventId}&amount=${amount}&method={{ $donationSuggestion['method'] ?? 'paymee' }}`;
      window.location.href = donationUrl;
    });
  });
});
</script>
@endif

 <!--Footer Start -->
        <div class="container-fluid footer bg-dark text-body py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Newsletter</h4>
                            <p class="mb-4">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                                <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Our Services</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Ocean Turtle</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> White Tiger</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Social Ecology</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Loneliness</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Beauty of Life</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Present for You</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Volunteer</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Karen Dawson</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Jack Simmons</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Michael Linden</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Simon Green</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Natalie Channing</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Caroline Gerwig</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Our Gallery</h4>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-1.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-1.jpg" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-2.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-2.jpg" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-3.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-3.jpg" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-4.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-4.jpg" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
									<div class="footer-gallery">
										<img src="img/gallery-footer-5.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-5.jpg" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
									</div>
								</div>
                                <div class="col-4">
									<div class="footer-gallery">
										<img src="img/gallery-footer-6.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-6.jpg" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

<!-- Enhanced JavaScript for modern functionality -->
<script>
function toggleChatbot() {
    const chatbotWindow = document.getElementById('chatbotWindow');
    chatbotWindow.classList.toggle('active');
}

// Add click tracking for AI suggestions
document.addEventListener('DOMContentLoaded', function() {
    const suggestionLinks = document.querySelectorAll('.suggestion-card a');
    suggestionLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Track suggestion click
            fetch('/donations/suggestion-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    event_id: {{ $event->id ?? 0 }},
                    suggestion_amount: this.href.match(/amount=(\d+)/)?.[1] || 0
                })
            }).catch(err => console.log('Suggestion click tracking failed:', err));
        });
    });

    // Add loading animation to buttons
    const actionBtns = document.querySelectorAll('.action-btn');
    actionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            if (!this.classList.contains('btn-outline-secondary')) {
                const originalContent = this.innerHTML;
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Chargement...';
                
                // Reset after 3 seconds in case of issues
                setTimeout(() => {
                    this.innerHTML = originalContent;
                }, 3000);
            }
        });
    });

    // Enhanced comment form interactions
    const commentTextarea = document.querySelector('.comment-form textarea');
    if (commentTextarea) {
        commentTextarea.addEventListener('focus', function() {
            this.style.minHeight = '120px';
        });
        
        commentTextarea.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.style.minHeight = '100px';
            }
        });
    }

    // Smooth scroll to comments when comment is submitted
    const commentForm = document.querySelector('.comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function() {
            setTimeout(() => {
                document.querySelector('.comments-list')?.scrollIntoView({ 
                    behavior: 'smooth' 
                });
            }, 100);
        });
    }
});
</script>

<!-- Add AI Suggestions Section if data exists -->
@if(isset($suggestions) && count($suggestions) > 0)
    <div class="section-card fade-in-up mt-5" style="animation-delay: 0.4s;">
        <h3 class="section-title">
            <i class="bi bi-lightbulb-fill text-warning me-2"></i>
            Suggestions Personnalisées IA
            <span class="badge bg-gradient rounded-pill ms-2" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                Powered by AI
            </span>
        </h3>

        <div class="row g-3">
            @foreach($suggestions as $suggestion)
                <div class="col-md-6">
                    <div class="suggestion-card">
                        <div class="suggestion-header">
                            <span class="suggestion-amount">{{ $suggestion['amount'] ?? 'N/A' }}€</span>
                            <span class="suggestion-confidence">
                                Confiance: {{ number_format(($suggestion['confidence'] ?? 0) * 100, 0) }}%
                            </span>
                        </div>
                        <p class="suggestion-reason">{{ $suggestion['reason'] ?? 'Suggestion personnalisée' }}</p>
                        <div class="suggestion-actions">
                            <a href="{{ route('donations.create', ['event_id' => $event->id, 'amount' => $suggestion['amount'] ?? 0]) }}" 
                               class="action-btn btn-success-gradient">
                                <i class="bi bi-heart-fill me-2"></i>Choisir ce montant
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Floating Chatbot -->
<div class="chatbot-container">
    <div class="chatbot-toggle" onclick="toggleChatbot()">
        <i class="bi bi-chat-dots-fill"></i>
    </div>
    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <h6 class="mb-0">Assistant TuniVert</h6>
            <button onclick="toggleChatbot()" class="btn-close btn-close-white"></button>
        </div>
        <div class="chatbot-body">
            <div class="chatbot-message bot-message">
                Bonjour ! Je suis votre assistant TuniVert. Comment puis-je vous aider avec cet événement ?
            </div>
        </div>
        <div class="chatbot-input">
            <input type="text" class="form-control" placeholder="Tapez votre message...">
            <button class="btn btn-primary">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>
</body>
</html>