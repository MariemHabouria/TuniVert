@extends('layouts.site')
@section('title', 'Modifier la formation')

@section('content')
<div class="container py-5">
  <h1 class="mb-4">Modifier la formation</h1>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('formations.update', $formation) }}" method="POST" enctype="multipart/form-data" class="row g-3">
    @csrf
    @method('PUT')

    <div class="col-md-6">
      <label class="form-label">Titre</label>
      <input type="text" name="titre" class="form-control" value="{{ old('titre', $formation->titre) }}" required>
    </div>

    <div class="col-md-6">
      <label class="form-label">Catégorie</label>
      <input type="text" name="categorie" class="form-control" value="{{ old('categorie', $formation->categorie) }}">
    </div>

    <div class="col-md-12">
      <label class="form-label">Description</label>
      <textarea name="description" rows="5" class="form-control" required>{{ old('description', $formation->description) }}</textarea>
    </div>

    <div class="col-md-4">
      <label class="form-label">Type</label>
      <select name="type" class="form-select" required>
        @php
          $types = ['formation','atelier','conférence','webinaire'];
        @endphp
        @foreach ($types as $t)
          <option value="{{ $t }}" {{ old('type', $formation->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Capacité</label>
      <input type="number" name="capacite" class="form-control" min="1" value="{{ old('capacite', $formation->capacite) }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Statut</label>
      <select name="statut" class="form-select" required>
        @foreach (['ouverte','suspendue','terminee'] as $s)
          <option value="{{ $s }}" {{ old('statut', $formation->statut) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-8">
      <label class="form-label">Lien visioconférence (optionnel)</label>
      <input type="text" name="lien_visio" class="form-control" value="{{ old('lien_visio', $formation->lien_visio) }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Affiche (image)</label>
      <input type="file" name="image" class="form-control">
      @if($formation->image)
        <div class="form-text mt-1">
          Image actuelle :
          @if(Str::startsWith($formation->image, 'formations/'))
            <a href="{{ Storage::disk('public')->url($formation->image) }}" target="_blank">voir</a>
          @else
            <a href="{{ $formation->image }}" target="_blank">voir</a>
          @endif
        </div>
      @endif
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Enregistrer</button>
      <a href="{{ route('formations.show', $formation) }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>

  <hr class="my-4">

  <form action="{{ route('formations.destroy', $formation) }}" method="POST" onsubmit="return confirm('Supprimer définitivement cette formation ?');">
    @csrf
    @method('DELETE')
    <button class="btn btn-danger">Supprimer la formation</button>
  </form>
</div>
@endsection
