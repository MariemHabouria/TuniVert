@extends('layouts.admin')

@section('title', 'Tableau de Bord des Scores - Statistiques Détaillées')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-primary">Tableau de Bord des Scores</h1>
        <div class="d-flex">
            <button class="btn btn-success me-2" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>Exporter Excel
            </button>
            <button class="btn btn-danger" onclick="generatePDF()">
                <i class="fas fa-file-pdf me-2"></i>Exporter PDF
            </button>
        </div>
    </div>

    <!-- Cartes de Statistiques Principales -->
    <div class="row mb-4">
        @php
            $cards = [
                ['title' => 'Total Challenges', 'value' => $stats['total_challenges'], 'icon' => 'fa-trophy', 'border' => 'primary', 'color' => 'primary'],
                ['title' => 'Total Participants', 'value' => $stats['total_participants'], 'icon' => 'fa-users', 'border' => 'success', 'color' => 'success'],
                ['title' => 'Total Points', 'value' => number_format($stats['total_points'], 0, ',', ' '), 'icon' => 'fa-star', 'border' => 'info', 'color' => 'info'],
                ['title' => 'Points Moyens', 'value' => $stats['points_moyens'], 'icon' => 'fa-chart-line', 'border' => 'warning', 'color' => 'warning'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-{{ $card['border'] }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $card['color'] }} text-uppercase mb-1">
                                {{ $card['title'] }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $card['value'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas {{ $card['icon'] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Première ligne de graphiques -->
    <div class="row mb-4">
        <!-- Distribution des Badges -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Distribution des Badges</h6>
                </div>
                <div class="card-body text-center">
                    <canvas id="badgesChart" style="height:250px;"></canvas>
                    <div class="mt-3 small">
                        <span class="me-3"><i class="fas fa-circle text-warning me-1"></i> Or</span>
                        <span class="me-3"><i class="fas fa-circle text-secondary me-1"></i> Argent</span>
                        <span class="me-3"><i class="fas fa-circle text-danger me-1"></i> Bronze</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance par Challenge -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Performance par Challenge</h6>
                </div>
                <div class="card-body">
                    <canvas id="performanceChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistiques des Badges -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Statistiques des Badges</h6>
                </div>
                <div class="card-body text-center">
                    @foreach(['Or'=>'warning','Argent'=>'secondary','Bronze'=>'danger'] as $badge => $color)
                    <div class="mb-3 p-3 border rounded bg-light">
                        <i class="fas 
                            @if($badge=='Or') fa-crown
                            @elseif($badge=='Argent') fa-medal
                            @else fa-award @endif fa-2x text-{{ $color }} mb-2"></i>
                        <h4 class="font-weight-bold text-{{ $color }}">{{ $stats['badges_count'][$badge] ?? 0 }}</h4>
                        <small class="text-muted">Badges {{ $badge }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième ligne de graphiques -->
    <div class="row mb-4">
        <!-- Top Challenges par Participants -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">Top Challenges par Participants</h6>
                </div>
                <div class="card-body">
                    <canvas id="participantsChart" style="height:300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Distribution des Points -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-success text-white">
                    <h6 class="m-0 font-weight-bold">Distribution des Points</h6>
                </div>
                <div class="card-body">
                    <canvas id="pointsDistributionChart" style="height:300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Troisième ligne de graphiques -->
    <div class="row mb-4">
        <!-- Évolution des Participations -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Évolution des Participations</h6>
                </div>
                <div class="card-body">
                    <canvas id="evolutionChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Score Moyen par Badge -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Score Moyen par Badge</h6>
                </div>
                <div class="card-body">
                    <canvas id="avgScoreChart" style="height:250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // CORRECTION : Définir les couleurs en JavaScript
    const colors = {
    primary: '#006D2C',     // Vert principal
    secondary: '#F5F0E6',   // Beige clair
    light: '#F8F9FA',       // Gris très clair
    dark: '#212121',        // Gris foncé
    success: '#28A745',     // Vert succès
    info: '#1E88E5',        // Bleu clair
    warning: '#F9A825',     // Jaune doré
    danger: '#E53935'       // Rouge vif
};

    // Préparer les données pour les graphiques
    @php
        // Données pour Performance par Challenge
        $performanceData = [];
        $performanceLabels = [];
        $performanceColors = [];
        
        foreach($challenges->take(8) as $challenge) {
            $avgPoints = 0;
            $totalParticipants = $challenge->participants->count();
            
            if ($totalParticipants > 0) {
                $totalPoints = 0;
                foreach($challenge->participants as $participant) {
                    $totalPoints += $participant->score->points ?? 0;
                }
                $avgPoints = $totalPoints / $totalParticipants;
            }
            
            $performanceData[] = round($avgPoints, 1);
            $performanceLabels[] = Str::limit($challenge->titre, 20);
            // On laisse JavaScript gérer les couleurs
        }

        // Données pour Top Challenges par Participants
        $participantsData = [];
        $participantsLabels = [];
        
        $popularChallenges = $challenges->sortByDesc(fn($c) => $c->participants->count())->take(6);
        
        foreach($popularChallenges as $challenge) {
            $participantsData[] = $challenge->participants->count();
            $participantsLabels[] = Str::limit($challenge->titre, 25);
        }
    @endphp

    // 1. Pie Chart - Distribution des Badges
    const badgesChart = new Chart(document.getElementById('badgesChart'), {
        type: 'doughnut',
        data: {
            labels: ['Or', 'Argent', 'Bronze'],
            datasets: [{
                data: [
                    {{ $stats['badges_count']['Or'] ?? 0 }},
                    {{ $stats['badges_count']['Argent'] ?? 0 }},
                    {{ $stats['badges_count']['Bronze'] ?? 0 }}
                ],
                backgroundColor: [
                    colors.warning,  // Or
                    colors.secondary, // Argent
                    colors.danger    // Bronze
                ],
                borderColor: colors.primary,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // 2. Bar Chart - Performance par Challenge
    const performanceChart = new Chart(document.getElementById('performanceChart'), {
        type: 'bar',
        data: {
            labels: @json($performanceLabels),
            datasets: [{
                label: 'Points Moyens',
                data: @json($performanceData),
                backgroundColor: @json($performanceData).map(points => 
                    points >= 80 ? colors.success : 
                    points >= 50 ? colors.warning : colors.danger
                ),
                borderColor: colors.primary,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Points Moyens'
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45
                    }
                }
            }
        }
    });

    // 3. Horizontal Bar Chart - Top Challenges par Participants
    const participantsChart = new Chart(document.getElementById('participantsChart'), {
        type: 'bar',
        data: {
            labels: @json($participantsLabels),
            datasets: [{
                label: 'Nombre de Participants',
                data: @json($participantsData),
                backgroundColor: colors.primary,
                borderColor: colors.dark,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de Participants'
                    }
                }
            }
        }
    });

    // 4. Line Chart - Évolution des Participations (données simulées)
    const evolutionChart = new Chart(document.getElementById('evolutionChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'],
            datasets: [{
                label: 'Participations',
                data: [65, 59, 80, 81, 56, 55, 40, 45, 60, 75, 80, 90],
                borderColor: colors.primary,
                backgroundColor: colors.primary + '20',
                tension: 0.4,
                fill: true
            }, {
                label: 'Points Moyens',
                data: [45, 52, 65, 70, 62, 58, 50, 55, 68, 72, 75, 82],
                borderColor: colors.success,
                backgroundColor: colors.success + '20',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // 5. Radar Chart - Score Moyen par Badge (données simulées)
    const avgScoreChart = new Chart(document.getElementById('avgScoreChart'), {
        type: 'radar',
        data: {
            labels: ['Or', 'Argent', 'Bronze'],
            datasets: [{
                label: 'Score Moyen',
                data: [85, 65, 45],
                backgroundColor: colors.primary + '40',
                borderColor: colors.primary,
                pointBackgroundColor: colors.primary,
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: colors.primary
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });

    // 6. Histogram - Distribution des Points (données simulées)
    const pointsDistributionChart = new Chart(document.getElementById('pointsDistributionChart'), {
        type: 'bar',
        data: {
            labels: ['0-20', '21-40', '41-60', '61-80', '81-100'],
            datasets: [{
                label: 'Nombre de Participants',
                data: [12, 19, 28, 24, 17],
                backgroundColor: [
                    colors.danger,
                    colors.warning,
                    colors.info,
                    colors.success,
                    colors.primary
                ],
                borderColor: colors.dark,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de Participants'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Plage de Points'
                    }
                }
            }
        }
    });

    // Fonctions d'export
    function exportToExcel() {
        alert('Fonction d\'export Excel à implémenter');
    }

    function generatePDF() {
        alert('Fonction d\'export PDF à implémenter');
    }
});
</script>
@endpush

@push('styles')
<style>
.card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
}

.card-header {
    border-radius: 10px 10px 0 0 !important;
}

.bg-primary {
    background-color: #006D2C !important;
}

.text-primary {
    color: #006D2C !important;
}

.border-left-primary {
    border-left: 4px solid #006D2C !important;
}

.bg-success {
    background-color: #28A745 !important;
}

.bg-info {
    background-color: #1E88E5 !important;
}

.bg-warning {
    background-color: #F9A825 !important;
}

.border-left-success {
    border-left: 4px solid #28A745 !important;
}

.border-left-info {
    border-left: 4px solid #1E88E5 !important;
}

.border-left-warning {
    border-left: 4px solid #F9A825 !important;
}

.chart-container {
    position: relative;
    height: 300px;
    width: 100%;
}
</style>
@endpush