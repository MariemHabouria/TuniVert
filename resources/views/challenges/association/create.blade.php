<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Créer un Challenge - Tunivert</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

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
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>

    <!-- 🔹 Header existant (ne pas changer, même que event) -->
    <div class="container-fluid fixed-top px-0">
        <div class="container px-0">
            <nav class="navbar navbar-light bg-light navbar-expand-xl">
                <a href="{{ url('/') }}" class="navbar-brand ms-3">
                    <h1 class="text-primary display-5">Tunivert</h1>
                </a>
                <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="{{ url('/') }}" class="nav-item nav-link">Home</a>
                        <a href="#" class="nav-item nav-link">About</a>
                        <a href="#" class="nav-item nav-link">Services</a>
                        <a href="#" class="nav-item nav-link">Events</a>
                        <a href="{{ route('challenges.create') }}" class="nav-item nav-link active">Challenges</a>
                        <a href="{{ url('/contact') }}" class="nav-item nav-link">Contact</a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <!-- 🔹 Header End -->

    <!-- 🔹 Bandeau (même design que events) -->
    <div class="container-fluid bg-breadcrumb">
        <div class="container text-center py-5" style="max-width: 900px;">
            <h3 class="text-white display-3 mb-4">Créer un Challenge</h3>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active text-white">Challenges</li>
            </ol>
        </div>
    </div>

    <!-- 🔹 Partie Challenge -->
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white rounded shadow p-5">
                    <h2 class="mb-4 text-primary text-center">Créer un Challenge</h2>
                    <form method="POST" action="{{ route('challenges.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" name="titre" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date début</label>
                                <input type="date" name="date_debut" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Date fin</label>
                                <input type="date" name="date_fin" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <input type="text" name="categorie" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Difficulté</label>
                            <select name="difficulte" class="form-select">
                                <option value="facile">Facile</option>
                                <option value="moyen">Moyen</option>
                                <option value="difficile">Difficile</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Objectif</label>
                            <input type="number" name="objectif" class="form-control">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary px-5">Créer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- 🔹 Partie Challenge End -->

    <!-- 🔹 Footer existant (ne pas changer, même que event) -->
    <div class="container-fluid footer bg-dark text-body py-5">
        <div class="container text-center">
            <p class="mb-0 text-white">&copy; 2025 Tunivert. Tous droits réservés.</p>
        </div>
    </div>
    <!-- 🔹 Footer End -->

    <!-- JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('lib/owlcarousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('lib/lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>