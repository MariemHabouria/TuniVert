
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



    <!-- Navbar start -->
@include('layouts.navbar')

        <!-- Navbar -->
       

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
<div class="container-fluid ev-section py-5 bg-light">
  <div class="container py-5">

    <style>
      :root {
        --ev-bg: #ffffff;
        --ev-soft: #f4f6fa;
        --ev-text: #1f2937;
        --ev-muted: #64748b;
        --ev-primary: #0d6efd;
        --ev-success: #198754;
        --ev-danger: #dc3545;
        --ev-radius: 16px;
        --ev-shadow: 0 10px 28px rgba(0,0,0,.08);
        --ev-shadow-sm: 0 6px 18px rgba(0,0,0,.06);
      }

      .ev-section { background: #f8fafc; }

      .ev-card { display: flex; flex-direction: column; height: 100%; background: var(--ev-bg); border-radius: var(--ev-radius); overflow: hidden; box-shadow: var(--ev-shadow-sm); transition: transform .18s ease, box-shadow .18s ease; }
      .ev-card:hover { transform: translateY(-2px); box-shadow: var(--ev-shadow); }

      .ev-img { position: relative; width: 100%; aspect-ratio: 16/10; background: var(--ev-soft); overflow: hidden; }
      .ev-img img { width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .45s ease; }
      .ev-card:hover .ev-img img { transform: scale(1.04); }

      .ev-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(0,0,0,.0) 50%, rgba(0,0,0,.35) 100%); pointer-events: none; }
      .ev-badges { position: absolute; bottom: .75rem; left: .75rem; right: .75rem; display: flex; flex-wrap: wrap; gap: .5rem; justify-content: space-between; }
      .ev-pill { display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem; border-radius: 999px; font-size: .8rem; font-weight: 600; color: #fff; backdrop-filter: blur(6px); background: rgba(0,0,0,.35); }
      .ev-pill--date { background: rgba(13,110,253,.8); }
      .ev-pill--cat  { background: rgba(25,135,84,.85); }
      .ev-pill--recommended { background: linear-gradient(45deg, #ffd700, #ff8c00); color: #000; animation: sparkle 2s ease-in-out infinite; }

      .ev-body { display: flex; flex-direction: column; padding: 1.1rem 1.2rem 1.2rem; gap: .6rem; }
      .ev-title { color: var(--ev-text); font-weight: 700; font-size: 1.15rem; margin: 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
      .ev-desc { color: var(--ev-muted); font-size: .96rem; margin: .2rem 0 0; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

      .ev-meta, .ev-stats { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; margin-top: .2rem; color: #475569; font-size: .92rem; }
      .ev-meta i { margin-right: .45rem; color: var(--ev-primary); }
      .ev-stats i { margin-right: .45rem; color: #0ea5e9; }

      .ev-sentiment { margin-top: .5rem; font-size: .87rem; font-weight: 600; display: inline-flex; align-items: center; gap: .4rem; padding: .35rem .6rem; border-radius: 999px; color: #fff; }
      .ev-sentiment.positif { background-color: #198754; }
      .ev-sentiment.neutre { background-color: #6c757d; }
      .ev-sentiment.negatif { background-color: #dc3545; }

      .ev-actions { margin-top: .5rem; display: flex; gap: .5rem; align-items: center; }
      .ev-actions .btn { border-radius: 10px; font-weight: 600; }

      .event-carousel .owl-item { padding: .5rem; }

      @media (max-width: 991.98px) { .ev-title { font-size: 1.05rem; } .ev-meta, .ev-stats { grid-template-columns: 1fr; } }
      @media (max-width: 575.98px) { .ev-body { padding: 1rem; } }
      
      @keyframes sparkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.8; transform: scale(1.05); }
      }

      /* Styles existants pour la section générale */
      .ev-img {
        position: relative;
        width: 100%;
        aspect-ratio: 16/10;
        background: var(--ev-soft);
        overflow: hidden;
        cursor: pointer;
      }
      
      .ev-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform .45s ease, filter 0.3s ease;
      }
      
      .ev-card:hover .ev-img img {
        transform: scale(1.04);
        filter: brightness(1.1);
      }
      
      .recommended-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        cursor: pointer;
      }
      
      .recommended-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease, filter 0.3s ease;
      }
      
      .recommended-card:hover .recommended-image img {
        transform: scale(1.1);
        filter: brightness(1.1);
      }
      
      /* Image zoom indicator */
      .image-zoom-indicator {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 8px;
        border-radius: 4px;
        font-size: 12px;
        opacity: 0;
        transition: opacity 0.3s ease;
      }
      
      .ev-img:hover .image-zoom-indicator,
      .recommended-image:hover .image-zoom-indicator {
        opacity: 1;
      }
      .recommended-section {
        position: relative;
        background: linear-gradient(135deg, #1a5f3f 0%, #2d8a57 100%);
        border-radius: 24px;
        padding: 40px 30px;
        margin: 60px 0;
        box-shadow: 0 20px 60px rgba(26, 95, 63, 0.3);
        overflow: hidden;
      }
      
      .recommended-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><circle cx="20" cy="20" r="1" fill="white" opacity="0.1"/></svg>') repeat;
        pointer-events: none;
      }
      
      .recommended-header {
        position: relative;
        z-index: 2;
      }
      
      .recommended-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 12px 24px;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
      }
      
      .recommended-badge i {
        color: #ffd700;
        animation: pulse 2s infinite;
      }
      
      .recommended-title {
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
      }
      
      .recommended-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 1.1rem;
        margin-bottom: 0;
      }
      
      .recommended-container {
        position: relative;
        z-index: 2;
      }
      
      .recommended-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 30px;
      }
      
      .recommended-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }
      
      .recommended-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(26, 95, 63, 0.25);
      }
      
      .recommended-card.featured {
        grid-column: span 1;
        border: 2px solid #90EE90;
        box-shadow: 0 15px 40px rgba(144, 238, 144, 0.3);
      }
      
      .recommended-image {
        position: relative;
        height: 200px;
        overflow: hidden;
      }
      
      .recommended-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
      }
      
      .recommended-card:hover .recommended-image img {
        transform: scale(1.1);
      }
      
      .recommended-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(26, 95, 63, 0.3) 0%, rgba(45, 138, 87, 0.3) 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
      }
      
      .recommended-card:hover .recommended-overlay {
        opacity: 1;
      }
      
      .recommended-badges {
        position: absolute;
        top: 15px;
        left: 15px;
        right: 15px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
      }
      
      .recommended-star {
        background: linear-gradient(135deg, #ffd700, #ffed4e);
        color: #333;
        padding: 8px 12px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        animation: sparkle-star 3s infinite;
      }
      
      .featured-badge {
        background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
        color: white;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 15px rgba(255, 107, 107, 0.4);
      }
      
      .recommended-category {
        position: absolute;
        bottom: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        color: #333;
        padding: 6px 12px;
        border-radius: 15px;
        font-size: 12px;
        font-weight: 600;
      }
      
      .recommended-content {
        padding: 25px;
      }
      
      .recommended-date {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1a5f3f;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 12px;
      }
      
      .recommended-event-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.3;
      }
      
      .recommended-location {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #666;
        font-size: 14px;
        margin-bottom: 15px;
      }
      
      .recommended-description {
        color: #555;
        line-height: 1.6;
        margin-bottom: 20px;
        font-size: 14px;
      }
      
      .recommended-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        padding: 15px;
        background: rgba(26, 95, 63, 0.05);
        border-radius: 12px;
      }
      
      .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #666;
        font-size: 13px;
        font-weight: 500;
      }
      
      .recommended-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #1a5f3f, #2d8a57);
        color: white;
        padding: 12px 25px;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(26, 95, 63, 0.3);
      }
      
      .recommended-btn:hover {
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(26, 95, 63, 0.4);
      }
      
      /* Séparateur visuel */
      .section-divider {
        display: flex;
        align-items: center;
        margin: 60px 0;
        gap: 20px;
      }
      
      .divider-line {
        flex: 1;
        height: 2px;
        background: linear-gradient(90deg, transparent, #ddd, transparent);
      }
      
      .divider-icon {
        background: linear-gradient(135deg, #1a5f3f, #2d8a57);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        box-shadow: 0 4px 15px rgba(26, 95, 63, 0.3);
      }
      
      /* Animations */
      @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
      }
      
      @keyframes sparkle-star {
        0%, 100% { box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4); }
        50% { box-shadow: 0 6px 25px rgba(255, 215, 0, 0.6); }
      }
      
      /* Responsive */
      @media (max-width: 768px) {
        .recommended-section {
          padding: 30px 20px;
          margin: 40px 0;
        }
        
        .recommended-title {
          font-size: 2rem;
        }
        
        .recommended-grid {
          grid-template-columns: 1fr;
          gap: 20px;
        }
        
        .recommended-stats {
          flex-direction: column;
          gap: 10px;
        }
      }
    </style>

    <div class="text-center mx-auto mb-5 ev-head">
      <h5 class="ev-subtitle text-primary mb-2">Événements Recommandés</h5>
      <h1 class="mb-0 display-6">Découvrez les événements personnalisés pour vous</h1>
    </div>

    @if($recommendedEvents->count() > 0)
      <!-- Section Recommandations - Design Spécial -->
      <div class="recommended-section mb-5">
        <div class="recommended-header">
          <div class="text-center mx-auto mb-4">
            <div class="recommended-badge">
              <i class="fas fa-crown"></i>
              <span>Recommandé pour vous</span>
            </div>
            <h2 class="recommended-title">Événements Personnalisés</h2>
            <p class="recommended-subtitle">Sélectionnés spécialement selon vos préférences</p>
          </div>
        </div>
        
        <div class="recommended-container">
          <div class="recommended-grid">
            @foreach ($recommendedEvents as $index => $event)
              @php
                $img = $event->image ? asset($event->image) : asset('img/default-event.jpg');
                $dateHuman = \Carbon\Carbon::parse($event->date);

                // Calcul du sentiment global
                $comments = $event->comments;
                $sentimentCounts = ['positif'=>0,'neutre'=>0,'negatif'=>0];
                foreach($comments as $comment){
                    if($comment->sentiment && isset($sentimentCounts[$comment->sentiment])){
                        $sentimentCounts[$comment->sentiment]++;
                    }
                }
                $dominantSentiment = null;
                $dominantEmoji = '❓';
                $dominantLabel = 'Analyse en attente';
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

              <article class="recommended-card {{ $index === 0 ? 'featured' : '' }}" itemscope itemtype="https://schema.org/Event">
                <div class="recommended-image" data-lightbox="recommended-{{ $event->id }}" data-title="{{ $event->title }}">
                  <img src="{{ $img }}" alt="{{ $event->title }}" loading="lazy" itemprop="image">
                  <div class="recommended-overlay"></div>
                  <div class="image-zoom-indicator">
                    <i class="fas fa-search-plus"></i> Voir
                  </div>
                  <div class="recommended-badges">
                    <span class="recommended-star"><i class="fas fa-star"></i></span>
                    @if($index === 0)
                      <span class="featured-badge">Top Recommandé</span>
                    @endif
                  </div>
                  <div class="recommended-category">{{ $event->category }}</div>
                </div>

                <div class="recommended-content">
                  <div class="recommended-date">
                    <i class="fas fa-calendar-alt"></i>
                    <time itemprop="startDate" datetime="{{ $dateHuman->toDateString() }}">
                      {{ $dateHuman->translatedFormat('d M Y') }}
                    </time>
                  </div>
                  
                  <h3 class="recommended-event-title" itemprop="name">{{ $event->title }}</h3>
                  
                  <div class="recommended-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <span itemprop="location">{{ $event->location }}</span>
                  </div>
                  
                  <p class="recommended-description" itemprop="description">
                    {{ Str::limit($event->details, 100) }}
                  </p>
                  
                  <div class="recommended-stats">
                    <div class="stat-item">
                      <i class="fas fa-users"></i>
                      <span>{{ $event->participants->count() }}</span>
                    </div>
                    <div class="stat-item">
                      <i class="fas fa-comments"></i>
                      <span>{{ $event->comments->count() }}</span>
                    </div>
                    <div class="stat-item">
                      <span>{{ $dominantEmoji }}</span>
                      <span>{{ $dominantLabel }}</span>
                    </div>
                  </div>
                  
                  <a href="{{ route('events.show', $event) }}" class="recommended-btn">
                    <i class="fas fa-arrow-right"></i>
                    Découvrir
                  </a>
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </div>

      <!-- Séparateur visuel -->
      <div class="section-divider">
        <div class="divider-line"></div>
        <div class="divider-icon">
          <i class="fas fa-chevron-down"></i>
        </div>
        <div class="divider-line"></div>
      </div>
    @endif

    <!-- Section des tous les événements -->
    <div class="text-center mx-auto mb-5">
      <h5 class="ev-subtitle text-primary mb-2">Tous les Événements</h5>
    </div>

    @if($events->count() > 0)
      <div class="event-carousel owl-carousel" aria-label="Liste de tous les événements">
        @foreach ($events as $event)
          @php
            $img = $event->image ? asset($event->image) : asset('img/default-event.jpg');
            $dateHuman = \Carbon\Carbon::parse($event->date);

            // Calcul du sentiment global
            $comments = $event->comments;
            $sentimentCounts = ['positif'=>0,'neutre'=>0,'negatif'=>0];
            foreach($comments as $comment){
                if($comment->sentiment && isset($sentimentCounts[$comment->sentiment])){
                    $sentimentCounts[$comment->sentiment]++;
                }
            }
            $dominantSentiment = null;
            $dominantEmoji = '❓';
            $dominantLabel = 'Analyse en attente';
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

          <article class="ev-card" itemscope itemtype="https://schema.org/Event">
            <div class="ev-img" data-lightbox="event-{{ $event->id }}" data-title="{{ $event->title }}">
              <img src="{{ $img }}" alt="Illustration de l'événement {{ $event->title }}" loading="lazy" itemprop="image">
              <div class="ev-overlay"></div>
              <div class="image-zoom-indicator">
                <i class="fas fa-search-plus"></i> Voir
              </div>
              <div class="ev-badges">
                <span class="ev-pill ev-pill--date"><i class="fas fa-calendar-alt"></i>
                  <time itemprop="startDate" datetime="{{ $dateHuman->toDateString() }}">
                    {{ $dateHuman->translatedFormat('d F Y') }}
                  </time>
                </span>
                @if(!empty($event->category))
                  <span class="ev-pill ev-pill--cat"><i class="fas fa-tag"></i> {{ $event->category }}</span>
                @endif
              </div>
            </div>

            <div class="ev-body">
              <h3 class="ev-title" itemprop="name">{{ $event->title }}</h3>
              <p class="ev-desc" itemprop="description">{{ \Illuminate\Support\Str::limit(strip_tags($event->details ?? ''), 160) }}</p>

              <div class="ev-meta">
                <div><i class="fas fa-map-marker-alt"></i><span itemprop="location">{{ $event->location }}</span></div>
                <div><i class="fas fa-clock"></i>
                  {{ $dateHuman->isFuture() ? 'Dans ' . $dateHuman->diffForHumans(null, true) : $dateHuman->diffForHumans() }}
                </div>
              </div>

              <div class="ev-stats">
                <div><i class="fas fa-users"></i>{{ $event->participants->count() }} participant{{ $event->participants->count() > 1 ? 's' : '' }}</div>
                <div><i class="fas fa-comments"></i>{{ $event->comments->count() }} commentaire{{ $event->comments->count() > 1 ? 's' : '' }}</div>
              </div>

              {{-- Badge sentiment simplifié --}}
              <div class="ev-sentiment {{ $dominantSentiment ?? '' }}">
                {{ $dominantEmoji }} {{ $dominantLabel }}
              </div>

              <div class="ev-actions">
                <a class="btn btn-primary text-white" href="{{ route('events.show', $event->id) }}">
                  <i class="bi bi-info-circle me-1"></i> Détails
                </a>

                @auth
                  @php $isParticipating = $event->participants->contains('user_id', Auth::id()); @endphp
                  <form action="{{ route('events.participate', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" {{ $isParticipating ? 'disabled' : '' }}>
                      <i class="bi bi-check2-circle me-1"></i> {{ $isParticipating ? 'Déjà inscrit' : 'Participer' }}
                    </button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="btn btn-success">
                    <i class="bi bi-person-plus me-1"></i> Participer
                  </a>
                @endauth
              </div>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <p class="text-center fs-5 text-muted mb-0">Aucun événement disponible pour le moment.</p>
    @endif
  </div>
</div>
<!-- Events End -->


<!-- Section des événements recommandés -->
@include('events.partials.recommended')
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
        

        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end text-body">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


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