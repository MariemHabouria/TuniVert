@extends('layouts.app')

@section('title', 'Dashboard Association - Donations')

@section('content')
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb">
    <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">Dashboard Association</h3>
        <p class="fs-5 text-white mb-4">Tableau de bord pour la gestion des donations et analytics</p>
        <ol class="breadcrumb justify-content-center mb-0">
            <li class="breadcrumb-item"><a href="{{ route('donation') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="#">Donations</a></li>
            <li class="breadcrumb-item active text-white">Dashboard Association</li>
        </ol>    
    </div>
</div>
<!-- Header End -->

<!-- Dashboard Content Start -->
<div class="container-fluid py-5">
    <div class="container py-5">

        <!-- Dashboard Control Panel -->
        <div class="text-center mx-auto pb-5" style="max-width: 800px;">
            <h5 class="text-uppercase text-primary">Analytics & Management</h5>
            <h1 class="mb-4">Tableau de Bord Association</h1>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8">
                    <p class="text-muted mb-3">
                        <i class="fas fa-user-tie me-2"></i>Connecté en tant qu'association
                        <span class="mx-2">•</span>
                        <i class="fas fa-clock me-2"></i>{{ now()->format('d/m/Y à H:i') }}
                    </p>
                </div>
                <div class="col-md-4">
                    <!-- Period Filter -->
                    <div class="btn-group" role="group">
                        <a href="?period=7" class="btn btn-outline-primary btn-sm {{ $period == '7' ? 'active' : '' }}">7j</a>
                        <a href="?period=30" class="btn btn-outline-primary btn-sm {{ $period == '30' ? 'active' : '' }}">30j</a>
                        <a href="?period=90" class="btn btn-outline-primary btn-sm {{ $period == '90' ? 'active' : '' }}">90j</a>
                        <a href="?period=365" class="btn btn-outline-primary btn-sm {{ $period == '365' ? 'active' : '' }}">1an</a>
                    </div>
                </div>
            </div>
        </div>

    <!-- Alerts -->
    @if(count($alerts) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($alerts as $alert)
            <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                <i class="{{ $alert['icon'] }} me-2"></i>{{ $alert['message'] }}
                @if(isset($alert['action']))
                    <a href="{{ $alert['action'] }}" class="alert-link ms-2">Voir</a>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- KPI Cards -->
    <div class="row mb-4">
        <!-- Total Collected -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Total Collecté</h6>
                            <h3 class="mb-0">{{ number_format($totalCollected, 2) }} TND</h3>
                            @if($evolution != 0)
                                <small class="text-light">
                                    <i class="fas fa-{{ $evolution > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                    {{ abs(round($evolution, 1)) }}% vs période précédente
                                </small>
                            @endif
                        </div>
                        <i class="fas fa-coins fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Event Performance -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Meilleur Événement</h6>
                            @if($topEvents->count() > 0)
                                <h6 class="mb-0">{{ Str::limit($topEvents->first()->event_name, 20) }}</h6>
                                <h4 class="mb-0">{{ number_format($topEvents->first()->total, 2) }} TND</h4>
                            @else
                                <p class="mb-0">Aucun événement</p>
                            @endif
                        </div>
                        <i class="fas fa-trophy fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Activité Récente</h6>
                            <h3 class="mb-0">{{ $recentDonations->count() }}</h3>
                            <small class="text-light">Dernières donations</small>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-1">Moyens de Paiement</h6>
                            <h3 class="mb-0">{{ $paymentMethods->count() }}</h3>
                            <small class="text-light">Méthodes actives</small>
                        </div>
                        <i class="fas fa-credit-card fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables -->
    <div class="row">
        <!-- Top Events -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Top 5 Événements</h5>
                </div>
                <div class="card-body">
                    @if($topEvents->count() > 0)
                        @foreach($topEvents as $event)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div>
                                <h6 class="mb-0">{{ $event->event_name }}</h6>
                                <small class="text-muted">{{ $event->count }} donation(s)</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($event->total, 2) }} TND</strong>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">Aucun événement pour cette période</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-heart me-2"></i>Dernières Donations</h5>
                </div>
                <div class="card-body">
                    @if($recentDonations->count() > 0)
                        <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Donateur</th>
                                        <th>Montant</th>
                                        <th>Méthode</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentDonations as $donation)
                                    <tr>
                                        <td>
                                            <small>{{ $donation->donor_name }}</small><br>
                                            <small class="text-muted">{{ Str::limit($donation->event_name, 15) }}</small>
                                        </td>
                                        <td><strong>{{ number_format($donation->montant, 2) }} TND</strong></td>
                                        <td>
                                            @switch($donation->moyen_paiement)
                                                @case('stripe')
                                                    <span class="badge bg-primary">Stripe</span>
                                                    @break
                                                @case('paypal')
                                                    <span class="badge bg-info">PayPal</span>
                                                    @break
                                                @case('e_dinar')
                                                    <span class="badge bg-success">e-DINAR</span>
                                                    @break
                                                @case('virement_bancaire')
                                                    <span class="badge bg-warning">Virement</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($donation->moyen_paiement) }}</span>
                                            @endswitch
                                        </td>
                                        <td><small>{{ \Carbon\Carbon::parse($donation->date_don)->format('d/m H:i') }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center mb-0">Aucune donation récente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Event Progress & Payment Analytics -->
    <div class="row">
        <!-- Event Objectives Progress -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-bullseye me-2"></i>Progression sur Objectifs</h5>
                </div>
                <div class="card-body">
                    @if($eventProgress->count() > 0)
                        @foreach($eventProgress as $progress)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="mb-0">{{ Str::limit($progress->title, 25) }}</h6>
                                <small class="text-muted">
                                    {{ number_format($progress->raised, 0) }} / {{ number_format($progress->target_amount, 0) }} TND
                                </small>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar {{ $progress->progress_percentage >= 100 ? 'bg-success' : ($progress->progress_percentage >= 75 ? 'bg-info' : 'bg-primary') }}"
                                     style="width: {{ min($progress->progress_percentage, 100) }}%"></div>
                            </div>
                            <small class="text-muted">{{ number_format($progress->progress_percentage, 1) }}% atteint</small>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Aucune statistique d'événement disponible
                        </p>
                        <small class="text-muted d-block text-center">
                            Exécutez <code>php artisan donations:update-stats</code> pour générer les statistiques
                        </small>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Methods Analytics -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fas fa-credit-card me-2"></i>Analyses par Méthode</h5>
                </div>
                <div class="card-body">
                    @if($paymentMethods->count() > 0)
                        @foreach($paymentMethods as $method)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                            <div>
                                <h6 class="mb-0">
                                    @switch($method->moyen_paiement)
                                        @case('stripe')
                                            <i class="fab fa-stripe-s text-primary"></i> Stripe
                                            @break
                                        @case('paypal')
                                            <i class="fab fa-paypal text-info"></i> PayPal
                                            @break
                                        @case('e_dinar')
                                            <i class="fas fa-mobile-alt text-success"></i> e-DINAR
                                            @break
                                        @case('virement_bancaire')
                                            <i class="fas fa-university text-warning"></i> Virement bancaire
                                            @break
                                        @default
                                            {{ ucfirst($method->moyen_paiement) }}
                                    @endswitch
                                </h6>
                                <small class="text-muted">{{ $method->count }} transaction(s) • Moy: {{ number_format($method->average, 2) }} TND</small>
                            </div>
                            <div class="text-end">
                                <strong>{{ number_format($method->total, 2) }} TND</strong>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center mb-0">Aucune transaction pour cette période</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Dashboard Content End -->
@endsection

@push('scripts')
<script>
// Auto-refresh every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);

// Enhanced tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Dashboard enhancement - following donation page pattern
    // No special navbar adjustments needed as we follow the standard layout
});
</script>
@endpush

@push('styles')
<style>
.card {
    transition: transform 0.2s;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Dashboard Styling - Following donation page pattern */
.btn-group .btn.active {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.btn-group .btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .btn-group .btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
}

.progress {
    border-radius: 10px;
    background-color: #f8f9fa;
}

.progress-bar {
    border-radius: 10px;
    transition: width 0.3s ease;
}

.alert {
    border-left: 4px solid;
    border-radius: 0;
}

.alert-info {
    border-left-color: #17a2b8;
}

.alert-warning {
    border-left-color: #ffc107;
}

.btn-group .btn.active {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.table-responsive::-webkit-scrollbar {
    width: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush