
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>


    <meta charset="utf-8">
    <title>TuniVert - Environmental & Nature Website</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Environmental protection, nature conservation, Tunisia" name="keywords">
    <meta content="TuniVert is dedicated to protecting the environment and promoting sustainable practices in Tunisia." name="description">

    <!-- CSRF Token -->
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

    @yield('styles')
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
        <h3 class="text-white display-3 mb-4">Upcoming Events</h3>
        <p class="fs-5 text-white mb-4">Découvrez nos événements écologiques et rejoignez-nous !</p>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item active text-white">Events</li>
        </ol>
            <!-- Bouton Ajouter un événement (toujours visible) -->
            @auth
        @if(auth()->user()->role === 'association')
        <a href="{{ route('events.create') }}" class="btn btn-success mt-3">
            Ajouter un événement
        </a>
        @endif
        @endauth
    </div>
</div>


<!-- Header End -->
<!--css-->

 


<!-- Events Start -->
<div class="container-fluid event py-5 bg-light">
    <div class="container py-5">
        <style>
            /* General container styling */
            .event {
                background-color: #f8f9fa;
            }

            /* Card styling */
            .event-item {
                display: flex;
                flex-direction: column;
                height: 100%;
                background: white;
                border-radius: 15px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
                overflow: hidden;
            }

            .event-item:hover {
                transform: translateY(-5px);
            }

            /* Image wrapper */
            .event-img-wrapper {
                width: 100%;
                height: 250px;
                overflow: hidden;
                border-top-left-radius: 15px;
                border-top-right-radius: 15px;
            }

            .event-img-wrapper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .event-item:hover .event-img-wrapper img {
                transform: scale(1.05);
            }

            /* Content styling */
            .event-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                padding: 1.5rem;
            }

            /* Typography */
            .event-content h4 {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 0.75rem;
                color: #333;
            }

            .event-content p {
                font-size: 0.95rem;
                line-height: 1.5;
                color: #666;
            }

            /* Meta information */
            .event-meta {
                display: flex;
                justify-content: space-between;
                margin-bottom: 1rem;
                font-size: 0.9rem;
                color: #6c757d;
            }

            /* Buttons */
            .event-buttons {
                margin-top: auto;
                display: flex;
                gap: 0.5rem;
            }

            .btn-primary, .btn-success {
                padding: 0.5rem 1.5rem;
                border-radius: 5px;
                font-size: 0.9rem;
                transition: all 0.3s ease;
            }

            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }

            .btn-success {
                background-color: #28a745;
                border-color: #28a745;
            }

            .btn-success:hover:not(:disabled) {
                background-color: #218838;
                border-color: #218838;
            }

            .btn-success:disabled {
                opacity: 0.7;
                cursor: not-allowed;
            }

            /* Carousel adjustments */
            .event-carousel .owl-item {
                padding: 0.5rem;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .event-img-wrapper {
                    height: 200px;
                }

                .event-content h4 {
                    font-size: 1.1rem;
                }

                .event-buttons {
                    flex-direction: column;
                    gap: 0.75rem;
                }

                .btn-primary, .btn-success {
                    width: 100%;
                    text-align: center;
                }
            }
        </style>

        <div class="text-center mx-auto mb-5" style="max-width: 800px;">
            <h5 class="text-uppercase text-primary mb-3">Événements à venir</h5>
            <h1 class="mb-0 display-4">Rejoignez nos actions pour un avenir durable</h1>
        </div>

        @if($events->count() > 0)
            <div class="event-carousel owl-carousel">
                @foreach ($events as $event)
                    <div class="event-item">
                        <div class="event-img-wrapper">
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                        </div>

                        <div class="event-content">
                            <div class="event-meta">
                                <span><i class="fas fa-map-marker-alt me-2"></i>{{ $event->location }}</span>
                                <span><i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::parse($event->date)->format('d M, Y') }}</span>
                            </div>
                            <h4>{{ $event->title }}</h4>
                            <p>{{ Str::limit($event->details, 120) }}</p>
                            
                            <div class="event-meta">
                                <span><i class="fas fa-users me-2"></i>{{ $event->participants->count() }} participant{{ $event->participants->count() > 1 ? 's' : '' }}</span>
                                <span><i class="fas fa-comments me-2"></i>{{ $event->comments->count() }} commentaire{{ $event->comments->count() > 1 ? 's' : '' }}</span>
                            </div>

                            <div class="event-buttons">
                                <a class="btn btn-primary text-white" href="{{ route('events.show', $event->id) }}">Détails</a>

                                @auth
                                    @php
                                        $isParticipating = $event->participants->contains('user_id', Auth::id());
                                    @endphp
                                    <form action="{{ route('events.participate', $event->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success" {{ $isParticipating ? 'disabled' : '' }}>
                                            {{ $isParticipating ? 'Déjà inscrit' : 'Participer' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-success">Participer</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-center fs-5 text-muted">Aucun événement disponible pour le moment.</p>
        @endif
    </div>
</div>
<!-- Events End -->
<!-- Section des événements recommandés -->
@include('events.partials.recommended')
@include('layouts.footer')


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>
        

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

    </body>

</html>