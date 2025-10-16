<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>TuniVert - Modifier l'événement</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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

    <!-- Bootstrap & Template Stylesheet -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

</head>
<body>
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
<!-- Donations Dropdown -->
<div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle {{ request()->is('donations*') || request()->is('donation') ? 'active' : '' }}" data-bs-toggle="dropdown">
        Donations
    </a>
    <div class="dropdown-menu m-0 bg-secondary rounded-0">
        @auth
          
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
    </div><!-- ✅ Forums -->
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

<h1 class="text-center my-5">Modifier l'événement</h1>

@if ($errors->any())
    <div class="alert alert-danger w-75 mx-auto">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
$categories = [
    'Nettoyage de plage',
    'Plantation d’arbres',
    'Sensibilisation au recyclage',
    'Conférence environnementale',
    'Campagne de réduction des déchets',
    'Énergie renouvelable',
    'Journée écologique scolaire',
    'Écotourisme',
    'Collecte de fonds écologique'
];
@endphp

<form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="w-75 mx-auto p-4 border rounded shadow-sm bg-light">
    @csrf
    @method('PUT')

    <div class="form-floating mb-3">
        <input type="text" name="title" class="form-control" id="title" placeholder="Titre" value="{{ old('title', $event->title) }}">
        <label for="title">Titre</label>
    </div>

    <div class="form-floating mb-3 position-relative">
        <input type="text" name="location" class="form-control" id="location" placeholder="Lieu" value="{{ old('location', $event->location) }}">
        <label for="location">Lieu</label>
        <div id="location-suggestions" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
    </div>

    <div class="form-floating mb-3">
        <input type="date" name="date" class="form-control" id="date" placeholder="Date" value="{{ old('date', \Carbon\Carbon::parse($event->date)->format('Y-m-d')) }}">
        <label for="date">Date</label>
    </div>

    <div class="form-floating mb-3">
        <select name="category" id="category" class="form-select">
            <option value="" disabled>Choisir une catégorie</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ old('category', $event->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <label for="category">Catégorie</label>
    </div>

    <div class="form-floating mb-3">
        <textarea name="details" class="form-control" placeholder="Détails" id="details" style="height: 120px">{{ old('details', $event->details) }}</textarea>
        <label for="details">Détails</label>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image</label>
        <input type="file" name="image" class="form-control" id="image" accept="image/*" onchange="previewImage(event)">
        @if($event->image)
            <img id="imagePreview" src="{{ Storage::url($event->image) }}" class="img-fluid mt-3" style="max-height: 200px;" />
        @else
            <img id="imagePreview" class="img-fluid mt-3 d-none" style="max-height: 200px;" />
        @endif
    </div>

    <button type="submit" class="btn btn-success w-100 py-2">Enregistrer les modifications</button>
</form>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('imagePreview');
    if(input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.classList.remove('d-none');
    }
}

// Location autocomplete
$(document).ready(function() {
    $('#location').on('input', function() {
        let query = $(this).val();
        if(query.length < 2){
            $('#location-suggestions').empty();
            return;
        }

        $.get('https://nominatim.openstreetmap.org/search', {
            q: query + ', Tunisie',
            format: 'json',
            addressdetails: 1,
            limit: 5
        }, function(data) {
            let suggestions = '';
            data.forEach(function(item) {
                suggestions += `<a href="#" class="list-group-item list-group-item-action" data-display="${item.display_name}">${item.display_name}</a>`;
            });
            $('#location-suggestions').html(suggestions);
        });
    });

    $(document).on('click', '#location-suggestions a', function(e) {
        e.preventDefault();
        let val = $(this).data('display');
        $('#location').val(val);
        $('#location-suggestions').empty();
    });

    $(document).click(function(e) {
        if(!$(e.target).closest('#location').length) {
            $('#location-suggestions').empty();
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
<script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
<script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
