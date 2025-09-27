<!DOCTYPE html>
<html lang="fr">
<<<<<<< HEAD
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Tunivert - Protection de l\'Environnement')</title>
    
=======

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'TuniVert - Environnement & Nature')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

>>>>>>> donations
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
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
<<<<<<< HEAD
    
    @yield('styles')
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
=======

    @stack('styles')
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
>>>>>>> donations
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

<<<<<<< HEAD
    {{-- Inclure la navbar --}}
    @include('layouts.navbar')

    {{-- Contenu spécifique de la page --}}
    <main>
        @yield('content')
    </main>

    {{-- Inclure le footer --}}
    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>
=======
    <!-- Navbar start -->
    <div class="container-fluid fixed-top px-0">
        <div class="container px-0">
            <div class="topbar">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8">
                        <div class="topbar-info d-flex flex-wrap">
                            <a href="#" class="text-light me-4"><i class="fas fa-envelope text-white me-2"></i>Tunivert@gmail.tn</a>
                            <a href="#" class="text-light"><i class="fas fa-phone-alt text-white me-2"></i>+01234567890</a>
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
            <nav class="navbar navbar-light bg-light navbar-expand-xl">
                <a href="{{ route('home') }}" class="navbar-brand ms-3">
                    <h1 class="text-primary display-5">TuniVert</h1>
                </a>
                <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="{{ route('home') }}" class="nav-item nav-link {{ request()->is('/') ? 'active' : '' }}">Accueil</a>
                        <a href="{{ route('about') }}" class="nav-item nav-link {{ request()->is('about*') ? 'active' : '' }}">À propos</a>
                        <a href="{{ route('service') }}" class="nav-item nav-link {{ request()->is('service*') ? 'active' : '' }}">Services</a>
                        <a href="{{ route('causes') }}" class="nav-item nav-link {{ request()->is('causes*') ? 'active' : '' }}">Causes</a>
                        <a href="{{ route('events.browse') }}" class="nav-item nav-link {{ request()->is('events*') ? 'active' : '' }}">Événements</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Plus</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                <a href="{{ route('blog') }}" class="dropdown-item">Blog</a>
                                <a href="{{ route('gallery') }}" class="dropdown-item">Galerie</a>
                                <a href="{{ route('donation') }}" class="dropdown-item">Donation</a>
                                @auth
                                    <a href="{{ route('donations.history') }}" class="dropdown-item">Mes donations</a>
                                @endauth
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="nav-item nav-link {{ request()->is('contact*') ? 'active' : '' }}">Contact</a>
                    </div>
                    <div class="d-flex align-items-center flex-nowrap pt-xl-0" style="margin-left: 15px;">
                        @guest
                            <div class="d-flex align-items-center gap-2 ms-lg-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">
                                    Connexion
                                </a>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                                    Inscription
                                </a>
                            </div>
                        @endguest

                        @auth
                            <div class="dropdown">
                                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                                    <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile') }}">Mon profil</a></li>
                                    <li><a class="dropdown-item" href="{{ route('donations.history') }}">Mes donations</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">Déconnexion</button>
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

    <!-- Main Content -->
    @yield('content')

    <!-- Footer Start -->
    <div class="container-fluid footer py-5">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">TuniVert</h4>
                        <p>Ensemble pour un avenir plus vert en Tunisie. Rejoignez notre mission pour protéger l'environnement et sensibiliser les communautés.</p>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-share fa-2x text-white me-2"></i>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href=""><i class="fab fa-instagram"></i></a>
                            <a class="btn-square btn btn-primary text-white rounded-circle mx-1" href=""><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Liens rapides</h4>
                        <a href="{{ route('home') }}"><i class="fas fa-angle-right me-2"></i> Accueil</a>
                        <a href="{{ route('about') }}"><i class="fas fa-angle-right me-2"></i> À propos</a>
                        <a href="{{ route('events.browse') }}"><i class="fas fa-angle-right me-2"></i> Événements</a>
                        <a href="{{ route('donation') }}"><i class="fas fa-angle-right me-2"></i> Faire un don</a>
                        <a href="{{ route('contact') }}"><i class="fas fa-angle-right me-2"></i> Contact</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Services</h4>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Agriculture biologique</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Protection écosystème</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Recyclage</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Sensibilisation</a>
                        <a href="#"><i class="fas fa-angle-right me-2"></i> Reboisement</a>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="footer-item d-flex flex-column">
                        <h4 class="mb-4 text-white">Contact Info</h4>
                        <a href="#"><i class="fa fa-map-marker-alt me-2"></i> Tunis, Tunisie</a>
                        <a href="#"><i class="fas fa-envelope me-2"></i> tunivert@gmail.tn</a>
                        <a href="#"><i class="fas fa-phone me-2"></i> +216 12 345 678</a>
                        <a href="#" class="mb-3"><i class="fas fa-print me-2"></i> +216 98 765 432</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright text-body py-4">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-md-6 text-center text-md-end mb-md-0">
                    <i class="fas fa-copyright me-2"></i><a class="text-white" href="#">TuniVert</a>, Tous droits réservés.
                </div>
                <div class="col-md-6 text-center text-md-start">
                    Conçu avec <i class="fas fa-heart text-primary"></i> pour l'environnement
                </div>
            </div>
        </div>
    </div>
    <!-- Copyright End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>   
>>>>>>> donations

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<<<<<<< HEAD
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
=======
>>>>>>> donations
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>
<<<<<<< HEAD
    
    @yield('scripts')
=======

    @stack('scripts')
>>>>>>> donations
</body>
</html>