@extends('layouts.admin')
@section('title', 'Méthodes de Paiement')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-credit-card me-2"></i>Méthodes de Paiement
                    </h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                        <i class="mdi mdi-plus me-1"></i>Ajouter Méthode
                    </button>
                </div>

                <div class="row g-4">
                    @forelse($methodes as $methode)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0">
                                            {{ str_replace('_', ' ', ucfirst($methode->moyen_paiement)) }}
                                        </h5>
                                        @php
                                            $badgeClass = [
                                                'carte' => 'bg-info',
                                                'paypal' => 'bg-warning',
                                                'virement_bancaire' => 'bg-success',
                                                'paymee' => 'bg-primary',
                                                'crypto' => 'bg-dark'
                                            ][strtolower($methode->moyen_paiement)] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $methode->usage_count }} utilisations
                                        </span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Total collecté:</span>
                                            <strong class="text-success">{{ number_format($methode->total_amount, 2, ',', ' ') }} TND</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Moyenne par don:</span>
                                            <strong>{{ number_format($methode->total_amount / $methode->usage_count, 2, ',', ' ') }} TND</strong>
                                        </div>
                                    </div>

                                    <div class="progress mb-3" style="height: 8px;">
                                        @php
                                            $totalGlobal = $methodes->sum('total_amount');
                                            $pourcentage = $totalGlobal > 0 ? ($methode->total_amount / $totalGlobal) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar {{ $badgeClass }}" role="progressbar" 
                                             style="width: {{ $pourcentage }}%" 
                                             title="{{ number_format($pourcentage, 1) }}% du total">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editMethod('{{ $methode->moyen_paiement }}')">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewMethodStats('{{ $methode->moyen_paiement }}')">
                                            <i class="mdi mdi-chart-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="disableMethod('{{ $methode->moyen_paiement }}')">
                                            <i class="mdi mdi-eye-off"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="mdi mdi-credit-card-off display-3 text-muted"></i>
                                <h5 class="mt-3 text-muted">Aucune méthode de paiement</h5>
                                <p class="text-muted">Commencez par ajouter une méthode de paiement</p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMethodModal">
                                    <i class="mdi mdi-plus me-1"></i>Ajouter la première méthode
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Méthodes de paiement suggérées -->
                <div class="row mt-5">
                    <div class="col-12">
                        <h5 class="mb-3">Méthodes Suggérées à Ajouter</h5>
                        <div class="row g-3">
                            @php
                                $suggestions = [
                                    'crypto_bitcoin' => ['nom' => 'Bitcoin (BTC)', 'icon' => 'mdi-bitcoin', 'color' => 'warning'],
                                    'crypto_ethereum' => ['nom' => 'Ethereum (ETH)', 'icon' => 'mdi-ethereum', 'color' => 'info'],
                                    'mobile_money' => ['nom' => 'Mobile Money', 'icon' => 'mdi-cellphone', 'color' => 'success'],
                                    'western_union' => ['nom' => 'Western Union', 'icon' => 'mdi-bank-transfer', 'color' => 'primary'],
                                    'cheque' => ['nom' => 'Chèque', 'icon' => 'mdi-checkbook', 'color' => 'secondary']
                                ];
                            @endphp
                            
                            @foreach($suggestions as $key => $suggestion)
                                <div class="col-lg-2 col-md-4 col-6">
                                    <div class="card border-dashed text-center p-3" style="border-style: dashed !important;">
                                        <i class="{{ $suggestion['icon'] }} display-6 text-{{ $suggestion['color'] }}"></i>
                                        <h6 class="mt-2 mb-2">{{ $suggestion['nom'] }}</h6>
                                        <button class="btn btn-sm btn-outline-{{ $suggestion['color'] }}" 
                                                onclick="addSuggestedMethod('{{ $key }}', '{{ $suggestion['nom'] }}')">
                                            <i class="mdi mdi-plus"></i> Ajouter
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Méthode -->
<div class="modal fade" id="addMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter une Méthode de Paiement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addMethodForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="method_name" class="form-label">Nom d'affichage</label>
                        <input type="text" class="form-control" id="method_name" name="method_name" required
                               placeholder="Ex: Carte Bancaire, PayPal...">
                    </div>
                    <div class="mb-3">
                        <label for="method_key" class="form-label">Clé technique</label>
                        <input type="text" class="form-control" id="method_key" name="method_key" required
                               placeholder="Ex: carte, paypal, crypto_bitcoin...">
                        <div class="form-text">Cette clé sera utilisée dans le système (sans espaces, en minuscules)</div>
                    </div>
                    <div class="mb-3">
                        <label for="method_icon" class="form-label">Icône</label>
                        <input type="text" class="form-control" id="method_icon" name="method_icon"
                               placeholder="Ex: mdi-credit-card, mdi-paypal...">
                        <div class="form-text">Nom de l'icône Material Design (optionnel)</div>
                    </div>
                    <div class="mb-3">
                        <label for="method_description" class="form-label">Description</label>
                        <textarea class="form-control" id="method_description" name="method_description" rows="3"
                                  placeholder="Description de la méthode de paiement..."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="method_active" name="method_active" checked>
                        <label class="form-check-label" for="method_active">
                            Activer immédiatement cette méthode
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-plus me-1"></i>Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editMethod(methodKey) {
    alert('Modifier la méthode: ' + methodKey);
}

function viewMethodStats(methodKey) {
    alert('Statistiques pour: ' + methodKey);
}

function disableMethod(methodKey) {
    if (confirm('Êtes-vous sûr de vouloir désactiver cette méthode de paiement?')) {
        alert('Méthode désactivée: ' + methodKey);
    }
}

function addSuggestedMethod(key, name) {
    document.getElementById('method_key').value = key;
    document.getElementById('method_name').value = name;
    
    // Ouvrir le modal
    new bootstrap.Modal(document.getElementById('addMethodModal')).show();
}

document.getElementById('addMethodForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    console.log('Nouvelle méthode:', data);
    alert('Méthode ajoutée avec succès: ' + data.method_name);
    
    // Fermer le modal et reset le form
    bootstrap.Modal.getInstance(document.getElementById('addMethodModal')).hide();
    this.reset();
});
</script>
@endsection