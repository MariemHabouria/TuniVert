@extends('layouts.app')

@section('title', $forum->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">{{ $forum->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('forums.index') }}" class="text-white">Forums</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Sujet</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Forum Content Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <!-- Contenu Principal -->
            <div class="col-lg-8">
                <!-- Forum Principal -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                         style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%);
                                border-radius:15px 15px 0 0;">
                        <h3 class="mb-0 text-white">{{ $forum->titre }}</h3>
                        <div class="metric-badges">
                            <span class="badge bg-light text-dark">{{ $forum->nb_vues }} 👁️</span>
                            <span class="badge bg-light text-dark">{{ $forum->nb_reponses }} 💬</span>
                            @if($forum->popularite_score > 100)
                                <span class="badge bg-warning">🔥 Populaire</span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px; font-size: 1.2em;">
                                    {{ substr($forum->utilisateur->name, 0, 1) }}
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">{{ $forum->utilisateur->name }}</h6>
                                <small class="text-muted">{{ $forum->created_at->diffForHumans() }}</small>
                            </div>
                        </div>

                        <p class="fs-5" style="line-height:1.7;">{{ $forum->contenu }}</p>

                        @if($forum->tags)
                            <div class="mt-3">
                                @foreach(explode(',', $forum->tags) as $tag)
                                    <span class="badge bg-light text-dark me-1">#{{ trim($tag) }}</span>
                                @endforeach
                            </div>
                        @endif

                        <!-- Actions Auteur -->
                        @auth
                            @if($forum->utilisateur_id === Auth::id())
                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                    <small class="text-muted">
                                        👤 Vous êtes l'auteur de ce sujet
                                    </small>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('forums.edit', $forum->id) }}"
                                           class="btn btn-warning btn-sm">✏️ Modifier</a>

                                        <form action="{{ route('forums.destroy', $forum->id) }}" method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce sujet ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">🗑️ Supprimer</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>

                <!-- Réponses -->
                @if(class_exists(\App\Models\ReponseForum::class) && isset($forum->reponses))
                    <div class="card border-0 shadow-sm" style="border-radius:15px;">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">💬 Réponses ({{ $forum->reponses->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if($forum->reponses->count() > 0)
                                @foreach($forum->reponses as $reponse)
                                    <div class="border-bottom pb-3 mb-3 {{ $reponse->est_resolution ? 'border-success bg-light-success' : '' }}">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; font-size: 1em;">
                                                    {{ substr($reponse->utilisateur->name, 0, 1) }}
                                                </div>
                                                @if($reponse->est_resolution)
                                                    <div class="text-center mt-1">
                                                        <span class="badge bg-success">✅ Solution</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h6 class="mb-1">{{ $reponse->utilisateur->name }}</h6>
                                                    <small class="text-muted">{{ $reponse->created_at->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-2">{{ $reponse->contenu }}</p>

                                                <!-- Marquer comme solution -->
                                                @auth
                                                    @if(Auth::id() == $forum->utilisateur_id && !$reponse->est_resolution)
                                                        <form action="{{ route('forums.reponses.solution', ['forum' => $forum->id, 'reponse' => $reponse->id]) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">✅ Marquer comme solution</button>
                                                        </form>
                                                    @endif
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-3">Aucune réponse pour le moment. Soyez le premier à répondre !</p>
                            @endif

                            <!-- Formulaire de réponse avec IA -->
                            @auth
                                <div class="mt-4 pt-3 border-top">
                                    <h6>✍️ Ajouter une réponse</h6>
                                    <form action="{{ route('forums.reponses.store', $forum->id) }}" method="POST">
                                        @csrf
                                        <textarea id="reponse" name="contenu" placeholder="Écrivez votre réponse..." rows="5" class="form-control"></textarea>

                                        <button type="button" id="ia-suggestion" class="btn btn-info mt-2">💡 Suggestion IA</button>
                                        <button type="submit" class="btn btn-primary">Publier la réponse</button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-info mt-3">
                                    <a href="{{ route('login') }}" class="alert-link">Connectez-vous</a> pour participer à la discussion.
                                </div>
                            @endauth
                        </div>
                    </div>
                @else
                    <!-- Système de réponses non disponible -->
                    <div class="card border-0 shadow-sm mt-4" style="border-radius:15px;">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">💬 Système de réponses</h5>
                        </div>
                        <div class="card-body text-center py-4">
                            <p class="text-muted">Le système de réponses sera bientôt disponible.</p>
                        </div>
                    </div>
                @endif

                <!-- Script Suggestion IA -->
                <script>
                document.getElementById('ia-suggestion').addEventListener('click', function() {
                    let texte = document.getElementById('reponse').value;
                    let forumId = {{ $forum->id }};

                    fetch(`/forums/${forumId}/reponse-suggestion`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ texte_courant: texte })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.suggestion) {
                            document.getElementById('reponse').value = data.suggestion;
                        } else {
                            alert('Aucune suggestion disponible.');
                        }
                    });
                });
                </script>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Statistiques -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">📊 Statistiques du sujet</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-6">
                                <div class="metric-value">{{ $forum->nb_vues }}</div>
                                <small class="metric-label">Vues</small>
                            </div>
                            <div class="col-6">
                                <div class="metric-value text-success">{{ $forum->nb_reponses }}</div>
                                <small class="metric-label">Réponses</small>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="metric-value text-warning">{{ $forum->popularite_score }}</div>
                            <small class="metric-label">Score de popularité</small>
                        </div>
                    </div>
                </div>

                <!-- Auteur -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">👤 Auteur</h6>
                    </div>
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-2" 
                             style="width: 60px; height: 60px; font-size: 1.5em;">
                            {{ substr($forum->utilisateur->name, 0, 1) }}
                        </div>
                        <h6>{{ $forum->utilisateur->name }}</h6>
                        <small class="text-muted">Membre depuis {{ $forum->utilisateur->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-body text-center">
                        <a href="{{ route('forums.index') }}" class="btn btn-outline-secondary w-100 mb-2">⬅️ Retour aux forums</a>
                        <a href="{{ route('forums.create') }}" class="btn btn-success w-100">➕ Nouveau sujet</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Forum Content End -->
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

.metric-value {
    font-weight: bold;
    font-size: 1.5em;
    line-height: 1;
}
.metric-label {
    font-size: 0.8em;
    color: #6c757d;
    line-height: 1;
}

.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.btn-warning, .btn-danger, .btn-secondary {
    border-radius: 8px;
}

.btn-warning:hover, .btn-danger:hover, .btn-secondary:hover {
    opacity: 0.9;
    transform: translateY(-1px);
    transition: 0.2s ease-in-out;
}
</style>
@endsection
