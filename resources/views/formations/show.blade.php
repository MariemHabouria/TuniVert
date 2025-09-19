@extends('layouts.site')
@section('title', $formation->titre)

@section('content')
<div class="container py-5">
  <div class="row g-4">
    <div class="col-lg-7">
      <h2 class="mb-3">{{ $formation->titre }}</h2>
      <p class="text-muted mb-1">Organisée par <strong>{{ $formation->organisateur->name }}</strong></p>
      <p class="text-muted">Type : <span class="badge bg-success text-uppercase">{{ $formation->type }}</span>
         &nbsp; Statut : <span class="badge bg-secondary">{{ $formation->statut }}</span></p>
      <p class="mt-3">{{ $formation->description }}</p>

      @if($formation->lien_visio)
        <p><i class="bi bi-camera-video"></i> Visioconférence :
          <a href="{{ $formation->lien_visio }}" target="_blank">{{ $formation->lien_visio }}</a>
        </p>
      @endif

      {{-- Nouveau bloc : messages et formulaires inscription --}}
      @if(session('status')) 
        <div class="alert alert-success">{{ session('status') }}</div> 
      @endif
      @error('inscription') 
        <div class="alert alert-danger">{{ $message }}</div> 
      @enderror

      <p class="mb-2">
        <strong>Capacité :</strong> {{ $formation->capacite }} |
        <strong>Restantes :</strong> {{ $formation->placesRestantes() }}
      </p>

      @auth
        @php $deja = auth()->user()->formationsInscrites->contains($formation->id); @endphp
        @if(!$deja && $formation->statut === 'ouverte' && !$formation->estComplete())
          <form action="{{ route('formations.inscrire',$formation) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-primary">S’inscrire</button>
          </form>
        @elseif($deja)
          <form action="{{ route('formations.desinscrire',$formation) }}" method="POST" class="d-inline">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Se désinscrire</button>
          </form>
        @endif
      @endauth

      {{-- Début bloc Avis --}}
      <hr class="my-4">

      <h5 class="mb-3">Avis @if($formation->noteMoyenne()) — Moyenne {{ $formation->noteMoyenne() }}/5 @endif</h5>

      @auth
        @error('avis') <div class="alert alert-danger">{{ $message }}</div> @enderror
        @if(session('status')) <div class="alert alert-success">{{ session('status') }}</div> @endif

        <form action="{{ route('formations.avis.store', $formation) }}" method="POST" class="mb-4">
          @csrf
          <div class="row g-2 align-items-center">
            <div class="col-auto">
              <label for="note" class="col-form-label">Note</label>
            </div>
            <div class="col-auto">
              <select id="note" name="note" class="form-select">
                @for($i=1;$i<=5;$i++)
                  <option value="{{ $i }}">{{ $i }}</option>
                @endfor
              </select>
            </div>
            <div class="col-12 mt-2">
              <textarea name="commentaire" class="form-control" rows="3" placeholder="Votre commentaire (optionnel)"></textarea>
            </div>
            <div class="col-12 mt-2">
              <button class="btn btn-primary">Envoyer mon avis</button>
            </div>
          </div>
        </form>
      @endauth

      <ul class="list-group">
        @forelse($formation->avis()->latest()->with('user')->get() as $a)
          <li class="list-group-item">
            <div class="d-flex justify-content-between">
              <strong>{{ $a->user->name }}</strong>
              <span class="badge bg-success">{{ $a->note }}/5</span>
            </div>
            @if($a->commentaire)
              <div class="mt-1">{{ $a->commentaire }}</div>
            @endif
            <div class="text-muted small mt-1">{{ $a->created_at->diffForHumans() }}</div>
          </li>
        @empty
          <li class="list-group-item">Pas encore d’avis.</li>
        @endforelse
      </ul>
      {{-- Fin bloc Avis --}}
    </div>

    <div class="col-lg-5">
      @if($formation->image)
        <img class="img-fluid rounded mb-3" src="{{ asset('storage/'.$formation->image) }}" alt="">
      @endif

      {{-- Liste des ressources --}}
      <ul class="list-group mb-4">
        @forelse($formation->ressources as $r)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <span class="badge bg-secondary me-2">{{ strtoupper($r->type) }}</span>
              {{ $r->titre }}
            </div>
            @if($r->urlPublic())
              <a class="btn btn-sm btn-outline-primary" href="{{ $r->urlPublic() }}" target="_blank">Ouvrir</a>
            @endif
          </li>
        @empty
          <li class="list-group-item text-muted">Aucune ressource pour le moment.</li>
        @endforelse
      </ul>

      {{-- Formulaire ajout ressource (uniquement organisateur connecté) --}}
      @auth
        @if(auth()->id() === $formation->organisateur_id)
          <div class="card">
            <div class="card-header">Ajouter une ressource</div>
            <div class="card-body">
              @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
              @endif

              <form action="{{ route('formations.ressources.store', $formation) }}" method="POST" enctype="multipart/form-data" class="row g-2">
                @csrf
                <div class="col-md-4">
                  <input type="text" name="titre" class="form-control" placeholder="Titre" required>
                </div>
                <div class="col-md-3">
                  <select name="type" class="form-select" id="typeSelect" required>
                    <option value="pdf">PDF</option>
                    <option value="ppt">PPT</option>
                    <option value="video">Vidéo (lien)</option>
                    <option value="lien">Lien</option>
                  </select>
                </div>
                <div class="col-md-5" id="fileWrapper">
                  <input type="file" name="file" class="form-control" accept=".pdf,.ppt,.pptx">
                  <div class="form-text">PDF/PPT max 10 Mo</div>
                </div>
                <div class="col-md-5 d-none" id="urlWrapper">
                  <input type="url" name="url" class="form-control" placeholder="https://...">
                </div>
                <div class="col-12">
                  <button class="btn btn-primary">Ajouter</button>
                </div>
              </form>

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
            </div>
          </div>
        @endif
      @endauth
    </div>
  </div>
</div>
@endsection
