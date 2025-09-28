@extends('layouts.app')

@section('title', 'Participants - ' . $challenge->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top: -1px; padding-top: 6rem !important; background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size: cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Participants - {{ $challenge->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('challenges.index') }}" class="text-white">Challenges</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Participants</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Participants Section Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- En-tête -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="display-6 fw-bold" style="color: var(--bs-primary);">
                            <i class="fas fa-users me-2"></i>Participants du Challenge
                        </h2>
                        <p class="lead text-muted">Gérez les participants de "{{ $challenge->titre }}"</p>
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

        <!-- Grille des Participants -->
        <div class="row g-4">
            @forelse($participants as $p)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="card border-0 shadow-sm h-100 participant-card" 
                         style="border-radius: 15px; overflow: hidden; transition: all 0.3s ease;">
                        <!-- En-tête de la carte -->
                        <div class="card-header position-relative p-0" style="border: none;">
                            <!-- Badge de statut -->
                            <div class="position-absolute top-0 start-0 m-3">
                                @if($p->statut == 'en_cours')
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #ffc107, #fd7e14); font-size: 0.8rem;">
                                        <i class="fas fa-clock me-1"></i>En attente
                                    </span>
                                @elseif($p->statut == 'valide')
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #28a745, #20c997); font-size: 0.8rem;">
                                        <i class="fas fa-check-circle me-1"></i>Validé
                                    </span>
                                @elseif($p->statut == 'rejete')
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #dc3545, #e83e8c); font-size: 0.8rem;">
                                        <i class="fas fa-times-circle me-1"></i>Rejeté
                                    </span>
                                @elseif($p->statut == 'complet')
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #17a2b8, #0dcaf0); font-size: 0.8rem;">
                                        <i class="fas fa-flag-checkered me-1"></i>Complété
                                    </span>
                                @elseif($p->statut == 'annule')
                                    <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #6c757d, #adb5bd); font-size: 0.8rem;">
                                        <i class="fas fa-ban me-1"></i>Annulé
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Image/icône du participant -->
                            <div style="height: 180px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-info) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user" style="font-size: 4rem; color: white; opacity: 0.9;"></i>
                            </div>
                        </div>

                        <!-- Corps de la carte -->
                        <div class="card-body p-4 d-flex flex-column">
                            <!-- Nom du participant -->
                            <h5 class="card-title mb-3" style="color: var(--bs-dark); font-weight: 600; line-height: 1.3;">
                                {{ $p->utilisateur->name }}
                            </h5>

                            <!-- Informations du participant -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-chart-line me-1" style="color: var(--bs-primary);"></i>Score:
                                    </small>
                                    <small class="text-muted fw-bold">
                                        {{ $p->score->points ?? '0' }} pts
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-calendar-alt me-1" style="color: var(--bs-primary);"></i>Date d'inscription:
                                    </small>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($p->created_at)->format('d/m/Y') }}
                                    </small>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-id-card me-1" style="color: var(--bs-primary);"></i>ID:
                                    </small>
                                    <small class="text-muted">
                                        #{{ $p->id }}
                                    </small>
                                </div>

                                <!-- Preuve soumise - CORRECTION -->
                                @if($p->preuve)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <small class="fw-semibold" style="color: var(--bs-dark);">
                                        <i class="fas fa-paperclip me-1" style="color: var(--bs-primary);"></i>Preuve:
                                    </small>
                                    <small>
                                        @php
                                            $filePath = $p->preuve;
                                            // Si le chemin commence par 'preuves/', on utilise storage_path
                                            if (strpos($filePath, 'preuves/') === 0) {
                                                $fileUrl = asset('storage/' . $filePath);
                                            } else {
                                                $fileUrl = asset('storage/preuves/' . $filePath);
                                            }
                                        @endphp
                                        <a href="{{ $fileUrl }}" 
                                           target="_blank" 
                                           class="text-primary text-decoration-none"
                                           onclick="return openProofModal('{{ $fileUrl }}', '{{ $p->utilisateur->name }}')">
                                            <i class="fas fa-eye me-1"></i>Voir
                                        </a>
                                    </small>
                                </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            @if($p->statut == 'en_cours' && $p->preuve)
                            <div class="mt-auto pt-3">
                                <form action="{{ route('challenges.participants.action', $p->id) }}" method="POST" class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    @csrf
                                    <button name="action" value="valider" 
                                            class="btn btn-success btn-sm flex-fill me-md-1 mb-1"
                                            style="border-radius: 8px; transition: all 0.3s ease;"
                                            onmouseover="this.style.transform='translateY(-2px)'"
                                            onmouseout="this.style.transform='translateY(0)'">
                                        <i class="fas fa-check me-1"></i>Valider
                                    </button>
                                    <button name="action" value="rejeter" 
                                            class="btn btn-danger btn-sm flex-fill mb-1"
                                            style="border-radius: 8px; transition: all 0.3s ease;"
                                            onmouseover="this.style.transform='translateY(-2px)'"
                                            onmouseout="this.style.transform='translateY(0)'">
                                        <i class="fas fa-times me-1"></i>Rejeter
                                    </button>
                                </form>
                            </div>
                            @elseif($p->statut == 'en_cours' && !$p->preuve)
                            <div class="mt-auto pt-3">
                                <div class="alert alert-warning text-center mb-0 py-2" style="border-radius: 8px;">
                                    <small><i class="fas fa-clock me-1"></i>En attente de preuve</small>
                                </div>
                            </div>
                            @else
                            <div class="mt-auto pt-3">
                                <div class="alert alert-{{ $p->statut == 'valide' ? 'success' : ($p->statut == 'rejete' ? 'danger' : ($p->statut == 'complet' ? 'info' : 'secondary')) }} text-center mb-0 py-2" style="border-radius: 8px;">
                                    <small>
                                        <i class="fas fa-{{ $p->statut == 'valide' ? 'check' : ($p->statut == 'rejete' ? 'times' : ($p->statut == 'complet' ? 'flag-checkered' : 'ban')) }}-circle me-1"></i>
                                        Participant {{ $p->statut == 'valide' ? 'validé' : ($p->statut == 'rejete' ? 'rejeté' : ($p->statut == 'complet' ? 'complété' : 'annulé')) }}
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Carte vide avec message -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 15px; background: var(--bs-light);">
                        <div class="card-body py-5">
                            <i class="fas fa-users" style="font-size: 4rem; color: var(--bs-primary); opacity: 0.5; margin-bottom: 1.5rem;"></i>
                            <h4 class="text-muted mb-3">Aucun participant pour le moment</h4>
                            <p class="text-muted mb-4">Ce challenge n'a pas encore de participants.</p>
                            <a href="{{ route('challenges.index') }}" class="btn btn-primary btn-lg px-4"
                               style="border-radius: 10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-info) 100%); border: none;">
                               <i class="fas fa-arrow-left me-2"></i>Retour aux challenges
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Statistiques -->
        @if($participants->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body">
                        <h5 class="card-title mb-4" style="color: var(--bs-primary);">
                            <i class="fas fa-chart-bar me-2"></i>Statistiques des Participants
                        </h5>
                        <div class="row text-center">
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(255, 193, 7, 0.1);">
                                    <h4 class="fw-bold text-warning">{{ $participants->where('statut', 'en_cours')->count() }}</h4>
                                    <small class="text-muted">En attente</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(40, 167, 69, 0.1);">
                                    <h4 class="fw-bold text-success">{{ $participants->where('statut', 'valide')->count() }}</h4>
                                    <small class="text-muted">Validés</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(220, 53, 69, 0.1);">
                                    <h4 class="fw-bold text-danger">{{ $participants->where('statut', 'rejete')->count() }}</h4>
                                    <small class="text-muted">Rejetés</small>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="p-3 rounded" style="background: rgba(108, 117, 125, 0.1);">
                                    <h4 class="fw-bold text-secondary">{{ $participants->whereIn('statut', ['complet', 'annule'])->count() }}</h4>
                                    <small class="text-muted">Autres</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<!-- Participants Section End -->

<!-- Modal pour afficher les preuves -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-labelledby="proofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="proofModalLabel">Preuve de <span id="participantName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="proofContent">
                    <!-- Le contenu de la preuve sera chargé ici -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <a href="#" id="downloadProof" class="btn btn-primary" download>
                    <i class="fas fa-download me-1"></i>Télécharger
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.participant-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background: white;
}

