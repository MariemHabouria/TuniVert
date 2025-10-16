@extends('layouts.app')

@section('title', 'Modifier une alerte')

@section('content')
<!-- Header Start -->
<div class="container-fluid page-header py-5 mb-5"
     style="margin-top:-1px; padding-top:6rem!important;
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
                        url('{{ asset('img/carousel-2.jpg') }}') center center no-repeat; background-size:cover;">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-12 text-center">
                <h1 class="display-4 text-white animated slideInDown mb-3">✏️ Modifier l’Alerte</h1>
                <nav aria-label="breadcrumb animated slideInDown">
                    <ol class="breadcrumb justify-content-center mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('alertes.index') }}" class="text-white">Alertes</a></li>
                        <li class="breadcrumb-item text-primary active" aria-current="page">Modifier</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Header End -->

<!-- Edit Alert Start -->
<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row g-5 justify-content-center">
            <div class="col-lg-8 col-xl-7">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header py-4 text-center text-white"
                         style="background: linear-gradient(135deg, var(--bs-warning) 0%, var(--bs-danger) 100%);
                                border-radius:15px 15px 0 0;">
                        <h3 class="mb-0">Mettre à Jour l’Alerte</h3>
                    </div>

                    <div class="card-body p-5">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('alertes.update', $alerte->id) }}" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')

                            <!-- Titre -->
                            <div class="mb-4">
                                <label for="titre" class="form-label fw-semibold">
                                    <i class="fas fa-heading me-2 text-danger"></i>Titre de l’Alerte *
                                </label>
                                <input type="text" name="titre" id="titre"
                                       class="form-control form-control-lg"
                                       style="border-radius:10px; border:2px solid #ddd; padding:12px;"
                                       value="{{ old('titre', $alerte->titre) }}" required>
                                @error('titre')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-2 text-danger"></i>Description *
                                </label>
                                <textarea name="description" id="description"
                                          class="form-control"
                                          style="border-radius:10px; border:2px solid #ddd; padding:12px; min-height:120px;"
                                          required>{{ old('description', $alerte->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gravité -->
                            <div class="mb-4">
                                <label for="gravite" class="form-label fw-semibold">
                                    <i class="fas fa-exclamation-triangle me-2 text-danger"></i>Niveau de Gravité *
                                </label>
                                <select name="gravite" id="gravite"
                                        class="form-select"
                                        style="border-radius:10px; border:2px solid #ddd; padding:12px;" required>
                                    <option value="basse" {{ $alerte->gravite == 'basse' ? 'selected' : '' }}>🟢 Basse</option>
                                    <option value="moyenne" {{ $alerte->gravite == 'moyenne' ? 'selected' : '' }}>🟡 Moyenne</option>
                                    <option value="haute" {{ $alerte->gravite == 'haute' ? 'selected' : '' }}>🔴 Haute</option>
                                    <option value="feu" {{ $alerte->gravite == 'feu' ? 'selected' : '' }}>🔥 Feu</option>
                                </select>
                                @error('gravite')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Boutons -->
                            <div class="d-flex justify-content-between pt-3">
                                <a href="{{ route('alertes.index') }}" class="btn btn-secondary btn-lg px-4"
                                   style="border-radius:10px;">
                                    ⬅️ Annuler
                                </a>
                                <button type="submit"
                                        class="btn btn-warning btn-lg px-5"
                                        style="border-radius:10px; background: linear-gradient(135deg, #ffc107, #fd7e14); border:none;">
                                    💾 Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Conseil -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm" style="border-radius:15px;">
                    <div class="card-header bg-warning text-dark" style="border-radius:15px 15px 0 0;">
                        <h5 class="mb-0"><i class="fas fa-lightbulb me-2"></i>Conseils</h5>
                    </div>
                    <div class="card-body">
                        <ul class="small">
                            <li>Mettez à jour uniquement ce qui a changé</li>
                            <li>Vérifiez les fautes avant de sauvegarder</li>
                            <li>Ne changez la gravité que si nécessaire</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Edit Alert End -->
@endsection

@section('styles')
<style>
.page-header {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)),
                url('{{ asset('img/carousel-2.jpg') }}') center center no-repeat !important;
    background-size: cover !important;
}
.card:hover {
    transform: translateY(-3px);
    transition: 0.3s ease-in-out;
}
</style>
@endsection
