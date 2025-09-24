@extends('layouts.app')

@section('title', 'Modifier le Challenge - ' . $challenge->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Modifier le Challenge</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}" class="text-white">Challenges</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.show', $challenge->id) }}" class="text-white">{{ $challenge->titre }}</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Modifier</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Edit Challenge Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-8 col-xl-8">
                <div class="card border-0 shadow-sm" style="border-radius: 15px; background: var(--bs-light);">
                    <div class="card-header py-4" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-warning) 100%); border-radius: 15px 15px 0 0;">
                        <h3 class="mb-0 text-center text-white">
                            <i class="fas fa-edit me-2"></i>Modifier le Challenge
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('challenges.update', $challenge->id) }}" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                            
                            <div class="row g-4">
                                <!-- Titre -->
                                <div class="col-12">
                                    <label for="titre" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-heading me-2" style="color: var(--bs-primary);"></i>Titre du Challenge *
                                    </label>
                                    <input type="text" name="titre" class="form-control form-control-lg" 
                                           style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 15px;"
                                           id="titre" placeholder="Ex: Nettoyage des plages" required
                                           value="{{ old('titre', $challenge->titre) }}">
                                    @error('titre')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="col-12">
                                    <label for="description" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-file-alt me-2" style="color: var(--bs-primary);"></i>Description *
                                    </label>
                                    <textarea name="description" class="form-control" id="description" 
                                              style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 15px; min-height: 120px;"
                                              rows="5" placeholder="Décrivez votre challenge en détail..." 
                                              required>{{ old('description', $challenge->description) }}</textarea>
                                    <div class="d-flex justify-content-between mt-2">
                                        <small class="text-muted">Soyez précis et motivant</small>
                                        <small class="text-muted"><span id="charCount">0</span>/500 caractères</small>
                                    </div>
                                    @error('description')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Dates -->
                                <div class="col-md-6">
                                    <label for="date_debut" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-alt me-2" style="color: var(--bs-primary);"></i>Date de début *
                                    </label>
                                    <input type="date" name="date_debut" class="form-control" 
                                           style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 12px;"
                                           id="date_debut" required value="{{ old('date_debut', \Carbon\Carbon::parse($challenge->date_debut)->format('Y-m-d')) }}">
                                    @error('date_debut')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="date_fin" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-check me-2" style="color: var(--bs-primary);"></i>Date de fin *
                                    </label>
                                    <input type="date" name="date_fin" class="form-control" 
                                           style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 12px;"
                                           id="date_fin" required value="{{ old('date_fin', \Carbon\Carbon::parse($challenge->date_fin)->format('Y-m-d')) }}">
                                    @error('date_fin')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Catégorie -->
                                <div class="col-md-6">
                                    <label for="categorie" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-tags me-2" style="color: var(--bs-primary);"></i>Catégorie
                                    </label>
                                    <select name="categorie" class="form-select" id="categorie"
                                            style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 12px;">
                                        <option value="">Choisir une catégorie</option>
                                        <option value="environnement" {{ old('categorie', $challenge->categorie) == 'environnement' ? 'selected' : '' }}>🌱 Environnement</option>
                                        <option value="recyclage" {{ old('categorie', $challenge->categorie) == 'recyclage' ? 'selected' : '' }}>♻️ Recyclage</option>
                                        <option value="nettoyage" {{ old('categorie', $challenge->categorie) == 'nettoyage' ? 'selected' : '' }}>🧹 Nettoyage</option>
                                        <option value="plantation" {{ old('categorie', $challenge->categorie) == 'plantation' ? 'selected' : '' }}>🌳 Plantation</option>
                                        <option value="sensibilisation" {{ old('categorie', $challenge->categorie) == 'sensibilisation' ? 'selected' : '' }}>📢 Sensibilisation</option>
                                    </select>
                                </div>

                                <!-- Difficulté -->
                                <div class="col-md-6">
                                    <label for="difficulte" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-chart-line me-2" style="color: var(--bs-primary);"></i>Niveau de difficulté
                                    </label>
                                    <select name="difficulte" class="form-select" id="difficulte"
                                            style="border: 2px solid var(--bs-gray-300); border-radius: 10px; padding: 12px;">
                                        <option value="facile" {{ old('difficulte', $challenge->difficulte) == 'facile' ? 'selected' : '' }}>⭐ Facile</option>
                                        <option value="moyen" {{ old('difficulte', $challenge->difficulte) == 'moyen' ? 'selected' : '' }}>⭐⭐ Moyen</option>
                                        <option value="difficile" {{ old('difficulte', $challenge->difficulte) == 'difficile' ? 'selected' : '' }}>⭐⭐⭐ Difficile</option>
                                    </select>
                                </div>

                                <!-- Objectif -->
                                <div class="col-12">
                                    <label for="objectif" class="form-label fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-bullseye me-2" style="color: var(--bs-primary);"></i>Objectif à atteindre
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="objectif" class="form-control" 
                                               style="border: 2px solid var(--bs-gray-300); border-radius: 10px 0 0 10px; padding: 12px;"
                                               id="objectif" placeholder="Ex: 100 participants, 500 arbres plantés..." 
                                               value="{{ old('objectif', $challenge->objectif) }}">
                                        <span class="input-group-text" style="background: var(--bs-primary); color: white; border: 2px solid var(--bs-primary);">participants</span>
                                    </div>
                                    <small class="text-muted">Laissez vide si aucun objectif quantitatif</small>
                                </div>

                            </div>

                            <!-- Boutons -->
                            <div class="row mt-5 pt-4">
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-secondary btn-lg px-4" 
                                       style="border-radius: 10px; background: var(--bs-gray); border: none;">
                                        <i class="fas fa-times me-2"></i>Annuler
                                    </a>
                                    <button type="submit" class="btn btn-warning btn-lg px-5"
                                            style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-warning) 100%); border: none;">
                                        <i class="fas fa-save me-2"></i>Mettre à jour
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Informative -->
            <div class="col-lg-4 col-xl-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: var(--bs-light);">
                    <div class="card-header py-3" style="background: var(--bs-primary); border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-info-circle me-2"></i>Informations du Challenge
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3 p-3" style="background: rgba(0, 76, 33, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar" style="color: var(--bs-primary);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Créé le</small>
                                <p class="mb-0 small text-muted">{{ \Carbon\Carbon::parse($challenge->created_at)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 p-3" style="background: rgba(0, 76, 33, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users" style="color: var(--bs-primary);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Participants</small>
                                <p class="mb-0 small text-muted">{{ $challenge->participations_count ?? 0 }} participant(s)</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 p-3" style="background: rgba(0, 76, 33, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chart-bar" style="color: var(--bs-primary);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Progression</small>
                                <p class="mb-0 small text-muted">
                                    @if($challenge->objectif)
                                        {{ round(($challenge->participations_count ?? 0) / $challenge->objectif * 100) }}% de l'objectif
                                    @else
                                        Aucun objectif défini
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="d-flex p-3" style="background: rgba(0, 76, 33, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock" style="color: var(--bs-primary);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Statut</small>
                                <p class="mb-0 small text-muted">
                                    @if(\Carbon\Carbon::now()->between(\Carbon\Carbon::parse($challenge->date_debut), \Carbon\Carbon::parse($challenge->date_fin)))
                                        <span class="badge bg-success">En cours</span>
                                    @elseif(\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($challenge->date_debut)))
                                        <span class="badge bg-info">À venir</span>
                                    @else
                                        <span class="badge bg-secondary">Terminé</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; background: var(--bs-light);">
                    <div class="card-header py-3" style="background: var(--bs-primary); border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-lightbulb me-2"></i>Conseils de Modification
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3 p-3" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle" style="color: var(--bs-warning);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Attention aux dates</small>
                                <p class="mb-0 small text-muted">Modifier les dates peut affecter les participants existants</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 p-3" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-bullhorn" style="color: var(--bs-warning);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Informez les participants</small>
                                <p class="mb-0 small text-muted">Pensez à notifier les changements importants</p>
                            </div>
                        </div>
                        <div class="d-flex p-3" style="background: rgba(255, 193, 7, 0.1); border-radius: 10px;">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle" style="color: var(--bs-warning);"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <small class="fw-semibold" style="color: var(--bs-dark);">Objectifs réalistes</small>
                                <p class="mb-0 small text-muted">Ajustez les objectifs en fonction de la participation actuelle</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 15px; background: var(--bs-light);">
                    <div class="card-header py-3" style="background: var(--bs-primary); border-radius: 15px 15px 0 0;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-question-circle me-2"></i>Actions Rapides
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="d-grid gap-2">
                            <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-outline-primary btn-lg" style="border-radius: 10px;">
                                <i class="fas fa-eye me-2"></i>Voir le Challenge
                            </a>
                            <a href="{{ route('challenges.index') }}" class="btn btn-outline-secondary btn-lg" style="border-radius: 10px;">
                                <i class="fas fa-list me-2"></i>Tous les Challenges
                            </a>
                            @if($challenge->participations_count > 0)
                            <a href="#" class="btn btn-outline-info btn-lg" style="border-radius: 10px;">
                                <i class="fas fa-users me-2"></i>Voir les Participants
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Challenge End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.form-control:focus, .form-select:focus {
    border-color: var(--bs-primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(0, 76, 33, 0.25) !important;
}

.btn-warning {
    transition: all 0.3s ease;
}

.btn-warning:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.4);
}

.breadcrumb-item.active {
    color: var(--bs-primary) !important;
    font-weight: 600;
}

.btn-outline-primary:hover, .btn-outline-secondary:hover, .btn-outline-info:hover {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white !important;
}

.badge {
    font-size: 0.75em;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validation des dates
    const dateDebut = document.getElementById('date_debut');
    const dateFin = document.getElementById('date_fin');
    
    if (dateDebut && dateFin) {
        const today = new Date().toISOString().split('T')[0];
        dateDebut.min = today;
        
        dateDebut.addEventListener('change', function() {
            dateFin.min = this.value;
            if (dateFin.value && dateFin.value < this.value) {
                dateFin.value = this.value;
            }
        });
    }

    // Compteur de caractères pour la description
    const description = document.getElementById('description');
    if (description) {
        const charCount = document.getElementById('charCount');
        
        description.addEventListener('input', function() {
            charCount.textContent = this.value.length;
            
            // Changement de couleur selon le nombre de caractères
            if (this.value.length > 400) {
                charCount.style.color = 'var(--bs-danger)';
            } else if (this.value.length > 300) {
                charCount.style.color = 'var(--bs-warning)';
            } else {
                charCount.style.color = 'var(--bs-success)';
            }
        });
        
        // Initialiser le compteur
        charCount.textContent = description.value.length;
        if (description.value.length > 400) {
            charCount.style.color = 'var(--bs-danger)';
        } else if (description.value.length > 300) {
            charCount.style.color = 'var(--bs-warning)';
        } else {
            charCount.style.color = 'var(--bs-success)';
        }
    }

    // Validation Bootstrap
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
                // Ajouter des animations sur les champs invalides
                const invalidFields = form.querySelectorAll(':invalid');
                invalidFields.forEach(field => {
                    field.style.animation = 'shake 0.5s ease-in-out';
                    setTimeout(() => {
                        field.style.animation = '';
                    }, 500);
                });
            }
            form.classList.add('was-validated');
        }, false);
    });
});

// Animation shake pour les champs invalides
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>
@endsection