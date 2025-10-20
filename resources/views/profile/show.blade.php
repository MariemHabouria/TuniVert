<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Mon Profil - TuniVert</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/lightbox/css/lightbox.min.css') }}" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    @include('layouts.navbar')
    <div style="height: 120px"></div>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="text-white display-3 mb-4">Mon Profil</h3>
            <p class="fs-5 text-white mb-4">Gérez vos informations personnelles et paramètres de compte</p>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item active text-white">Mon Profil</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Profile Section Start -->
    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profile Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center p-4">
                        <!-- Avatar -->
                        <div class="position-relative d-inline-block mb-3">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" 
                                     style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar">
                            @else
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                     style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: 600;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            @if($user->role === 'association')
                                <span class="position-absolute bottom-0 end-0 bg-success rounded-circle p-2" title="Association vérifiée">
                                    <i class="fas fa-check text-white"></i>
                                </span>
                            @endif
                        </div>

                        <h4 class="card-title mb-1">{{ $user->name }}</h4>
                        <span class="badge bg-{{ $user->role === 'association' ? 'success' : 'primary' }} mb-3">
                            {{ $user->role === 'association' ? 'Association' : 'Utilisateur' }}
                        </span>

                        @if($user->bio)
                            <p class="text-muted mb-3">{{ $user->bio }}</p>
                        @endif

                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Modifier le profil
                            </a>
                        </div>

                        <hr class="my-4">

                        <!-- Quick Stats -->
                        <div class="row text-center">
                            @if($user->role === 'association')
                                <div class="col-4">
                                    <div class="h5 text-primary mb-0">{{ $stats['formations_created'] }}</div>
                                    <small class="text-muted">Formations</small>
                                </div>
                                <div class="col-4">
                                    <div class="h5 text-success mb-0">{{ $stats['events_created'] }}</div>
                                    <small class="text-muted">Événements</small>
                                </div>
                                <div class="col-4">
                                    <div class="h5 text-info mb-0">{{ $stats['total_participants'] }}</div>
                                    <small class="text-muted">Participants</small>
                                </div>
                            @else
                                <div class="col-4">
                                    <div class="h5 text-primary mb-0">{{ $stats['formations_enrolled'] }}</div>
                                    <small class="text-muted">Formations</small>
                                </div>
                                <div class="col-4">
                                    <div class="h5 text-success mb-0">{{ number_format($stats['total_donated'], 0) }} TND</div>
                                    <small class="text-muted">Donations</small>
                                </div>
                                <div class="col-4">
                                    <div class="h5 text-warning mb-0">{{ $stats['points_earned'] }}</div>
                                    <small class="text-muted">Points</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Details -->
            <div class="col-lg-8">
                <div class="row g-4">
                    <!-- Personal Information -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-user text-primary me-2"></i>Informations Personnelles
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Nom complet</label>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Email</label>
                                        <div class="fw-bold">{{ $user->email }}</div>
                                    </div>
                                    @if($user->phone)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small">Téléphone</label>
                                            <div class="fw-bold">{{ $user->phone }}</div>
                                        </div>
                                    @endif
                                    @if($user->role === 'association' && $user->matricule_fiscale)
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label text-muted small">Matricule Fiscale</label>
                                            <div class="fw-bold">{{ $user->matricule_fiscale }}</div>
                                        </div>
                                    @endif
                                    @if($user->adresse)
                                        <div class="col-12 mb-3">
                                            <label class="form-label text-muted small">Adresse</label>
                                            <div class="fw-bold">{{ $user->adresse }}</div>
                                        </div>
                                    @endif
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Membre depuis</label>
                                        <div class="fw-bold">{{ $user->created_at->format('d F Y') }}</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label text-muted small">Statut du compte</label>
                                        <div>
                                            <span class="badge bg-{{ $user->is_active !== false ? 'success' : 'danger' }}">
                                                {{ $user->is_active !== false ? 'Actif' : 'Désactivé' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Statistics -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-chart-bar text-primary me-2"></i>Statistiques d'Activité
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($user->role === 'association')
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-graduation-cap"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-primary">{{ $stats['formations_created'] }}</div>
                                                    <small class="text-muted">Formations créées</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-success">{{ $stats['events_created'] }}</div>
                                                    <small class="text-muted">Événements organisés</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-users"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-info">{{ $stats['total_participants'] }}</div>
                                                    <small class="text-muted">Total participants</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-primary">{{ $stats['formations_enrolled'] }}</div>
                                                    <small class="text-muted">Formations suivies</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-donate"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-success">{{ $stats['donations_made'] }}</div>
                                                    <small class="text-muted">Donations faites</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-coins"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-warning">{{ number_format($stats['total_donated'], 0) }}</div>
                                                    <small class="text-muted">TND donnés</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                <div>
                                                    <div class="h4 mb-0 text-info">{{ $stats['points_earned'] }}</div>
                                                    <small class="text-muted">Points gagnés</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light border-0">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cog text-primary me-2"></i>Actions Rapides
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-edit me-2"></i>Modifier le profil
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-warning w-100" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            <i class="fas fa-key me-2"></i>Changer le mot de passe
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="{{ route('donations.history') }}" class="btn btn-outline-success w-100">
                                            <i class="fas fa-history me-2"></i>Historique des donations
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#dangerZoneModal">
                                            <i class="fas fa-exclamation-triangle me-2"></i>Zone de danger
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Section End -->

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key text-warning me-2"></i>Changer le mot de passe
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Danger Zone Modal -->
    <div class="modal fade" id="dangerZoneModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Zone de Danger
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="fas fa-warning me-2"></i>
                        <strong>Attention !</strong> Ces actions sont irréversibles.
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-warning" data-bs-toggle="collapse" data-bs-target="#deactivateForm">
                            <i class="fas fa-pause me-2"></i>Désactiver mon compte
                        </button>
                        
                        <div class="collapse" id="deactivateForm">
                            <form action="{{ route('profile.deactivate') }}" method="POST" class="mt-3 p-3 border rounded">
                                @csrf
                                <p class="text-muted small">Votre compte sera désactivé et vous serez déconnecté. Vous pourrez le réactiver en vous connectant à nouveau.</p>
                                <div class="mb-3">
                                    <label for="deactivate_password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="deactivate_password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="deactivate_confirmation" class="form-label">Tapez "DÉSACTIVER" pour confirmer</label>
                                    <input type="text" class="form-control" id="deactivate_confirmation" name="confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-warning w-100">Désactiver mon compte</button>
                            </form>
                        </div>
                        
                        <button class="btn btn-outline-danger" data-bs-toggle="collapse" data-bs-target="#deleteForm">
                            <i class="fas fa-trash me-2"></i>Supprimer définitivement mon compte
                        </button>
                        
                        <div class="collapse" id="deleteForm">
                            <form action="{{ route('profile.delete') }}" method="POST" class="mt-3 p-3 border border-danger rounded">
                                @csrf
                                @method('DELETE')
                                <p class="text-danger small">⚠️ Cette action supprimera définitivement votre compte et toutes vos données. Cette action ne peut pas être annulée !</p>
                                <div class="mb-3">
                                    <label for="delete_password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="delete_password" name="password" required>
                                </div>
                                <div class="mb-3">
                                    <label for="delete_confirmation" class="form-label">Tapez "SUPPRIMER" pour confirmer</label>
                                    <input type="text" class="form-control" id="delete_confirmation" name="confirmation" required>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Supprimer définitivement mon compte</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/easing/easing.min.js') }}"></script>
    <script src="{{ asset('lib/waypoints/waypoints.min.js') }}"></script>
    <script src="{{ asset('lib/counterup/counterup.min.js') }}"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>

    <!-- Template Javascript -->
    <script src="{{ asset('js/main.js') }}"></script>

    @if ($errors->any())
        <script>
            @if ($errors->has('current_password') || $errors->has('password'))
                // Show password modal if there are password errors
                var passwordModal = new bootstrap.Modal(document.getElementById('changePasswordModal'));
                passwordModal.show();
            @endif
        </script>
    @endif
</body>
</html>
