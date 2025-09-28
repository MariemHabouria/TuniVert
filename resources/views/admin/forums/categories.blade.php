@extends('layouts.admin')

@section('title', 'Alertes - Gestion')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">🚨 Gestion des Alertes</h4>
                    <a href="{{ route('alertes.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Alerte
                    </a>
                </div>

                <!-- Statistiques -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title">Total Alertes</h5>
                                    <h2>{{ $alertes->total() }}</h2>
                                </div>
                                <i class="mdi mdi-alert-circle-outline icon-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liste des alertes -->
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
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
                                    <td>
                                        <span class="badge 
                                            @if($alerte->gravite === 'haute') bg-danger
                                            @elseif($alerte->gravite === 'moyenne') bg-warning
                                            @elseif($alerte->gravite === 'feu') bg-dark
                                            @else bg-success @endif">
                                            {{ ucfirst($alerte->gravite) }}
                                        </span>
                                    </td>
                                    <td>{{ $alerte->user->name ?? 'Inconnu' }}</td>
                                    <td>{{ $alerte->created_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucune alerte trouvée</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $alertes->links() }}
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
