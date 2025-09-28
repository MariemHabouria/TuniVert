@extends('layouts.admin')

@section('title', 'Challenges - Liste')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Gestion des Challenges</h4>

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
                                        <a href="{{ route('admin.challenges.participations', $challenge->id) }}" class="btn btn-sm btn-info">Participants</a>

                                        <form action="{{ route('admin.challenges.toggle', $challenge->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $challenge->actif ? 'warning' : 'success' }}">
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