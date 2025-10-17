@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- En-tête moderne -->
    <div class="page-header mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="page-title mb-2">Créer un Utilisateur</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.utilisateurs.index') }}">Utilisateurs</a></li>
                        <li class="breadcrumb-item active">Créer</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-light btn-modern">
                    <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                </a>
            </div>
        </div>
    </div>

    <!-- Messages d'alerte -->
    @if($errors->any())
        <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <strong>Erreurs de validation :</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert">
            <div class="alert-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="alert-content">{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Formulaire principal -->
        <div class="col-lg-8">
            <div class="card card-modern">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user-edit me-2"></i>Informations de l'utilisateur
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.utilisateurs.store') }}" id="createUserForm">
                        @csrf
                        
                        <!-- Informations de base -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="fas fa-id-card me-2"></i>Informations de base
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        Nom complet <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-user input-icon"></i>
                                        <input type="text" 
                                               class="form-control ps-5 @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               placeholder="Ex: Mohamed Ben Ali"
                                               required 
                                               autofocus>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        Adresse email <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-envelope input-icon"></i>
                                        <input type="email" 
                                               class="form-control ps-5 @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="exemple@mail.com" 
                                               required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sécurité -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="fas fa-lock me-2"></i>Sécurité
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label">
                                        Mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" 
                                               class="form-control ps-5 @error('password') is-invalid @enderror" 
                                               id="password" 
                                               name="password"
                                               placeholder="••••••••" 
                                               required>
                                        <button type="button" class="toggle-password" data-target="password">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Minimum 6 caractères
                                    </small>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmer le mot de passe <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-key input-icon"></i>
                                        <input type="password" 
                                               class="form-control ps-5" 
                                               id="password_confirmation" 
                                               name="password_confirmation"
                                               placeholder="••••••••" 
                                               required>
                                        <button type="button" class="toggle-password" data-target="password_confirmation">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rôle et Statut -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="fas fa-cog me-2"></i>Rôle et Statut
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="role" class="form-label">
                                        Rôle <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="">-- Sélectionner un rôle --</option>
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>
                                            👤 Utilisateur
                                        </option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            🛡️ Administrateur
                                        </option>
                                        <option value="association" {{ old('role') == 'association' ? 'selected' : '' }}>
                                            🏢 Association
                                        </option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label class="form-label">Statut du compte</label>
                                    <div class="status-toggle-group">
                                        <div class="status-toggle">
                                            <input class="status-input" type="radio" name="is_blocked" id="active" value="0" checked>
                                            <label class="status-label status-success" for="active">
                                                <i class="fas fa-check-circle me-2"></i>Actif
                                            </label>
                                        </div>
                                        <div class="status-toggle">
                                            <input class="status-input" type="radio" name="is_blocked" id="blocked" value="1" {{ old('is_blocked') == '1' ? 'checked' : '' }}>
                                            <label class="status-label status-danger" for="blocked">
                                                <i class="fas fa-ban me-2"></i>Bloqué
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="form-section">
                            <h6 class="form-section-title">
                                <i class="fas fa-info-circle me-2"></i>Informations supplémentaires
                                <small class="text-muted fw-normal">(optionnel)</small>
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-phone input-icon"></i>
                                        <input type="tel" 
                                               class="form-control ps-5 @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}"
                                               placeholder="+216 XX XXX XXX">
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="address" class="form-label">Adresse</label>
                                    <div class="input-icon-wrapper">
                                        <i class="fas fa-map-marker-alt input-icon"></i>
                                        <input type="text" 
                                               class="form-control ps-5 @error('address') is-invalid @enderror" 
                                               id="address" 
                                               name="address" 
                                               value="{{ old('address') }}"
                                               placeholder="Tunis, Tunisie">
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="notes" class="form-label">Notes</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3"
                                          placeholder="Ajoutez des notes concernant cet utilisateur...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-modern">
                                <i class="fas fa-save me-2"></i>Créer l'utilisateur
                            </button>
                            <a href="{{ route('admin.utilisateurs.index') }}" class="btn btn-light btn-modern">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panneau d'informations -->
        <div class="col-lg-4">
            <!-- Guide des rôles -->
            <div class="card card-modern info-card mb-3">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-user-tag me-2"></i>Rôles disponibles
                    </h5>
                </div>
                <div class="card-body">
                    <div class="role-info-item">
                        <div class="role-badge role-badge-user">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="role-details">
                            <h6>Utilisateur</h6>
                            <p>Accès basique aux fonctionnalités du système</p>
                        </div>
                    </div>
                    
                    <div class="role-info-item">
                        <div class="role-badge role-badge-admin">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="role-details">
                            <h6>Administrateur</h6>
                            <p>Accès complet avec privilèges de gestion</p>
                        </div>
                    </div>
                    
                    <div class="role-info-item">
                        <div class="role-badge role-badge-association">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="role-details">
                            <h6>Association</h6>
                            <p>Accès partenaire pour les organisations</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Guide de sécurité -->
            <div class="card card-modern info-card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="fas fa-shield-alt me-2"></i>Sécurité
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="security-list">
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Mot de passe minimum 6 caractères
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Email doit être unique
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Validation immédiate du compte
                        </li>
                        <li>
                            <i class="fas fa-check text-success me-2"></i>
                            Notification par email
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Astuce -->
            <div class="alert alert-modern alert-info mt-3">
                <div class="alert-icon">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="alert-content">
                    <strong>Astuce :</strong> Les utilisateurs recevront un email de bienvenue avec leurs identifiants de connexion.
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('createUserForm');
        const emailInput = document.getElementById('email');
        const roleSelect = document.getElementById('role');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Validation email en temps réel
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            if (email && !isValidEmail(email)) {
                this.classList.add('is-invalid');
                showInputError(this, 'Format d\'email invalide');
            } else {
                this.classList.remove('is-invalid');
                removeInputError(this);
            }
        });
        
        // Vérification des mots de passe identiques
        passwordConfirmInput.addEventListener('blur', function() {
            if (this.value && this.value !== passwordInput.value) {
                this.classList.add('is-invalid');
                showInputError(this, 'Les mots de passe ne correspondent pas');
            } else {
                this.classList.remove('is-invalid');
                removeInputError(this);
            }
        });
        
        // Confirmation avant soumission
        form.addEventListener('submit', function(e) {
            const email = emailInput.value;
            const role = roleSelect.value;
            
            if (!email || !role) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires.');
                return;
            }
            
            // Confirmation pour admin
            if (role === 'admin') {
                e.preventDefault();
                if (confirm('⚠️ Création d\'un compte administrateur\n\nLes administrateurs ont accès à toutes les fonctionnalités du système.\n\nConfirmer la création ?')) {
                    form.submit();
                }
            }
        });
        
        // Fonctions utilitaires
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        function showInputError(input, message) {
            let feedback = input.parentElement.parentElement.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block';
                input.parentElement.parentElement.appendChild(feedback);
            }
            feedback.textContent = message;
        }
        
        function removeInputError(input) {
            const feedback = input.parentElement.parentElement.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        }
    });
