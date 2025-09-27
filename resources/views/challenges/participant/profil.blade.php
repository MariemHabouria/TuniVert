@extends('layouts.app')

@section('title', 'Mes Challenges et Badges - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Mes Challenges et Badges</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Mon Profil</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Dashboard Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- En-tête du dashboard -->
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="display-6 fw-bold" style="color: var(--bs-primary);">
                    <i class="fas fa-trophy me-2"></i>Mon Tableau de Bord
                </h2>
                <p class="lead text-muted">Suivez votre progression et vos réalisations</p>
            </div>
        </div>

        <div class="row g-5">
            <!-- Colonne principale -->
            <div class="col-lg-8">
                <!-- Mes Challenges -->
                <div class="card border-0 shadow-sm mb-5" style="border-radius: 15px;">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-flag me-2"></i>Mes Challenges
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @forelse($participations as $p)
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm challenge-card h-100" 
                                         style="border-radius: 15px; transition: all 0.3s ease;">
                                        <div class="card-body p-4 d-flex flex-column">
                                            <h5 class="card-title mb-3" style="color: var(--bs-dark); font-weight: 600;">
                                                {{ $p->challenge->titre }}
                                            </h5>
                                            
                                            <div class="mb-3 flex-grow-1">
                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-calendar me-1" style="color: var(--bs-primary);"></i>
                                                    Du {{ \Carbon\Carbon::parse($p->challenge->date_debut)->format('d/m/Y') }} 
                                                    au {{ \Carbon\Carbon::parse($p->challenge->date_fin)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge px-3 py-2 {{ $p->statut == 'valide' ? 'bg-success' : ($p->statut == 'en_attente' ? 'bg-warning' : 'bg-danger') }}"
                                                      style="font-size: 0.8rem;">
                                                    <i class="fas fa-{{ $p->statut == 'valide' ? 'check' : ($p->statut == 'en_attente' ? 'clock' : 'times') }}-circle me-1"></i>
                                                    {{ ucfirst($p->statut) }}
                                                </span>
                                                <small class="text-muted">
                                                    Score: {{ $p->score->points ?? '0' }} pts
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <i class="fas fa-flag display-1 text-muted mb-3" style="opacity: 0.5;"></i>
                                    <h5 class="text-muted">Aucun challenge pour le moment</h5>
                                    <p class="text-muted">Relevez votre premier défi dès maintenant !</p>
                                    <a href="{{ route('challenges.index') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Découvrir les challenges
                                    </a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Classement général -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, var(--bs-info) 0%, var(--bs-primary) 100%); border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-chart-line me-2"></i>Classement Général
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($classement as $entry)
                                @php
                                    $rankClass = match($entry->rang) {
                                        1 => 'rank-1',
                                        2 => 'rank-2',
                                        3 => 'rank-3',
                                        default => 'rank-others'
                                    };
                                    $isCurrentUser = $entry->utilisateur_id == Auth::id();
                                @endphp
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rank-card {{ $isCurrentUser ? 'border-primary border-2' : '' }}" 
                                         style="border-radius: 10px; transition: all 0.3s ease;">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="rank-badge {{ $rankClass }} me-3">
                                                        {{ $entry->rang }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 {{ $isCurrentUser ? 'text-primary fw-bold' : 'text-dark' }}">
                                                            {{ $entry->utilisateur->name }}
                                                            @if($isCurrentUser)
                                                                <small class="text-muted">(Vous)</small>
                                                            @endif
                                                        </h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-star me-1" style="color: var(--bs-warning);"></i>
                                                            {{ $entry->score->points ?? 0 }} points
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <small class="text-muted d-block">Niveau</small>
                                                    <span class="badge bg-primary">{{ floor(($entry->score->points ?? 0) / 100) + 1 }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <i class="fas fa-users display-1 text-muted mb-3" style="opacity: 0.5;"></i>
                                    <h5 class="text-muted">Aucun participant pour le moment</h5>
                                    <p class="text-muted">Soyez le premier à rejoindre un challenge !</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar - Mes Badges -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, var(--bs-warning) 0%, var(--bs-danger) 100%); border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-medal me-2"></i>Mes Badges
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @forelse($badges as $badge)
                                <div class="col-6 col-sm-4 text-center">
                                    <div class="badge-card p-3" style="border-radius: 10px; background: var(--bs-light); transition: all 0.3s ease;">
                                        <div class="mb-2">
                                            <i class="fas fa-medal fa-2x" style="color: var(--bs-warning);"></i>
                                        </div>
                                        <small class="fw-semibold d-block" style="color: var(--bs-dark);">
                                            {{ $badge->badge }}
                                        </small>
                                        <small class="text-muted">Débloqué</small>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-4">
                                    <i class="fas fa-medal display-1 text-muted mb-3" style="opacity: 0.5;"></i>
                                    <h6 class="text-muted">Aucun badge pour le moment</h6>
                                    <p class="text-muted small">Participez à des challenges pour débloquer des badges !</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, var(--bs-success) 0%, var(--bs-info) 100%); border-radius: 15px 15px 0 0;">
                        <h4 class="mb-0 text-white">
                            <i class="fas fa-chart-bar me-2"></i>Mes Statistiques
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3" style="background: rgba(25, 135, 84, 0.1); border-radius: 10px;">
                            <div>
                                <small class="fw-semibold d-block" style="color: var(--bs-dark);">Challenges complétés</small>
                                <span class="h5 mb-0 fw-bold text-primary">{{ $participations->where('statut', 'valide')->count() }}</span>
                            </div>
                            <i class="fas fa-check-circle fa-2x" style="color: var(--bs-success);"></i>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3" style="background: rgba(13, 110, 253, 0.1); border-radius: 10px;">
                            <div>
                                <small class="fw-semibold d-block" style="color: var(--bs-dark);">Badges obtenus</small>
                                <span class="h5 mb-0 fw-bold text-primary">{{ $badges->count() }}</span>
                            </div>
                            <i class="fas fa-medal fa-2x" style="color: var(--bs-warning);"></i>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center p-3" style="background: rgba(108, 117, 125, 0.1); border-radius: 10px;">
                            <div>
                                <small class="fw-semibold d-block" style="color: var(--bs-dark);">Score total</small>
                                <span class="h5 mb-0 fw-bold text-primary">
                                    {{ $participations->sum(fn($p) => $p->score->points ?? 0) }} pts
                                </span>
                            </div>
                            <i class="fas fa-star fa-2x" style="color: var(--bs-warning);"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.challenge-card, .badge-card, .rank-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.challenge-card:hover, .badge-card:hover, .rank-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
}

.rank-badge {
    font-weight: bold;
    font-size: 1rem;
    padding: 0.5rem 0.8rem;
    border-radius: 50%;
    color: #fff;
    min-width: 40px;
    text-align: center;
}

.rank-1 { 
    background: linear-gradient(135deg, #FFD700, #FFA500); /* Gold */
    box-shadow: 0 2px 8px rgba(255, 215, 0, 0.4);
}
.rank-2 { 
    background: linear-gradient(135deg, #C0C0C0, #A9A9A9); /* Silver */
    box-shadow: 0 2px 8px rgba(192, 192, 192, 0.4);
}
.rank-3 { 
    background: linear-gradient(135deg, #CD7F32, #8B4513); /* Bronze */
    box-shadow: 0 2px 8px rgba(205, 127, 50, 0.4);
}
.rank-others { 
    background: linear-gradient(135deg, #6c757d, #495057);
}

.breadcrumb-item.active {
    color: var(--bs-primary) !important;
    font-weight: 600;
}

.card-header {
    border-bottom: none;
}

.badge {
    font-weight: 500;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .display-6 {
        font-size: 1.8rem;
    }
    
    .rank-badge {
        font-size: 0.9rem;
        padding: 0.4rem 0.7rem;
        min-width: 35px;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au chargement des cartes
    const cards = document.querySelectorAll('.challenge-card, .badge-card, .rank-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation des badges au survol
    const badgeCards = document.querySelectorAll('.badge-card');
    badgeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.05)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Mise en évidence de la position de l'utilisateur dans le classement
    const userRankCards = document.querySelectorAll('.rank-card.border-primary');
    userRankCards.forEach(card => {
        card.style.animation = 'pulse 2s infinite';
    });
});

// Animation pulse pour la carte de l'utilisateur
const style = document.createElement('style');
style.textContent = `
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);
</script>
@endsection