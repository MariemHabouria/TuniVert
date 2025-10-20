@extends('layouts.admin')

@section('title', 'Gestion des Forums')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $totalForums }}</h4>
                        <span class="text-white-50">Total Forums</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-comments fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $forumsThisMonth }}</h4>
                        <span class="text-white-50">Ce Mois</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-plus fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $activeForums }}</h4>
                        <span class="text-white-50">Actifs (7j)</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-fire fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $totalReplies }}</h4>
                        <span class="text-white-50">Total Réponses</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-reply-all fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Main Forums Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">📋 Liste des Forums</h4>
                <div class="dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Actions
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Exporter</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-filter me-2"></i>Filtrer</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Vues</th>
                                <th>Réponses</th>
                                <th>Popularité</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($forums as $forum)
                            <tr>
                                <td><span class="badge bg-light text-dark">{{ $forum->id }}</span></td>
                                <td>
                                    <strong>{{ Str::limit($forum->titre, 50) }}</strong>
                                    @if($forum->nb_vues > 100)
                                        <span class="badge bg-warning ms-1">🔥</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                            <span class="text-white">{{ substr($forum->utilisateur->name ?? 'U', 0, 1) }}</span>
                                        </div>
                                        {{ $forum->utilisateur->name ?? 'Inconnu' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">👁️ {{ $forum->nb_vues ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">💬 {{ $forum->reponses_count ?? 0 }}</span>
                                </td>
                                <td>
                                    @php
                                        $score = $forum->popularite_score ?? 0;
                                        $class = $score > 100 ? 'bg-danger' : ($score > 50 ? 'bg-warning' : 'bg-secondary');
                                    @endphp
                                    <span class="badge {{ $class }}">{{ $score }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $forum->created_at->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('forums.show', $forum->id) }}" class="btn btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-outline-danger" onclick="confirmDelete({{ $forum->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-comment-slash fa-3x mb-3 text-muted"></i>
                                    <br>Aucun forum trouvé
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {{ $forums->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar with charts and recent activity -->
    <div class="col-lg-4">
        <!-- Recent Forums -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">🆕 Forums Récents</h6>
            </div>
            <div class="card-body">
                @forelse($recentForums as $forum)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                            <span class="text-white small">{{ $loop->iteration }}</span>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ Str::limit($forum->titre, 30) }}</h6>
                            <small class="text-muted">{{ $forum->utilisateur->name ?? 'Inconnu' }} • {{ $forum->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucune activité récente</p>
                @endforelse
            </div>
        </div>

        <!-- Popular Forums -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">🔥 Forums Populaires</h6>
            </div>
            <div class="card-body">
                @forelse($popularForums as $forum)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <h6 class="mb-0">{{ Str::limit($forum->titre, 25) }}</h6>
                            <small class="text-muted">{{ $forum->nb_vues ?? 0 }} vues</small>
                        </div>
                        <span class="badge bg-warning">{{ $forum->nb_vues ?? 0 }}</span>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucun forum populaire</p>
                @endforelse
            </div>
        </div>

        <!-- Forums by Topic Chart -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">📊 Répartition par Sujet</h6>
            </div>
            <div class="card-body">
                <canvas id="forumsChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Forums by Topic Chart
const ctx = document.getElementById('forumsChart').getContext('2d');
const forumsByTopic = @json($forumsByTopic);

const chart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: forumsByTopic.map(item => item.topic || 'Général'),
        datasets: [{
            data: forumsByTopic.map(item => item.count),
            backgroundColor: [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

function confirmDelete(forumId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce forum ?')) {
        // Implementation for delete functionality
        console.log('Delete forum:', forumId);
    }
}
</script>
@endsection

@section('styles')
<style>
.avatar-sm {
    width: 32px;
    height: 32px;
    font-size: 12px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.opacity-75 {
    opacity: 0.75;
}
</style>
@endsection