</script>
@endsection

@section('styles')
<!-- FontAwesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Variables */
    :root {
        --primary: #667eea;
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

    /* Sections de formulaire */
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 2rem;
        border-bottom: 1px solid var(--border);
    }

    .form-section:last-of-type {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .form-section-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
    }

    /* Inputs avec icônes */
    .input-icon-wrapper {
        position: relative;
    }

    .input-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #858796;
        pointer-events: none;
        z-index: 5;
    }

    .toggle-password {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #858796;
        cursor: pointer;
        padding: 0.25rem;
        transition: color 0.2s;
    }

    .toggle-password:hover {
        color: var(--primary);
    }

    /* Form controls */
    .form-control, .form-select {
        border-radius: 0.5rem;
        border: 1px solid var(--border);
        padding: 0.625rem 1rem;
        transition: all 0.2s;
        font-size: 0.95rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    }

    .form-label {
        font-weight: 600;
        color: var(--dark);
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .form-text {
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Status toggles */
    .status-toggle-group {
        display: flex;
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .status-toggle {
        flex: 1;
    }

    .status-input {
        display: none;
    }

    .status-label {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border-radius: 0.5rem;
        border: 2px solid var(--border);
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .status-success {
        color: #858796;
    }

    .status-danger {
        color: #858796;
    }

    .status-input:checked + .status-label.status-success {
        background: #d4f4e7;
        border-color: var(--success);
        color: var(--success);
    }

    .status-input:checked + .status-label.status-danger {
        background: #ffe6e3;
        border-color: var(--danger);
        color: var(--danger);
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

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }

    /* Form actions */
    .form-actions {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid var(--border);
        display: flex;
        gap: 0.75rem;
    }

    /* Info cards */
    .info-card .card-body {
        padding: 1rem;
    }

    .role-info-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        border-radius: 0.5rem;
        margin-bottom: 0.75rem;
        transition: background 0.2s;
    }

    .role-info-item:hover {
        background: var(--light);
    }

    .role-info-item:last-child {
        margin-bottom: 0;
    }

    .role-badge {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        color: white;
        flex-shrink: 0;
    }

    .role-badge-user {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .role-badge-admin {
        background: linear-gradient(135deg, #1cc88a 0%, #17a673 100%);
    }

    .role-badge-association {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }

    .role-details h6 {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .role-details p {
        font-size: 0.8rem;
        color: #858796;
        margin: 0;
    }

    /* Security list */
    .security-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .security-list li {
        padding: 0.625rem 0;
        font-size: 0.875rem;
        color: #858796;
        display: flex;
        align-items: center;
    }

    /* Alertes modernes */
    .alert-modern {
        border-radius: 0.75rem;
        border: none;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        box-shadow: var(--shadow-sm);
    }

    .alert-icon {
        font-size: 1.5rem;
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .alert-content {
        flex: 1;
        font-size: 0.875rem;
    }

    .alert-content strong {
        display: block;
        margin-bottom: 0.25rem;
    }

    .alert-content ul {
        margin-bottom: 0;
        padding-left: 1.25rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .col-lg-4 {
            margin-top: 1rem;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .status-toggle-group {
            flex-direction: column;
        }

        .form-actions {
            flex-direction: column;
        }

        .form-actions .btn {
            width: 100%;
        }
    }
</style>
@endsection