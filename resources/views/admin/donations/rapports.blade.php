@extends('layouts.admin')
@section('title', 'Rapports des Donations')

@section('content')
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
                <canvas id="donationsChart" height="120"></canvas>
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
                <canvas id="paymentMethodsChart" height="200"></canvas>
                
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
                                <canvas id="campaignsChart" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Top Donateurs</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Donateur</th>
                                                <th>Montant Total</th>
                                                <th>Dernier don</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Pierre Martin</td>
                                                <td class="text-success fw-bold">€1,250</td>
                                                <td>25/09/2024</td>
                                            </tr>
                                            <tr>
                                                <td>Sophie Lambert</td>
                                                <td class="text-success fw-bold">€850</td>
                                                <td>24/09/2024</td>
                                            </tr>
                                            <tr>
                                                <td>Marc Dubois</td>
                                                <td class="text-success fw-bold">€600</td>
                                                <td>23/09/2024</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Méthodes de Paiement</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Méthode</th>
                                                <th>Nombre</th>
                                                <th>Montant Total</th>
                                                <th>Pourcentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Carte Bancaire</td>
                                                <td>89</td>
                                                <td>€7,845</td>
                                                <td>63%</td>
                                            </tr>
                                            <tr>
                                                <td>PayPal</td>
                                                <td>45</td>
                                                <td>€3,120</td>
                                                <td>25%</td>
                                            </tr>
                                            <tr>
                                                <td>Virement</td>
                                                <td>22</td>
                                                <td>€1,493</td>
                                                <td>12%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">Rapport Détaillé</h5>
                                    <button class="btn btn-primary">
                                        <i class="mdi mdi-file-pdf"></i> Exporter PDF
                                    </button>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Période</th>
                                                <th>Donations</th>
                                                <th>Montant Total</th>
                                                <th>Donateurs</th>
                                                <th>Moyenne/Don</th>
                                                <th>Taux de Croissance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>Septembre 2024</td>
                                                <td>45</td>
                                                <td class="text-success fw-bold">€3,845</td>
                                                <td>32</td>
                                                <td>€85.44</td>
                                                <td class="text-success">+15%</td>
                                            </tr>
                                            <tr>
                                                <td>Août 2024</td>
                                                <td>39</td>
                                                <td class="text-success fw-bold">€3,342</td>
                                                <td>28</td>
                                                <td>€85.69</td>
                                                <td class="text-success">+8%</td>
                                            </tr>
                                            <tr>
                                                <td>Juillet 2024</td>
                                                <td>36</td>
                                                <td class="text-success fw-bold">€3,095</td>
                                                <td>25</td>
                                                <td>€85.97</td>
                                                <td class="text-success">+12%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
// Scripts pour les graphiques (exemple)
document.addEventListener('DOMContentLoaded', function() {
    // Graphique d'évolution des donations
    const donationsCtx = document.getElementById('donationsChart').getContext('2d');
    const donationsChart = new Chart(donationsCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep'],
            datasets: [{
                label: 'Donations (€)',
                data: [1200, 1900, 1500, 2200, 1800, 2500, 3000, 2800, 3200],
                borderColor: '#4CAF50',
                backgroundColor: 'rgba(76, 175, 80, 0.1)',
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Graphique de répartition
    const campaignsCtx = document.getElementById('campaignsChart').getContext('2d');
    const campaignsChart = new Chart(campaignsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aide Humanitaire', 'Éducation', 'Environnement', 'Autres'],
            datasets: [{
                data: [45, 30, 20, 5],
                backgroundColor: ['#4CAF50', '#2196F3', '#FF9800', '#9E9E9E']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endsection