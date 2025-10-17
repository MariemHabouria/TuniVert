<!-- Events Start -->
<div class="container-fluid ev-section py-5 bg-light">
  <div class="container py-5">

    <!-- ====== Styles dédiés aux cartes d'événements ====== -->
    <style>
      :root {
        --ev-bg: #ffffff;
        --ev-soft: #f4f6fa;
        --ev-text: #1f2937;   /* gris foncé */
        --ev-muted: #64748b;  /* gris moyen */
        --ev-primary: #0d6efd;
        --ev-success: #198754;
        --ev-danger: #dc3545;
        --ev-warning: #ffc107;
        --ev-radius: 16px;
        --ev-shadow: 0 10px 28px rgba(0,0,0,.08);
        --ev-shadow-sm: 0 6px 18px rgba(0,0,0,.06);
      }

      .ev-section { background: #f8fafc; }

      /* --- Header de section --- */
      .ev-head {
        max-width: 880px;
      }
      .ev-subtitle {
        text-transform: uppercase;
        letter-spacing: .12em;
        font-weight: 700;
      }

      /* --- Carte --- */
      .ev-card {
        position: relative;
        display: flex; flex-direction: column;
        height: 100%;
        background: var(--ev-bg);
        border-radius: var(--ev-radius);
        overflow: hidden;
        box-shadow: var(--ev-shadow-sm);
        transition: transform .18s ease, box-shadow .18s ease;
        will-change: transform, box-shadow;
      }
      .ev-card:hover { transform: translateY(-2px); box-shadow: var(--ev-shadow); }

      /* --- Image --- */
      .ev-img {
        position: relative; width: 100%;
        aspect-ratio: 16/10; /* ratio propre, responsive */
        background: var(--ev-soft);
        overflow: hidden;
      }
      .ev-img img {
        width: 100%; height: 100%; object-fit: cover; display: block;
        transition: transform .45s ease;
      }
      .ev-card:hover .ev-img img { transform: scale(1.04); }

      /* Overlay fade + badges sur l'image */
      .ev-overlay {
        position: absolute; inset: 0;
        background: linear-gradient(180deg, rgba(0,0,0,.0) 50%, rgba(0,0,0,.35) 100%);
        pointer-events: none;
      }
      .ev-badges {
        position: absolute; bottom: .75rem; left: .75rem; right: .75rem;
        display: flex; flex-wrap: wrap; gap: .5rem; justify-content: space-between;
      }
      .ev-pill {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .35rem .6rem; border-radius: 999px;
        font-size: .8rem; font-weight: 600; color: #fff;
        backdrop-filter: blur(6px);
        background: rgba(0,0,0,.35);
      }
      .ev-pill--date { background: rgba(13,110,253,.8); }
      .ev-pill--cat  { background: rgba(25,135,84,.85); }
      .ev-pill i { line-height: 1; }

      /* --- Contenu --- */
      .ev-body { display: flex; flex-direction: column; padding: 1.1rem 1.2rem 1.2rem; gap: .6rem; }
      .ev-title {
        color: var(--ev-text); font-weight: 700; font-size: 1.15rem; margin: 0;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
      }
      .ev-desc {
        color: var(--ev-muted); font-size: .96rem; margin: .2rem 0 0;
        display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;
      }

      /* --- Meta --- */
      .ev-meta {
        display: grid; grid-template-columns: 1fr 1fr; gap: .6rem;
        margin-top: .2rem; color: #475569; font-size: .92rem;
      }
      .ev-meta i { margin-right: .45rem; color: var(--ev-primary); }

      .ev-stats {
        display: grid; grid-template-columns: 1fr 1fr; gap: .6rem;
        color: #475569; font-size: .9rem; margin-top: .2rem;
      }
      .ev-stats i { margin-right: .45rem; color: #0ea5e9; }

      /* --- Actions --- */
      .ev-actions {
        margin-top: .5rem; display: flex; gap: .5rem; align-items: center;
      }
      .ev-actions .btn { border-radius: 10px; font-weight: 600; }
      .ev-actions .btn-primary { box-shadow: 0 6px 16px rgba(13,110,253,.18); }
      .ev-actions .btn-success { box-shadow: 0 6px 16px rgba(25,135,84,.18); }
      .ev-actions .btn:disabled { opacity: .75; cursor: not-allowed; }

      /* --- Owl spacing --- */
      .event-carousel .owl-item { padding: .5rem; }

      /* --- Responsive --- */
      @media (max-width: 991.98px) {
        .ev-title { font-size: 1.05rem; }
        .ev-meta, .ev-stats { grid-template-columns: 1fr; }
      }
      @media (max-width: 575.98px) {
        .ev-body { padding: 1rem; }
      }
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
          @endphp

          <article class="ev-card" itemscope itemtype="https://schema.org/Event">
            <!-- Image -->
            <div class="ev-img">
              <img src="{{ $img }}" alt="Illustration de l'événement {{ $event->title }}" loading="lazy" itemprop="image">
              <div class="ev-overlay"></div>

              <div class="ev-badges">
                <span class="ev-pill ev-pill--date" title="Date de l'événement">
                  <i class="fas fa-calendar-alt"></i>
                  <time itemprop="startDate" datetime="{{ $dateHuman->toDateString() }}">
                    {{ $dateHuman->translatedFormat('d F Y') }}
                  </time>
                </span>

                @if(!empty($event->category))
                  <span class="ev-pill ev-pill--cat" title="Catégorie">
                    <i class="fas fa-tag"></i> {{ $event->category }}
                  </span>
                @endif
              </div>
            </div>

            <!-- Contenu -->
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

              <div class="ev-actions">
                <a class="btn btn-primary text-white" href="{{ route('events.show', $event->id) }}" aria-label="Voir les détails de {{ $event->title }}">
                  <i class="bi bi-info-circle me-1"></i> Détails
                </a>

                @auth
                  @php $isParticipating = $event->participants->contains('user_id', Auth::id()); @endphp
                  <form action="{{ route('events.participate', $event->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success"
                            {{ $isParticipating ? 'disabled' : '' }}
                            aria-disabled="{{ $isParticipating ? 'true' : 'false' }}"
                            aria-label="{{ $isParticipating ? 'Déjà inscrit à ' . $event->title : 'Participer à ' . $event->title }}">
                      <i class="bi bi-check2-circle me-1"></i>
                      {{ $isParticipating ? 'Déjà inscrit' : 'Participer' }}
                    </button>
                  </form>
                @else
                  <a href="{{ route('login') }}" class="btn btn-success" aria-label="Se connecter pour participer à {{ $event->title }}">
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