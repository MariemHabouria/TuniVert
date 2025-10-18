@extends('layouts.app')

@section('title', 'Liste des Forums - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top:-1px; padding-top:6rem!important;
     background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                 url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">💬 Forums Communautaires</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Forums</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Forum List Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <!-- === WIDGET STATISTIQUES === -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                        <small>Total Sujets</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <h3 class="mb-0">{{ $stats['aujourdhui'] ?? 0 }}</h3>
                        <small>Aujourd'hui</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <h3 class="mb-0">{{ $stats['populaires'] ?? 0 }}</h3>
                        <small>Populaires</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger border-0 shadow-sm text-center">
                    <div class="card-body py-3">
                        <h3 class="mb-0">{{ $stats['alertes_urgentes'] ?? 0 }}</h3>
                        <small>Alertes Urgentes</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- === BARRE RECHERCHE ET TRI === -->
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="{{ route('forums.index') }}" method="GET" class="d-flex">
                    <div class="input-group">
                        <input type="text" name="recherche" class="form-control" 
                               placeholder="🔍 Rechercher dans les forums..." value="{{ request('recherche') }}">
                        <button type="submit" class="btn btn-outline-primary">Rechercher</button>
                        <a href="{{ route('recherche.avancee') }}" class="btn btn-outline-secondary">
                            Recherche Avancée
                        </a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    <a href="{{ route('forums.index', ['tri' => 'recent']) }}" 
                       class="btn btn-outline-primary {{ ($typeTri ?? 'recent') == 'recent' ? 'active' : '' }}">
                        ⏰ Récents
                    </a>
                    <a href="{{ route('forums.index', ['tri' => 'populaire']) }}" 
                       class="btn btn-outline-primary {{ ($typeTri ?? '') == 'populaire' ? 'active' : '' }}">
                        🔥 Populaires
                    </a>
                    <a href="{{ route('forums.index', ['tri' => 'actif']) }}" 
                       class="btn btn-outline-primary {{ ($typeTri ?? '') == 'actif' ? 'active' : '' }}">
                        💬 Actifs
                    </a>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success">💬 Discussions de la Communauté</h2>
            <a href="{{ route('forums.create') }}" class="btn btn-success px-4" 
               style="border-radius:10px; background:linear-gradient(135deg,var(--bs-primary) 0%, var(--bs-success) 100%); border:none;">
                <i class="fas fa-plus me-2"></i> Nouveau Sujet
            </a>
        </div>

        @foreach ($forums as $forum)
            <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-1 text-center">
                            <!-- Métriques -->
                            <div class="metric-box mb-2">
                                <div class="metric-value">{{ $forum->nb_vues }}</div>
                                <small class="metric-label">vues</small>
                            </div>
                            <div class="metric-box">
                                <div class="metric-value text-success">{{ $forum->nb_reponses }}</div>
                                <small class="metric-label">réponses</small>
                            </div>
                        </div>
                        
                        <div class="col-md-11">
                            <h4 class="fw-bold text-primary mb-2">
                                <a href="{{ route('forums.show', $forum->id) }}" class="text-decoration-none">
                                    {{ $forum->titre }}
                                </a>
                                @if($forum->popularite_score > 100)
                                    <span class="badge bg-warning ms-2">🔥 Populaire</span>
                                @endif
                            </h4>
                            
                            <p class="text-muted mb-3">{{ Str::limit($forum->contenu, 200) }}</p>
                            
                            <!-- Tags -->
                            @if($forum->tags)
                                <div class="mb-3">
                                    @foreach(explode(',', $forum->tags) as $tag)
                                        <span class="badge bg-light text-dark me-1">#{{ trim($tag) }}</span>
                                    @endforeach
                                </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-secondary">
                                    <i class="fas fa-user me-1"></i>
                                    Auteur : {{ $forum->utilisateur->name ?? 'Inconnu' }}
                                    • {{ $forum->created_at->diffForHumans() }}
                                </small>
                                
                                <div class="d-flex gap-2">
                                    <a href="{{ route('forums.show', $forum->id) }}" class="btn btn-outline-primary btn-sm">
                                        👀 Voir la discussion
                                    </a>

                                    @if ($forum->utilisateur_id === Auth::id())
                                        <a href="{{ route('forums.edit', $forum->id) }}" class="btn btn-outline-warning btn-sm">
                                            ✏️ Modifier
                                        </a>

                                        <form action="{{ route('forums.destroy', $forum->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Supprimer ce sujet ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-4">
            {{ $forums->links() }}
        </div>
    </div>
</div>
<!-- Forum List End -->
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
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.metric-box {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 8px 5px;
    min-width: 60px;
}
.metric-value {
    font-weight: bold;
    font-size: 1.2em;
    line-height: 1;
}
.metric-label {
    font-size: 0.7em;
    color: #6c757d;
    line-height: 1;
}

.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    color: #fff !important;
}
</style>
@endsection