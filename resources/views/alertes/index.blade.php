@extends('layouts.app')

@section('title', '🚨 Liste des Alertes')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">🚨 Liste des Alertes</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Alertes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Liste Alertes -->
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-danger">🚨 Alertes Publiées</h2>
        <a href="{{ route('alertes.create') }}" class="btn btn-success btn-lg">
            ➕ Nouvelle Alerte
        </a>
    </div>

    @foreach ($alertes as $alerte)
        <div class="card shadow-sm mb-4 border-0" style="border-radius:10px;">
            <div class="card-body">
                <h4 class="card-title fw-bold text-primary">{{ $alerte->titre }}</h4>
                <p class="card-text">{{ $alerte->description }}</p>
                <p class="mb-2">
                    <strong>Gravité :</strong>
                    @switch($alerte->gravite)
                        @case('basse') <span class="badge bg-success">Basse</span> @break
                        @case('moyenne') <span class="badge bg-warning text-dark">Moyenne</span> @break
                        @case('haute') <span class="badge bg-danger">Haute</span> @break
                        @case('feu') <span class="badge bg-dark">🔥 Feu</span> @break
                        @default <span class="badge bg-secondary">N/A</span>
                    @endswitch
                </p>
                <small class="text-muted">
                    <em>Auteur :</em> {{ $alerte->user->name ?? 'Inconnu' }}
                </small>

                <div class="mt-3">
                    <a href="{{ route('alertes.show', $alerte->id) }}" class="btn btn-outline-primary btn-sm">
                        👀 Voir
                    </a>

                    @auth
                        @if ($alerte->utilisateur_id === Auth::id())
                            <a href="{{ route('alertes.edit', $alerte->id) }}" class="btn btn-outline-warning btn-sm">
                                ✏️ Modifier
                            </a>
                            <form action="{{ route('alertes.destroy', $alerte->id) }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">🗑️ Supprimer</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-4">
        {{ $alertes->links() }}
    </div>
</div>
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: 0.3s ease-in-out;
}
</style>
@endsection
