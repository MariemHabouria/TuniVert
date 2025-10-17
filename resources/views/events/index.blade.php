
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
                    <a href="{{ route('donations.create') }}" class="nav-item nav-link {{ request()->routeIs('donations.create') ? 'active' : '' }}">Donations</a>
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
    </style>

    <div class="text-center mx-auto mb-5 ev-head">
      <h5 class="ev-subtitle text-primary mb-2">Événements à venir</h5>
      <h1 class="mb-0 display-6">Rejoignez nos actions pour un avenir durable</h1>
    </div>

    @if($events->count() > 0)
      <div class="event-carousel owl-carousel" aria-label="Liste d'événements à venir">
        @foreach ($events as $event)
          @php
            $img = $event->image ? Storage::url($event->image) : asset('img/default-event.jpg');
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
            <div class="ev-img">
              <img src="{{ $img }}" alt="Illustration de l'événement {{ $event->title }}" loading="lazy" itemprop="image">
              <div class="ev-overlay"></div>
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