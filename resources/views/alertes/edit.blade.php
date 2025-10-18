@extends('layouts.app')

@section('title', 'Modifier une alerte')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-2.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">✏️ Modifier l'Alerte</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alertes.index') }}" class="text-white">Alertes</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alertes.show', $alerte->id) }}" class="text-white">Détails</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Modifier</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Edit Alert Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header py-4 text-center text-white"
                         style="background: linear-gradient(135deg, var(--bs-warning) 0%, var(--bs-danger) 100%);
                                border-radius:15px 15px 0 0;">
                        <h3 class="mb-0">Mettre à Jour l'Alerte</h3>
                    </div>

                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('alertes.update', $alerte->id) }}" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Titre -->
                            <div class="mb-4">
                                <label for="titre" class="form-label fw-semibold">
                                    <i class="fas fa-heading me-2 text-danger"></i>Titre de l'Alerte *
                                </label>
                                <input type="text" name="titre" id="titre"
                                       class="form-control form-control-lg"
                                       style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                       value="{{ old('titre', $alerte->titre) }}" required>
                                @error('titre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-2 text-danger"></i>Description *
                                </label>
                                <textarea name="description" id="description"
                                          class="form-control"
                                          style="border-radius:10px; border:2px solid #ddd; padding:12px; min-height:120px;"
                                          placeholder="Décrivez précisément le problème..."
                                          required>{{ old('description', $alerte->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Zone Géographique -->
                            <div class="mb-4">
                                <label for="zone_geographique" class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt me-2 text-danger"></i>Zone Géographique *
                                </label>
                                <input type="text" name="zone_geographique" id="zone_geographique"
                                       class="form-control"
                                       style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                       placeholder="Ex: Tunis Centre, Ariana Ville, Sousse Médina..."
                                       value="{{ old('zone_geographique', $alerte->zone_geographique) }}"
                                       list="zones-list"
                                       required>
                                <datalist id="zones-list">
                                    <option value="Tunis Centre">
                                    <option value="Ariana Ville">
                                    <option value="Sousse Médina">
                                    <option value="Sfax Centre">
                                    <option value="Nabeul Ville">
                                    <option value="Bizerte Centre">
                                    <option value="Monastir Ville">
                                    <option value="Kairouan Médina">
                                    <option value="Gabès Ville">
                                    <option value="Gafsa Centre">
                                    <option value="Tozeur Ville">
                                    <option value="Médenine Ville">
                                    <option value="Ben Arous">
                                    <option value="Manouba Ville">
                                    <option value="Zaghouan Centre">
                                </datalist>
                                @error('zone_geographique')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Zone principale concernée par l'alerte</small>
                            </div>

                            <!-- Localisation détaillée (optionnelle) -->
                            <div class="mb-4">
                                <label for="localisation" class="form-label fw-semibold">
                                    <i class="fas fa-location-arrow me-2 text-danger"></i>Localisation détaillée
                                </label>
                                <input type="text" name="localisation" id="localisation"
                                       class="form-control"
                                       style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                       placeholder="Ex: Rue Habib Bourguiba, près du marché central..."
                                       value="{{ old('localisation', $alerte->localisation) }}">
                                @error('localisation')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Adresse précise ou repères (optionnel)</small>
                            </div>

                            <!-- Coordonnées GPS (optionnelles) -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="lat" class="form-label fw-semibold">
                                        <i class="fas fa-globe me-2 text-danger"></i>Latitude
                                    </label>
                                    <input type="number" step="any" name="lat" id="lat"
                                           class="form-control"
                                           style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                           placeholder="Ex: 36.8065"
                                           value="{{ old('lat', $alerte->lat) }}">
                                    @error('lat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="lng" class="form-label fw-semibold">
                                        <i class="fas fa-globe me-2 text-danger"></i>Longitude
                                    </label>
                                    <input type="number" step="any" name="lng" id="lng"
                                           class="form-control"
                                           style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                           placeholder="Ex: 10.1815"
                                           value="{{ old('lng', $alerte->lng) }}">
                                    @error('lng')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted mt-2">Coordonnées GPS pour la carte (optionnel)</small>
                            </div>

                            <!-- Statut de l'alerte -->
                            <div class="mb-4">
                                <label for="statut" class="form-label fw-semibold">
                                    <i class="fas fa-info-circle me-2 text-danger"></i>Statut de l'Alerte
                                </label>
                                <select name="statut" id="statut"
                                        class="form-select"
                                        style="border-radius:10px; border:2px solid #ddd; padding:12px;">
                                    <option value="en_cours" {{ old('statut', $alerte->statut) == 'en_cours' ? 'selected' : '' }}>🟡 En cours</option>
                                    <option value="resolue" {{ old('statut', $alerte->statut) == 'resolue' ? 'selected' : '' }}>✅ Résolue</option>
                                </select>
                                @error('statut')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Changez le statut si le problème est résolu</small>
                            </div>

                            <!-- Gravité -->
                            <div class="mb-4">
                                <label for="gravite" class="form-label fw-semibold">
                                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Niveau de Gravité *
                                </label>
                                <select name="gravite" id="gravite"
                                        class="form-select"
                                        style="border-radius:10px; border:2px solid #ddd; padding:12px;" required>
                                    <option value="basse" {{ old('gravite', $alerte->gravite) == 'basse' ? 'selected' : '' }}>🟢 Basse - Problème mineur</option>
                                    <option value="moyenne" {{ old('gravite', $alerte->gravite) == 'moyenne' ? 'selected' : '' }}>🟡 Moyenne - Attention requise</option>
                                    <option value="haute" {{ old('gravite', $alerte->gravite) == 'haute' ? 'selected' : '' }}>🔴 Haute - Intervention urgente</option>
                                    <option value="feu" {{ old('gravite', $alerte->gravite) == 'feu' ? 'selected' : '' }}>🔥 Situation critique - Danger immédiat</option>
                                </select>
                                @error('gravite')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Informations de suivi -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">📊 Informations de suivi</h6>
                                <small class="mb-0">
                                    <strong>Créée le :</strong> {{ $alerte->created_at->format('d/m/Y à H:i') }}<br>
                                    <strong>Vues :</strong> {{ $alerte->nombre_vues ?? 0 }} • 
                                    <strong>Partages :</strong> {{ $alerte->nombre_partages ?? 0 }}<br>
                                    <strong>Commentaires :</strong> {{ $alerte->commentaires->count() ?? 0 }}
                                </small>
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between pt-3">
                                <div>
                                    <a href="{{ route('alertes.show', $alerte->id) }}" class="btn btn-secondary btn-lg px-4 me-2"
                                       style="border-radius:10px;">
                                        ⬅️ Retour
                                    </a>
                                    <a href="{{ route('alertes.index') }}" class="btn btn-outline-secondary btn-lg px-4"
                                       style="border-radius:10px;">
                                        📋 Liste
                                    </a>
                                </div>
                                <div>
                                    <button type="submit"
                                            class="btn btn-warning btn-lg px-5"
                                            style="border-radius:10px; background: linear-gradient(135deg, #ffc107, #fd7e14); border:none;">
                                        💾 Enregistrer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Conseils -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-warning text-dark" style="border-radius:15px 15px 0 0;">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils de Modification</h5>
                    </div>
                    <div class="card-body">
                        <ul class="small mb-0">
                            <li class="mb-2">✅ <strong>Mettez à jour</strong> uniquement les informations qui ont changé</li>
                            <li class="mb-2">✅ <strong>Vérifiez l'exactitude</strong> des informations</li>
                            <li class="mb-2">✅ <strong>Adaptez la gravité</strong> si la situation a évolué</li>
                            <li class="mb-2">✅ <strong>Marquez comme résolu</strong> si le problème est traité</li>
                            <li class="mb-0">✅ <strong>Sauvegardez les coordonnées GPS</strong> pour la carte</li>
                        </ul>
                    </div>
                </div>

                <!-- Aperçu rapide -->
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header bg-info text-white" style="border-radius:15px 15px 0 0;">
                        <h5 class="mb-0"><i class="fas fa-eye me-2"></i>Aperçu Rapide</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Statut actuel :</strong><br>
                            <span class="badge bg-{{ $alerte->statut == 'resolue' ? 'success' : 'warning' }}">
                                {{ $alerte->statut == 'resolue' ? '✅ Résolue' : '🟡 En cours' }}
                            </span>
                        </div>
                        <div class="mb-3">
                            <strong>Gravité actuelle :</strong><br>
                            @switch($alerte->gravite)
                                @case('basse') <span class="badge bg-success">🟢 Basse</span> @break
                                @case('moyenne') <span class="badge bg-warning text-dark">🟡 Moyenne</span> @break
                                @case('haute') <span class="badge bg-danger">🔴 Haute</span> @break
                                @case('feu') <span class="badge bg-dark">🔥 Critique</span> @break
                            @endswitch
                        </div>
                        <div class="mb-0">
                            <strong>Zone :</strong><br>
                            <small class="text-muted">{{ $alerte->zone_geographique ?? 'Non spécifiée' }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Alert End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-2.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}
.card:hover {
    transform: translateY(-3px);
    transition: 0.3s ease-in-out;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}
.form-control:focus, .form-select:focus {
    border-color: #ffc107 !important;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation en temps réel
    const forms = document.querySelectorAll('.needs-validation');
    
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        }, false);
    });

    // Géolocalisation automatique (optionnel)
    const zoneInput = document.getElementById('zone_geographique');
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');

    if (navigator.geolocation && zoneInput && latInput && lngInput) {
        const geoBtn = document.createElement('button');
        geoBtn.type = 'button';
        geoBtn.className = 'btn btn-outline-primary btn-sm mt-2';
        geoBtn.innerHTML = '📍 Utiliser ma position actuelle';
        geoBtn.onclick = function() {
            navigator.geolocation.getCurrentPosition(function(position) {
                latInput.value = position.coords.latitude.toFixed(6);
                lngInput.value = position.coords.longitude.toFixed(6);
                
                // Reverse geocoding simple
                zoneInput.value = 'Position GPS: ' + position.coords.latitude.toFixed(4) + ', ' + position.coords.longitude.toFixed(4);
                
                geoBtn.innerHTML = '✅ Position capturée';
                geoBtn.className = 'btn btn-outline-success btn-sm mt-2';
            }, function(error) {
                alert('Impossible de récupérer votre position. Veuillez la saisir manuellement.');
            });
        };
        
        zoneInput.parentNode.appendChild(geoBtn);
    }

    // Avertissement si changement de statut vers "résolue"
    const statutSelect = document.getElementById('statut');
    if (statutSelect) {
        statutSelect.addEventListener('change', function() {
            if (this.value === 'resolue') {
                if (!confirm('Êtes-vous sûr de vouloir marquer cette alerte comme résolue ? Cette action sera visible par tous les utilisateurs.')) {
                    this.value = 'en_cours';
                }
            }
        });
    }
});
</script>
@endsection