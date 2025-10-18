@extends('layouts.app')

@section('title', 'Recherche Avancée - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top:-1px; padding-top:6rem!important;
     background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                 url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">🔍 Recherche Avancée</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Recherche</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Recherche Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Formulaire Recherche -->
                <div class="card border-0 shadow-sm mb-5" style="border-radius:15px;">
                    <div class="card-header bg-primary text-white py-4" style="border-radius:15px 15px 0 0;">
                        <h4 class="mb-0 text-center">🔍 Rechercher dans les contenus</h4>
                    </div>
                    <div class="card-body p-5">
                        <form action="{{ route('recherche.avancee') }}" method="GET">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <input type="text" name="q" class="form-control form-control-lg" 
                                           placeholder="Que cherchez-vous ?" value="{{ $motCle }}">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Rechercher</button>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Type de contenu</label>
                                    <select name="type" class="form-select">
                                        <option value="tous" {{ $type == 'tous' ? 'selected' : '' }}>📝 Tous les contenus</option>
                                        <option value="forums" {{ $type == 'forums' ? 'selected' : '' }}>💬 Forums seulement</option>
                                        <option value="alertes" {{ $type == 'alertes' ? 'selected' : '' }}>🚨 Alertes seulement</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Niveau d'urgence</label>
                                    <select name="gravite" class="form-select">
                                        <option value="tous" {{ $gravite == 'tous' ? 'selected' : '' }}>🚨 Tous les niveaux</option>
                                        <option value="feu" {{ $gravite == 'feu' ? 'selected' : '' }}>🔥 Feu</option>
                                        <option value="haute" {{ $gravite == 'haute' ? 'selected' : '' }}>⚠️ Haute</option>
                                        <option value="moyenne" {{ $gravite == 'moyenne' ? 'selected' : '' }}>📢 Moyenne</option>
                                        <option value="basse" {{ $gravite == 'basse' ? 'selected' : '' }}>💬 Basse</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Période</label>
                                    <select name="date" class="form-select">
                                        <option value="tous" {{ $dateFiltre == 'tous' ? 'selected' : '' }}>📅 Toute période</option>
                                        <option value="24h" {{ $dateFiltre == '24h' ? 'selected' : '' }}>⏰ Dernières 24h</option>
                                        <option value="7j" {{ $dateFiltre == '7j' ? 'selected' : '' }}>📆 7 derniers jours</option>
                                        <option value="30j" {{ $dateFiltre == '30j' ? 'selected' : '' }}>🗓️ 30 derniers jours</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Résultats -->
                @if($motCle)
                    <h3 class="mb-4">Résultats pour "{{ $motCle }}"</h3>
                    
                    <!-- Forums -->
                    @if($resultatsForums->count() > 0)
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0">💬 Forums ({{ $resultatsForums->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @foreach($resultatsForums as $forum)
                                    <div class="border-bottom pb-3 mb-3">
                                        <h6>
                                            <a href="{{ route('forums.show', $forum->id) }}" class="text-decoration-none">
                                                {{ $forum->titre }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ Str::limit($forum->contenu, 200) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Par {{ $forum->utilisateur->name }} • 
                                                {{ $forum->created_at->diffForHumans() }}
                                            </small>
                                            <div>
                                                <span class="badge bg-light text-dark me-1">{{ $forum->nb_vues }} vues</span>
                                                <span class="badge bg-light text-dark">{{ $forum->nb_reponses }} réponses</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Alertes -->
                    @if($resultatsAlertes->count() > 0)
                        <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                            <div class="card-header bg-danger text-white">
                                <h5 class="mb-0">🚨 Alertes ({{ $resultatsAlertes->count() }})</h5>
                            </div>
                            <div class="card-body">
                                @foreach($resultatsAlertes as $alerte)
                                    <div class="border-bottom pb-3 mb-3">
                                        <h6>
                                            <span class="badge bg-{{ $alerte->gravite == 'feu' ? 'danger' : ($alerte->gravite == 'haute' ? 'warning' : 'info') }} me-2">
                                                {{ strtoupper($alerte->gravite) }}
                                            </span>
                                            <a href="{{ route('alertes.show', $alerte->id) }}" class="text-decoration-none">
                                                {{ $alerte->titre }}
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2">{{ $alerte->description }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                @if($alerte->localisation)
                                                    📍 {{ $alerte->localisation }} • 
                                                @endif
                                                Par {{ $alerte->user->name }} • 
                                                {{ $alerte->created_at->diffForHumans() }}
                                            </small>
                                            <span class="badge bg-light text-dark">
                                                Statut: {{ $alerte->statut }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($resultatsForums->count() == 0 && $resultatsAlertes->count() == 0)
                        <div class="text-center py-5">
                            <div class="display-1 text-muted mb-3">🔍</div>
                            <h4 class="text-muted">Aucun résultat trouvé</h4>
                            <p class="text-muted">Essayez avec d'autres termes de recherche</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <div class="display-1 text-primary mb-3">🔍</div>
                        <h4 class="text-primary">Commencez votre recherche</h4>
                        <p class="text-muted">Utilisez le formulaire ci-dessus pour trouver du contenu</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- Recherche End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.card:hover {
    transform: translateY(-3px);
    transition: 0.3s ease-in-out;
}
</style>
@endsection