@extends('layouts.app')

@section('title', 'Classement - ' . $challenge->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Classement - {{ $challenge->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}" class="text-white">Challenges</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Classement</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Classement Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- En-tête -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="display-6 fw-bold" style="color: var(--bs-primary);">
                            <i class="fas fa-trophy me-2"></i>Classement du Challenge
                        </h2>
                        <p class="lead text-muted">Découvrez le classement des participants de "{{ $challenge->titre }}"</p>
                    </div>
                    <a href="{{ route('challenges.index') }}" class="btn btn-outline-primary btn-lg px-4 shadow-sm"
                       style="border-radius: 10px; transition: all 0.3s ease;"
                       onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(0, 76, 33, 0.2)'"
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0, 0, 0, 0.1)'">
                       <i class="fas fa-arrow-left me-2"></i>Retour aux Challenges
                    </a>
                </div>
            </div>
        </div>

        <!-- Grille du Classement -->
        <div class="row g-4 justify-content-center">
            @forelse($participants as $participant)
                @php
                    $rankClass = match($participant->rang) {
                        1 => 'rank-1',
                        2 => 'rank-2',
                        3 => 'rank-3',
                        default => 'rank-others'
                    };
                    
                    $medalIcon = match($participant->rang) {
                        1 => 'fas fa-trophy',
                        2 => 'fas fa-medal',
                        3 => 'fas fa-award',
                        default => 'fas fa-star'
                    };
                @endphp
                
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 rank-card" 
                         style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
                        <!-- En-tête de la carte avec rang -->
                        <div class="card-header position-relative p-0" style="border: none;">
                            <!-- Badge de rang -->
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="rank-badge {{ $rankClass }} d-flex align-items-center justify-content-center">
                                    <i class="{{ $medalIcon }} me-1" style="font-size: 0.8rem;"></i>
                                    {{ $participant->rang }}
                                </span>
                            </div>
                            
                            <!-- Image/icône du participant -->
                            <div style="height: 180px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-warning) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-trophy" style="font-size: 4rem; color: white; opacity: 0.9;"></i>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Nom du participant -->
                            <h5 class="card-title mb-3" style="color: var(--bs-dark); font-weight: 600; line-height: 1.3;">
                                {{ $participant->utilisateur->name }}
                            </h5>

                            <!-- Informations du participant -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-chart-line me-1" style="color: var(--bs-primary);"></i>Score:
                                    </small>
                                    <small class="text-muted fw-bold" style="font-size: 1.1rem;">
                                        {{ $participant->score ? $participant->score->points : 0 }} pts
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-trophy me-1" style="color: var(--bs-primary);"></i>Position:
                                    </small>
                                    <small class="text-muted">
                                        @if($participant->rang == 1)
                                            🥇 1ère place
                                        @elseif($participant->rang == 2)
                                            🥈 2ème place
                                        @elseif($participant->rang == 3)
                                            🥉 3ème place
                                        @else
                                            #{{ $participant->rang }} place
                                        @endif
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--bs-primary);"></i>Participant depuis:
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($participant->created_at)->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>

                            <!-- Barre de progression indicative -->
                            <div class="mt-auto pt-3">
                                <div class="progress mb-2" style="height: 8px; border-radius: 4px;">
                                    @php
                                        $maxScore = $participants->max('score.points') ?? 100;
                                        $participantScore = $participant->score ? $participant->score->points : 0;
                                        $progressPercentage = $maxScore > 0 ? min(100, ($participantScore / $maxScore) * 100) : 0;
                                    @endphp
                                    <div class="progress-bar {{ $rankClass }}-bg" 
                                         role="progressbar" 
                                         style="width: {{ $progressPercentage }}%; border-radius: 4px;"
                                         aria-valuenow="{{ $progressPercentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted d-flex justify-content-between">
                                    <span>Progression</span>
                                    <span>{{ number_format($progressPercentage, 1) }}%</span>
                                </small>
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
                            <h4 class="text-muted mb-3">Aucun participant pour le moment</h4>
                            <p class="text-muted mb-4">Le classement sera disponible dès que des participants auront rejoint ce challenge.</p>
                            <a href="{{ route('challenges.index') }}" class="btn btn-primary btn-lg px-4"
                               style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-warning) 100%); border: none;">
                               <i class="fas fa-arrow-left me-2"></i>Retour aux challenges
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Section des podiums pour les 3 premiers -->
        @if($participants->count() >= 3)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="text-center mb-4" style="color: var(--bs-primary);">
                    <i class="fas fa-crown me-2"></i>Podium
                </h3>
                <div class="row justify-content-center">
                    <!-- 2ème place -->
                    @if($participants->count() >= 2)
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <div class="rank-badge rank-2 mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="fas fa-medal me-1"></i>2
                                </div>
                            </div>
                            <h5>{{ $participants[1]->utilisateur->name }}</h5>
                            <p class="text-muted">{{ $participants[1]->score ? $participants[1]->score->points : 0 }} pts</p>
                        </div>
                    </div>
                    @endif

                    <!-- 1ère place -->
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <div class="rank-badge rank-1 mb-3" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                                    <i class="fas fa-trophy me-1"></i>1
                                </div>
                            </div>
                            <h4 class="fw-bold">{{ $participants[0]->utilisateur->name }}</h4>
                            <p class="text-primary fw-bold">{{ $participants[0]->score ? $participants[0]->score->points : 0 }} pts</p>
                        </div>
                    </div>

                    <!-- 3ème place -->
                    @if($participants->count() >= 3)
                    <div class="col-lg-3 col-md-4 mb-4">
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <div class="rank-badge rank-3 mb-3" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                                    <i class="fas fa-award me-1"></i>3
                                </div>
                            </div>
                            <h5>{{ $participants[2]->utilisateur->name }}</h5>
                            <p class="text-muted">{{ $participants[2]->score ? $participants[2]->score->points : 0 }} pts</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Classement Section End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.rank-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
    clip-path: polygon(0 0, 100% 0, 100% 85%, 85% 100%, 0 100%);
}

