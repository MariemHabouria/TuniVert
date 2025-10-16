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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-body p-4">
                        <h2 class="mb-3 text-primary">{{ $forum->titre }}</h2>
                        <p class="fs-5" style="line-height:1.7;">{{ $forum->contenu }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <small class="text-muted">
                                👤 Auteur :
                                <strong>{{ $forum->utilisateur->name ?? 'Anonyme' }}</strong>
                                • Publié le {{ $forum->created_at->format('d/m/Y H:i') }}
                            </small>

                            @auth
                                @if($forum->utilisateur_id === Auth::id())
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('forums.edit', $forum->id) }}"
                                           class="btn btn-warning btn-sm">
                                           ✏️ Modifier
                                        </a>

                                        <form action="{{ route('forums.destroy', $forum->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer ce sujet ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                🗑️ Supprimer
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('forums.index') }}" class="btn btn-secondary">
                                ⬅️ Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Zone commentaires (future amélioration) -->
                {{-- <div class="card border-0 shadow-sm mt-4" style="border-radius:15px;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">💬 Commentaires</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Pas encore de commentaires...</p>
                    </div>
                </div> --}}
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
