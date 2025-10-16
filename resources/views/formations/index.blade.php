@extends('layouts.site')
@section('title', 'Formations')

@section('content')
<div class="container-fluid bg-breadcrumb">
  <div class="container text-center py-5" style="max-width:900px;">
    <h3 class="text-white display-3 mb-3">Formations</h3>
    <p class="fs-5 text-white mb-0">Inscrivez-vous et accédez aux ressources pédagogiques</p>
  </div>
</div>

<div class="container py-5">
  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  {{-- Formulaire de filtre --}}
  <form method="GET" class="row g-2 mb-4">
    <div class="col-md-4">
      <input type="search" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Rechercher (titre, description)">
    </div>
    <div class="col-md-3">
      <select name="categorie" class="form-select">
        <option value="">Toutes catégories</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}" @selected(($categorie ?? '') === $cat)>{{ ucfirst($cat) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="type" class="form-select">
        <option value="">Tous types</option>
        @foreach(['formation','atelier','conférence','webinaire'] as $t)
          <option value="{{ $t }}" @selected(($type ?? '') === $t)>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-2">
      <select name="statut" class="form-select">
        <option value="">Tous statuts</option>
        @foreach(['ouverte','suspendue','terminee'] as $s)
          <option value="{{ $s }}" @selected(($statut ?? '') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-1 d-grid">
      <button class="btn btn-primary">Filtrer</button>
    </div>
  </form>

  {{-- Grille des formations --}}
  <div class="row g-4">
    @forelse($formations as $f)
      <div class="col-lg-4 col-md-6">
        <div class="card h-100 shadow-sm">
          @if($f->image)
            <img src="{{ asset('storage/'.$f->image) }}" class="card-img-top" alt="{{ $f->titre }}">
          @endif
          <div class="card-body">
            <span class="badge bg-success me-2 text-uppercase">{{ $f->type }}</span>
            <span class="badge bg-secondary">{{ $f->statut }}</span>
            <h5 class="mt-3 mb-2">{{ $f->titre }}</h5>
            <p class="text-muted small mb-2">par {{ $f->organisateur->name }}</p>
            <p class="card-text">{{ Str::limit($f->description, 140) }}</p>
            <a class="btn btn-primary" href="{{ route('formations.show', $f) }}">Détails</a>

            {{-- Ajout : Boutons Modifier / Supprimer pour l'organisateur --}}
            @auth
              @if(Auth::id() === $f->organisateur_id)
                <div class="mt-2 d-flex gap-2">
                  <a class="btn btn-sm btn-outline-primary" href="{{ route('formations.edit', $f) }}">Modifier</a>
                  <form action="{{ route('formations.destroy', $f) }}" method="POST" onsubmit="return confirm('Supprimer cette formation ?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                  </form>
                </div>
              @endif
            @endauth
            {{-- Fin ajout --}}

          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">Aucune formation pour le moment.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-4">
    {{ $formations->links() }}
  </div>
</div>
@endsection
