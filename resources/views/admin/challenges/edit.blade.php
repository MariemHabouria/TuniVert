@extends('layouts.admin')

@section('title', 'Modifier Challenge')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">✏️ Modifier le Challenge</h4>
                    <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Retour
                    </a>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.challenges.update', $challenge->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="titre">Titre du Challenge *</label>
                                <input type="text" 
                                       class="form-control @error('titre') is-invalid @enderror" 
                                       id="titre" 
                                       name="titre" 
                                       value="{{ old('titre', $challenge->titre) }}" 
                                       required>
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categorie">Catégorie</label>
                                <select class="form-control @error('categorie') is-invalid @enderror" 
                                        id="categorie" 
                                        name="categorie">
                                    <option value="">Sélectionner une catégorie</option>
                                    <option value="environnement" {{ old('categorie', $challenge->categorie) == 'environnement' ? 'selected' : '' }}>Environnement</option>
                                    <option value="transport" {{ old('categorie', $challenge->categorie) == 'transport' ? 'selected' : '' }}>Transport</option>
                                    <option value="energie" {{ old('categorie', $challenge->categorie) == 'energie' ? 'selected' : '' }}>Énergie</option>
                                    <option value="dechets" {{ old('categorie', $challenge->categorie) == 'dechets' ? 'selected' : '' }}>Déchets</option>
                                    <option value="eau" {{ old('categorie', $challenge->categorie) == 'eau' ? 'selected' : '' }}>Eau</option>
                                </select>
                                @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="4" 
                                  required>{{ old('description', $challenge->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="objectif">Objectif</label>
                        <textarea class="form-control @error('objectif') is-invalid @enderror" 
                                  id="objectif" 
                                  name="objectif" 
                                  rows="3">{{ old('objectif', $challenge->objectif) }}</textarea>
                        @error('objectif')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_debut">Date de Début *</label>
                                <input type="date" 
                                       class="form-control @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" 
                                       name="date_debut" 
                                       value="{{ old('date_debut', $challenge->date_debut ? $challenge->date_debut->format('Y-m-d') : '') }}" 
                                       required>
                                @error('date_debut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_fin">Date de Fin *</label>
                                <input type="date" 
                                       class="form-control @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" 
                                       name="date_fin" 
                                       value="{{ old('date_fin', $challenge->date_fin ? $challenge->date_fin->format('Y-m-d') : '') }}" 
                                       required>
                                @error('date_fin')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="difficulte">Difficulté</label>
                                <select class="form-control @error('difficulte') is-invalid @enderror" 
                                        id="difficulte" 
                                        name="difficulte">
                                    <option value="facile" {{ old('difficulte', $challenge->difficulte) == 'facile' ? 'selected' : '' }}>Facile</option>
                                    <option value="moyen" {{ old('difficulte', $challenge->difficulte) == 'moyen' ? 'selected' : '' }}>Moyen</option>
                                    <option value="difficile" {{ old('difficulte', $challenge->difficulte) == 'difficile' ? 'selected' : '' }}>Difficile</option>
                                </select>
                                @error('difficulte')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" 
                                           class="form-check-input" 
                                           id="actif" 
                                           name="actif" 
                                           value="1" 
                                           {{ old('actif', $challenge->actif) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="actif">
                                        Challenge Actif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-close"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-check"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation de dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    function validateDates() {
        if (dateDebut.value && dateFin.value) {
            if (new Date(dateFin.value) <= new Date(dateDebut.value)) {
                dateFin.setCustomValidity('La date de fin doit être après la date de début');
            } else {
                dateFin.setCustomValidity('');
            }
        }
    }
    
    dateDebut.addEventListener('change', validateDates);
    dateFin.addEventListener('change', validateDates);
});
</script>
@endsection