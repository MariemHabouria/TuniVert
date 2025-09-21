<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Challenges - Association</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
</head>
<body>
<div class="container py-5">
    <h2 class="text-primary mb-4 text-center">Mes Challenges</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('challenges.create') }}" class="btn btn-success mb-3">Créer un Challenge</a>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Titre</th>
            <th>Difficulté</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($challenges as $c)
            <tr>
                <td>{{ $c->titre }}</td>
                <td>{{ $c->difficulte }}</td>
                <td>{{ $c->date_debut }} - {{ $c->date_fin }}</td>
                <td>
                    <a href="{{ route('challenges.participants', $c->id) }}" class="btn btn-info btn-sm">Participants</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
