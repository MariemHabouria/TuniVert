<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Challenges - Participant</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>
<body>
<div class="container py-5">
    <h2 class="text-primary mb-4 text-center">Mes Challenges et Badges</h2>

    <h4>Mes Challenges</h4>
    <ul class="list-group mb-4">
        @foreach($participations as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $p->challenge->titre }}
                <span class="badge bg-primary">{{ $p->statut }}</span>
            </li>
        @endforeach
    </ul>

    <h4>Mes Badges</h4>
    <div class="d-flex gap-3 mb-4">
        @foreach($badges as $badge)
            <div class="border p-2 text-center">
                <img src="{{ $badge->badge }}" alt="{{ $badge->badge }}" width="50">
                <p>{{ $badge->badge }}</p>
            </div>
        @endforeach
    </div>

    <h4>Classement général</h4>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Rang</th>
            <th>Nom</th>
            <th>Score</th>
        </tr>
        </thead>
        <tbody>
        @foreach($classement as $index => $entry)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $entry->utilisateur->name }}</td>
                <td>{{ $entry->score ? $entry->score->points : 0 }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
