@extends('layouts.app')

@section('title', $event['title'])

@section('content')
<div class="container-fluid py-5" style="margin-top: 90px;">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('events.browse') }}">Événements</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $event['title'] }}</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Event Image -->
            <div class="col-lg-6 mb-4">
                <div class="event-image-container">
                    <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}" 
                         class="img-fluid rounded shadow-lg w-100" 
                         style="height: 400px; object-fit: cover;">
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 start-0 m-3">
                        <span class="badge bg-success fs-6 px-3 py-2">
                            <i class="fa fa-check-circle me-1"></i>Actif
                        </span>
                    </div>
                    
                    <!-- Category Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <span class="badge bg-primary fs-6 px-3 py-2">{{ $event['category'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Event Details -->
            <div class="col-lg-6">
                <div class="event-details">
                    <!-- Title and Location -->
                    <h1 class="display-6 mb-3">{{ $event['title'] }}</h1>
                    <p class="text-muted mb-4">
                        <i class="fa fa-map-marker-alt me-2"></i>{{ $event['location'] }}
                        <span class="ms-3">
                            <i class="fa fa-user me-2"></i>{{ $event['organizer'] }}
                        </span>
                    </p>

                    <!-- Progress Section -->
                    <div class="progress-section bg-light p-4 rounded mb-4">
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <h4 class="text-primary">{{ number_format($event['collected_amount'], 0) }} DT</h4>
                                <small class="text-muted">Collecté</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-success">{{ $event['progress'] }}%</h4>
                                <small class="text-muted">Atteint</small>
                            </div>
                            <div class="col-4">
                                <h4 class="text-secondary">{{ number_format($event['goal_amount'], 0) }} DT</h4>
                                <small class="text-muted">Objectif</small>
                            </div>
                        </div>
                        
                        <div class="progress mb-3" style="height: 12px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $event['progress'] }}%" 
                                 aria-valuenow="{{ $event['progress'] }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <small class="text-muted">
                                <i class="fa fa-calendar me-1"></i>Début: {{ \Carbon\Carbon::parse($event['start_date'])->format('d/m/Y') }}
                            </small>
                            <small class="text-muted">
                                <i class="fa fa-calendar-check me-1"></i>Fin: {{ \Carbon\Carbon::parse($event['end_date'])->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>

                    <!-- Donation Button -->
                    <div class="text-center mb-4">
                        <a href="{{ route('donations.create', ['event_id' => $event['id']]) }}" 
                           class="btn btn-success btn-lg px-5 py-3">
                            <i class="fa fa-heart me-2"></i>Faire un don pour cette cause
                        </a>
                    </div>

                    <!-- Share Buttons -->
                    <div class="text-center">
                        <p class="mb-2"><strong>Partagez cette cause :</strong></p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-link"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h3 class="mb-4">À propos de cette cause</h3>
                    <p class="lead">{{ $event['description'] }}</p>
                    
                    <!-- Additional Info -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>Informations pratiques</h5>
                            <ul class="list-unstyled">
                                <li><i class="fa fa-check text-success me-2"></i>Donations sécurisées</li>
                                <li><i class="fa fa-check text-success me-2"></i>Reçu par email</li>
                                <li><i class="fa fa-check text-success me-2"></i>Suivi transparent</li>
                                <li><i class="fa fa-check text-success me-2"></i>Impact mesurable</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>Méthodes de paiement</h5>
                            <div class="payment-methods">
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">
                                    <i class="fa fa-credit-card me-1"></i>Carte bancaire
                                </span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">
                                    <i class="fab fa-paypal me-1"></i>PayPal
                                </span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">
                                    <i class="fab fa-stripe me-1"></i>Stripe
                                </span>
                                <span class="badge bg-light text-dark me-2 mb-2 p-2">
                                    <i class="fa fa-mobile-alt me-1"></i>Paymee
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="text-center bg-primary text-white p-5 rounded">
                    <h3 class="text-white mb-3">Prêt à faire la différence ?</h3>
                    <p class="mb-4">Votre contribution, quelle que soit sa taille, contribue à créer un impact positif durable.</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="{{ route('donations.create', ['event_id' => $event['id']]) }}" 
                           class="btn btn-light btn-lg">
                            <i class="fa fa-heart me-2"></i>Faire un don
                        </a>
                        <a href="{{ route('events.browse') }}" class="btn btn-outline-light btn-lg">
                            <i class="fa fa-search me-2"></i>Voir d'autres causes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.event-image-container {
    position: relative;
}

.progress-section {
    border: 1px solid #e9ecef;
}

.progress {
    border-radius: 6px;
}

.progress-bar {
    background: linear-gradient(45deg, #198754, #20c997);
    border-radius: 6px;
}

.btn-lg {
    font-size: 1.1rem;
    font-weight: 600;
}

.payment-methods .badge {
    border: 1px solid #dee2e6;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(25, 135, 84, 0.4);
}

.btn-group .btn {
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
}

.list-unstyled li {
    padding: 0.25rem 0;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
}
</style>
@endpush