@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- En-tête moderne -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title mb-2">Gestion des Utilisateurs & Associations</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Utilisateurs</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.utilisateurs.create') }}" class="btn btn-primary btn-modern">
                    <i class="fas fa-user-plus me-2"></i>Nouvel Utilisateur
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques modernes avec séparation Associations -->
    <div class="row g-3 mb-4">
        @php
            $associations = $users->where('role', 'association');
            $normalUsers = $users->whereIn('role', ['user', 'admin']);
            $verifiedAssoc = $associations->whereNotNull('matricule')->where('email_verified_at', '!=', null);
            $pendingAssoc = $associations->where(function($a) { 
                return is_null($a->email_verified_at) || is_null($a->matricule); 
            });
        @endphp

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-primary">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Utilisateurs</div>
                        <div class="stat-value">{{ $users->count() }}</div>
                        <div class="stat-detail">{{ $normalUsers->count() }} users · {{ $associations->count() }} assoc.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-warning">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Associations</div>
                        <div class="stat-value">{{ $associations->count() }}</div>
                        <div class="stat-detail">{{ $verifiedAssoc->count() }} vérifiées</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-success">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Actifs</div>
                        <div class="stat-value">{{ $users->where('is_blocked', false)->count() }}</div>
                        <div class="stat-detail">Comptes actifs</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stat-card stat-card-danger">
                <div class="stat-card-body">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">À Vérifier</div>
                        <div class="stat-value">{{ $pendingAssoc->count() }}</div>
                        <div class="stat-detail">Associations en attente</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Onglets de navigation -->
    <ul class="nav nav-tabs-modern mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#allUsers" type="button">
                <i class="fas fa-users me-2"></i>Tous les utilisateurs
                <span class="badge bg-primary ms-2">{{ $users->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#associations" type="button">
                <i class="fas fa-building me-2"></i>Associations
                <span class="badge bg-warning ms-2">{{ $associations->count() }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                <i class="fas fa-clock me-2"></i>En attente de vérification
                <span class="badge bg-danger ms-2">{{ $pendingAssoc->count() }}</span>
            </button>
        </li>
    </ul>

    <!-- Contenu des onglets -->
    <div class="tab-content">
        
        <!-- TAB 1: Tous les utilisateurs -->
        <div class="tab-pane fade show active" id="allUsers">
            
            <!-- Filtres -->
            <div class="card card-modern mb-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-filter me-2"></i>Filtres de recherche
                    </h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.utilisateurs.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="name" class="form-control" placeholder="Rechercher..." value="{{ request('name') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="exemple@mail.com" value="{{ request('email') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Rôle</label>
                                <select name="role" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                                    <option value="user" {{ request('role')=='user'?'selected':'' }}>Utilisateur</option>
                                    <option value="association" {{ request('role')=='association'?'selected':'' }}>Association</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select">
                                    <option value="">Tous</option>
                                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Actif</option>
                                    <option value="blocked" {{ request('status')=='blocked'?'selected':'' }}>Bloqué</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i>Rechercher
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tableau tous utilisateurs -->
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-users me-2"></i>Liste Complète
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Utilisateur</th>
                                    <th>Email</th>
                                    <th width="120">Rôle</th>
                                    <th width="150">Matricule</th>
                                    <th width="100">Statut</th>
                                    <th width="130">Inscription</th>
                                    <th width="150" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr class="{{ $user->role == 'association' ? 'row-association' : '' }}">
                                    <td><span class="text-muted fw-medium">#{{ $user->id }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 {{ $user->role == 'association' ? 'bg-warning' : 'bg-primary' }}">
                                                @if($user->role == 'association')
                                                    <i class="fas fa-building"></i>
                                                @else
                                                    {{ strtoupper(substr($user->name,0,2)) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $user->name }}</div>
                                                <div class="small">
                                                    @if($user->email_verified_at)
                                                        <span class="text-success"><i class="fas fa-check-circle fa-xs"></i> Vérifié</span>
                                                    @else
                                                        <span class="text-warning"><i class="fas fa-clock fa-xs"></i> Non vérifié</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $user->email }}</td>
                                    <td>
                                        @if($user->role == 'admin')
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-user-shield fa-xs me-1"></i>Admin
                                            </span>
                                        @elseif($user->role == 'association')
                                            <span class="badge-modern badge-warning">
                                                <i class="fas fa-building fa-xs me-1"></i>Association
                                            </span>
                                        @else
                                            <span class="badge-modern badge-primary">
                                                <i class="fas fa-user fa-xs me-1"></i>User
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->role == 'association')
                                            @if($user->matricule)
                                                <span class="badge-modern badge-info">
                                                    <i class="fas fa-id-card fa-xs me-1"></i>{{ $user->matricule }}
                                                </span>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="fas fa-minus"></i> Non fourni
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_blocked)
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-ban fa-xs me-1"></i>Bloqué
                                            </span>
                                        @else
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-check fa-xs me-1"></i>Actif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group-modern">
                                            @if($user->role == 'association')
                                                <!-- Actions spécifiques associations -->
                                                <button type="button" 
                                                        class="btn-action btn-action-info"
                                                        title="Modifier le matricule"
                                                        onclick="openMatriculeModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->matricule }}')">
                                                    <i class="fas fa-id-card"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.associations.toggle-verify', $user->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn-action btn-action-{{ $user->email_verified_at && $user->matricule ? 'warning' : 'success' }}"
                                                            title="{{ $user->email_verified_at && $user->matricule ? 'Révoquer' : 'Vérifier' }}"
                                                            onclick="return confirm('Confirmer cette action ?')">
                                                        <i class="fas {{ $user->email_verified_at && $user->matricule ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <!-- Bloquer/Débloquer (tous) -->
                                            <form action="{{ route('admin.utilisateurs.toggle', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn-action btn-action-{{ $user->is_blocked ? 'success' : 'danger' }}"
                                                        title="{{ $user->is_blocked ? 'Débloquer' : 'Bloquer' }}"
                                                        onclick="return confirm('Confirmer cette action ?')">
                                                    <i class="fas {{ $user->is_blocked ? 'fa-unlock' : 'fa-lock' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="empty-state">
                                            <i class="fas fa-user-slash"></i>
                                            <p>Aucun utilisateur trouvé</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: Associations uniquement -->
        <div class="tab-pane fade" id="associations">
            <div class="card card-modern">
                <div class="card-header bg-warning-subtle">
                    <h5 class="card-title">
                        <i class="fas fa-building me-2"></i>Gestion des Associations
                        <span class="badge bg-warning ms-2">{{ $associations->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Association</th>
                                    <th>Email</th>
                                    <th width="150">Matricule</th>
                                    <th width="120">Vérification</th>
                                    <th width="100">Statut</th>
                                    <th width="130">Inscription</th>
                                    <th width="180" class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($associations as $assoc)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-warning">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $assoc->name }}</div>
                                                <small class="text-muted">ID: #{{ $assoc->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $assoc->email }}</td>
                                    <td>
                                        @if($assoc->matricule)
                                            <span class="badge-modern badge-info">
                                                <i class="fas fa-id-card fa-xs me-1"></i>{{ $assoc->matricule }}
                                            </span>
                                        @else
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-exclamation fa-xs me-1"></i>Manquant
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assoc->email_verified_at && $assoc->matricule)
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-check-circle fa-xs me-1"></i>Vérifiée
                                            </span>
                                        @else
                                            <span class="badge-modern badge-warning">
                                                <i class="fas fa-clock fa-xs me-1"></i>En attente
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($assoc->is_blocked)
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-ban fa-xs me-1"></i>Bloqué
                                            </span>
                                        @else
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-check fa-xs me-1"></i>Actif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $assoc->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group-modern">
                                            <button type="button" 
                                                    class="btn-action btn-action-info"
                                                    title="Modifier le matricule"
                                                    onclick="openMatriculeModal({{ $assoc->id }}, '{{ $assoc->name }}', '{{ $assoc->matricule }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('admin.associations.toggle-verify', $assoc->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn-action btn-action-{{ $assoc->email_verified_at && $assoc->matricule ? 'warning' : 'success' }}"
                                                        title="{{ $assoc->email_verified_at && $assoc->matricule ? 'Révoquer' : 'Vérifier' }}"
                                                        onclick="return confirm('Confirmer cette action ?')">
                                                    <i class="fas {{ $assoc->email_verified_at && $assoc->matricule ? 'fa-times-circle' : 'fa-check-circle' }}"></i>
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.utilisateurs.toggle', $assoc->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn-action btn-action-{{ $assoc->is_blocked ? 'success' : 'danger' }}"
                                                        title="{{ $assoc->is_blocked ? 'Débloquer' : 'Bloquer' }}"
                                                        onclick="return confirm('Confirmer cette action ?')">
                                                    <i class="fas {{ $assoc->is_blocked ? 'fa-unlock' : 'fa-lock' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <i class="fas fa-building"></i>
                                            <p>Aucune association trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 3: En attente de vérification -->
        <div class="tab-pane fade" id="pending">
            <div class="alert alert-modern alert-warning mb-3">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <strong>{{ $pendingAssoc->count() }} association(s)</strong> nécessitent une vérification (matricule manquant ou email non vérifié)
                </div>
            </div>

            <div class="card card-modern">
                <div class="card-header bg-danger-subtle">
                    <h5 class="card-title">
                        <i class="fas fa-clock me-2"></i>Associations en Attente
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th>Association</th>
                                    <th>Email</th>
                                    <th width="150">Matricule</th>
                                    <th width="200">Problème</th>
                                    <th width="130">Inscription</th>
                                    <th width="180" class="text-center">Actions Rapides</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingAssoc as $pending)
                                <tr class="row-pending">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3 bg-warning">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-dark">{{ $pending->name }}</div>
                                                <small class="text-muted">ID: #{{ $pending->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $pending->email }}</td>
                                    <td>
                                        @if($pending->matricule)
                                            <span class="badge-modern badge-info">
                                                <i class="fas fa-id-card fa-xs me-1"></i>{{ $pending->matricule }}
                                            </span>
                                        @else
                                            <span class="badge-modern badge-danger">
                                                <i class="fas fa-times fa-xs me-1"></i>Manquant
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!$pending->matricule)
                                            <span class="text-danger small">
                                                <i class="fas fa-exclamation-circle"></i> Matricule manquant
                                            </span>
                                        @endif
                                        @if(!$pending->email_verified_at)
                                            <span class="text-warning small">
                                                <i class="fas fa-envelope"></i> Email non vérifié
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-muted">{{ $pending->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="btn-group-modern">
                                            <button type="button" 
                                                    class="btn-action btn-action-primary"
                                                    title="Ajouter matricule"
                                                    onclick="openMatriculeModal({{ $pending->id }}, '{{ $pending->name }}', '{{ $pending->matricule }}')">
                                                <i class="fas fa-plus"></i>
                                            </button>

                                            @if($pending->matricule)
                                            <form action="{{ route('admin.associations.toggle-verify', $pending->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn-action btn-action-success"
                                                        title="Vérifier maintenant"
                                                        onclick="return confirm('Vérifier cette association ?')">
                                                    <i class="fas fa-check-double"></i>
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('admin.utilisateurs.toggle', $pending->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn-action btn-action-danger"
                                                        title="Bloquer"
                                                        onclick="return confirm('Bloquer cette association ?')">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="empty-state">
                                            <i class="fas fa-check-circle text-success"></i>
                                            <p>Toutes les associations sont vérifiées !</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Modal Matricule -->
<div class="modal fade" id="matriculeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-id-card me-2"></i>Gestion du Matricule
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="matriculeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Association</label>
                        <input type="text" class="form-control" id="associationName" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="matricule" class="form-label">
                            Matricule <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="matricule" 
                               name="matricule" 
                               placeholder="Ex: ASSOC-2024-001"
                               required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>Format recommandé: ASSOC-ANNEE-NUMERO
                        </small>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Note:</strong> L'association sera automatiquement vérifiée après l'ajout du matricule.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openMatriculeModal(id, name, matricule) {
        document.getElementById('associationName').value = name;
        document.getElementById('matricule').value = matricule || '';
        document.getElementById('matriculeForm').action = `/admin/associations/${id}/matricule`;
        
        const modal = new bootstrap.Modal(document.getElementById('matriculeModal'));
        modal.show();
    }
</script>
@endsection

@section('styles')
<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Variables */
    :root {
        --primary: #4e73df;
        --success: #1cc88a;
        --info: #36b9cc;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --dark: #2c3e50;
        --light: #f8f9fc;
        --border: #e3e6f0;
        --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        --shadow-sm: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Page Header */
    .page-header {
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border);
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin: 0;
        font-size: 0.875rem;
    }

    .breadcrumb-item a {
        color: #858796;
        text-decoration: none;
        transition: color 0.2s;
    }

    .breadcrumb-item a:hover {
        color: var(--primary);
    }

    /* Boutons modernes */
    .btn-modern {
        padding: 0.625rem 1.25rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }

    /* Onglets modernes */
    .nav-tabs-modern {
        border-bottom: 2px solid var(--border);
    }

    .nav-tabs-modern .nav-item {
        margin-bottom: -2px;
    }

    .nav-tabs-modern .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #858796;
        font-weight: 600;
        padding: 1rem 1.5rem;
        transition: all 0.3s;
        background: transparent;
    }

    .nav-tabs-modern .nav-link:hover {
        color: var(--primary);
        border-bottom-color: var(--border);
    }

    .nav-tabs-modern .nav-link.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
        background: transparent;
    }

    .nav-tabs-modern .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    /* Cartes de statistiques */
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: width 0.3s ease;
    }

    .stat-card-primary::before { background: var(--primary); }
    .stat-card-success::before { background: var(--success); }
    .stat-card-warning::before { background: var(--warning); }
    .stat-card-danger::before { background: var(--danger); }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow);
    }

    .stat-card:hover::before {
        width: 100%;
        opacity: 0.05;
    }

    .stat-card-body {
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.75rem;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .stat-card-primary .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .stat-card-success .stat-icon { background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%); }
    .stat-card-warning .stat-icon { background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%); }
    .stat-card-danger .stat-icon { background: linear-gradient(135deg, #e74a3b 0%, #c0392b 100%); }

    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        color: #858796;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark);
        line-height: 1;
    }

    .stat-detail {
        font-size: 0.75rem;
        color: #858796;
        margin-top: 0.25rem;
    }

    /* Cartes modernes */
    .card-modern {
        border: 1px solid var(--border);
        border-radius: 1rem;
        box-shadow: var(--shadow-sm);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-modern .card-header {
        background: white;
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
    }

    .card-modern .card-header.bg-warning-subtle {
        background: #fef5e0 !important;
        border-left: 4px solid var(--warning);
    }

    .card-modern .card-header.bg-danger-subtle {
        background: #ffe6e3 !important;
        border-left: 4px solid var(--danger);
    }

    .card-modern .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
    }

    .card-modern .card-body {
        padding: 1.5rem;
    }

    /* Alertes */
    .alert-modern {
        border-radius: 0.75rem;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .alert-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .alert-content {
        flex: 1;
        font-weight: 500;
    }

    /* Tableau moderne */
    .table-modern {
        margin: 0;
    }

    .table-modern thead {
        background: var(--light);
    }

    .table-modern thead th {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #858796;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        border: none;
    }

    .table-modern tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid var(--border);
    }

    .table-modern tbody tr {
        transition: all 0.2s;
    }

    .table-modern tbody tr:hover {
        background-color: rgba(78, 115, 223, 0.03);
    }

    /* Mise en évidence des associations */
    .table-modern tbody tr.row-association {
        background-color: rgba(246, 194, 62, 0.05);
    }

    .table-modern tbody tr.row-association:hover {
        background-color: rgba(246, 194, 62, 0.1);
    }

    /* Mise en évidence des en attente */
    .table-modern tbody tr.row-pending {
        background-color: rgba(231, 74, 59, 0.05);
        border-left: 3px solid var(--danger);
    }

    .table-modern tbody tr.row-pending:hover {
        background-color: rgba(231, 74, 59, 0.1);
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* Avatar */
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.875rem;
        color: white;
        flex-shrink: 0;
    }

    .avatar.bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .avatar.bg-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    /* Badges modernes */
    .badge-modern {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
    }

    .badge-primary { background: #e3e8ff; color: #4e73df; }
    .badge-success { background: #d4f4e7; color: #1cc88a; }
    .badge-warning { background: #fef5e0; color: #f6c23e; }
    .badge-danger { background: #ffe6e3; color: #e74a3b; }
    .badge-info { background: #e0f7fa; color: #36b9cc; }

    /* Boutons d'action */
    .btn-group-modern {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        transition: all 0.2s;
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-action-primary { background: #e3e8ff; color: #4e73df; }
    .btn-action-primary:hover { background: #4e73df; color: white; }

    .btn-action-success { background: #d4f4e7; color: #1cc88a; }
    .btn-action-success:hover { background: #1cc88a; color: white; }

    .btn-action-warning { background: #fef5e0; color: #f6c23e; }
    .btn-action-warning:hover { background: #f6c23e; color: white; }

    .btn-action-info { background: #e0f7fa; color: #36b9cc; }
    .btn-action-info:hover { background: #36b9cc; color: white; }

    .btn-action-danger { background: #ffe6e3; color: #e74a3b; }
    .btn-action-danger:hover { background: #e74a3b; color: white; }

    /* État vide */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #858796;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Form controls */
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid var(--border);
        padding: 0.625rem 1rem;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    /* Modal */
    .modal-content {
        border-radius: 1rem;
        border: none;
        box-shadow: var(--shadow);
    }

    .modal-header {
        border-bottom: 1px solid var(--border);
        padding: 1.25rem 1.5rem;
    }

    .modal-title {
        font-weight: 700;
        color: var(--dark);
    }

    .modal-footer {
        border-top: 1px solid var(--border);
        padding: 1rem 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .stat-card-body {
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .nav-tabs-modern .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
        }

        .btn-group-modern {
            flex-wrap: wrap;
        }
    }
</style>
@endsection