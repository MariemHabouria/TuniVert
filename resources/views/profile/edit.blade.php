<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Modifier le Profil - TuniVert</title>
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
            <h3 class="text-white display-3 mb-4">Modifier le Profil</h3>
            <p class="fs-5 text-white mb-4">Mettez à jour vos informations personnelles</p>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profile') }}">Mon Profil</a></li>
                <li class="breadcrumb-item active text-white">Modifier</li>
            </ol>
        </div>
    </div>
    <!-- Header End -->

    <!-- Edit Profile Section Start -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Erreur !</strong> Veuillez corriger les erreurs suivantes :
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>Modifier mes informations
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Avatar Section -->
                            <div class="text-center mb-4">
                                <div class="position-relative d-inline-block">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" 
                                             style="width: 120px; height: 120px; object-fit: cover;" alt="Avatar" id="avatar-preview">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                             style="width: 120px; height: 120px; font-size: 2.5rem; font-weight: 600;" id="avatar-placeholder">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <label for="avatar" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 cursor-pointer" 
                                           style="cursor: pointer;" title="Changer l'avatar">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                </div>
                                <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                                <div class="mt-2">
                                    <small class="text-muted">Cliquez sur l'icône pour changer votre photo de profil</small>
                                </div>
                            </div>

                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom complet <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                           placeholder="+216 XX XXX XXX">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                @if($user->role === 'association')
                                    <div class="col-md-6 mb-3">
                                        <label for="matricule_fiscale" class="form-label">Matricule Fiscale</label>
                                        <input type="text" class="form-control @error('matricule_fiscale') is-invalid @enderror" 
                                               id="matricule_fiscale" name="matricule_fiscale" 
                                               value="{{ old('matricule_fiscale', $user->matricule_fiscale) }}"
                                               placeholder="Matricule fiscal de l'association">
                                        @error('matricule_fiscale')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                @endif

                                <div class="col-12 mb-3">
                                    <label for="bio" class="form-label">Biographie</label>
                                    <textarea class="form-control @error('bio') is-invalid @enderror" 
                                              id="bio" name="bio" rows="3" maxlength="500"
                                              placeholder="Parlez-nous un peu de vous...">{{ old('bio', $user->bio) }}</textarea>
                                    <div class="form-text">Maximum 500 caractères</div>
                                    @error('bio')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('adresse') is-invalid @enderror" 
                                              id="adresse" name="adresse" rows="2"
                                              placeholder="Votre adresse complète">{{ old('adresse', $user->adresse) }}</textarea>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Form Actions -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Annuler
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Profile Section End -->

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

    <script>
        // Avatar preview functionality
        document.getElementById('avatar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const avatarPreview = document.getElementById('avatar-preview');
                    const avatarPlaceholder = document.getElementById('avatar-placeholder');
                    
                    if (avatarPreview) {
                        avatarPreview.src = e.target.result;
                    } else if (avatarPlaceholder) {
                        // Replace placeholder with image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded-circle';
                        img.style.width = '120px';
                        img.style.height = '120px';
                        img.style.objectFit = 'cover';
                        img.id = 'avatar-preview';
                        avatarPlaceholder.parentNode.replaceChild(img, avatarPlaceholder);
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // Character counter for bio
        const bioTextarea = document.getElementById('bio');
        const maxLength = 500;
        
        function updateBioCounter() {
            const currentLength = bioTextarea.value.length;
            const remaining = maxLength - currentLength;
            const formText = bioTextarea.parentNode.querySelector('.form-text');
            
            if (remaining < 50) {
                formText.className = 'form-text text-warning';
            } else if (remaining < 0) {
                formText.className = 'form-text text-danger';
            } else {
                formText.className = 'form-text text-muted';
            }
            
            formText.textContent = `${remaining} caractères restants`;
        }

        bioTextarea.addEventListener('input', updateBioCounter);
        updateBioCounter(); // Initial call
    </script>
</body>
</html>