@extends('layouts.admin')

@section('title', 'Gestion des alertes')

@section('content')
<div class="container">
    <h3 class="mb-4">🚨 Liste des alertes</h3>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Description</th>
                    <th>Gravité</th>
                    <th>Auteur</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alertes as $alerte)
                    <tr>
                        <td>{{ $alerte->id }}</td>
                        <td>{{ $alerte->titre }}</td>
                        <td>{{ Str::limit($alerte->description, 50) }}</td>
                        <td><span class="badge bg-{{ $alerte->gravite === 'haute' ? 'danger' : ($alerte->gravite === 'moyenne' ? 'warning' : 'success') }}">{{ ucfirst($alerte->gravite) }}</span></td>
                        <td>{{ $alerte->user->name ?? 'Inconnu' }}</td>
                        <td>{{ $alerte->created_at->format('d/m/Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">Aucune alerte trouvée</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    {{ $alertes->links() }}
</div>
@endsection
