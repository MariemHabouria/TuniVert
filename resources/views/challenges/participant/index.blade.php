<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Challenges - Participant</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
<div class="container py-5">
    <h2 class="text-primary mb-4 text-center">Tous les Challenges</h2>
    <div class="row">
        @foreach($challenges as $challenge)
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="card-title">{{ $challenge->titre }}</h5>
                    <p class="card-text">{{ Str::limit($challenge->description, 100) }}</p>
                   <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-primary">Voir</a>

                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>
