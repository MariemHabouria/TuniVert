{{-- resources/views/formations/show.blade.php --}}
@extends('layouts.site')

@section('title', $formation->titre)

@section('content')
@php
  $isOrga = auth()->check() && auth()->id() === ($formation->organisateur_id ?? null);
@endphp
<div class="container py-5">
  {{-- ===== Titre / meta ===== --}}
  <div class="row align-items-center mb-4 g-3">
    <div class="col-lg-8">
      <h1 class="h2 mb-2 fw-semibold">{{ $formation->titre }}</h1>
      <div class="d-flex flex-wrap align-items-center gap-2 text-muted">
        <span>Organisée par <strong class="text-dark">{{ $formation->organisateur?->name ?? '—' }}</strong></span>
        <span class="vr d-none d-sm-inline mx-2"></span>
        <span>Type :
          <span class="badge rounded-pill bg-success-subtle text-success fw-semibold">
            {{ strtoupper($formation->type ?? '—') }}
          </span>
        </span>
        <span class="vr d-none d-sm-inline mx-2"></span>
        <span>Statut :
          <span class="badge rounded-pill {{ ($formation->statut ?? '') === 'ouverte' ? 'bg-primary-subtle text-primary' : 'bg-secondary-subtle text-secondary' }} fw-semibold">
            {{ $formation->statut ?? '—' }}
          </span>
        </span>
      </div>
    </div>

    {{-- Actions (quiz + orga) --}}
    <div class="col-lg-4 d-flex justify-content-lg-end flex-wrap gap-2">
      {{-- Utilisateur classique : bouton "Passer le quiz" --}}
      @if(!$isOrga)
        <a class="btn btn-outline-secondary" href="{{ route('quiz.show', $formation) }}">
          <i class="bi bi-clipboard-check me-1"></i> Passer le quiz
        </a>
      @endif

      {{-- Organisateur : uniquement Générer quiz (IA) + Modifier + Supprimer --}}
      @auth
        @if($isOrga)
          <form action="{{ route('quiz.generate', $formation) }}" method="POST" class="d-inline">
            @csrf
            <input type="hidden" name="question_count" value="10">
            <input type="hidden" name="difficulty" value="2">
            <button class="btn btn-warning">
              <i class="bi bi-magic me-1"></i> Générer le quiz (IA)
            </button>
          </form>

          <a class="btn btn-outline-primary" href="{{ route('formations.edit', $formation) }}">
            <i class="bi bi-pencil-square me-1"></i> Modifier
          </a>
          <form action="{{ route('formations.destroy', $formation) }}" method="POST" onsubmit="return confirm('Supprimer cette formation ?');">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">
              <i class="bi bi-trash me-1"></i> Supprimer
            </button>
          </form>
        @endif
      @endauth
    </div>
  </div>

  <div class="row g-4">
    {{-- ===== Colonne principale ===== --}}
    <div class="col-lg-7">

      {{-- Carte : Détails & (Inscription si non-organisateur) --}}
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
          @if($formation->description)
            <p class="mb-3 fs-5">{{ $formation->description }}</p>
          @endif

          @if($formation->lien_visio)
            <p class="mb-3">
              <i class="bi bi-camera-video text-primary"></i>
              <span class="fw-medium">Visioconférence :</span>
              <a href="{{ $formation->lien_visio }}" target="_blank" rel="noopener" class="link-primary">
                {{ $formation->lien_visio }}
              </a>
            </p>
          @endif

          <div class="d-flex flex-wrap gap-3 mb-3">
            <div class="badge bg-light text-dark border">
              <i class="bi bi-people me-1"></i>
              Capacité : <strong>{{ $formation->capacite ?? '—' }}</strong>
            </div>
            @if(method_exists($formation,'placesRestantes'))
              <div class="badge bg-light text-dark border">
                <i class="bi bi-check2-circle me-1 text-success"></i>
                Restantes : <strong>{{ $formation->placesRestantes() }}</strong>
              </div>
            @endif
          </div>

          {{-- Rappel quiz (seulement non-organisateur) --}}
          @unless($isOrga)
            <div class="mb-3">
              <a href="{{ route('quiz.show', $formation) }}" class="btn btn-outline-secondary btn-sm">
                📝 Quiz / Auto-évaluation
              </a>
            </div>
          @endunless

          {{-- Messages flash --}}
          @if(session('status'))
            <div class="alert alert-success py-2 px-3">{{ session('status') }}</div>
          @endif
          @error('inscription')
            <div class="alert alert-danger py-2 px-3">{{ $message }}</div>
          @enderror

          {{-- CTA inscription : on le masque pour l’organisateur --}}
          @if(!$isOrga)
            @auth
              @php
                $deja = method_exists(auth()->user(), 'formationsInscrites')
                  ? auth()->user()->formationsInscrites->contains($formation->id)
                  : false;
              @endphp

              @if(!$deja && ($formation->statut === 'ouverte') && (method_exists($formation,'estComplete') ? !$formation->estComplete() : true))
                <form action="{{ route('formations.inscrire', $formation) }}" method="POST" class="d-inline">
                  @csrf
                  <button class="btn btn-success px-4">
                    <i class="bi bi-person-check me-1"></i> S’inscrire
                  </button>
                </form>
              @elseif($deja)
                <form action="{{ route('formations.desinscrire', $formation) }}" method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button class="btn btn-outline-danger px-4">
                    <i class="bi bi-x-circle me-1"></i> Se désinscrire
                  </button>
                </form>
              @endif
            @endauth
          @endif
        </div>
      </div>

      {{-- Carte : Avis --}}
      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 pb-0">
          <h5 class="mb-0 d-flex align-items-center gap-2">
            <i class="bi bi-chat-quote text-primary"></i> Avis
            @if(method_exists($formation,'noteMoyenne') && $formation->noteMoyenne())
              <span class="badge bg-warning-subtle text-warning ms-1">Moyenne {{ $formation->noteMoyenne() }}/5</span>
            @endif
          </h5>
        </div>
        <div class="card-body">

          {{-- Formulaire d’avis : caché pour l’organisateur, visible pour les autres --}}
          @if(!$isOrga)
            @auth
              @error('avis')
                <div class="alert alert-danger py-2 px-3 mb-3">{{ $message }}</div>
              @enderror
              @if(session('status_avis'))
                <div class="alert alert-success py-2 px-3 mb-3">{{ session('status_avis') }}</div>
              @endif

              <form action="{{ route('formations.avis.store', $formation) }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-2 align-items-center">
                  <div class="col-sm-2 col-4">
                    <label for="note" class="form-label mb-0">Note</label>
                    <select id="note" name="note" class="form-select">
                      @for($i=1;$i<=5;$i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                      @endfor
                    </select>
                  </div>
                  <div class="col-12">
                    <textarea name="commentaire" class="form-control" rows="3" placeholder="Votre commentaire (optionnel)"></textarea>
                  </div>
                  <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary">
                      <i class="bi bi-send me-1"></i> Envoyer mon avis
                    </button>
                  </div>
                </div>
              </form>
            @endauth
          @endif

          {{-- Liste des avis (toujours visible) --}}
          <ul class="list-group list-group-flush">
            @forelse($formation->avis()->latest()->with('user')->get() as $a)
              <li class="list-group-item px-0">
                <div class="d-flex justify-content-between">
                  <div class="fw-semibold">{{ $a->user?->name ?? '—' }}</div>
                  <span class="badge bg-success-subtle text-success">{{ $a->note }}/5</span>
                </div>
                @if($a->commentaire)
                  <div class="mt-1">{{ $a->commentaire }}</div>
                @endif
                <div class="text-muted small mt-1">{{ $a->created_at->diffForHumans() }}</div>
              </li>
            @empty
              <li class="list-group-item px-0 text-muted">Pas encore d’avis.</li>
            @endforelse
          </ul>
        </div>
      </div>

      {{-- Carte : Assistant Chat Formation (inchangé) --}}
      <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="avatar-chat bg-primary-subtle text-primary fw-bold">AI</span>
            <div>
              <div class="fw-semibold">Assistant de la formation</div>
              <div class="text-muted small">Pose une question (prérequis, contenu, durée, dates…).</div>
            </div>
          </div>
          <div class="form-check form-switch m-0">
            <input class="form-check-input" type="checkbox" id="modeGeneral">
            <label class="form-check-label" for="modeGeneral">Assistant général</label>
          </div>
        </div>

        <div class="card-body p-0">
          <div id="chat-box" class="p-3" style="max-height: 380px; overflow:auto; background: linear-gradient(180deg,#fff, #fbfcfe);">
            <div class="text-muted small">
              Commence la discussion en bas. Basculer en « Assistant général » pour des questions plus larges.
            </div>
          </div>
        </div>

        <div class="card-footer bg-white">
          <form id="chat-form" class="d-flex gap-2">
            @csrf
            <input
              type="text"
              name="message"
              class="form-control"
              placeholder="Écris ta question…"
              maxlength="4000"
              required
              autocomplete="off"
            >
            <button class="btn btn-primary px-3" type="submit" title="Envoyer">
              <i class="mdi mdi-send"></i>
            </button>
          </form>
        </div>
      </div>
      {{-- /Assistant --}}
    </div>

    {{-- ===== Colonne droite (sticky) ===== --}}
    <div class="col-lg-5">
      <div class="position-sticky" style="top: 84px;">
        {{-- Image --}}
        @if($formation->image)
          <div class="card shadow-sm border-0 mb-4">
            <img class="card-img-top rounded-3" src="{{ asset('storage/'.$formation->image) }}" alt="Illustration de la formation">
          </div>
        @endif

        {{-- Ressources --}}
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white">
            <h5 class="mb-0 d-flex align-items-center gap-2">
              <i class="bi bi-folder2-open text-primary"></i> Ressources
            </h5>
          </div>
          <div class="card-body p-0">
            <ul class="list-group list-group-flush">
              @forelse($formation->ressources as $r)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-secondary-subtle text-secondary">{{ strtoupper($r->type) }}</span>
                    <span>{{ $r->titre }}</span>
                  </div>
                  @if(method_exists($r,'urlPublic') && $r->urlPublic())
                    <a class="btn btn-sm btn-outline-primary" href="{{ $r->urlPublic() }}" target="_blank" rel="noopener">
                      Ouvrir
                    </a>
                  @endif
                </li>
              @empty
                <li class="list-group-item text-muted">Aucune ressource pour le moment.</li>
              @endforelse
            </ul>
          </div>
        </div>

        {{-- Ajout ressource (orga seulement) --}}
        @auth
          @if($isOrga)
            <div class="card shadow-sm border-0 mt-4">
              <div class="card-header bg-white">Ajouter une ressource</div>
              <div class="card-body">
                @if(session('status_ressource'))
                  <div class="alert alert-success py-2 px-3">{{ session('status_ressource') }}</div>
                @endif

                <form action="{{ route('formations.ressources.store', $formation) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                  @csrf
                  <div class="col-md-5">
                    <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                  </div>
                  <div class="col-md-4">
                    <select name="type" class="form-select" id="typeSelect" required>
                      <option value="pdf">PDF</option>
                      <option value="ppt">PPT</option>
                      <option value="video">Vidéo (lien)</option>
                      <option value="lien">Lien</option>
                    </select>
                  </div>
                  <div class="col-md-12" id="fileWrapper">
                    <input type="file" name="file" class="form-control" accept=".pdf,.ppt,.pptx">
                    <div class="form-text">PDF/PPT max 10 Mo</div>
                  </div>
                  <div class="col-md-12 d-none" id="urlWrapper">
                    <input type="url" name="url" class="form-control" placeholder="https://...">
                  </div>
                  <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-primary">
                      <i class="bi bi-plus-circle me-1"></i> Ajouter
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <script>
              (function(){
                const type = document.getElementById('typeSelect');
                const fileW = document.getElementById('fileWrapper');
                const urlW  = document.getElementById('urlWrapper');
                function toggle(){
                  if(['pdf','ppt'].includes(type.value)){
                    fileW.classList.remove('d-none');
                    urlW.classList.add('d-none');
                  } else {
                    fileW.classList.add('d-none');
                    urlW.classList.remove('d-none');
                  }
                }
                type.addEventListener('change', toggle);
                toggle();
              })();
            </script>
          @endif
        @endauth

        {{-- Résultats du quiz (participants) : seulement pour l’organisateur --}}
        @if($isOrga)
          @php
            $attempts = method_exists($formation,'quizAttempts')
              ? $formation->quizAttempts()->with('user')->latest()->limit(50)->get()
              : collect();
          @endphp
          <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white">
              <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-graph-up text-primary"></i> Résultats du quiz (participants)
              </h5>
            </div>
            <div class="card-body p-0">
              @if($attempts->isEmpty())
                <div class="p-3 text-muted">Aucun résultat pour le moment.</div>
              @else
                <div class="table-responsive">
                  <table class="table table-sm mb-0">
                    <thead>
                      <tr>
                        <th>Participant</th>
                        <th class="text-center">Score</th>
                        <th class="text-center">Total</th>
                        <th>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($attempts as $at)
                        <tr>
                          <td>{{ $at->user?->name ?? '—' }}</td>
                          <td class="text-center">{{ $at->score ?? '—' }}</td>
                          <td class="text-center">{{ $at->total ?? '—' }}</td>
                          <td>{{ optional($at->created_at)->diffForHumans() }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
  .avatar-chat{
    width: 36px; height: 36px;
    display: inline-flex; align-items:center; justify-content:center;
    border-radius: 50%;
  }
  .chat-bubble{
    max-width: 85%;
    padding: .6rem .8rem;
    border-radius: 14px;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
  }
  .chat-user { background: var(--bs-primary); color: #fff; border-top-right-radius: 4px; }
  .chat-assistant { background: #f3f6fb; color: #212529; border-top-left-radius: 4px; }
  .chat-row{ display:flex; margin-bottom:.5rem; }
</style>
@endpush

@push('scripts')
<script>
(function(){
  const form = document.getElementById('chat-form');
  const box  = document.getElementById('chat-box');
  const modeToggle = document.getElementById('modeGeneral');

  function esc(html){
    return (html || '')
      .replace(/&/g,'&amp;')
      .replace(/</g,'&lt;')
      .replace(/>/g,'&gt;');
  }

  function addBubble(text, role){
    const row = document.createElement('div');
    row.className = 'chat-row ' + (role==='user' ? 'justify-content-end' : 'justify-content-start');

    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble ' + (role==='user' ? 'chat-user' : 'chat-assistant');
    bubble.innerHTML = esc(text).replace(/\n/g,'<br>');

    row.appendChild(bubble);
    box.appendChild(row);
    box.scrollTop = box.scrollHeight;
  }

  if(form){
    form.addEventListener('submit', async (e)=>{
      e.preventDefault();
      const input = form.querySelector('input[name="message"]');
      const msg = (input.value || '').trim();
      if(!msg) return;

      addBubble(msg, 'user');
      input.value = '';

      const typing = document.createElement('div');
      typing.className = 'text-muted small px-3 pb-2';
      typing.textContent = 'Assistant est en train d’écrire…';
      box.appendChild(typing);
      box.scrollTop = box.scrollHeight;

      try{
        const res = await fetch("{{ route('formations.chat', $formation->id) }}", {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            message: msg,
            mode: modeToggle && modeToggle.checked ? 'general' : 'formation'
          })
        });

        const data = await res.json();
        typing.remove();

        if (data && data.reply) {
          addBubble(data.reply, 'assistant');
        } else if (data && data.error) {
          addBubble('⚠️ ' + data.error, 'assistant');
        } else {
          addBubble('Désolé, je n’ai pas trouvé d’info.', 'assistant');
        }
      } catch (err) {
        typing.remove();
        addBubble('Erreur réseau. Réessaie dans un instant.', 'assistant');
      }
    });
  }
})();
</script>
@endpush
