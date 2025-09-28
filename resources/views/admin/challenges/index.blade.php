@extends('layouts.admin')

@section('title', 'Challenges - Liste')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Gestion des Challenges</h4>
                    <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouveau Challenge
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Participants</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                       <tbody>
    @forelse($challenges as $challenge)
        <tr>
            <td>{{ $challenge->id }}</td>
            <td>{{ $challenge->titre }}</td>
            <td>{{ $challenge->date_debut }}</td>
            <td>{{ $challenge->date_fin }}</td>
            <td>{{ $challenge->participants_count }}</td>
            <td>
                @php
                    $statut = now()->between($challenge->date_debut, $challenge->date_fin) ? 'actif' : 'inactif';
                @endphp
                <span class="badge badge-{{ $statut === 'actif' ? 'success' : 'secondary' }}">
                    {{ ucfirst($statut) }}
                </span>
            </td>
            <td>
                <a href="{{ route('challenges.show', $challenge->id) }}" class="btn btn-sm btn-info">Voir</a>
<a href="{{ route('challenges.edit', $challenge->id) }}" class="btn btn-sm btn-warning">Modifier</a>
<form action="{{ route('challenges.destroy', $challenge->id) }}" method="POST">

                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ce challenge ?')">Supprimer</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center">Aucun challenge trouvé</td>
        </tr>
    @endforelse
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection