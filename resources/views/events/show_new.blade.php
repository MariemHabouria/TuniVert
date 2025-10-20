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

    <!-- Event Details Section Start -->
    <div class="container py-5">
        <div class="text-center mx-auto pb-4" style="max-width: 800px;">
            <h5 class="text-uppercase text-primary">Détails de l'événement</h5>
            <h1 class="mb-0">{{ $event->title }}</h1>
        </div>

        <!-- Event Info Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="row g-0">
                <!-- Event Image -->
                <div class="col-lg-6">
                    @if($event->image)
                        <img src="{{ Storage::url($event->image) }}" class="img-fluid rounded-start" style="height: 400px; width: 100%; object-fit: cover;" alt="{{ $event->title }}">
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light rounded-start" style="height: 400px;">
                            <div class="text-center text-muted">
                                <i class="bi bi-calendar-event" style="font-size: 4rem;"></i>
                                <h4 class="mt-3">Événement TuniVert</h4>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Event Details -->
                <div class="col-lg-6">
                    <div class="card-body p-4 p-lg-5">
                        <!-- Event Info -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-danger mb-1">Lieu</h6>
                                        <p class="mb-0 text-muted">{{ $event->location }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-calendar-event"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-success mb-1">Date</h6>
                                        <p class="mb-0 text-muted">{{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-primary mb-1">Organisateur</h6>
                                        <p class="mb-0 text-muted">{{ $event->organizer->name ?? 'TuniVert' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-info mb-1">Participants</h6>
                                        <p class="mb-0 text-muted">{{ $event->participants->count() }} inscrits</p>
                                    </div>
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
                            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Retour aux événements
                            </a>

                            @auth
                                <a href="{{ route('donations.create', ['event_id' => $event->id]) }}" class="btn btn-success">
                                    <i class="bi bi-heart-fill me-2"></i>Faire un Don
                                </a>

                                @if(Auth::id() === $event->organizer_id)
                                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">
                                        <i class="bi bi-pencil-square me-2"></i>Modifier
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter pour participer
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI Donation Suggestions -->
        @if(isset($donationSuggestion) && $donationSuggestion && auth()->check())
            <div class="card border-0 shadow-sm mb-4">
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
                                    <a href="{{ route('donations.create', ['event_id' => $event->id, 'amount' => $amount]) }}" 
                                       class="btn {{ $badgeClass }}">
                                        {{ $levelText }}: {{ $amount }} TND
                                    </a>
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

        <!-- Comments Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h4 class="text-primary mb-4">
                    <i class="bi bi-chat-dots-fill me-2"></i>
                    Commentaires & Discussions
                    <span class="badge bg-primary rounded-pill ms-2">{{ $event->comments->count() }}</span>
                </h4>

                <!-- Add Comment Form -->
                @auth
                    <form action="{{ route('comments.store', $event->id) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <span class="fw-bold">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <textarea name="content" class="form-control" rows="3" placeholder="Partagez votre avis..." required></textarea>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-chat-left-dots me-2"></i>Publier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                @endauth

                <!-- Comments List -->
                @if($event->comments->count() > 0)
                    <div class="comments-list">
                        @foreach($event->comments as $comment)
                            <div class="d-flex gap-3 mb-3 p-3 bg-light rounded">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; min-width: 40px;">
                                    <span class="fw-bold">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <h6 class="mb-0">{{ $comment->user->name }}</h6>
                                        @if($comment->user_id === $event->organizer_id)
                                            <span class="badge bg-primary text-xs">Organisateur</span>
                                        @endif
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-chat-quote text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">Aucun commentaire pour le moment</h5>
                        <p class="text-muted">Soyez le premier à partager vos pensées !</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Event Details Section End -->

    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>