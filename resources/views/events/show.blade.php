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
                        <img src="{{ asset($event->image) }}" class="img-fluid rounded-start" style="height: 400px; width: 100%; object-fit: cover;" alt="{{ $event->title }}">
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
                                {{-- Participation Button --}}
                                @php $isParticipating = $event->participants->contains('user_id', Auth::id()); @endphp
                                <form action="{{ route('events.participate', $event->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn {{ $isParticipating ? 'btn-warning' : 'btn-info' }}">
                                        @if($isParticipating)
                                            <i class="bi bi-person-check-fill me-2"></i>Déjà inscrit
                                        @else
                                            <i class="bi bi-person-plus-fill me-2"></i>Participer
                                        @endif
                                    </button>
                                </form>

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
            <div class="card border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #1a5f3f 0%, #2d8a57 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="text-white mb-3">
                                <h4 class="text-white mb-2">
                                    <i class="bi bi-robot text-warning me-2"></i>
                                    <strong>Suggestions IA de Don Personnalisé</strong>
                                    <span class="badge bg-warning text-dark ms-2">
                                        <i class="bi bi-sparkles me-1"></i>Personnalisé
                                    </span>
                                </h4>
                                <p class="mb-0 opacity-90">
                                    <i class="bi bi-lightbulb-fill text-warning me-2"></i>
                                    Notre IA suggère un montant personnalisé basé sur votre historique et cet événement.
                                    <br>
                                    <strong class="text-warning">
                                        <i class="bi bi-graph-up me-1"></i>
                                        Probabilité de don: {{ number_format($donationSuggestion['propensity'] * 100, 1) }}%
                                    </strong>
                                </p>
                            </div>
                            
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($donationSuggestion['amounts'] as $level => $amount)
                                    @php
                                        // Enhanced color scheme for donation levels
                                        $buttonConfig = match($level) {
                                            'low' => [
                                                'class' => 'btn-outline-light border-2',
                                                'icon' => 'bi-heart',
                                                'label' => 'Modeste',
                                                'glow' => 'rgba(255,255,255,0.3)'
                                            ],
                                            'mid' => [
                                                'class' => 'btn-warning text-dark border-0',
                                                'icon' => 'bi-heart-fill',
                                                'label' => 'Recommandé',
                                                'glow' => 'rgba(255,193,7,0.4)'
                                            ],
                                            'high' => [
                                                'class' => 'btn-danger border-0',
                                                'icon' => 'bi-heart-fill',
                                                'label' => 'Généreux',
                                                'glow' => 'rgba(220,53,69,0.4)'
                                            ],
                                            default => [
                                                'class' => 'btn-outline-light',
                                                'icon' => 'bi-heart',
                                                'label' => ucfirst($level),
                                                'glow' => 'rgba(255,255,255,0.2)'
                                            ]
                                        };
                                    @endphp
                                    
                                    <a href="{{ route('donations.create', ['event_id' => $event->id, 'amount' => $amount]) }}" 
                                       class="btn {{ $buttonConfig['class'] }} btn-lg px-4 py-3 position-relative overflow-hidden donation-btn"
                                       style="min-width: 150px; transition: all 0.3s ease; box-shadow: 0 4px 20px {{ $buttonConfig['glow'] }};"
                                       data-level="{{ $level }}">
                                        
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="{{ $buttonConfig['icon'] }} mb-1 donation-icon" style="font-size: 1.3rem;"></i>
                                            <div class="fw-bold" style="font-size: 0.9rem;">{{ $buttonConfig['label'] }}</div>
                                            <div class="fw-bold text-decoration-underline" style="font-size: 1.2rem;">
                                                {{ number_format($amount, 2) }} TND
                                            </div>
                                        </div>
                                        
                                        <!-- Special badge for recommended option -->
                                        @if($level === 'mid')
                                            <div class="position-absolute top-0 end-0 p-2">
                                                <i class="bi bi-star-fill text-white recommendation-star" style="font-size: 0.8rem;"></i>
                                            </div>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="col-md-4 text-center">
                            <div class="text-center text-white">
                                <div class="position-relative d-inline-block">
                                    <i class="bi bi-heart-fill text-warning ai-heart" style="font-size: 5rem; filter: drop-shadow(0 0 15px rgba(255,193,7,0.6));"></i>
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <i class="bi bi-cpu-fill text-white" style="font-size: 2rem; animation: pulse 2s infinite;"></i>
                                    </div>
                                </div>
                                <p class="small opacity-90 mb-0 mt-3">
                                    <i class="bi bi-magic me-1"></i>
                                    <strong>Soutenez avec intelligence</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Enhanced AI Donation Suggestions CSS -->
            <style>
                @keyframes pulse {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.1); }
                }
                
                @keyframes sparkle {
                    0%, 100% { transform: scale(1) rotate(0deg); opacity: 1; }
                    50% { transform: scale(1.2) rotate(180deg); opacity: 0.8; }
                }
                
                @keyframes heartbeat {
                    0% { transform: scale(1); }
                    14% { transform: scale(1.1); }
                    28% { transform: scale(1); }
                    42% { transform: scale(1.1); }
                    70% { transform: scale(1); }
                }
                
                .donation-btn:hover {
                    transform: translateY(-3px) scale(1.02);
                    box-shadow: 0 8px 25px rgba(0,0,0,0.3) !important;
                }
                
                .donation-btn:hover .donation-icon {
                    animation: heartbeat 1s ease-in-out infinite;
                }
                
                .recommendation-star {
                    animation: sparkle 2s infinite;
                }
                
                .ai-heart {
                    animation: heartbeat 3s ease-in-out infinite;
                }
                
                .donation-btn[data-level="mid"] {
                    position: relative;
                    overflow: hidden;
                }
                
                .donation-btn[data-level="mid"]::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
                    transform: rotate(45deg);
                    transition: all 0.5s;
                    opacity: 0;
                }
                
                .donation-btn[data-level="mid"]:hover::before {
                    animation: shimmer 1.5s infinite;
                    opacity: 1;
                }
                
                @keyframes shimmer {
                    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
                    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
                }
            </style>
        @endif

        <!-- Comments Section -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="text-primary mb-0">
                        <i class="bi bi-chat-dots-fill me-2"></i>
                        Commentaires & Discussions
                        <span class="badge bg-primary rounded-pill ms-2">{{ $event->comments->count() }}</span>
                    </h4>
                    
                    {{-- Overall Sentiment Analysis --}}
                    @if($event->comments->count() > 0)
                        @php
                            $comments = $event->comments;
                            $sentimentCounts = ['positif'=>0,'neutre'=>0,'negatif'=>0];
                            foreach($comments as $comment){
                                if($comment->sentiment && isset($sentimentCounts[$comment->sentiment])){
                                    $sentimentCounts[$comment->sentiment]++;
                                }
                            }
                            $dominantSentiment = null;
                            $dominantEmoji = '❓';
                            $dominantLabel = 'Analyse en cours';
                            if(array_sum($sentimentCounts) > 0){
                                $dominantSentiment = array_keys($sentimentCounts, max($sentimentCounts))[0];
                                $dominantEmoji = match($dominantSentiment){
                                    'positif' => '😊',
                                    'neutre'  => '😐',
                                    'negatif' => '😞',
                                };
                                $dominantLabel = ucfirst($dominantSentiment);
                            }
                        @endphp
                        <div class="text-end">
                            <small class="text-muted">Sentiment général:</small>
                            <span class="badge bg-{{
                                $dominantSentiment === 'positif' ? 'success' :
                                ($dominantSentiment === 'negatif' ? 'danger' : 'secondary')
                            }} ms-1">
                                {{ $dominantEmoji }} {{ $dominantLabel }}
                            </span>
                        </div>
                    @endif
                </div>

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
                                        
                                        {{-- Sentiment Analysis Badge --}}
                                        @if($comment->sentiment)
                                            @php
                                                $sentimentConfig = match($comment->sentiment) {
                                                    'positif' => ['class' => 'bg-success', 'icon' => '😊', 'text' => 'Positif'],
                                                    'negatif' => ['class' => 'bg-danger', 'icon' => '😞', 'text' => 'Négatif'],
                                                    'neutre' => ['class' => 'bg-secondary', 'icon' => '😐', 'text' => 'Neutre'],
                                                    'en_attente' => ['class' => 'bg-warning text-dark', 'icon' => '⏳', 'text' => 'En analyse'],
                                                    default => ['class' => 'bg-secondary', 'icon' => '❓', 'text' => 'Inconnu']
                                                };
                                            @endphp
                                            <span class="badge {{ $sentimentConfig['class'] }} rounded-pill text-xs">
                                                {{ $sentimentConfig['icon'] }} {{ $sentimentConfig['text'] }}
                                            </span>
                                        @endif
                                        
                                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                    </div>
                                    
                                    <!-- Comment Content Display -->
                                    <div id="comment-display-{{ $comment->id }}">
                                        <p class="mb-0">{{ $comment->content }}</p>
                                    </div>
                                    
                                    <!-- Comment Edit Form (Hidden by default) -->
                                    <div id="comment-edit-{{ $comment->id }}" style="display: none;">
                                        <form action="{{ route('comments.update', $comment->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="mb-2">
                                                <textarea name="content" class="form-control" rows="3" required>{{ $comment->content }}</textarea>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check2"></i> Sauvegarder
                                                </button>
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                        onclick="toggleEditComment({{ $comment->id }})">
                                                    <i class="bi bi-x"></i> Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    {{-- Actions for comment owner --}}
                                    @auth
                                        @if(Auth::id() === $comment->user_id)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <button type="button" class="btn btn-link btn-sm p-0 text-info me-2"
                                                            onclick="toggleEditComment({{ $comment->id }})">
                                                        <i class="bi bi-pencil"></i> Modifier
                                                    </button>
                                                    
                                                    @if($comment->sentiment === 'en_attente')
                                                        <form action="{{ route('comments.reanalyse', $comment->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-link btn-sm p-0 text-primary me-2">
                                                                <i class="bi bi-arrow-clockwise"></i> Réanalyser
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Supprimer ce commentaire ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link btn-sm p-0 text-danger">
                                                            <i class="bi bi-trash"></i> Supprimer
                                                        </button>
                                                    </form>
                                                </small>
                                            </div>
                                        @endif
                                    @endauth
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
    
    <!-- Comment Edit Toggle Script -->
    <script>
        function toggleEditComment(commentId) {
            const displayDiv = document.getElementById('comment-display-' + commentId);
            const editDiv = document.getElementById('comment-edit-' + commentId);
            
            if (editDiv.style.display === 'none') {
                // Show edit form, hide display
                displayDiv.style.display = 'none';
                editDiv.style.display = 'block';
            } else {
                // Show display, hide edit form
                displayDiv.style.display = 'block';
                editDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>