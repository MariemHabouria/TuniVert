@extends('layouts.app')

@section('title', $alerte->titre)

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-3.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">🚨 {{ $alerte->titre }}</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alertes.index') }}" class="text-white">Alertes</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Détails</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Show Alerte -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7">
            <div class="card shadow-lg border-0" style="border-radius:15px;">
                <div class="card-header text-white"
                     style="background: linear-gradient(135deg, var(--bs-danger), var(--bs-dark)); border-radius:15px 15px 0 0;">
                    <h3 class="mb-0"><i class="fas fa-bell me-2"></i>{{ $alerte->titre }}</h3>
                </div>
                <div class="card-body p-4">
                    <p class="fs-5">{{ $alerte->description }}</p>

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

                    <p class="text-muted">
                        <em>Publié par :</em> {{ $alerte->user->name ?? 'Inconnu' }}
                    </p>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <a href="{{ route('alertes.index') }}" class="btn btn-secondary">
                        ⬅️ Retour à la liste
                    </a>

                    @auth
                        @if ($alerte->utilisateur_id === Auth::id())
                            <div>
                                <a href="{{ route('alertes.edit', $alerte->id) }}" class="btn btn-warning">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('alertes.destroy', $alerte->id) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Voulez-vous supprimer cette alerte ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">🗑️ Supprimer</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
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
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
</style>
@endsection
