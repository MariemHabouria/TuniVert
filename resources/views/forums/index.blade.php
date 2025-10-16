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
                <h1 class="display-4 text-white animated slideInDown mb-3">💬 Forums</h1>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-success">💬 Liste des Sujets</h2>
            <a href="{{ route('forums.create') }}" class="btn btn-success px-4" 
               style="border-radius:10px; background:linear-gradient(135deg,var(--bs-primary) 0%, var(--bs-success) 100%); border:none;">
                <i class="fas fa-plus me-2"></i> Nouveau Sujet
            </a>
        </div>

        @foreach ($forums as $forum)
            <div class="card border-0 shadow-sm mb-4" style="border-radius:15px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold text-primary">{{ $forum->titre }}</h4>
                    <p class="text-muted mb-2">{{ Str::limit($forum->contenu, 120, '...') }}</p>
                    <small class="text-secondary">
                        <i class="fas fa-user me-1"></i>
                        Auteur : {{ $forum->utilisateur->name ?? 'Inconnu' }}
                    </small>

                    <div class="mt-3">
                        <a href="{{ route('forums.show', $forum->id) }}" class="btn btn-outline-primary btn-sm me-2">
                            👀 Voir
                        </a>

                        @if ($forum->utilisateur_id === Auth::id())
                            <a href="{{ route('forums.edit', $forum->id) }}" class="btn btn-outline-warning btn-sm me-2">
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

.btn-outline-primary:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover {
    color: #fff !important;
}
</style>
@endsection