.rank-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15) !important;
}

.rank-badge {
    font-weight: bold;
    font-size: 1rem;
    padding: 0.5rem;
    border-radius: 50%;
    color: #fff;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.rank-1 { 
    background: linear-gradient(135deg, #FFD700, #FFA500); /* Gold */
}
.rank-2 { 
    background: linear-gradient(135deg, #C0C0C0, #A0A0A0); /* Silver */
}
.rank-3 { 
    background: linear-gradient(135deg, #CD7F32, #A65C00); /* Bronze */
}
.rank-others { 
    background: linear-gradient(135deg, #6c757d, #495057);
}

.rank-1-bg { background: linear-gradient(90deg, #FFD700, #FFA500); }
.rank-2-bg { background: linear-gradient(90deg, #C0C0C0, #A0A0A0); }
.rank-3-bg { background: linear-gradient(90deg, #CD7F32, #A65C00); }

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

/* Responsive adjustments */
@media (max-width: 768px) {
    .rank-card {
        clip-path: none; /* Supprimer l'effet de clip-path sur mobile */
    }
    
    .rank-badge {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
}

/* Animation pour le podium */
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
    40% {transform: translateY(-10px);}
    60% {transform: translateY(-5px);}
}

.rank-badge.rank-1 {
    animation: bounce 2s infinite;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation au chargement des cartes
    const cards = document.querySelectorAll('.rank-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Animation spéciale pour le podium
    const podiumBadges = document.querySelectorAll('.rank-badge.rank-1, .rank-badge.rank-2, .rank-badge.rank-3');
    podiumBadges.forEach((badge, index) => {
        setTimeout(() => {
            badge.style.transition = 'transform 0.5s ease';
            badge.style.transform = 'scale(1.1)';
            setTimeout(() => {
                badge.style.transform = 'scale(1)';
            }, 500);
        }, index * 200 + 1000);
    });
});
</script>
@endsection