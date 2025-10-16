@extends('layouts.site')

@section('title', $formation->titre.' — Quiz')

@section('content')
<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-8">
      <h3 class="mb-1">{{ $formation->titre }}</h3>
      <p class="text-muted mb-3">Quiz / auto-évaluation</p>

      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if(session('quiz_result'))
        @php($r = session('quiz_result'))
        <div class="alert alert-info">
          Résultat : <strong>{{ $r['score'] }}%</strong> — {{ $r['correct'] }}/{{ $r['total'] }} bonnes réponses.
        </div>
      @endif

      @if(!$quiz)
        <div class="alert alert-warning">
          Aucun quiz généré pour le moment.
        </div>
        @auth
          @if(auth()->id() === (int)$formation->organisateur_id)
            <form action="{{ route('quiz.generate', $formation) }}" method="POST" class="row gy-2 gx-2 align-items-end">
              @csrf
              <div class="col-auto">
                <label class="form-label">Nb questions</label>
                <input type="number" name="question_count" class="form-control" min="5" max="20" value="10">
              </div>
              <div class="col-auto">
                <label class="form-label">Difficulté</label>
                <select name="difficulty" class="form-select">
                  <option value="1">Facile</option>
                  <option value="2" selected>Intermédiaire</option>
                  <option value="3">Avancé</option>
                </select>
              </div>
              <div class="col-auto">
                <button class="btn btn-primary">
                  Générer le quiz (IA)
                </button>
              </div>
            </form>
          @endif
        @endauth
      @else
        {{-- Quiz existant --}}
        <form action="{{ route('quiz.submit', $formation) }}" method="POST" class="card">
          @csrf
          <div class="card-header d-flex align-items-center justify-content-between">
            <strong>{{ $quiz->title }}</strong>
            <a href="{{ route('quiz.history', $formation) }}" class="btn btn-sm btn-outline-secondary"
               onclick="event.preventDefault(); loadHistory();">Historique</a>
          </div>
          <div class="card-body">
            @foreach($quiz->questions as $q)
              <div class="mb-3">
                <div class="fw-semibold mb-1">{{ $loop->iteration }}. {{ $q->question }}</div>
                @foreach($q->choices as $idx => $label)
                  <div class="form-check">
                    <input class="form-check-input" type="radio"
                           name="answers[{{ $q->id }}]" id="q{{ $q->id }}_{{ $idx }}" value="{{ $idx }}">
                    <label class="form-check-label" for="q{{ $q->id }}_{{ $idx }}">
                      {{ $label }}
                    </label>
                  </div>
                @endforeach
                @if($q->explanation)
                  <div class="form-text">Astuce : {{ $q->explanation }}</div>
                @endif
              </div>
              @if(!$loop->last)<hr>@endif
            @endforeach
          </div>
          <div class="card-footer d-flex justify-content-between">
            @auth
              <button class="btn btn-primary">Envoyer mes réponses</button>
            @else
              <a class="btn btn-outline-primary" href="{{ route('login') }}">Connectez-vous pour répondre</a>
            @endauth
            @auth
              @if(auth()->id() === (int)$formation->organisateur_id)
                <form action="{{ route('quiz.generate', $formation) }}" method="POST" onsubmit="return confirm('Régénérer et remplacer le quiz ?');">
                  @csrf
                  <button class="btn btn-outline-danger">Régénérer le quiz</button>
                </form>
              @endif
            @endauth
          </div>
        </form>
        <div id="quizHistory" class="mt-3"></div>
      @endif
    </div>

    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Infos formation</div>
        <div class="card-body small">
          <div><strong>Type :</strong> {{ $formation->type ?? '—' }}</div>
          <div class="mt-1"><strong>Statut :</strong> {{ $formation->statut ?? '—' }}</div>
          @if($formation->lien_visio)
            <div class="mt-1"><strong>Visio :</strong> <a href="{{ $formation->lien_visio }}" target="_blank">lien</a></div>
          @endif
          @if($formation->description)
            <div class="mt-2">{{ \Illuminate\Support\Str::limit($formation->description, 220) }}</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function loadHistory(){
  fetch(@json(route('quiz.history', $formation)), {headers:{'Accept':'application/json'}})
    .then(r=>r.json())
    .then(d=>{
      const c = document.getElementById('quizHistory');
      const items = (d.attempts || []).map(a =>
        `<div class="border rounded p-2 mb-2">Score: <b>${a.score}%</b> — le ${new Date(a.created_at).toLocaleString()}</div>`
      ).join('') || '<div class="text-muted">Pas encore de tentatives.</div>';
      c.innerHTML = `<div class="card"><div class="card-header">Historique</div><div class="card-body">${items}</div></div>`;
    })
    .catch(()=>{});
}
</script>
@endpush
