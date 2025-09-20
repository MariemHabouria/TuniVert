<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Challenges Disponibles | Tunivert</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Bootstrap + Style -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Navbar Start (inchangé) -->
    <div class="container-fluid fixed-top px-0">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-light navbar-expand-xl">
                <a href="{{ url('/') }}" class="navbar-brand ms-3">
                    <h1 class="text-primary display-5">Tunivert</h1>
                </a>
                <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="{{ url('/') }}" class="nav-item nav-link">Home</a>
                        <a href="#" class="nav-item nav-link">About</a>
                        <a href="#" class="nav-item nav-link">Services</a>
                        <a href="#" class="nav-item nav-link active">Challenges</a>
                        <a href="{{ url('/contact') }}" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- Navbar End -->

    <!-- Header / Breadcrumb -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="text-white display-3 mb-4">Challenges Disponibles</h3>
            <p class="fs-5 text-white mb-4">Participez à nos challenges pour agir pour l'environnement !</p>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active text-white">Challenges</li>
            </ol>    
        </div>
    </div>

    <!-- Challenges Start -->
    <div class="container-fluid event py-5">
        <div class="container py-5">
            <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                <h5 class="text-uppercase text-primary">Nos Challenges</h5>
                <h1 class="mb-0">Relevez un défi pour protéger notre planète</h1>
            </div>

            <div class="event-carousel owl-carousel">
                @foreach($challenges as $challenge)
                    <div class="event-item">
                        <img src="{{ asset('img/challenge-default.jpg') }}" class="img-fluid w-100" alt="Challenge Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>
                                    {{ \Carbon\Carbon::parse($challenge->date_debut)->format('d M, Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($challenge->date_fin)->format('d M, Y') }}
                                </span>
                                <span class="text-body"><i class="fas fa-trophy me-2"></i>{{ $challenge->difficulte }}</span>
                            </div>
                            <h4 class="mb-4">{{ $challenge->titre }}</h4>
                            <p class="mb-4">{{ Str::limit($challenge->description, 100) }}</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" 
                               href="{{ route('challenges.show', $challenge->id) }}">
                               Voir le challenge
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Challenges End -->

    <!-- Footer Start (inchangé) -->
    <div class="container-fluid footer bg-dark text-body py-5">
        <div class="container text-center">
            <p class="mb-0 text-white">&copy; 2025 Tunivert. Tous droits réservés.</p>
        </div>
    </div>
    <!-- Footer End -->

    <!-- JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        $(document).ready(function(){
            $(".event-carousel").owlCarousel({
                autoplay:true,
                autoplayTimeout:5000,
                loop:true,
                margin:30,
                nav:true,
                dots:false,
                responsive:{
                    0:{items:1},
                    576:{items:2},
                    992:{items:3}
                }
            });
        });
    </script>
</body>
</html>
