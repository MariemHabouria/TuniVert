<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>TuniVert - {{ $event->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- Icon Fonts -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap & Custom CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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

<!-- Contenu principal (amélioré) -->
<div class="container py-5" style="margin-top: 120px;">
  <div class="event-card card shadow-lg border-0 rounded-4 overflow-hidden">
    <div class="row g-0">
      <!-- Image -->
      <div class="col-lg-5">
        @if($event->image)
          <img src="{{ Storage::url($event->image) }}" class="img-fluid h-100 w-100 object-cover" alt="{{ $event->title }}">
        @else
          <img src="{{ asset('img/default-event.jpg') }}" class="img-fluid h-100 w-100 object-cover" alt="Image par défaut">
        @endif
      </div>

      <!-- Infos -->
      <div class="col-lg-7">
        <div class="card-body p-4 p-md-5">
          <div class="d-flex align-items-start justify-content-between gap-3">
            <h2 class="card-title text-primary mb-3 fw-bold">{{ $event->title }}</h2>

            {{-- Chip catégorie --}}
            @if($event->category)
              <span class="badge rounded-pill bg-soft-primary text-primary-emphasis px-3 py-2">
                <i class="bi bi-tags-fill me-1"></i>{{ $event->category }}
              </span>
            @endif
          </div>

          <div class="row gy-3 text-secondary">
            <div class="col-sm-6">
              <div class="d-flex align-items-center">
                <i class="bi bi-geo-alt-fill text-danger me-2 fs-5"></i>
                <div><span class="text-dark fw-semibold">Lieu :</span> {{ $event->location }}</div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="d-flex align-items-center">
                <i class="bi bi-calendar-event text-success me-2 fs-5"></i>
                <div><span class="text-dark fw-semibold">Date :</span>
                  {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }}
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="d-flex align-items-center">
                <i class="bi bi-person-circle text-primary me-2 fs-5"></i>
                <div><span class="text-dark fw-semibold">Organisateur :</span> {{ $event->organizer->name ?? 'N/A' }}</div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="d-flex align-items-center">
                <i class="bi bi-people-fill text-secondary me-2 fs-5"></i>
                <div><span class="text-dark fw-semibold">Participants :</span> {{ $event->participants->count() }}</div>
              </div>
            </div>
          </div>

          @if($event->details)
            <hr class="my-4">
            <div class="prose text-dark">
              {!! nl2br(e($event->details)) !!}
            </div>
          @endif

          <div class="d-flex flex-wrap align-items-center gap-2 mt-4">
            <a href="{{ route('events.index') }}" class="btn btn-outline-secondary">
              <i class="bi bi-arrow-left"></i> Retour
            </a>

            @auth
              {{-- Donation Button - Available for all authenticated users --}}
              <a href="{{ route('donations.create', ['event_id' => $event->id]) }}" class="btn btn-success">
                <i class="bi bi-heart-fill"></i> Faire un Don
              </a>

              @if(Auth::id() === $event->organizer_id)
                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning">
                  <i class="bi bi-pencil-square"></i> Modifier
                </a>

                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Supprimer cet événement ?')">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash3"></i> Supprimer
                  </button>
                </form>
              @endif
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

  {{-- ====== Commentaires (refonte avec classes CSS dédiées) ====== --}}
<section id="comments" class="mt-5">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
    <h3 class="h4 m-0">💬 Commentaires <span class="text-muted fw-normal">({{ $event->comments->count() }})</span></h3>

    {{-- Filtre/tri (optionnel) --}}
    <form method="GET" class="d-flex align-items-center gap-2 ms-auto">
      <label for="sort" class="small text-muted">Trier par</label>
      <select id="sort" name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
        <option value="new" @selected(request('sort','new')==='new')>Plus récents</option>
        <option value="old" @selected(request('sort')==='old')>Plus anciens</option>
      </select>
    </form>
  </div>

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

  {{-- Liste des commentaires --}}
  <div class="cmt-list mt-4">
    @forelse($event->comments as $comment)
      @php
        $sentiment = $comment->sentiment; // 'positif', 'negatif', 'neutre' ou null
        $sentimentLabel = [
          'positif' => 'Positif',
          'negatif' => 'Négatif',
          'neutre'  => 'Neutre',
        ][$sentiment ?? ''] ?? null;

        $sentimentEmoji = [
          'positif' => '😊',
          'negatif' => '😞',
          'neutre'  => '😐',
        ][$sentiment ?? ''] ?? null;
      @endphp

      <article class="cmt-card card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-sm-4">
          <div class="d-flex align-items-start gap-3">
            {{-- Avatar --}}
            <div class="cmt-avatar">
              <span class="cmt-avatar__initial">
                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
              </span>
            </div>

            {{-- Contenu --}}
            <div class="flex-grow-1">
              {{-- En-tête auteur/meta --}}
              <div class="cmt-header d-flex flex-wrap align-items-center gap-2">
                <strong class="cmt-author">{{ $comment->user->name }}</strong>

                @if($comment->user_id === $event->organizer_id)
                  <span class="cmt-badge cmt-badge--role">Organisateur</span>
                @endif

                @if($sentimentLabel)
                  <span class="cmt-badge cmt-badge--sentiment cmt-badge--{{ $sentiment }}">
                    {{ $sentimentEmoji }} {{ $sentimentLabel }}
                  </span>
                @else
                  <span class="cmt-badge cmt-badge--sentiment cmt-badge--pending">Analyse en attente</span>
                @endif

                <span class="cmt-dot">•</span>

                <time class="cmt-time text-muted" datetime="{{ $comment->created_at }}">
                  {{ $comment->created_at->diffForHumans() }}
                  @if($comment->updated_at && $comment->updated_at->ne($comment->created_at))
                    · <span title="{{ $comment->updated_at }}">modifié</span>
                  @endif
                </time>

                {{-- Actions (affichées au survol) --}}
                @auth
                  @if(Auth::id() === $comment->user_id)
                    <div class="cmt-actions ms-auto d-flex align-items-center gap-2">
                      <button class="btn btn-sm btn-outline-secondary"
                              type="button"
                              data-bs-toggle="collapse"
                              data-bs-target="#edit-{{ $comment->id }}"
                              aria-expanded="false"
                              aria-controls="edit-{{ $comment->id }}">
                        <i class="bi bi-pencil-square"></i> Modifier
                      </button>

                      <form action="{{ route('comments.destroy', $comment->id) }}"
                            method="POST"
                            onsubmit="return confirm('Supprimer ce commentaire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                          <i class="bi bi-trash"></i>
                        </button>
                      </form>
                    </div>
                  @endif
                @endauth
              </div>

              {{-- Texte --}}
              <div class="cmt-text mt-2">
                {!! nl2br(e($comment->content)) !!}
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
      </article>
    @empty
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
          <i class="bi bi-chat-square-text fs-1 d-block mb-2"></i>
          Aucun commentaire pour le moment.
        </div>
      </div>
    @endforelse
  </div>
</section>

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
</body>
</html>