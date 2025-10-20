@extends('layouts.admin')

@section('title', 'Événements - Dashboard')

@section('styles')
<style>
.pagination .page-link {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}
.pagination .page-link i {
    font-size: 0.75rem;
}
.event-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}
.participant-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}
.participant-item:last-child {
    border-bottom: none;
}
.comment-item {
    border-bottom: 1px solid #eee;
    padding: 15px 0;
}
.comment-item:last-child {
    border-bottom: none;
}
.avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="card-title">📅 Gestion des Événements</h4>
            <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
                <i class="mdi mdi-plus"></i> Nouvel Événement
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h4>{{ $events->total() ?? 0 }}</h4>
                        <p>Total Événements</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h4>{{ $stats['events_this_month'] ?? 0 }}</h4>
                        <p>Ce Mois</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h4>{{ $stats['upcoming_events'] ?? 0 }}</h4>
                        <p>À Venir</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body text-center">
                        <h4>{{ $stats['past_events'] ?? 0 }}</h4>
                        <p>Terminés</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Events List -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">🎯 Liste des Événements avec Participants et Commentaires</h5>
                
                @forelse($events as $event)
                    <div class="border rounded p-3 mb-3">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                @if($event->image)
                                    <img src="{{ asset($event->image) }}" alt="Event" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                @else
                                    <div style="width: 60px; height: 60px; background: #f8f9fa; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="mdi mdi-calendar mdi-24px text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <p class="text-muted mb-0">
                                    <i class="mdi mdi-calendar"></i> 
                                    {{ \Carbon\Carbon::parse($event->date)->format('d/m/Y H:i') }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="mdi mdi-map-marker"></i> {{ $event->location }}
                                </p>
                            </div>
                            <div class="col-md-2">
                                <span class="badge badge-primary">{{ $event->category }}</span>
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-success mb-1" onclick="showParticipants({{ $event->id }})">
                                    <i class="mdi mdi-account-group"></i> Participants ({{ $event->participants_count ?? 0 }})
                                </button>
                                <button class="btn btn-sm btn-info" onclick="showComments({{ $event->id }})">
                                    <i class="mdi mdi-comment"></i> Commentaires ({{ $event->comments_count ?? 0 }})
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.evenements.show', $event) }}" 
                                   class="btn btn-sm btn-outline-info mb-1">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                                <a href="{{ route('admin.evenements.edit', $event) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="mdi mdi-pencil"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5">
                        <i class="mdi mdi-calendar mdi-48px text-muted"></i>
                        <h5 class="text-muted mt-3">Aucun événement trouvé</h5>
                        <a href="{{ route('admin.evenements.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Créer un événement
                        </a>
                    </div>
                @endforelse

                @if($events->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Navigation des événements">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($events->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            <i class="mdi mdi-chevron-left"></i> Précédent
                                        </span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $events->previousPageUrl() }}">
                                            <i class="mdi mdi-chevron-left"></i> Précédent
                                        </a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                    @if ($page == $events->currentPage())
                                        <li class="page-item active">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($events->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $events->nextPageUrl() }}">
                                            Suivant <i class="mdi mdi-chevron-right"></i>
                                        </a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">
                                            Suivant <i class="mdi mdi-chevron-right"></i>
                                        </span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Participants Modal -->
<div class="modal fade" id="participantsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">👥 Participants de l'Événement</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="participantsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">💬 Commentaires de l'Événement</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="commentsContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function showParticipants(eventId) {
    $('#participantsContent').html('<div class="text-center p-4"><div class="spinner-border"></div><p class="mt-2">Chargement des participants...</p></div>');
    $('#participantsModal').modal('show');
    
    // Real AJAX call to get participants
    fetch(`/admin/evenements/${eventId}/participants`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.participants.length > 0) {
                let html = '';
                data.participants.forEach(participant => {
                    html += `
                        <div class="participant-item">
                            <div class="d-flex align-items-center">
                                <img src="${participant.user.avatar}" 
                                     alt="${participant.user.name}" 
                                     class="avatar-small me-3">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">${participant.user.name}</h6>
                                    <small class="text-muted">${participant.user.email}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">Inscrit le ${participant.created_at}</small>
                                    <br>
                                    <small class="text-muted">${participant.created_at_human}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#participantsContent').html(html);
            } else {
                $('#participantsContent').html(`
                    <div class="text-center py-4">
                        <i class="mdi mdi-account-group mdi-48px text-muted"></i>
                        <h5 class="text-muted mt-3">Aucun participant</h5>
                        <p class="text-muted">Cet événement n'a pas encore de participants inscrits.</p>
                    </div>
                `);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#participantsContent').html(`
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Impossible de charger les participants.
                </div>
            `);
        });
}

function showComments(eventId) {
    $('#commentsContent').html('<div class="text-center p-4"><div class="spinner-border"></div><p class="mt-2">Chargement des commentaires...</p></div>');
    $('#commentsModal').modal('show');
    
    // Real AJAX call to get comments
    fetch(`/admin/evenements/${eventId}/comments`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.comments.length > 0) {
                let html = '';
                data.comments.forEach(comment => {
                    html += `
                        <div class="comment-item">
                            <div class="d-flex align-items-start">
                                <img src="${comment.user.avatar}" 
                                     alt="${comment.user.name}" 
                                     class="avatar-small me-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">${comment.user.name}</h6>
                                        <small class="text-muted">${comment.created_at_human}</small>
                                    </div>
                                    <p class="mb-0">${comment.content}</p>
                                    <small class="text-muted">${comment.created_at}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#commentsContent').html(html);
            } else {
                $('#commentsContent').html(`
                    <div class="text-center py-4">
                        <i class="mdi mdi-comment-multiple mdi-48px text-muted"></i>
                        <h5 class="text-muted mt-3">Aucun commentaire</h5>
                        <p class="text-muted">Cet événement n'a pas encore de commentaires.</p>
                    </div>
                `);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            $('#commentsContent').html(`
                <div class="alert alert-danger">
                    <strong>Erreur!</strong> Impossible de charger les commentaires.
                </div>
            `);
        });
}
</script>
@endsection