.participant-card:hover {
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

/* Style pour les badges de statut */
.badge {
    font-weight: 500;
    letter-spacing: 0.5px;
}

/* Style pour les images dans le modal */
#proofContent img {
    max-width: 100%;
    max-height: 70vh;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Style pour les PDF dans le modal */
#proofContent embed,
#proofContent iframe {
    width: 100%;
    height: 70vh;
    border: none;
    border-radius: 10px;
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
    
    .participant-card {
        margin-bottom: 1.5rem;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
}
</style>
@endsection

@section('scripts')
<script>
function openProofModal(fileUrl, participantName) {
    const modal = new bootstrap.Modal(document.getElementById('proofModal'));
    const modalLabel = document.getElementById('participantName');
    const proofContent = document.getElementById('proofContent');
    const downloadLink = document.getElementById('downloadProof');
    
    // Mettre à jour le nom du participant
    modalLabel.textContent = participantName;
    
    // Mettre à jour le lien de téléchargement
    downloadLink.href = fileUrl;
    
    // Déterminer le type de fichier et afficher le contenu approprié
    const fileExtension = fileUrl.split('.').pop().toLowerCase();
    
    if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
        // Image
        proofContent.innerHTML = `<img src="${fileUrl}" alt="Preuve ${participantName}" class="img-fluid">`;
    } else if (fileExtension === 'pdf') {
        // PDF
        proofContent.innerHTML = `<embed src="${fileUrl}" type="application/pdf" class="w-100">`;
    } else if (['doc', 'docx'].includes(fileExtension)) {
        // Document Word - afficher un lien de téléchargement
        proofContent.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-file-word fa-3x mb-3 text-primary"></i>
                <h5>Document Word</h5>
                <p>Ce fichier ne peut pas être prévisualisé directement.</p>
                <p>Veuillez le télécharger pour le consulter.</p>
            </div>
        `;
    } else {
        // Autres types de fichiers
        proofContent.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-file fa-3x mb-3 text-warning"></i>
                <h5>Fichier non prévisualisable</h5>
                <p>Ce type de fichier ne peut pas être affiché directement.</p>
                <p>Veuillez le télécharger pour le consulter.</p>
            </div>
        `;
    }
    
    modal.show();
    return false; // Empêcher le comportement par défaut du lien
}

document.addEventListener('DOMContentLoaded', function() {
    // Animation au chargement des cartes
    const cards = document.querySelectorAll('.participant-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Confirmation pour les actions de validation/rejet
    const actionForms = document.querySelectorAll('form[action*="participants.action"]');
    actionForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const action = e.submitter.value;
            const participantName = this.closest('.card').querySelector('.card-title').textContent;
            
            if (!confirm(`Voulez-vous vraiment ${action === 'valider' ? 'valider' : 'rejeter'} la participation de ${participantName} ?`)) {
                e.preventDefault();
                
                // Animation de secousse sur le bouton
                const button = e.submitter;
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
`;
document.head.appendChild(style);
</script>
@endsection