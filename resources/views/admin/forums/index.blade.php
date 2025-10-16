@extends('layouts.admin')

@section('title', 'Forums - Gestion')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Liste des Forums</h4>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Auteur</th>
                                <th>Créé le</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($forums as $forum)
                            <tr>
                                <td>{{ $forum->id }}</td>
                                <td>{{ $forum->titre }}</td>
                                <td>{{ $forum->utilisateur->name ?? 'Inconnu' }}</td>
                                <td>{{ $forum->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Aucun forum trouvé
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                {{ $forums->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
