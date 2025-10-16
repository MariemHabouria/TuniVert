{{-- Rich Payment Method Editor Modal --}}
<div class="modal fade" id="methodEditorModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="mdi mdi-palette me-2"></i>
                    <span id="modalTitleText">Créer une Méthode de Paiement Personnalisée</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="methodEditorForm" action="{{ route('admin.donations.methodes.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="methodFormMethod" name="_method" value="">
                <input type="hidden" id="editingMethodKey" value="">
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Panel: Form Editor -->
                        <div class="col-lg-6">
                            <h6 class="mb-3 fw-bold text-primary">Configuration</h6>
                            
                            <!-- Basic Info -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Informations de Base</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nom d'affichage <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="method_name" required placeholder="Ex: Carte Visa/Mastercard">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Clé technique <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="method_key" required placeholder="Ex: visa_card">
                                        <small class="form-text text-muted">Minuscules, chiffres, underscore/tiret uniquement</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Type de flux</label>
                                        <select name="method_type" class="form-select">
                                            <option value="">— Sélectionner —</option>
                                            <option value="card">Carte bancaire</option>
                                            <option value="paypal">PayPal</option>
                                            <option value="bank_transfer">Virement bancaire</option>
                                            <option value="paymee">Paymee (e-DINAR)</option>
                                            <option value="custom">Personnalisé</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" name="method_description" rows="2" placeholder="Courte description..."></textarea>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="method_active" id="method_active" checked>
                                        <label class="form-check-label" for="method_active">Activer cette méthode</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Design & Branding -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Design & Branding</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Couleur principale</label>
                                            <input type="color" class="form-control form-control-color w-100" name="color_primary" value="#007bff" title="Couleur principale">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Couleur secondaire</label>
                                            <input type="color" class="form-control form-control-color w-100" name="color_secondary" value="#ffffff" title="Couleur secondaire">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Logo/Icône</label>
                                        <input type="file" class="form-control" name="method_icon_file" accept="image/*" id="iconUpload">
                                        <small class="form-text text-muted">PNG, JPG, SVG (max 4 Mo)</small>
                                        <div id="iconPreview" class="mt-2"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Ou icône Material Design</label>
                                        <input type="text" class="form-control" name="method_icon" placeholder="mdi-credit-card">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Texte du bouton</label>
                                        <input type="text" class="form-control" name="button_text" value="Pay now" placeholder="Pay now">
                                    </div>
                                </div>
                            </div>

                            <!-- Custom Form Fields -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Champs de Formulaire Personnalisés</h6>
                                    <div id="customFieldsBuilder" class="mb-3">
                                        <div class="alert alert-info small">
                                            <i class="mdi mdi-information me-1"></i>
                                            Ajoutez des champs supplémentaires (numéro de compte, téléphone, etc.)
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-primary" id="addFieldBtn">
                                            <i class="mdi mdi-plus"></i> Ajouter un champ
                                        </button>
                                        <div id="fieldsList" class="mt-3"></div>
                                    </div>
                                    <input type="hidden" name="custom_form_fields" id="customFormFieldsInput">
                                </div>
                            </div>

                            <!-- Custom CSS & Instructions -->
                            <div class="card shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title text-muted mb-3">Personnalisation Avancée</h6>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Instructions HTML</label>
                                        <textarea class="form-control font-monospace" name="instructions_html" rows="4" placeholder="<p>Veuillez entrer vos informations...</p>"></textarea>
                                        <small class="form-text text-muted">HTML affiché avant le formulaire</small>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">CSS personnalisé</label>
                                        <textarea class="form-control font-monospace" name="custom_css" rows="4" placeholder=".custom-method-card { border-radius: 10px; }"></textarea>
                                        <small class="form-text text-muted">CSS spécifique à cette méthode</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Panel: Live Preview -->
                        <div class="col-lg-6">
                            <h6 class="mb-3 fw-bold text-primary">Aperçu en Direct</h6>
                            <div class="card shadow-lg border-0" style="position: sticky; top: 20px;">
                                <div class="card-body">
                                    <h6 class="text-muted mb-3">Vue utilisateur</h6>
                                    <div id="livePreview" class="p-4 bg-light rounded">
                                        <div class="text-center text-muted py-5">
                                            <i class="mdi mdi-eye-outline display-4"></i>
                                            <p class="mt-2">L'aperçu s'affichera ici</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close me-1"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitMethodBtn">
                        <i class="mdi mdi-check me-1"></i><span id="submitBtnText">Créer la Méthode</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    // Make fields array global for edit access
    window.fields = [];
    const fieldsList = document.getElementById('fieldsList');
    const addBtn = document.getElementById('addFieldBtn');
    const hiddenInput = document.getElementById('customFormFieldsInput');
    const preview = document.getElementById('livePreview');
    const form = document.getElementById('methodEditorForm');

    // Ensure hidden JSON has a default value
    if (hiddenInput && (!hiddenInput.value || hiddenInput.value.trim() === '')) {
        hiddenInput.value = '[]';
    }

    // Add custom field
    addBtn?.addEventListener('click', () => {
        const id = 'field_' + Date.now();
        const field = {
            id,
            label: 'New Field',
            type: 'text',
            required: false,
            placeholder: ''
        };
        window.fields.push(field);
        renderFields();
        updatePreview();
    });

    function renderFields() {
        if (!fieldsList) return;
        fieldsList.innerHTML = window.fields.map((f, idx) => `
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong class="text-primary">${f.label}</strong>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="window.removeField(${idx})">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="text" class="form-control form-control-sm" placeholder="Label" value="${f.label}" onchange="window.updateField(${idx}, 'label', this.value)">
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" onchange="window.updateField(${idx}, 'type', this.value)">
                                <option value="text" ${f.type==='text'?'selected':''}>Text</option>
                                <option value="email" ${f.type==='email'?'selected':''}>Email</option>
                                <option value="tel" ${f.type==='tel'?'selected':''}>Téléphone</option>
                                <option value="number" ${f.type==='number'?'selected':''}>Nombre</option>
                            </select>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control form-control-sm" placeholder="Placeholder" value="${f.placeholder}" onchange="window.updateField(${idx}, 'placeholder', this.value)">
                        </div>
                        <div class="col-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" ${f.required?'checked':''} onchange="window.updateField(${idx}, 'required', this.checked)">
                                <label class="form-check-label small">Requis</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
        hiddenInput.value = JSON.stringify(window.fields);
    }
    
    // Make renderFields global for edit access
    window.renderFields = renderFields;

    window.updateField = (idx, key, val) => {
        window.fields[idx][key] = val;
        renderFields();
        updatePreview();
    };

    window.removeField = (idx) => {
        window.fields.splice(idx, 1);
        renderFields();
        updatePreview();
    };

    // Live preview - make global for edit access
    window.updatePreview = function updatePreview() {
        const name = form.querySelector('[name="method_name"]')?.value || 'Ma Méthode';
        const desc = form.querySelector('[name="method_description"]')?.value || '';
        const colorPrimary = form.querySelector('[name="color_primary"]')?.value || '#007bff';
        const colorSecondary = form.querySelector('[name="color_secondary"]')?.value || '#ffffff';
        const buttonText = form.querySelector('[name="button_text"]')?.value || 'Pay now';
        const instructions = form.querySelector('[name="instructions_html"]')?.value || '';

        const html = `
            <div class="card border-0 shadow-sm" style="border-left: 4px solid ${colorPrimary} !important;">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0 me-3">
                            <div style="width:48px;height:48px;background:${colorPrimary};border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                <i class="mdi mdi-credit-card text-white" style="font-size:24px;"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1" style="color:${colorPrimary};">${name}</h5>
                            ${desc ? `<p class="text-muted small mb-0">${desc}</p>` : ''}
                        </div>
                    </div>
                    ${instructions ? `<div class="alert alert-info small mb-3">${instructions}</div>` : ''}
                    ${window.fields.map(f => `
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">${f.label}${f.required?' <span class="text-danger">*</span>':''}</label>
                            <input type="${f.type}" class="form-control" placeholder="${f.placeholder}" ${f.required?'required':''}>
                        </div>
                    `).join('')}
                    <button type="button" class="btn w-100 text-white fw-semibold" style="background-color:${colorPrimary};">
                        ${buttonText}
                    </button>
                </div>
            </div>
        `;
        preview.innerHTML = html;
    };

    // Listen to form changes
    form?.addEventListener('input', window.updatePreview);
    form?.addEventListener('change', window.updatePreview);

    // Icon preview
    document.getElementById('iconUpload')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                document.getElementById('iconPreview').innerHTML = 
                    `<img src="${ev.target.result}" alt="icon" class="img-thumbnail" style="max-width:100px;max-height:100px;">`;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submit
    form?.addEventListener('submit', async function(e) {
        e.preventDefault();
        // Ensure custom fields JSON is valid (use [] when empty)
        try {
            const current = Array.isArray(window.fields) ? window.fields : [];
            if (hiddenInput) hiddenInput.value = JSON.stringify(current);
        } catch(_) { if (hiddenInput) hiddenInput.value = '[]'; }
        const formData = new FormData(this);
        
        // Get the HTTP method (PUT for update, POST for create)
        const httpMethod = document.getElementById('methodFormMethod').value || 'POST';
        const editingKey = document.getElementById('editingMethodKey').value;
        
        // For PUT requests, we need to use POST with _method override
        if (httpMethod === 'PUT') {
            formData.set('_method', 'PUT');
        }
        
        try {
            const res = await fetch(this.action, {
                method: 'POST', // Always use POST, Laravel will handle _method
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            let json;
            try { json = await res.json(); } catch(e) { json = { ok:false, error:`Réponse invalide (${res.status})` }; }
            if (!res.ok || !json.ok) {
                // Try to extract Laravel validation errors
                if (json?.errors) {
                    const lines = [];
                    for (const [field, msgs] of Object.entries(json.errors)) {
                        (Array.isArray(msgs)?msgs:[msgs]).forEach(m=>lines.push(`- ${field}: ${m}`));
                    }
                    throw new Error(`Validation échouée:\n${lines.join('\n')}`);
                }
                throw new Error(json.error || json.message || `Erreur HTTP ${res.status}`);
            }
            
            const action = httpMethod === 'PUT' ? 'modifiée' : 'créée';
            alert('✅ Méthode ' + action + ' avec succès: ' + json.method.name);
            bootstrap.Modal.getInstance(document.getElementById('methodEditorModal')).hide();
            location.reload();
        } catch (err) {
            alert('❌ Erreur: ' + err.message);
        }
    });

    // Initialize
    window.updatePreview();
})();
</script>

<style>
#methodEditorModal .modal-dialog {
    max-width: 1400px;
}
#methodEditorModal .card {
    transition: all 0.3s;
}
#methodEditorModal .card:hover {
    transform: translateY(-2px);
}
#livePreview {
    min-height: 400px;
}
</style>
