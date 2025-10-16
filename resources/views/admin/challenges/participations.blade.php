@extends('layouts.admin')

@section('title', 'Participants - Challenge')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Participants du challenge : {{ $challenge->titre ?? 'Challenge inconnu' }}</h4>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Points</th>
                                <th>Badge</th>
                                <th>Rang</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($challenge->participants as $participant)
                                <tr>
                                    <td>{{ $participant->utilisateur->id ?? '-' }}</td>
                                    <td>{{ $participant->utilisateur->name ?? '-' }}</td>
                                    <td>{{ $participant->utilisateur->email ?? '-' }}</td>
                                    <td>{{ $participant->score?->points ?? 0 }}</td>
                                    <td>{{ $participant->score?->badge ?? '-' }}</td>
                                    <td>{{ $participant->score?->rang ?? '-' }}</td>
                                    <td>
                                        <span class="badge badge-{{ 
                                            $participant->statut === 'valide' ? 'success' : 
                                            ($participant->statut === 'rejete' ? 'danger' : 'secondary') 
                                        }}">
                                            {{ ucfirst($participant->statut) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($participant->statut === 'en_cours')
                                            <form action="{{ route('challenges.participants.action', $participant->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="valider">
                                                <button type="submit" class="btn btn-sm btn-success">Valider</button>
                                            </form>
                                            <form action="{{ route('challenges.participants.action', $participant->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <input type="hidden" name="action" value="rejeter">
                                                <button type="submit" class="btn btn-sm btn-danger">Rejeter</button>
                                            </form>
                                        @else
                                            <span class="text-muted">Aucune action</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Aucun participant trouvé</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('admin.challenges.index') }}" class="btn btn-secondary mt-3">Retour à la liste des challenges</a>
            </div>
        </div>
    </div>
</div>
@endsection
