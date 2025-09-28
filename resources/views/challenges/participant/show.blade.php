@extends('layouts.app')

@section('title', $challenge->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">{{ $challenge->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}" class="text-white">Challenges</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">{{ Str::limit($challenge->titre, 30) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Challenge Details Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Carte principale du challenge -->
                <div class="card border-0 shadow-lg challenge-detail-card" 
                     style="border-radius: 20px; overflow: hidden; transition: all 0.3s ease;">
                    
                    <!-- En-tête avec image/icône -->
                    <div class="card-header position-relative p-0" style="border: none;">
                        <!-- Badges de statut et catégorie -->
                        <div class="position-absolute top-0 start-0 m-4 d-flex flex-column gap-2">
                            @php
                                $now = \Carbon\Carbon::now();
                                $dateDebut = \Carbon\Carbon::parse($challenge->date_debut);
                                $dateFin = \Carbon\Carbon::parse($challenge->date_fin);
                            @endphp
                            
                            @if($now->between($dateDebut, $dateFin))
                                <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #28a745, #20c997); font-size: 0.8rem;">
                                    <i class="fas fa-play-circle me-1"></i>En cours
                                </span>
                            @elseif($now->lt($dateDebut))
                                <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0); font-size: 0.8rem;">
                                    <i class="fas fa-clock me-1"></i>À venir
                                </span>
                            @else
                                <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6c757d, #adb5bd); font-size: 0.8rem;">
                                    <i class="fas fa-check-circle me-1"></i>Terminé
                                </span>
                            @endif
                            
                            <span class="badge px-3 py-2" 
                                  style="background: rgba(0, 76, 33, 0.1); color: var(--bs-primary); border: 1px solid var(--bs-primary); font-size: 0.8rem;">
                                @switch($challenge->categorie)
                                    @case('environnement') 🌱 Environnement @break
                                    @case('recyclage') ♻️ Recyclage @break
                                    @case('nettoyage') 🧹 Nettoyage @break
                                    @case('plantation') 🌳 Plantation @break
                                    @case('sensibilisation') 📢 Sensibilisation @break
                                    @default 🌍 Autre
                                @endswitch
                            </span>
                        </div>
                        
                        <!-- Image/icône du challenge -->
                        <div style="height: 300px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-seedling" style="font-size: 6rem; color: white; opacity: 0.9;"></i>
                        </div>
                    </div>

                    <!-- Corps de la carte -->
                    <div class="card-body p-5">
                        <!-- Titre et difficulté -->
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <h2 class="card-title mb-0" style="color: var(--bs-primary); font-weight: 700;">
                                {{ $challenge->titre }}
                            </h2>
                            <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #ffc107, #fd7e14); color: #212529; font-size: 0.9rem;">
                                @if($challenge->difficulte == 'facile')
                                    ⭐ Facile
                                @elseif($challenge->difficulte == 'moyen')
                                    ⭐⭐ Moyen
                                @else
                                    ⭐⭐⭐ Difficile
                                @endif
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <h5 class="mb-3" style="color: var(--bs-dark);">
                                <i class="fas fa-align-left me-2" style="color: var(--bs-primary);"></i>Description
                            </h5>
                            <p class="card-text lead" style="line-height: 1.6; color: #495057;">
                                {{ $challenge->description }}
                            </p>
                        </div>

                        <!-- Informations détaillées -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-bullseye me-3" style="font-size: 1.5rem; color: var(--bs-primary);"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--bs-dark);">Objectif</h6>
                                        <p class="mb-0 text-muted">{{ $challenge->objectif ?? 'Non spécifié' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-calendar-alt me-3" style="font-size: 1.5rem; color: var(--bs-primary);"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--bs-dark);">Période</h6>
                                        <p class="mb-0 text-muted">
                                            {{ $dateDebut->format('d/m/Y') }} - 
                                            {{ $dateFin->format('d/m/Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-users me-3" style="font-size: 1.5rem; color: var(--bs-primary);"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--bs-dark);">Participants</h6>
                                        <p class="mb-0 text-muted">{{ $challenge->participants_count ?? $challenge->participants->count() }} participants</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-trophy me-3" style="font-size: 1.5rem; color: var(--bs-primary);"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--bs-dark);">Récompense</h6>
                                        <p class="mb-0 text-muted">
                                            @if($challenge->difficulte == 'facile')
                                                50 points
                                            @elseif($challenge->difficulte == 'moyen')
                                                100 points
                                            @else
                                                200 points
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-user me-3" style="font-size: 1.5rem; color: var(--bs-primary);"></i>
                                    <div>
                                        <h6 class="mb-1" style="color: var(--bs-dark);">Organisateur</h6>
                                        <p class="mb-0 text-muted">{{ $challenge->organisateur->name ?? 'Non spécifié' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barre de progression du temps -->
                        <div class="mb-4">
                            @php
                                $totalDays = $dateDebut->diffInDays($dateFin);
                                $daysPassed = $now->diffInDays($dateDebut);
                                $progressPercentage = $totalDays > 0 ? min(100, max(0, ($daysPassed / $totalDays) * 100)) : 0;
                            @endphp
                            <div class="d-flex justify-content-between mb-2">
                                <small class="text-muted">Début: {{ $dateDebut->format('d/m/Y') }}</small>
                                <small class="text-muted">Fin: {{ $dateFin->format('d/m/Y') }}</small>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div class="progress-bar" 
                                     role="progressbar" 
                                     style="width: {{ $progressPercentage }}%; background: linear-gradient(90deg, var(--bs-primary), var(--bs-success)); border-radius: 5px;"
                                     aria-valuenow="{{ $progressPercentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    @if($progressPercentage < 100 && $now->between($dateDebut, $dateFin))
                                        {{ number_format($progressPercentage, 1) }}% du temps écoulé
                                    @elseif($now->lt($dateDebut))
                                        Début dans {{ $now->diffInDays($dateDebut) }} jours
                                    @else
                                        Challenge terminé
                                    @endif
                                </small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 pt-4 border-top">
                            @auth
                                @if(!$participantChallenge)
                                    <!-- Bouton Participer -->
                                    <form method="POST" action="{{ route('challenges.participate', $challenge->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn btn-success btn-lg px-4 me-3 shadow-sm"
                                                style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border: none; transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 76, 33, 0.4)'"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0, 0, 0, 0.1)'">
                                            <i class="fas fa-flag me-2"></i>Participer au Challenge
                                        </button>
                                    </form>
                                @else
                                    <!-- Informations sur la participation -->
                                    <div class="bg-light p-4 rounded mb-3" style="border-left: 4px solid var(--bs-success);">
                                        <h5 class="mb-3" style="color: var(--bs-success);">
                                            <i class="fas fa-check-circle me-2"></i>Vous participez à ce challenge
                                        </h5>
                                        
                                        <!-- Statut de la participation -->
                                        <div class="mb-3">
                                            <strong>Statut :</strong>
                                            @switch($participantChallenge->statut)
                                                @case('en_cours')
                                                    <span class="badge bg-warning text-dark">En cours</span>
                                                    @break
                                                @case('en_attente')
                                                    <span class="badge bg-info">En attente de validation</span>
                                                    @break
                                                @case('valide')
                                                    <span class="badge bg-success">Validé</span>
                                                    @break
                                                @case('rejete')
                                                    <span class="badge bg-danger">Rejeté</span>
                                                    @break
                                            @endswitch
                                        </div>

                                        <!-- Score actuel -->
                                        @if($participantChallenge->score)
                                            <div class="mb-3">
                                                <strong>Score :</strong>
                                                <span class="badge bg-primary">{{ $participantChallenge->score->points }} points</span>
                                                @if($participantChallenge->score->badge)
                                                    <span class="badge bg-warning text-dark ms-2">Badge: {{ $participantChallenge->score->badge }}</span>
                                                @endif
                                            </div>
                                        @endif

                                        <!-- Formulaire de soumission de preuve -->
                                        @if($participantChallenge->statut === 'en_cours' || $participantChallenge->statut === 'en_attente')
                                            <form method="POST" action="{{ route('challenges.submit', $challenge->id) }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="mb-3">
                                                            <label for="preuve" class="form-label fw-semibold">Ajouter votre preuve :</label>
                                                            <input type="file" name="preuve" id="preuve" class="form-control" required 
                                                                   accept="image/*,.pdf,.doc,.docx">
                                                            <div class="form-text">Formats acceptés: images, PDF, Word (max: 5MB)</div>
                                                            @error('preuve')
                                                                <div class="text-danger small">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" 
                                                                class="btn btn-warning btn-lg w-100 mt-4 shadow-sm"
                                                                style="border-radius: 10px; transition: all 0.3s ease;">
                                                            <i class="fas fa-paper-plane me-2"></i>Soumettre
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        @endif

                                        <!-- Preuve existante -->
                                        @if($participantChallenge->preuve)
                                            <div class="mt-3">
                                                <strong>Preuve soumise :</strong>
                                                <a href="{{ asset('storage/' . $participantChallenge->preuve) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary ms-2">
                                                    <i class="fas fa-eye me-1"></i>Voir la preuve
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                @endif



                            @else
                                <!-- Message pour les non-connectés -->
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour participer à ce challenge et gagner des points !
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Challenge Details Section End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.challenge-detail-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.challenge-detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.breadcrumb-item.active {
    color: var(--bs-primary) !important;
    font-weight: 600;
}

/* Style pour les badges */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .challenge-detail-card {
        margin: 0 -15px;
        border-radius: 0;
    }
    
    .card-body {
        padding: 2rem !important;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 1rem;
    }
}

/* Animation pour la barre de progression */
.progress-bar {
    transition: width 1.5s ease-in-out;
}

/* Styles pour les statuts */
.badge.bg-warning { background: linear-gradient(135deg, #ffc107, #fd7e14) !important; }
.badge.bg-info { background: linear-gradient(135deg, #17a2b8, #0dcaf0) !important; }
.badge.bg-success { background: linear-gradient(135deg, #28a745, #20c997) !important; }
.badge.bg-danger { background: linear-gradient(135deg, #dc3545, #bb2d3b) !important; }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au chargement de la carte
    const card = document.querySelector('.challenge-detail-card');
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
        card.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 200);

    // Prévisualisation du nom du fichier
    const fileInput = document.getElementById('preuve');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                // Vous pourriez afficher le nom du fichier dans un élément
                console.log('Fichier sélectionné:', fileName);
            }
        });
    }

    // Confirmation de participation
    const participateForm = document.querySelector('form[action*="participate"]');
    if (participateForm) {
        participateForm.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir participer à ce challenge ?')) {
                e.preventDefault();
                
                // Animation de secousse
                const button = this.querySelector('button');
                button.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    button.style.animation = '';
                }, 500);
            }
        });
    }

    // Animation pour la barre de progression
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = width;
        }, 500);
    }
});

// Animation shake
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