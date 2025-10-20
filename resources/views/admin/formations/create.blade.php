@extends('layouts.admin')

@section('title', 'Formations - Création')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Créer une Nouvelle Formation</h4>
                    <a href="{{ route('admin.formations.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left"></i> Retour au catalogue
                    </a>
                </div>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.formations.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="titre">Titre de la formation <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('titre') is-invalid @enderror" 
                                       name="titre" id="titre" value="{{ old('titre') }}" 
                                       placeholder="Titre attractif de la formation">
                                @error('titre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="statut">Statut <span class="text-danger">*</span></label>
                                <select class="form-control @error('statut') is-invalid @enderror" name="statut" id="statut">
                                    <option value="">Choisir un statut</option>
                                    <option value="actif" {{ old('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    <option value="brouillon" {{ old('statut') == 'brouillon' ? 'selected' : 'selected' }}>Brouillon</option>
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  name="description" id="description" rows="4" 
                                  placeholder="Description détaillée de la formation">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="categorie">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-control @error('categorie') is-invalid @enderror" name="categorie" id="categorie">
                                    <option value="">Choisir une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ old('categorie') == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                    <option value="autre">Autre</option>
                                </select>
                                @error('categorie')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group" id="nouvelle-categorie" style="display: none;">
                                <label for="nouvelle_categorie">Nouvelle catégorie</label>
                                <input type="text" class="form-control" id="nouvelle_categorie" 
                                       placeholder="Nom de la nouvelle catégorie">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="type">Type de formation <span class="text-danger">*</span></label>
                                <select class="form-control @error('type') is-invalid @enderror" name="type" id="type">
                                    <option value="">Choisir un type</option>
                                    <option value="presentiel" {{ old('type') == 'presentiel' ? 'selected' : '' }}>Présentiel</option>
                                    <option value="ligne" {{ old('type') == 'ligne' ? 'selected' : '' }}>En ligne</option>
                                    <option value="hybride" {{ old('type') == 'hybride' ? 'selected' : '' }}>Hybride</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="capacite">Capacité (participants)</label>
                                <input type="number" class="form-control @error('capacite') is-invalid @enderror" 
                                       name="capacite" id="capacite" value="{{ old('capacite') }}" 
                                       placeholder="Nombre max de participants" min="1">
                                <small class="form-text text-muted">Laissez vide pour une capacité illimitée</small>
                                @error('capacite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="organisateur_id">Organisateur <span class="text-danger">*</span></label>
                                <select class="form-control @error('organisateur_id') is-invalid @enderror" name="organisateur_id" id="organisateur_id">
                                    <option value="">Choisir un organisateur</option>
                                    @foreach($organisateurs as $organisateur)
                                        <option value="{{ $organisateur->id }}" {{ old('organisateur_id') == $organisateur->id ? 'selected' : '' }}>
                                            {{ $organisateur->name }} ({{ ucfirst($organisateur->role) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('organisateur_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lien_visio">Lien visioconférence</label>
                                <input type="url" class="form-control @error('lien_visio') is-invalid @enderror" 
                                       name="lien_visio" id="lien_visio" value="{{ old('lien_visio') }}" 
                                       placeholder="https://meet.google.com/...">
                                <small class="form-text text-muted">Pour les formations en ligne ou hybrides</small>
                                @error('lien_visio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="image">Image de la formation</label>
                        <input type="file" class="form-control-file @error('image') is-invalid @enderror" 
                               name="image" id="image" accept="image/*">
                        <small class="form-text text-muted">Formats acceptés: JPEG, PNG, JPG, GIF. Taille max: 2MB</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Créer la Formation
                        </button>
                        <a href="{{ route('admin.formations.index') }}" class="btn btn-light">
                            <i class="mdi mdi-cancel"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('categorie').addEventListener('change', function() {
    const nouvelleCategorie = document.getElementById('nouvelle-categorie');
    const nouvelleInput = document.getElementById('nouvelle_categorie');
    
    if (this.value === 'autre') {
        nouvelleCategorie.style.display = 'block';
        nouvelleInput.required = true;
        nouvelleInput.addEventListener('input', function() {
            document.getElementById('categorie').value = this.value;
        });
    } else {
        nouvelleCategorie.style.display = 'none';
        nouvelleInput.required = false;
    }
});
</script>
@endsection