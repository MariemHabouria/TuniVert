@extends('layouts.app')

@section('title', 'Modifier le Forum - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top:-1px; padding-top:6rem!important;
     background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                 url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">✏️ Modifier le Sujet</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('forums.index') }}" class="text-white">Forums</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Modifier</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Edit Forum Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header py-3" style="background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border-radius:15px 15px 0 0;">
                        <h3 class="text-white mb-0 text-center">
                            <i class="fas fa-edit me-2"></i>Modifier le Sujet
                        </h3>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('forums.update', $forum->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Titre -->
                            <div class="mb-4">
                                <label for="titre" class="form-label fw-semibold">
                                    <i class="fas fa-heading me-2 text-primary"></i>Titre du sujet
                                </label>
                                <input type="text" name="titre" id="titre" class="form-control form-control-lg"
                                       value="{{ old('titre', $forum->titre) }}" required>
                                @error('titre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contenu -->
                            <div class="mb-4">
                                <label for="contenu" class="form-label fw-semibold">
                                    <i class="fas fa-file-alt me-2 text-primary"></i>Contenu
                                </label>
                                <textarea name="contenu" id="contenu" class="form-control"
                                          rows="6" required>{{ old('contenu', $forum->contenu) }}</textarea>
                                @error('contenu')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('forums.index') }}" class="btn btn-secondary btn-lg px-4"
                                   style="border-radius:10px;">
                                    ⬅️ Retour
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5"
                                        style="border-radius:10px; background: linear-gradient(135deg, var(--bs-primary) 0%, var(--bs-success) 100%); border:none;">
                                    💾 Sauvegarder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Forum End -->
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

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 76, 33, 0.4);
}
</style>
@endsection
