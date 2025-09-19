@extends('layouts.site')
@section('title', 'Créer une formation')

@section('content')
<div class="container py-5" style="max-width: 880px;">
  <h2 class="mb-4">Créer une formation</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('formations.store') }}" enctype="multipart/form-data" class="row g-3">
    @csrf

    <div class="col-md-8">
      <label class="form-label">Titre *</label>
      <input type="text" name="titre" class="form-control" value="{{ old('titre') }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Catégorie</label>
      <input type="text" name="categorie" class="form-control" value="{{ old('categorie') }}">
    </div>

    <div class="col-md-4">
      <label class="form-label">Type *</label>
      <select name="type" class="form-select" required>
        @foreach(['formation','atelier','conférence','webinaire'] as $t)
          <option value="{{ $t }}" @selected(old('type')===$t)>{{ ucfirst($t) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-4">
      <label class="form-label">Capacité *</label>
      <input type="number" name="capacite" min="0" class="form-control" value="{{ old('capacite', 0) }}" required>
    </div>

    <div class="col-md-4">
      <label class="form-label">Statut *</label>
      <select name="statut" class="form-select" required>
        @foreach(['ouverte','suspendue','terminee'] as $s)
          <option value="{{ $s }}" @selected(old('statut')===$s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-12">
      <label class="form-label">Description</label>
      <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
    </div>

    <div class="col-md-6">
      <label class="form-label">Lien visioconférence</label>
      <input type="url" name="lien_visio" class="form-control" value="{{ old('lien_visio') }}">
    </div>

    <div class="col-md-6">
      <label class="form-label">Affiche (jpg/png/webp)</label>
      <input type="file" name="image" accept="image/*" class="form-control">
    </div>

    <div class="col-12">
      <button class="btn btn-primary">Créer</button>
      <a href="{{ route('formations.index') }}" class="btn btn-outline-secondary">Annuler</a>
    </div>
  </form>
</div>
@endsection
