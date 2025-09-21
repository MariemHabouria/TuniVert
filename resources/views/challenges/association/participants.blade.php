<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Participants - {{ $challenge->titre }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>
<body>
<div class="container py-5">
    <h2 class="text-primary mb-4 text-center">Participants - {{ $challenge->titre }}</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Statut</th>
            <th>Score</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($participants as $p)
            <tr>
                <td>{{ $p->utilisateur->name }}</td>
                <td>{{ $p->statut }}</td>
                <td>{{ $p->score }}</td>
                <td>
                    <form action="{{ route('challenges.validate', $p->id) }}" method="POST">
                        @csrf
                        <button name="action" value="valider" class="btn btn-success btn-sm">Valider</button>
                        <button name="action" value="rejeter" class="btn btn-danger btn-sm">Rejeter</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
