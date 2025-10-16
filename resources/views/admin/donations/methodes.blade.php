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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#methodEditorModal">
                        <i class="mdi mdi-plus me-1"></i>Ajouter Méthode
                    </button>
                </div>

                <div class="row g-4" id="methodsGrid">
                    @forelse($methodes as $methode)
                        <div class="col-lg-4 col-md-6">
                            <div class="card border" data-method-key="{{ $methode->key }}" data-method-type="{{ $methode->type }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                                            @if(!empty($methode->icon_path))
                                                @php
                                                    $src = $methode->icon_path;
                                                    if ($src && str_starts_with($src, 'public/')) {
                                                        $src = 'storage/' . substr($src, 7);
                                                    }
                                                @endphp
                                                <img src="{{ asset($src) }}" alt="icon" style="width:24px;height:24px;object-fit:cover;border-radius:4px;">
                                            @elseif(!empty($methode->icon))
                                                <i class="mdi {{ $methode->icon }}"></i>
                                            @endif
                                            {{ $methode->name }}
                                            @if(!$methode->active)
                                                <span class="badge bg-secondary ms-1">Inactif</span>
                                            @endif
                                        </h5>
                                        @php
                                            $badgeClass = [
                                                'carte' => 'bg-info',
                                                'paypal' => 'bg-warning',
                                                'virement_bancaire' => 'bg-success',
                                                'paymee' => 'bg-primary',
                                                'crypto' => 'bg-dark'
                                            ][$methode->key] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">
                                            {{ $methode->usage_count ?? 0 }} utilisations
                                        </span>
                                    </div>
                                    @if(!empty($methode->type))
                                        <div class="mb-2"><span class="badge bg-light text-dark">Type: {{ $methode->type }}</span></div>
                                    @endif
                                    
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Total collecté:</span>
                                            <strong class="text-success">{{ number_format($methode->total_amount ?? 0, 2, ',', ' ') }} TND</strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Moyenne par don:</span>
                                            @php $avg = ($methode->usage_count ?? 0) > 0 ? ($methode->total_amount ?? 0)/$methode->usage_count : 0; @endphp
                                            <strong>{{ number_format($avg, 2, ',', ' ') }} TND</strong>
                                        </div>
                                    </div>

                                    <div class="progress mb-3" style="height: 8px;">
                                        @php
                                            $totalGlobal = $methodes->sum('total_amount');
                                            $pourcentage = $totalGlobal > 0 ? (($methode->total_amount ?? 0) / $totalGlobal) * 100 : 0;
                                        @endphp
                                        <div class="progress-bar {{ $badgeClass }}" role="progressbar" 
                                             style="width: {{ $pourcentage }}%" 
                                             title="{{ number_format($pourcentage, 1) }}% du total">
                                        </div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" onclick="editMethod('{{ $methode->key }}')">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewMethodStats('{{ $methode->key }}')">
                                            <i class="mdi mdi-chart-line"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="disableMethod('{{ $methode->key }}')">
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
            <form id="addMethodForm" action="{{ route('admin.donations.methodes.store') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf
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
                        <label for="method_type" class="form-label">Type (flux)</label>
                        <select id="method_type" name="method_type" class="form-select">
                            <option value="">— Choisir —</option>
                            <option value="card">Carte</option>
                            <option value="paypal">PayPal</option>
                            <option value="bank_transfer">Virement bancaire</option>
                            <option value="paymee">Paymee (e‑DINAR)</option>
                            <option value="test">Test (mock)</option>
                            <option value="custom">Personnalisé</option>
                        </select>
                        <div class="form-text">Détermine le formulaire et le flux à afficher côté utilisateur</div>
                    </div>
                    <div class="mb-3">
                        <label for="method_icon" class="form-label">Icône</label>
                        <input type="text" class="form-control" id="method_icon" name="method_icon"
                               placeholder="Ex: mdi-credit-card, mdi-paypal...">
                        <div class="form-text">Nom de l'icône Material Design (optionnel) ou téléversez une image ci-dessous</div>
                    </div>
                    <div class="mb-3">
                        <label for="method_icon_file" class="form-label">Icône (image)</label>
                        <input type="file" class="form-control" id="method_icon_file" name="method_icon_file" accept="image/*">
                        <div class="form-text">PNG/JPG/SVG/WEBP, max 2 Mo</div>
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
async function editMethod(methodKey) {
    try {
        // Fetch method data
        const res = await fetch(`/admin/donations/methodes/${encodeURIComponent(methodKey)}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        let json;
        try { json = await res.json(); } catch(e) { json = { ok:false, error: `Réponse invalide (${res.status})` }; }
        if (!res.ok || !json.ok) throw new Error(json.error || (json.message ?? `Erreur HTTP ${res.status}`));
        
        const method = json.method;
        
        // Open modal
        const modal = new bootstrap.Modal(document.getElementById('methodEditorModal'));
        
        // Update modal title and button
        document.getElementById('modalTitleText').textContent = 'Modifier la Méthode de Paiement';
        document.getElementById('submitBtnText').textContent = 'Mettre à Jour';
        
        // Set form action and method
        const form = document.getElementById('methodEditorForm');
        form.action = `/admin/donations/methodes/${methodKey}`;
        document.getElementById('methodFormMethod').value = 'PUT';
        document.getElementById('editingMethodKey').value = methodKey;
        
        // Populate form fields
        form.querySelector('[name="method_name"]').value = method.name || '';
        form.querySelector('[name="method_key"]').value = method.key || '';
        form.querySelector('[name="method_type"]').value = method.type || '';
        form.querySelector('[name="method_description"]').value = method.description || '';
        form.querySelector('[name="method_active"]').checked = method.active;
        form.querySelector('[name="method_icon"]').value = method.icon || '';
        form.querySelector('[name="color_primary"]').value = method.color_primary || '#007bff';
        form.querySelector('[name="color_secondary"]').value = method.color_secondary || '#ffffff';
        form.querySelector('[name="button_text"]').value = method.button_text || 'Pay now';
        form.querySelector('[name="custom_css"]').value = method.custom_css || '';
        form.querySelector('[name="instructions_html"]').value = method.instructions_html || '';
        
        // Populate custom fields
        if (method.custom_form_fields) {
            const fieldsData = typeof method.custom_form_fields === 'string' 
                ? JSON.parse(method.custom_form_fields) 
                : method.custom_form_fields;
            if (Array.isArray(fieldsData) && fieldsData.length > 0) {
                // Access the global fields array from the modal's script
                window.fields = fieldsData.map((f, i) => ({
                    id: f.id || (i + 1),
                    label: f.label || '',
                    type: f.type || 'text',
                    placeholder: f.placeholder || '',
                    required: f.required || false
                }));
                if (typeof window.renderFields === 'function') window.renderFields();
                // Ensure hidden JSON is updated
                const hiddenInput = document.getElementById('customFormFieldsInput');
                if (hiddenInput) hiddenInput.value = JSON.stringify(window.fields);
            }
        }
        
        // Show existing icon if present
        if (method.icon_path) {
            const iconPreview = form.querySelector('#iconPreview');
            if (iconPreview) {
                let src = method.icon_path;
                if (src?.startsWith('public/')) src = 'storage/' + src.substring(7);
                if (!src?.startsWith('http') && !src?.startsWith('/')) src = '/' + src;
                iconPreview.innerHTML = `<img src="${src}" style="max-width:100px;max-height:100px;border-radius:8px;">`;
            }
        }
        
        // Update live preview
        if (typeof window.updatePreview === 'function') window.updatePreview();
        
        modal.show();
    } catch (err) {
        console.error(err);
        alert('❌ Erreur (chargement): ' + (err?.message || err));
    }
}

// Reset modal to create mode when opening via "Add" button
document.querySelector('[data-bs-target="#methodEditorModal"]')?.addEventListener('click', function() {
    const form = document.getElementById('methodEditorForm');
    form.action = '{{ route('admin.donations.methodes.store') }}';
    document.getElementById('methodFormMethod').value = '';
    document.getElementById('editingMethodKey').value = '';
    document.getElementById('modalTitleText').textContent = 'Créer une Méthode de Paiement Personnalisée';
    document.getElementById('submitBtnText').textContent = 'Créer la Méthode';
    form.reset();
    
    // Clear custom fields
    if (window.fields) {
        window.fields = [];
        if (typeof window.renderFields === 'function') window.renderFields();
    }
    
    // Clear icon preview
    const iconPreview = form.querySelector('#iconPreview');
    if (iconPreview) iconPreview.innerHTML = '';
    
    if (typeof window.updatePreview === 'function') window.updatePreview();
});

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

document.getElementById('addMethodForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const url = form.getAttribute('action');
    const formData = new FormData(form);
    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const json = await res.json();
        if (!res.ok || !json.ok) throw new Error(json.error || 'Erreur lors de l\'ajout');
        alert('Méthode ajoutée: ' + json.method.name);
        bootstrap.Modal.getInstance(document.getElementById('addMethodModal')).hide();
        form.reset();
        // Optionally, refresh page to reflect new method stats
        location.reload();
    } catch (err) {
        alert('Erreur: ' + err.message);
    }
});
</script>

@include('admin.donations.method-editor-modal')

@endsection