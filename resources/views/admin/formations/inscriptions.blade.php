@extends('layouts.admin')

@section('title', 'Formations - Inscriptions')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">Total Inscriptions</h4>
                        <h2>{{ $stats['total_inscriptions'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="mdi mdi-account-multiple icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">Ce Mois</h4>
                        <h2>{{ $stats['inscriptions_mois'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="mdi mdi-calendar-month icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">Formations Actives</h4>
                        <h2>{{ $stats['formations_actives'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="mdi mdi-school icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">Utilisateurs Uniques</h4>
                        <h2>{{ $stats['utilisateurs_uniques'] }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="mdi mdi-account-group icon-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Gestion des Inscriptions</h4>
                
                <!-- Search and Filters -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher par étudiant, email ou formation..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="formation_id" class="form-control">
                                    <option value="">Toutes les formations</option>
                                    @foreach($formations as $id => $titre)
                                        <option value="{{ $id }}" {{ request('formation_id') == $id ? 'selected' : '' }}>
                                            {{ $titre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </div>
                </form>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Étudiant</th>
                                <th>Email</th>
                                <th>Formation</th>
                                <th>Catégorie</th>
                                <th>Date Inscription</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inscriptions as $inscription)
                                <tr>
                                    <td>{{ $inscription->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <div class="avatar-initial rounded-circle bg-primary">
                                                    {{ strtoupper(substr($inscription->user_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            {{ $inscription->user_name }}
                                        </div>
                                    </td>
                                    <td>{{ $inscription->user_email }}</td>
                                    <td>
                                        <strong>{{ $inscription->formation_title }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge badge-info">{{ ucfirst($inscription->categorie) }}</span>
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($inscription->created_at)->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" title="Voir le profil">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-warning" title="Envoyer un message">
                                            <i class="mdi mdi-email"></i>
                                        </button>
                                        <form action="#" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer l'inscription" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette inscription ?')">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="mdi mdi-account-multiple text-muted" style="font-size: 4rem;"></i>
                                        <h5 class="mt-3 text-muted">Aucune inscription trouvée</h5>
                                        <p class="text-muted">
                                            @if(request()->hasAny(['search', 'formation_id']))
                                                Aucune inscription ne correspond à vos critères de recherche.
                                                <a href="{{ route('admin.formations.inscriptions') }}">Réinitialiser les filtres</a>
                                            @else
                                                Les inscriptions apparaîtront ici dès qu'il y en aura.
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($inscriptions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Inscriptions pagination">
                            <ul class="pagination pagination-sm">
                                {{-- Previous Page Link --}}
                                @if ($inscriptions->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Précédent</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $inscriptions->appends(request()->query())->previousPageUrl() }}">Précédent</a></li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $currentPage = $inscriptions->currentPage();
                                    $lastPage = $inscriptions->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item"><a class="page-link" href="{{ $inscriptions->appends(request()->query())->url(1) }}">1</a></li>
                                    @if($start > 2)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif
                                
                                @for($page = $start; $page <= $end; $page++)
                                    @if ($page == $currentPage)
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $inscriptions->appends(request()->query())->url($page) }}">{{ $page }}</a></li>
                                    @endif
                                @endfor
                                
                                @if($end < $lastPage)
                                    @if($end < $lastPage - 1)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $inscriptions->appends(request()->query())->url($lastPage) }}">{{ $lastPage }}</a></li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($inscriptions->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $inscriptions->appends(request()->query())->nextPageUrl() }}">Suivant</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Suivant</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    width: 2rem;
    height: 2rem;
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
}
</style>

                                    <button class="btn btn-sm btn-info">Détails</button>
                                    <button class="btn btn-sm btn-warning">Modifier</button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jean Martin</td>
                                <td>React Masterclass</td>
                                <td>18/09/2024</td>
                                <td><span class="badge badge-warning">En attente</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" style="width: 0%">0%</div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success">Approuver</button>
                                    <button class="btn btn-sm btn-danger">Refuser</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection