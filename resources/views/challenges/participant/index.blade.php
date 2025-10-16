@extends('layouts.app')

@section('title', 'Tous les Challenges - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Tous les Challenges</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Challenges</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Challenges List Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- En-tête -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="mb-3">
                        <h2 class="display-6 fw-bold" style="color: var(--bs-primary);">
                            <i class="fas fa-trophy me-2"></i>Découvrez tous les Challenges
                        </h2>
                        <p class="lead text-muted">Relevez des défis écologiques et contribuez à la protection de l'environnement</p>
                    </div>
                    <div class="mb-3">
                        <span class="badge bg-primary px-3 py-2 fs-6">
                            <i class="fas fa-challenge me-1"></i>{{ $challenges->count() }} Challenge(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grille des Challenges -->
        <div class="row g-4">
            @forelse($challenges as $challenge)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 challenge-card" 
                         style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
                        <!-- En-tête de la carte -->
                        <div class="card-header position-relative p-0" style="border: none;">
                            <!-- Badge de statut -->
                            <div class="position-absolute top-0 start-0 m-3">
                                @if($challenge->date_fin > now())
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #28a745, #20c997); font-size: 0.8rem;">
                                        <i class="fas fa-play-circle me-1"></i>En cours
                                    </span>
                                @else
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6c757d, #adb5bd); font-size: 0.8rem;">
                                        <i class="fas fa-flag-checkered me-1"></i>Terminé
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Image/icône du challenge -->
                            <div style="height: 180px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-leaf" style="font-size: 4rem; color: white; opacity: 0.9;"></i>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Titre du challenge -->
                            <h5 class="card-title mb-3" style="color: var(--bs-dark); font-weight: 600; line-height: 1.3;">
                                {{ $challenge->titre }}
                            </h5>

                            <!-- Description -->
                            <p class="text-muted mb-3 flex-grow-1">
                                {{ Str::limit($challenge->description, 120) }}
                            </p>

                            <!-- Informations du challenge -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--bs-primary);"></i>Début:
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($challenge->date_debut)->format('d/m/Y') }}
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-check me-1" style="color: var(--bs-primary);"></i>Fin:
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($challenge->date_fin)->format('d/m/Y') }}
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
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
                            </div>

                            <!-- Actions -->
                            <div class="mt-auto pt-3">
                                <a href="{{ route('challenges.show', $challenge->id) }}" 
                                   class="btn btn-success w-100 shadow-sm"
                                   style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border: none; transition: all 0.3s ease;"
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 76, 33, 0.3)'"
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-eye me-2"></i>Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <!-- Carte vide avec message -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px; background: var(--bs-light);">
                        <div class="card-body py-5">
                            <i class="fas fa-trophy" style="font-size: 4rem; color: var(--bs-primary); opacity: 0.5; margin-bottom: 1.5rem;"></i>
                            <h4 class="text-muted mb-3">Aucun challenge disponible</h4>
                            <p class="text-muted mb-4">Revenez plus tard pour découvrir de nouveaux défis écologiques !</p>
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4"
                               style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-info) 100%); border: none;">
                               <i class="fas fa-home me-2"></i>Retour à l'accueil
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->

    </div>
</div>
<!-- Challenges List End -->
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
    clip-path: polygon(0 0, 100% 0, 100% 85%, 85% 100%, 0 100%);
}

.challenge-card:hover {
    transform: translateY(-8px) scale(1.02);
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

/* Style pour la pagination */
.pagination .page-link {
    color: var(--bs-primary);
    border: 1px solid var(--bs-primary);
    margin: 0 2px;
    border-radius: 10px;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%);
    border-color: var(--bs-primary);
}

.pagination .page-link:hover {
    background-color: var(--bs-primary);
    color: white;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .challenge-card {
        margin-bottom: 1.5rem;
        clip-path: none;
    }
    
    .display-6 {
        font-size: 1.8rem;
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

    // Animation des boutons au survol
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Animation shake pour les interactions
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