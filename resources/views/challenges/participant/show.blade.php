<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $challenge->titre }} - Challenge</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>
<body>
<div class="container py-5">
    <h2 class="text-primary mb-4 text-center">{{ $challenge->titre }}</h2>

    <p>{{ $challenge->description }}</p>
    <p><strong>Catégorie:</strong> {{ $challenge->categorie }}</p>
    <p><strong>Difficulté:</strong> {{ $challenge->difficulte }}</p>
    <p><strong>Date:</strong> {{ $challenge->date_debut }} - {{ $challenge->date_fin }}</p>
    <p><strong>Objectif:</strong> {{ $challenge->objectif }}</p>

    @auth
        {{-- Participer --}}
        @if(!$participantChallenge)
            <form method="POST" action="{{ route('challenges.participate', $challenge->id) }}">
                @csrf
                <button type="submit" class="btn btn-success mb-3">Participer</button>
            </form>
        @else
            {{-- Soumettre preuve --}}
            <form method="POST" action="{{ route('challenges.submit', $participantChallenge->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="preuve" class="form-label">Ajouter votre preuve :</label>
                    <input type="file" name="preuve" id="preuve" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning mb-3">Soumettre preuve</button>
            </form>
        @endif
    @else
        <p class="text-muted">Connectez-vous pour participer.</p>
    @endauth

    <a href="{{ route('challenges.classement', $challenge->id) }}" class="btn btn-info">Voir le classement</a>
</div>
</body>
</html>
