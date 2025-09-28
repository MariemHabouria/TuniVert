@extends('layouts.app')

@section('title', 'Mes Challenges - Association')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Mes Challenges</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}" class="text-white">Challenges</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Mes Challenges</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Mes Challenges Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- En-tête avec bouton de création -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="display-6 fw-bold" style="color: var(--bs-primary);">
                            <i class="fas fa-tasks me-2"></i>Mes Challenges Créés
                        </h2>
                        <p class="lead text-muted">Gérez et suivez vos challenges écologiques</p>
                    </div>
                    <a href="{{ route('challenges.create') }}" class="btn btn-success btn-lg px-4 shadow-sm"
                       style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border: none; transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 76, 33, 0.4)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0, 0, 0, 0.1)'">
                       <i class="fas fa-plus-circle me-2"></i>Créer un Challenge
                    </a>
                </div>
            </div>
        </div>

        <!-- Grille des Challenges -->
        <div class="row g-4">
            @forelse($challenges as $challenge)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 challenge-card" 
                         style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
                        <!-- En-tête de la carte avec statut -->
                        <div class="card-header position-relative p-0" style="border: none;">
                            <!-- Badge de statut -->
                            <div class="position-absolute top-0 start-0 m-3">
                                @if(\Carbon\Carbon::now()->between(\Carbon\Carbon::parse($challenge->date_debut), \Carbon\Carbon::parse($challenge->date_fin)))
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #28a745, #20c997); font-size: 0.8rem;">
                                        <i class="fas fa-play-circle me-1"></i>En cours
                                    </span>
                                @elseif(\Carbon\Carbon::now()->lt(\Carbon\Carbon::parse($challenge->date_debut)))
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0); font-size: 0.8rem;">
                                        <i class="fas fa-clock me-1"></i>À venir
                                    </span>
                                @else
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6c757d, #adb5bd); font-size: 0.8rem;">
                                        <i class="fas fa-check-circle me-1"></i>Terminé
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Image/icône du challenge -->
                            <div style="height: 180px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-seedling" style="font-size: 4rem; color: white; opacity: 0.9;"></i>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Catégorie -->
                            <div class="mb-2">
                                <span class="badge px-3 py-2" 
                                      style="background: rgba(0, 76, 33, 0.1); color: var(--bs-primary); border: 1px solid var(--bs-primary); font-size: 0.75rem;">
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

                            <!-- Titre -->
                            <h5 class="card-title mb-3" style="color: var(--bs-dark); font-weight: 600; line-height: 1.3;">
                                {{ $challenge->titre }}
                            </h5>

                            <!-- Description courte -->
                            <p class="card-text text-muted small mb-3 flex-grow-1" style="line-height: 1.5;">
                                {{ Str::limit($challenge->description, 120) }}
                            </p>

                            <!-- Informations du challenge -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--bs-primary);"></i>Dates:
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($challenge->date_debut)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($challenge->date_fin)->format('d/m/Y') }}
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-chart-line me-1" style="color: var(--bs-primary);"></i>Difficulté:
                                    </small>
                                    <small class="text-muted">
                                        @if($challenge->difficulte == 'facile')
                                            ⭐ Facile
                                        @elseif($challenge->difficulte == 'moyen')
                                            ⭐⭐ Moyen
                                        @else
                                            ⭐⭐⭐ Difficile
                                        @endif
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-users me-1" style="color: var(--bs-primary);"></i>Participants:
                                    </small>
                                    <small class="text-muted">
    {{ $challenge->participants_count }}
</small>

                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-auto pt-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <a href="{{ route('challenges.edit', $challenge->id) }}" 
                                       class="btn btn-warning btn-sm flex-fill me-md-1 mb-1"
                                       style="border-radius: 8px; transition: all 0.3s ease;"
                                       onmouseover="this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.transform='translateY(0)'">
                                        <i class="fas fa-edit me-1"></i>Modifier
                                    </a>
                                    
                                    <a href="{{ route('challenges.participants', $challenge->id) }}" 
                                       class="btn btn-info btn-sm flex-fill me-md-1 mb-1"
                                       style="border-radius: 8px; transition: all 0.3s ease;"
                                       onmouseover="this.style.transform='translateY(-2px)'"
                                       onmouseout="this.style.transform='translateY(0)'">
                                        <i class="fas fa-users me-1"></i>Participants
                                    </a>
                                    
                                    <form action="{{ route('challenges.destroy', $challenge->id) }}" method="POST" 
                                          class="d-inline flex-fill mb-1"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer ce challenge ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-danger btn-sm w-100"
                                                style="border-radius: 8px; transition: all 0.3s ease;"
                                                onmouseover="this.style.transform='translateY(-2px)'"
                                                onmouseout="this.style.transform='translateY(0)'">
                                            <i class="fas fa-trash me-1"></i>Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Carte vide avec message -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px; background: var(--bs-light);">
                        <div class="card-body py-5">
                            <i class="fas fa-inbox" style="font-size: 4rem; color: var(--bs-primary); opacity: 0.5; margin-bottom: 1.5rem;"></i>
                            <h4 class="text-muted mb-3">Aucun challenge créé pour le moment</h4>
                            <p class="text-muted mb-4">Commencez par créer votre premier challenge écologique !</p>
                            <a href="{{ route('challenges.create') }}" class="btn btn-success btn-lg px-4"
                               style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border: none;">
                               <i class="fas fa-plus-circle me-2"></i>Créer mon premier challenge
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

       
    </div>
</div>
<!-- Mes Challenges Section End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.challenge-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.challenge-card:hover {
    transform: translateY(-8px);
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

/* Style pour les badges de statut */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Style pour les cartes vides */
.card-empty {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-md-flex {
        flex-direction: column;
    }
    
    .me-md-1 {
        margin-right: 0 !important;
        margin-bottom: 0.5rem;
    }
    
    .challenge-card {
        margin-bottom: 1.5rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au chargement des cartes
    const cards = document.querySelectorAll('.challenge-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Confirmation de suppression avec animation
    const deleteForms = document.querySelectorAll('form[onsubmit]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm(this.getAttribute('onsubmit').replace("return confirm('", "").replace("');", ""))) {
                e.preventDefault();
                
                // Animation de secousse sur le bouton
                const button = this.querySelector('button');
                button.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    button.style.animation = '';
                }, 500);
            }
        });
    });
});

// Animation shake pour les boutons
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    
    .pagination .page-link {
        color: var(--bs-primary);
        border: 1px solid var(--bs-primary);
    }
    
    .pagination .page-item.active .page-link {
        background-color: var(--bs-primary);
        border-color: var(--bs-primary);
    }
    
    .pagination .page-link:hover {
        background-color: rgba(0, 76, 33, 0.1);
    }
`;
document.head.appendChild(style);
</script>
@endsection