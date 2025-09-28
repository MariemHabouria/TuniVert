@extends('layouts.app')

@section('title', 'Parcourir les Événements')

@section('content')
<div class="container-fluid py-5" style="margin-top: 90px;">
    <div class="container">
        <!-- Header Section -->
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <h1 class="display-5 text-uppercase mb-4">Événements & Causes</h1>
            <p class="text-primary mb-0">Découvrez les causes qui ont besoin de votre soutien</p>
        </div>

        <!-- Filters Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-light p-4 rounded">
                    <form method="GET" action="{{ route('events.browse') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Rechercher</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Titre ou description...">
                        </div>
                        <div class="col-md-3">
                            <label for="location" class="form-label">Localisation</label>
                            <select class="form-select" id="location" name="location">
                                <option value="">Toutes les villes</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                        {{ $loc }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="category" class="form-label">Catégorie</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Toutes les catégories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-search me-2"></i>Filtrer
                            </button>
                        </div>
                    </form>
                    
                    @if(request()->hasAny(['search', 'location', 'category']))
                        <div class="mt-3">
                            <a href="{{ route('events.browse') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fa fa-times me-1"></i>Effacer les filtres
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Results Count -->
        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-muted">{{ $events->count() }} événement(s) trouvé(s)</h5>
            </div>
        </div>

        <!-- Events Grid -->
        <div class="row">
            @forelse($events as $event)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="causes-item h-100">
                        <div class="causes-img position-relative">
                            <img class="img-fluid w-100" src="{{ $event['image'] }}" alt="{{ $event['title'] }}" style="height: 250px; object-fit: cover;">
                            <div class="causes-overlay">
                                <a class="btn btn-outline-primary" href="{{ route('events.show', $event['id']) }}">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                        <div class="causes-item-text bg-light p-4 h-100">
                            <!-- Category Badge -->
                            <span class="badge bg-primary mb-2">{{ $event['category'] }}</span>
                            
                            <!-- Location -->
                            <p class="text-muted mb-2">
                                <i class="fa fa-map-marker-alt me-2"></i>{{ $event['location'] }}
                            </p>

                            <!-- Title -->
                            <h5 class="mb-3">{{ $event['title'] }}</h5>
                            
                            <!-- Description -->
                            <p class="text-muted mb-3">{{ Str::limit($event['description'], 100) }}</p>
                            
                            <!-- Progress Section -->
                            <div class="causes-progress bg-white p-3 rounded mb-3">
                                <div class="d-flex mb-2">
                                    <p class="text-dark">Collecté: <strong>{{ number_format($event['collected_amount'], 0) }} DT</strong></p>
                                    <p class="text-dark ms-auto">Objectif: <strong>{{ number_format($event['goal_amount'], 0) }} DT</strong></p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ $event['progress'] }}%" 
                                         aria-valuenow="{{ $event['progress'] }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $event['progress'] }}%
                                    </div>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">{{ $event['progress'] }}% de l'objectif atteint</small>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a class="btn btn-primary" href="{{ route('events.show', $event['id']) }}">
                                    En savoir plus
                                </a>
                                <a class="btn btn-outline-success" href="{{ route('donations.create', ['event_id' => $event['id']]) }}">
                                    <i class="fa fa-heart me-1"></i>Faire un don
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fa fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">Aucun événement trouvé</h4>
                        <p class="text-muted">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('events.browse') }}" class="btn btn-primary">
                            Voir tous les événements
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        @if($events->count() > 0)
            <!-- Call to Action -->
            <div class="text-center mt-5">
                <div class="bg-primary text-white p-4 rounded">
                    <h4 class="text-white mb-3">Votre contribution fait la différence</h4>
                    <p class="mb-3">Chaque don, petit ou grand, contribue à créer un impact positif dans notre communauté.</p>
                    <a href="{{ route('donations.create') }}" class="btn btn-light btn-lg">
                        <i class="fa fa-heart me-2"></i>Faire un don maintenant
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.causes-item {
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 45px rgba(0, 0, 0, .08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.causes-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 45px rgba(0, 0, 0, .15);
}

.causes-img {
    position: relative;
    overflow: hidden;
    border-radius: 10px 10px 0 0;
}

.causes-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
}

.causes-item:hover .causes-overlay {
    opacity: 1;
}

.causes-item-text {
    flex: 1;
    display: flex;
    flex-direction: column;
    border-radius: 0 0 10px 10px;
}

.causes-progress {
    border: 1px solid #e9ecef;
}

.progress {
    height: 8px;
    border-radius: 4px;
}

.progress-bar {
    background: linear-gradient(45deg, #198754, #20c997);
    border-radius: 4px;
}

.badge {
    font-size: 0.8rem;
    padding: 0.5rem 1rem;
}

.btn-outline-primary:hover,
.btn-outline-success:hover {
    transform: translateY(-2px);
}
</style>
@endpush