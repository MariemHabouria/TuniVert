@extends('layouts.admin')

@section('title', 'Événements - Modification')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Modifier l'Événement</h4>
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('admin.evenements.update', $evenement) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Titre de l'événement *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="{{ old('title', $evenement->title) }}" placeholder="Entrez le titre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="date">Date *</label>
                                <input type="datetime-local" class="form-control" id="date" name="date" 
                                       value="{{ old('date', \Carbon\Carbon::parse($evenement->date)->format('Y-m-d\TH:i')) }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="details">Description</label>
                        <textarea class="form-control" id="details" name="details" rows="4" 
                                  placeholder="Description de l'événement">{{ old('details', $evenement->details) }}</textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="location">Lieu *</label>
                                <input type="text" class="form-control" id="location" name="location" 
                                       value="{{ old('location', $evenement->location) }}" placeholder="Lieu de l'événement" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category">Catégorie *</label>
                                <select class="form-control" id="category" name="category" required>
                                    <option value="">Choisir une catégorie</option>
                                    <option value="Nettoyage de plage" {{ old('category', $evenement->category) == 'Nettoyage de plage' ? 'selected' : '' }}>Nettoyage de plage</option>
                                    <option value="Sensibilisation" {{ old('category', $evenement->category) == 'Sensibilisation' ? 'selected' : '' }}>Sensibilisation</option>
                                    <option value="Plantation" {{ old('category', $evenement->category) == 'Plantation' ? 'selected' : '' }}>Plantation</option>
                                    <option value="Écosystème" {{ old('category', $evenement->category) == 'Écosystème' ? 'selected' : '' }}>Écosystème</option>
                                    <option value="Formation" {{ old('category', $evenement->category) == 'Formation' ? 'selected' : '' }}>Formation</option>
                                    <option value="Conférence" {{ old('category', $evenement->category) == 'Conférence' ? 'selected' : '' }}>Conférence</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="image">Image de l'événement</label>
                                
                                @if($evenement->image)
                                    <div class="current-image mb-3">
                                        <label class="form-label text-muted">Image actuelle :</label>
                                        <div class="current-image-container">
                                            <img src="{{ asset($evenement->image) }}" alt="Image actuelle" style="max-width: 200px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); object-fit: cover;">
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="image-upload-container">
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                    <small class="text-muted">Formats acceptés: JPG, PNG, GIF (max 2MB). Laissez vide pour conserver l'image actuelle.</small>
                                    
                                    <!-- Preview container for new image -->
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <label class="form-label text-muted">Nouvelle image :</label>
                                        <div class="preview-container">
                                            <img id="preview" src="" alt="Aperçu de la nouvelle image" style="max-width: 200px; max-height: 150px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removePreview()">
                                                <i class="mdi mdi-delete"></i> Annuler changement
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-check"></i> Mettre à jour
                        </button>
                        <a href="{{ route('admin.evenements.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewImage(input) {
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreview() {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview');
    const previewContainer = document.getElementById('imagePreview');
    
    input.value = '';
    preview.src = '';
    previewContainer.style.display = 'none';
}
</script>
@endsection