@extends('layouts.admin')
@section('title', 'Gestion des Donations')

@section('content')
<div class="row">
    <!-- Statistiques Cards -->
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-hand-heart display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Total Collecté</h6>
                        <h4 class="mb-0">{{ number_format($stats['total'], 2, ',', ' ') }} TND</h4>
                        <small class="opacity-75">{{ $stats['count'] }} donations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-calendar-month display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Ce Mois</h6>
                        <h4 class="mb-0">{{ number_format($stats['total_mois'], 2, ',', ' ') }} TND</h4>
                        <small class="opacity-75">{{ $stats['count_mois'] }} donations</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-chart-line display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Moyenne</h6>
                        <h4 class="mb-0">{{ $stats['count'] > 0 ? number_format($stats['total'] / $stats['count'], 2, ',', ' ') : '0,00' }} TND</h4>
                        <small class="opacity-75">par donation</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        <i class="mdi mdi-account-multiple display-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1">Donateurs</h6>
                        <h4 class="mb-0">{{ $stats['donateurs_uniques'] ?? 0 }}</h4>
                        <small class="opacity-75">uniques</small>
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-table me-2"></i>Historique des Donations
                    </h4>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.donations.rapports') }}" class="btn btn-outline-primary btn-sm">
                            <i class="mdi mdi-chart-bar me-1"></i>Rapports
                        </a>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                            <i class="mdi mdi-plus me-1"></i>Ajouter Méthode
                        </button>
                    </div>
                </div>

                <!-- Filtres améliorés -->
                <form method="GET" class="row g-3 mb-4 p-3 bg-light rounded">
                    <div class="col-md-3">
                        <label for="moyen_paiement" class="form-label">Moyen de Paiement</label>
                        <select class="form-select" name="moyen_paiement" id="moyen_paiement">
                            <option value="">Tous les moyens</option>
                            @foreach($moyens_paiement as $moyen)
                                <option value="{{ $moyen }}" @selected(request('moyen_paiement')===$moyen)>
                                    {{ str_replace('_', ' ', ucfirst($moyen)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="date_debut" class="form-label">Date de début</label>
                        <input type="date" class="form-control" name="date_debut" id="date_debut" value="{{ request('date_debut') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="date_fin" class="form-label">Date de fin</label>
                        <input type="date" class="form-control" name="date_fin" id="date_fin" value="{{ request('date_fin') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" name="search" id="search" 
                               placeholder="Nom, email, transaction..." value="{{ request('search') }}">
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify me-1"></i>Filtrer
                            </button>
                            <a href="{{ route('admin.donations.index') }}" class="btn btn-outline-secondary">
                                <i class="mdi mdi-refresh me-1"></i>Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th width="70">#ID</th>
                                <th width="130">Date</th>
                                <th>Utilisateur</th>
                                <th width="120">Montant</th>
                                <th width="140">Moyen</th>
                                <th width="100">Événement</th>
                                <th width="150">Transaction</th>
                                <th width="100">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dons as $don)
                                <tr>
                                    <td>
                                        <span class="badge bg-primary">#{{ $don->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $don->date_don?->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $don->date_don?->format('H:i') }}</small>
                                    </td>
                                    <td>
                                        @if($don->user)
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-success text-white rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px;">
                                                    {{ strtoupper(substr($don->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $don->user->name }}</div>
                                                    <small class="text-muted">{{ $don->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted fst-italic">Anonyme</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success fs-6">{{ number_format($don->montant, 2, ',', ' ') }} TND</span>
                                    </td>
                                    <td>
                                        @php
                                            $moyenClass = [
                                                'carte' => 'bg-info',
                                                'paypal' => 'bg-warning',
                                                'virement_bancaire' => 'bg-success',
                                                'paymee' => 'bg-primary'
                                            ][$don->moyen_paiement] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $moyenClass }}">
                                            {{ str_replace('_', ' ', ucfirst($don->moyen_paiement)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($don->event)
                                            <span class="badge bg-light text-dark">{{ $don->event->nom ?? $don->evenement_id }}</span>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($don->transaction_id)
                                            <code class="text-truncate d-block" style="max-width: 140px;" title="{{ $don->transaction_id }}">
                                                {{ Str::limit($don->transaction_id, 20) }}
                                            </code>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="mdi mdi-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="viewDonation({{ $don->id }})">
                                                    <i class="mdi mdi-eye me-1"></i>Voir détails
                                                </a></li>
                                                <li><a class="dropdown-item" href="#">
                                                    <i class="mdi mdi-email me-1"></i>Envoyer reçu
                                                </a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item text-danger" href="#">
                                                    <i class="mdi mdi-delete me-1"></i>Supprimer
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="mdi mdi-inbox display-4 d-block mb-2"></i>
                                            Aucune donation trouvée
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($dons->hasPages())
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $dons->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter une méthode de paiement -->
<div class="modal fade" id="addPaymentMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une Méthode de Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentMethodForm">
                    <div class="mb-3">
                        <label for="method_name" class="form-label">Nom de la méthode</label>
                        <input type="text" class="form-control" id="method_name" name="method_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="method_key" class="form-label">Clé technique</label>
                        <input type="text" class="form-control" id="method_key" name="method_key" required>
                        <div class="form-text">Ex: crypto_bitcoin, mobile_money, etc.</div>
                    </div>
                    <div class="mb-3">
                        <label for="method_description" class="form-label">Description</label>
                        <textarea class="form-control" id="method_description" name="method_description" rows="3"></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="method_active" name="method_active" checked>
                        <label class="form-check-label" for="method_active">
                            Activer cette méthode
                        </label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="savePaymentMethod()">Ajouter</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewDonation(id) {
    // Implémenter la vue des détails
    alert('Voir donation #' + id);
}

function savePaymentMethod() {
    // Implémenter l'ajout de méthode de paiement
    alert('Fonctionnalité à implémenter');
    $('#addPaymentMethodModal').modal('hide');
}
</script>
@endsection
