@extends('layouts.app')

@section('title', 'Créer un Forum - Tunivert')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5" style="margin-top:-1px; padding-top:6rem!important;
     background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                 url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">Créer un Nouveau Sujet</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('forums.index') }}" class="text-white">Forums</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Nouveau Sujet</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Create Forum Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-8 col-xl-8">
                <div class="card border-0 shadow-sm" style="border-radius:15px; background:var(--bs-light);">
                    <div class="card-header py-4"
                         style="background: linear-gradient(135deg,var(--bs-primary) 0%, var(--bs-success) 100%);
                                border-radius:15px 15px 0 0;">
                        <h3 class="mb-0 text-center text-white">
                            <i class="fas fa-comments me-2"></i>Nouveau Sujet de Forum
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('forums.store') }}" class="needs-validation" novalidate>
                            @csrf

                            <!-- Titre -->
                            <div class="mb-4">
                                <label for="titre" class="form-label fw-semibold" style="color:var(--bs-dark);">
                                    <i class="fas fa-heading me-2" style="color:var(--bs-primary);"></i>
                                    Titre du Sujet *
                                </label>
                                <input type="text" name="titre" id="titre"
                                       class="form-control form-control-lg"
                                       style="border:2px solid var(--bs-gray-300); border-radius:10px; padding:15px;"
                                       placeholder="Ex: Idées pour améliorer le recyclage"
                                       value="{{ old('titre') }}" required>
                                @error('titre')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contenu -->
                            <div class="mb-4">
                                <label for="contenu" class="form-label fw-semibold" style="color:var(--bs-dark);">
                                    <i class="fas fa-file-alt me-2" style="color:var(--bs-primary);"></i>
                                    Contenu du Sujet *
                                </label>
                                <textarea name="contenu" id="contenu" rows="6"
                                          class="form-control"
                                          style="border:2px solid var(--bs-gray-300); border-radius:10px; padding:15px;"
                                          placeholder="Exprimez vos idées, partagez vos expériences..."
                                          required>{{ old('contenu') }}</textarea>
                                @error('contenu')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between mt-5">
                                <a href="{{ route('forums.index') }}" class="btn btn-secondary btn-lg px-4"
                                   style="border-radius:10px;">
                                    <i class="fas fa-arrow-left me-2"></i>Retour au Forum
                                </a>
                                <button type="submit" class="btn btn-success btn-lg px-5"
                                        style="border-radius:10px; background:linear-gradient(135deg,var(--bs-primary) 0%, var(--bs-success) 100%); border:none;">
                                    <i class="fas fa-paper-plane me-2"></i>Publier le Sujet
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 col-xl-4">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:15px; background:var(--bs-light);">
                    <div class="card-header py-3" style="background:var(--bs-primary); border-radius:15px 15px 0 0;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-lightbulb me-2"></i>Conseils pour un Bon Sujet
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="small text-muted">✅ Soignez le titre pour attirer l’attention.</p>
                        <p class="small text-muted">✅ Expliquez clairement votre idée dans le contenu.</p>
                        <p class="small text-muted">✅ Restez respectueux et constructif.</p>
                        <p class="small text-muted">✅ Ajoutez des détails pour enrichir la discussion.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius:15px; background:var(--bs-light);">
                    <div class="card-header py-3" style="background:var(--bs-primary); border-radius:15px 15px 0 0;">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-question-circle me-2"></i>Besoin d’Aide ?
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <p class="small mb-3" style="color:var(--bs-dark);">
                            Pour toute question, contactez notre équipe d’assistance.
                        </p>
                        <a href="{{ route('contact') }}" class="btn btn-outline-success btn-lg w-100"
                           style="border-radius:10px;">
                            <i class="fas fa-envelope me-2"></i>Contactez-nous
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Create Forum End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-1.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}

.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
}

.form-control:focus {
    border-color: var(--bs-primary) !important;
    box-shadow: 0 0 0 0.2rem rgba(0,76,33,0.25) !important;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,76,33,0.4);
}
</style>
@endsection
