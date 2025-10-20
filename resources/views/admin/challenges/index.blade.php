@extends('layouts.admin')

@section('title', 'Challenges - Liste')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">🏆 Gestion des Challenges</h4>
                    @if(Route::has('admin.challenges.create'))
                        <a href="{{ route('admin.challenges.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Nouveau Challenge
                        </a>
                    @endif
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
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
                                    <td>{{ \Carbon\Carbon::parse($challenge->date_debut)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($challenge->date_fin)->format('d/m/Y') }}</td>
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
                                        <a href="{{ route('admin.challenges.participations', $challenge->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="Voir les participants">
                                            <i class="mdi mdi-account-group"></i> Participants
                                        </a>

                                        <a href="{{ route('admin.challenges.edit', $challenge->id) }}" 
                                           class="btn btn-sm btn-warning" 
                                           title="Modifier le challenge">
                                            <i class="mdi mdi-pencil"></i> Modifier
                                        </a>

                                        <form action="{{ route('admin.challenges.toggle', $challenge->id) }}" 
                                              method="POST" 
                                              style="display:inline-block;">
                                            @csrf
                                            <button type="submit" 
                                                    class="btn btn-sm btn-{{ $challenge->actif ? 'warning' : 'success' }}"
                                                    title="{{ $challenge->actif ? 'Désactiver' : 'Activer' }} le challenge">
                                                <i class="mdi mdi-{{ $challenge->actif ? 'pause' : 'play' }}"></i>
                                                {{ $challenge->actif ? 'Bloquer' : 'Débloquer' }}
                                            </button>
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
