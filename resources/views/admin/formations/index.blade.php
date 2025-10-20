@extends('layouts.admin')

@section('title', 'Formations - Catalogue')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="text-white">{{ $stats['total'] }}</h4>
                        <p class="text-light mb-0">Total Formations</p>
                    </div>
                    <i class="mdi mdi-school text-light" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="text-white">{{ $stats['active'] }}</h4>
                        <p class="text-light mb-0">Formations Actives</p>
                    </div>
                    <i class="mdi mdi-check-circle text-light" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="text-white">{{ $stats['draft'] }}</h4>
                        <p class="text-light mb-0">Brouillons</p>
                    </div>
                    <i class="mdi mdi-file-document text-light" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="text-white">{{ $stats['total_inscriptions'] }}</h4>
                        <p class="text-light mb-0">Inscriptions Totales</p>
                    </div>
                    <i class="mdi mdi-account-multiple text-light" style="font-size: 2rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title">Catalogue des Formations</h4>
                    <a href="{{ route('admin.formations.create') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Nouvelle Formation
                    </a>
                </div>
                
                <!-- Search and Filters -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="text" name="search" class="form-control" placeholder="Rechercher une formation..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="categorie" class="form-control">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('categorie') == $category ? 'selected' : '' }}>
                                            {{ ucfirst($category) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="statut" class="form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactif" {{ request('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                                    <option value="brouillon" {{ request('statut') == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                        </div>
                    </div>
                </form>
                
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Formations Grid -->
                <div class="row">
                    @forelse($formations as $formation)
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                @if($formation->image)
                                    <img src="{{ asset($formation->image) }}" class="card-img-top" alt="{{ $formation->titre }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="mdi mdi-school text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $formation->titre }}</h5>
                                    <p class="card-text">{{ Str::limit($formation->description, 80) }}</p>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="mdi mdi-tag"></i> {{ ucfirst($formation->categorie) }}
                                        </small>
                                    </div>
                                    
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            <i class="mdi mdi-account"></i> {{ $formation->organisateur->name ?? 'Anonyme' }}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span class="text-muted">
                                            <i class="mdi mdi-account-multiple"></i> 
                                            {{ $formation->inscrits->count() }}/@if($formation->capacite){{ $formation->capacite }}@else∞@endif inscrits
                                        </span>
                                        <span class="badge badge-{{ $formation->statut == 'actif' ? 'success' : ($formation->statut == 'brouillon' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($formation->statut) }}
                                        </span>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('formations.show', $formation) }}" class="btn btn-sm btn-info" target="_blank">
                                            <i class="mdi mdi-eye"></i> Voir
                                        </a>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('formations.show', $formation) }}" class="btn btn-sm btn-warning flex-fill">
                                                <i class="mdi mdi-pencil"></i> Modifier
                                            </a>
                                            <form action="#" method="POST" class="flex-fill">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger w-100" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?')">
                                                    <i class="mdi mdi-delete"></i> Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-school text-muted" style="font-size: 4rem;"></i>
                                <h5 class="mt-3 text-muted">Aucune formation trouvée</h5>
                                <p class="text-muted">
                                    @if(request()->hasAny(['search', 'categorie', 'statut']))
                                        Aucune formation ne correspond à vos critères de recherche.
                                        <a href="{{ route('admin.formations.index') }}">Réinitialiser les filtres</a>
                                    @else
                                        Commencez par créer votre première formation.
                                        <a href="{{ route('admin.formations.create') }}">Créer une formation</a>
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>
                
                <!-- Pagination -->
                @if($formations->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Formations pagination">
                            <ul class="pagination pagination-sm">
                                {{-- Previous Page Link --}}
                                @if ($formations->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Précédent</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $formations->appends(request()->query())->previousPageUrl() }}">Précédent</a></li>
                                @endif

                                {{-- Pagination Elements --}}
                                @php
                                    $currentPage = $formations->currentPage();
                                    $lastPage = $formations->lastPage();
                                    $start = max(1, $currentPage - 2);
                                    $end = min($lastPage, $currentPage + 2);
                                @endphp
                                
                                @if($start > 1)
                                    <li class="page-item"><a class="page-link" href="{{ $formations->appends(request()->query())->url(1) }}">1</a></li>
                                    @if($start > 2)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                @endif
                                
                                @for($page = $start; $page <= $end; $page++)
                                    @if ($page == $currentPage)
                                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $formations->appends(request()->query())->url($page) }}">{{ $page }}</a></li>
                                    @endif
                                @endfor
                                
                                @if($end < $lastPage)
                                    @if($end < $lastPage - 1)
                                        <li class="page-item disabled"><span class="page-link">...</span></li>
                                    @endif
                                    <li class="page-item"><a class="page-link" href="{{ $formations->appends(request()->query())->url($lastPage) }}">{{ $lastPage }}</a></li>
                                @endif

                                {{-- Next Page Link --}}
                                @if ($formations->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $formations->appends(request()->query())->nextPageUrl() }}">Suivant</a></li>
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
@endsection