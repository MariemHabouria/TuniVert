@extends('layouts.admin')

@section('title', 'Gestion des Alertes')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $totalAlertes }}</h4>
                        <span class="text-white-50">Total Alertes</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
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
                        <h4 class="mb-0 text-white">{{ $alertesThisMonth }}</h4>
                        <span class="text-white-50">Ce Mois</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0 text-white">{{ $alertesHaute }}</h4>
                        <span class="text-white-50">Gravité Haute</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-skull-crossbones fa-2x opacity-75"></i>
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
                        <h4 class="mb-0 text-white">{{ $alertesBasse }}</h4>
                        <span class="text-white-50">Gravité Basse</span>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-leaf fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Main Alerts Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">🚨 Liste des Alertes</h4>
                <div class="dropdown">
                    <button class="btn btn-outline-danger btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Filtres
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="filterBySeverity('haute')">🔴 Gravité Haute</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterBySeverity('moyenne')">🟡 Gravité Moyenne</a></li>
                        <li><a class="dropdown-item" href="#" onclick="filterBySeverity('basse')">🟢 Gravité Basse</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="showAll()">Toutes les alertes</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="alertesTable">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Gravité</th>
                                <th>Localisation</th>
                                <th>Auteur</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($alertes as $alerte)
                                <tr data-severity="{{ $alerte->gravite }}">
                                    <td><span class="badge bg-light text-dark">{{ $alerte->id }}</span></td>
                                    <td>
                                        <strong>{{ Str::limit($alerte->titre, 40) }}</strong>
                                        @if($alerte->gravite === 'haute')
                                            <span class="badge bg-danger ms-1">🚨</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ Str::limit($alerte->description, 60) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $severityConfig = [
                                                'haute' => ['bg-danger', '🔴', 'Haute'],
                                                'moyenne' => ['bg-warning', '🟡', 'Moyenne'],
                                                'basse' => ['bg-success', '🟢', 'Basse']
                                            ];
                                            $config = $severityConfig[$alerte->gravite] ?? ['bg-secondary', '⚪', 'Inconnue'];
                                        @endphp
                                        <span class="badge {{ $config[0] }}">{{ $config[1] }} {{ $config[2] }}</span>
                                    </td>
                                    <td>
                                        @if($alerte->zone_geographique)
                                            <i class="fas fa-map-marker-alt text-primary"></i> 
                                            {{ Str::limit($alerte->zone_geographique, 20) }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm bg-info rounded-circle d-flex align-items-center justify-content-center me-2">
                                                <span class="text-white">{{ substr($alerte->user->name ?? 'U', 0, 1) }}</span>
                                            </div>
                                            <small>{{ $alerte->user->name ?? 'Inconnu' }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $alerte->created_at->format('d/m/Y') }}<br>
                                            <span class="text-primary">{{ $alerte->created_at->format('H:i') }}</span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" onclick="viewAlert({{ $alerte->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-success" onclick="resolveAlert({{ $alerte->id }})">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" onclick="deleteAlert({{ $alerte->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-shield-alt fa-3x mb-3 text-muted"></i>
                                        <br>Aucune alerte trouvée
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $alertes->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar with charts and recent activity -->
    <div class="col-lg-4">
        <!-- Recent Alerts -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">⚡ Alertes Récentes</h6>
            </div>
            <div class="card-body">
                @forelse($recentAlertes as $alerte)
                    <div class="d-flex align-items-start mb-3">
                        @php
                            $severityColors = ['haute' => 'danger', 'moyenne' => 'warning', 'basse' => 'success'];
                            $color = $severityColors[$alerte->gravite] ?? 'secondary';
                        @endphp
                        <div class="avatar-sm bg-{{ $color }} rounded-circle d-flex align-items-center justify-content-center me-3">
                            <i class="fas fa-exclamation text-white"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ Str::limit($alerte->titre, 25) }}</h6>
                            <small class="text-muted">
                                {{ $alerte->user->name ?? 'Inconnu' }} • {{ $alerte->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Aucune alerte récente</p>
                @endforelse
            </div>
        </div>

        <!-- Severity Distribution -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">📊 Répartition par Gravité</h6>
            </div>
            <div class="card-body">
                <canvas id="severityChart" height="200"></canvas>
            </div>
        </div>

        <!-- Monthly Trend -->
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">📈 Évolution Mensuelle</h6>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="150"></canvas>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Severity Distribution Chart
const severityCtx = document.getElementById('severityChart').getContext('2d');
const severityChart = new Chart(severityCtx, {
    type: 'doughnut',
    data: {
        labels: ['Haute', 'Moyenne', 'Basse'],
        datasets: [{
            data: [{{ $alertesHaute }}, {{ $alertesMoyenne }}, {{ $alertesBasse }}],
            backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
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
                    padding: 15,
                    font: {size: 12}
                }
            }
        }
    }
});

// Monthly Trend Chart
const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
const monthlyData = @json($monthlyTrend);
const monthNames = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];

const monthlyChart = new Chart(monthlyCtx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => monthNames[item.month - 1]),
        datasets: [{
            label: 'Alertes',
            data: monthlyData.map(item => item.count),
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {display: false}
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Filter functions
function filterBySeverity(severity) {
    const rows = document.querySelectorAll('#alertesTable tbody tr');
    rows.forEach(row => {
        if (row.dataset.severity === severity) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function showAll() {
    const rows = document.querySelectorAll('#alertesTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
}

// Action functions
function viewAlert(id) {
    console.log('View alert:', id);
    // Implement view functionality
}

function resolveAlert(id) {
    if (confirm('Marquer cette alerte comme résolue ?')) {
        console.log('Resolve alert:', id);
        // Implement resolve functionality
    }
}

function deleteAlert(id) {
    if (confirm('Supprimer définitivement cette alerte ?')) {
        console.log('Delete alert:', id);
        // Implement delete functionality
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

.badge {
    font-size: 0.75em;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style>
@endsection
