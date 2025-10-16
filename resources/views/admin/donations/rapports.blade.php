@extends('layouts.admin')
@section('title', 'Rapports des Donations')

@section('content')
<style>
/* Constrain chart sizes and make them responsive */
.chart-container { position: relative; height: 340px; min-height: 280px; width: 100%; }
.chart-container.sm { height: 260px; }
.chart-container > canvas { width: 100% !important; height: 100% !important; }
</style>
<!-- Statistiques KPI -->
<div class="row">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-calendar-today display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Cette Année</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_annee'], 2, ',', ' ') }} TND</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-calendar-month display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Ce Mois</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_mois'], 2, ',', ' ') }} TND</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-calendar-week display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Cette Semaine</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_semaine'], 2, ',', ' ') }} TND</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-trending-up display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Croissance</h6>
                        <h4 class="mb-0">+12.5%</h4>
                        <small class="opacity-75">vs mois dernier</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Graphique des donations par mois -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-chart-line me-2"></i>Évolution des Donations
                    </h4>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary btn-sm active" onclick="toggleChart('monthly')">Mensuel</button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleChart('weekly')">Hebdomadaire</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="donationsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Répartition par méthodes de paiement -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-credit-card me-2"></i>Méthodes de Paiement
                </h4>
                <div class="chart-container sm">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
                
                <div class="mt-3">
                    @foreach($stats['moyens_stats'] as $moyen)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                @php
                                    $colors = [
                                        'carte' => 'info',
                                        'paypal' => 'warning',
                                        'virement_bancaire' => 'success',
                                        'paymee' => 'primary'
                                    ];
                                    $color = $colors[$moyen->moyen_paiement] ?? 'secondary';
                                @endphp
                                <div class="badge bg-{{ $color }} me-2" style="width: 12px; height: 12px;"></div>
                                <span class="small">{{ str_replace('_', ' ', ucfirst($moyen->moyen_paiement)) }}</span>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold small">{{ number_format($moyen->total, 0, ',', ' ') }} TND</div>
                                <div class="text-muted" style="font-size: 0.75rem;">{{ $moyen->count }} dons</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Donations par mois détaillé -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-table me-2"></i>Détail par Mois
                    </h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success btn-sm" onclick="exportData('excel')">
                            <i class="mdi mdi-file-excel me-1"></i>Excel
                        </button>
                        <button class="btn btn-outline-danger btn-sm" onclick="exportData('pdf')">
                            <i class="mdi mdi-file-pdf me-1"></i>PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Mois</th>
                                <th>Nombre de Donations</th>
                                <th>Total Collecté</th>
                                <th>Moyenne par Don</th>
                                <th>Croissance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousTotal = 0;
                                $moisFr = [
                                    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
                                    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
                                    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                                ];
                            @endphp
                            @foreach($stats['donations_par_mois'] as $index => $mois)
                                <tr>
                                    <td>
                                        <strong>{{ $moisFr[$mois->month] ?? $mois->month }} {{ $mois->year }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $mois->count }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">{{ number_format($mois->total, 2, ',', ' ') }} TND</strong>
                                    </td>
                                    <td>
                                        {{ $mois->count > 0 ? number_format($mois->total / $mois->count, 2, ',', ' ') : '0,00' }} TND
                                    </td>
                                    <td>
                                        @if($previousTotal > 0)
                                            @php
                                                $growth = (($mois->total - $previousTotal) / $previousTotal) * 100;
                                            @endphp
                                            <span class="badge bg-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                                {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                            </span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                                @php $previousTotal = $mois->total; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Configuration des graphiques
const ctx1 = document.getElementById('donationsChart').getContext('2d');
const donationsChart = new Chart(ctx1, {
    type: 'line',
    data: {
        labels: [
            @foreach($stats['donations_par_mois'] as $mois)
                '{{ $moisFr[$mois->month] ?? $mois->month }}',
            @endforeach
        ],
        datasets: [{
            label: 'Montant (TND)',
            data: [
                @foreach($stats['donations_par_mois'] as $mois)
                    {{ $mois->total }},
                @endforeach
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Nombre de donations',
            data: [
                @foreach($stats['donations_par_mois'] as $mois)
                    {{ $mois->count }},
                @endforeach
            ],
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { display: true },
            tooltip: { enabled: true }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                },
            }
        }
    }
});

// Graphique des méthodes de paiement
const ctx2 = document.getElementById('paymentMethodsChart').getContext('2d');
const paymentChart = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($stats['moyens_stats'] as $moyen)
                '{{ str_replace("_", " ", ucfirst($moyen->moyen_paiement)) }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($stats['moyens_stats'] as $moyen)
                    {{ $moyen->total }},
                @endforeach
            ],
            backgroundColor: [
                '#17a2b8', '#ffc107', '#28a745', '#007bff', '#6c757d'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

function toggleChart(type) {
    // Basculer entre vue mensuelle et hebdomadaire
    alert('Basculer vers: ' + type);
}

function exportData(format) {
    alert('Exporter en ' + format.toUpperCase());
}
</script>
@endsection