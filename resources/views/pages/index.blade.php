<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>TuniVert - Plateforme Environnementale Tunisienne</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="environnement, tunisie, écologie, donations, événements" name="keywords">
    <meta content="TuniVert - Protégeons l'environnement tunisien ensemble" name="description">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="16x16" href="{{ asset('favicon-16x16.svg') }}">
    <link rel="icon" type="image/svg+xml" sizes="32x32" href="{{ asset('favicon-32x32.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

<!-- Navbar start -->
@include('layouts.navbar')
<!-- space for fixed navbar -->
<div style="height: 112px"></div>



        
       <!-- Carousel Start -->
        <div class="container-fluid carousel-header vh-100 px-0">
            <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-bs-target="#carouselId" data-bs-slide-to="0" class="active"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="1"></li>
                    <li data-bs-target="#carouselId" data-bs-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <img src="img/carousel-1.jpg" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Protégeons Notre Tunisie</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Environnement Durable</h1>
                                <p class="mb-5 fs-5">TuniVert est votre plateforme dédiée à la protection de l'environnement tunisien. Ensemble, nous pouvons créer un avenir plus vert pour notre pays.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Rejoignez-nous</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel-2.jpg" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Sauvons Notre Patrimoine</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Biodiversité Tunisienne</h1>
                                <p class="mb-5 fs-5">Préservons la richesse naturelle de la Tunisie. Des oasis du sud aux forêts du nord, chaque écosystème compte pour notre avenir.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Agissez Maintenant</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <img src="img/carousel-3.jpg" class="img-fluid" alt="Image">
                        <div class="carousel-caption">
                            <div class="p-3" style="max-width: 900px;">
                                <h4 class="text-white text-uppercase fw-bold mb-4" style="letter-spacing: 3px;">Action Collective</h4>
                                <h1 class="display-1 text-capitalize text-white mb-4">Communauté Verte</h1>
                                <p class="mb-5 fs-5">Rejoignez une communauté engagée pour l'environnement. Participez à nos événements, faites des dons et contribuez à un changement positif.
                                </p>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a class="btn-hover-bg btn btn-primary text-white py-3 px-5" href="#">Participez</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
        <!-- Carousel End -->





        <!-- About Start -->
        <div class="container-fluid about  py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-xl-5">
                        <div class="h-100">
                            <img src="img/about-1.jpg" class="img-fluid w-100 h-100" alt="Image">
                        </div>
                    </div>
                    <div class="col-xl-7">
                        <h5 class="text-uppercase text-primary">À Propos de Nous</h5>
                        <h1 class="mb-4">Notre mission : protéger l'environnement tunisien</h1>
                        <p class="fs-5 mb-4">TuniVert est une plateforme innovante dédiée à la protection de l'environnement en Tunisie. Nous connectons les citoyens, les associations et les entreprises pour créer un impact positif durable sur notre écosystème national.
                        </p>
                        <div class="tab-class bg-secondary p-4">
                            <ul class="nav d-flex mb-2">
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 text-center bg-white active" data-bs-toggle="pill" href="#tab-1">
                                        <span class="text-dark" style="width: 150px;">À Propos</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 mx-3 text-center bg-white" data-bs-toggle="pill" href="#tab-2">
                                        <span class="text-dark" style="width: 150px;">Mission</span>
                                    </a>
                                </li>
                                <li class="nav-item mb-3">
                                    <a class="d-flex py-2 text-center bg-white" data-bs-toggle="pill" href="#tab-3">
                                        <span class="text-dark" style="width: 150px;">Vision</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div id="tab-1" class="tab-pane fade show p-0 active">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">Plateforme Collaborative</h5>
                                                    <p class="mb-4">TuniVert rassemble tous les acteurs soucieux de l'environnement en Tunisie. Notre plateforme facilite les donations, organise des événements écologiques et sensibilise la population aux enjeux environnementaux.
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">En Savoir Plus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-2" class="tab-pane fade show p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">Notre Mission</h5>
                                                    <p class="mb-4">Mobiliser la communauté tunisienne pour la protection de l'environnement à travers des actions concrètes, des campagnes de sensibilisation et un système de financement participatif pour les projets verts.
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Notre Impact</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="tab-3" class="tab-pane fade show p-0">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex">
                                                <div class="text-start my-auto">
                                                    <h5 class="text-uppercase mb-3">Notre Vision</h5>
                                                    <p class="mb-4">Faire de la Tunisie un modèle de développement durable en Afrique du Nord, où chaque citoyen participe activement à la préservation de l'environnement pour les générations futures.
                                                    </p>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Nos Projets</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- About End -->


        <!-- Services Start -->
        <div class="container-fluid service py-5 bg-light">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Nos Services</h5>
                    <h1 class="mb-0">Comment nous protégeons l'environnement tunisien</h1>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="service-item">
                            <img src="img/service-1.jpg" class="img-fluid w-100" alt="Image">
                            <div class="service-link">
                                <a href="#" class="h4 mb-0">Collecte de Fonds</a>
                            </div>
                        </div>
                        <p class="my-4">Facilitons les donations pour financer des projets environnementaux en Tunisie et soutenir les initiatives locales de protection de la nature.
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="service-item">
                            <img src="img/service-2.jpg" class="img-fluid w-100" alt="Image">
                            <div class="service-link">
                                <a href="#" class="h4 mb-0">Partenariats Associatifs</a>
                            </div>
                        </div>
                        <p class="my-4">Collaboration étroite avec les associations environnementales tunisiennes pour maximiser l'impact de nos actions collectives.
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="service-item">
                            <img src="img/service-3.jpg" class="img-fluid w-100" alt="Image">
                            <div class="service-link">
                                <a href="#" class="h4 mb-0">Événements Écologiques</a>
                            </div>
                        </div>
                        <p class="my-4">Organisation d'événements de sensibilisation et d'actions concrètes pour la protection de l'environnement tunisien.
                        </p>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="service-item">
                            <img src="img/service-4.jpg" class="img-fluid w-100" alt="Image">
                            <div class="service-link">
                                <a href="#" class="h4 mb-0">Protection de la Faune</a>
                            </div>
                        </div>
                        <p class="my-4">Préservation des espèces endémiques tunisiennes et protection des habitats naturels de notre riche biodiversité.
                        </p>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">En Savoir Plus</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Services End -->


        <!-- Donation Start -->
        <div class="container-fluid donation py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Donations</h5>
                    <h1 class="mb-0">Vos dons protègent l'environnement tunisien</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/donation-1.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Agriculture Bio</h5>
                                <a href="#" class="btn-hover-color display-6 text-white">Soutenez-nous</a>
                                <h4 class="text-white mb-4">Agriculture Durable</h4>
                                <p class="text-white mb-4">Promotion de l'agriculture biologique en Tunisie pour préserver nos sols et notre santé.</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Faire un Don</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/service-2.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Écosystème</h5>
                                <a href="#" class="btn-hover-color display-6 text-white">Agissez Maintenant</a>
                                <h4 class="text-white mb-4">Protection Marine</h4>
                                <p class="text-white mb-4">Préservation de la biodiversité marine tunisienne et protection des côtes méditerranéennes.</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Faire un Don</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/donation-3.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Recyclage</h5>
                                <a href="#" class="btn-hover-color display-6 text-white">Participez</a>
                                <h4 class="text-white mb-4">Économie Circulaire</h4>
                                <p class="text-white mb-4">Développement du recyclage et de l'économie circulaire pour réduire les déchets en Tunisie.</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Faire un Don</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Toutes les Donations</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donation End -->


        <!-- Counter Start -->
        <div class="container-fluid counter py-5" style="background: linear-gradient(rgba(0, 0, 0, .4), rgba(0, 0, 0, 0.4)), url(img/volunteers-bg.jpg) center center; background-size: cover;">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Nos Réalisations</h5>
                    <p class="text-white mb-0">Découvrez l'impact de TuniVert sur la protection de l'environnement tunisien. Chaque action compte pour préserver notre patrimoine naturel et construire un avenir durable.
                    </p>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-thumbs-up fa-4x text-white"></i>
                            <h3 class="text-white my-4">Arbres Plantés</h3>
                            <div class="counter-counting">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">3600</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-file-invoice-dollar fa-4x text-white"></i>
                            <h3 class="text-white my-4">Fonds Collectés</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">513</span>
                                <span class="h1 fw-bold text-primary">DT</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-user fa-4x text-white"></i>
                            <h3 class="text-white my-4">Volontaires</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">713</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="counter-item text-center border p-5">
                            <i class="fas fa-heart fa-4x text-white"></i>
                            <h3 class="text-white my-4">Jours d'Action</h3>
                            <div class="counter-counting text-center border-white w-100" style="border-style: dotted; font-size: 30px;">
                                <span class="text-primary fs-2 fw-bold" data-toggle="counter-up">487</span>
                                <span class="h1 fw-bold text-primary">+</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Rejoignez-nous</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Counter End -->


        <!-- Causes Start -->
        <div class="container-fluid causes py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Causes Récentes</h5>
                    <h1 class="mb-4">L'environnement tunisien a besoin de notre protection</h1>
                    <p class="mb-0">Découvrez nos projets en cours pour la protection de l'environnement en Tunisie. Votre soutien fait la différence pour préserver notre patrimoine naturel.
                    </p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-xl-3">
                        <div class="causes-item">
                            <div class="causes-img">
                                <img src="img/causes-4.jpg" class="img-fluid w-100" alt="Image">
                                <div class="causes-link pb-2 px-3">
                                    <small class="text-white"><i class="fas fa-chart-bar text-primary me-2"></i>Goal: $3600</small>
                                    <small class="text-white"><i class="fa fa-thumbs-up text-primary me-2"></i>Raised: $4000</small>
                                </div>
                                <div class="causes-dination p-2">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">Donate Now</a>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
                                    <span>65%</span>
                                </div>
                            </div>
                            <div class="causes-content p-4">
                                <h4 class="mb-3">Protection des oasis tunisiennes</h4>
                                <p class="mb-4">Aidez aujourd'hui car demain vous pourriez avoir besoin d'aide pour protéger notre environnement!</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">En Savoir Plus</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="causes-item">
                            <div class="causes-img">
                                <img src="img/causes-2.jpg" class="img-fluid w-100" alt="Image">
                                <div class="causes-link pb-2 px-3">
                                    <small class="text-white"><i class="fas fa-chart-bar text-primary me-2"></i>Goal: $3600</small>
                                    <small class="text-white"><i class="fa fa-thumbs-up text-primary me-2"></i>Raised: $4000</small>
                                </div>
                                <div class="causes-dination p-2">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">Donate Now</a>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">
                                    <span>75%</span>
                                </div>
                            </div>
                            <div class="causes-content p-4">
                                <h4 class="mb-3">Build school for poor children.</h4>
                                <p class="mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="causes-item">
                            <div class="causes-img">
                                <img src="img/causes-3.jpg" class="img-fluid w-100" alt="Image">
                                <div class="causes-link pb-2 px-3">
                                    <small class="text-white"><i class="fas fa-chart-bar text-primary me-2"></i>Goal: $3600</small>
                                    <small class="text-white"><i class="fa fa-thumbs-up text-primary me-2"></i>Raised: $4000</small>
                                </div>
                                <div class="causes-dination p-2">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">Donate Now</a>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                    <span>85%</span>
                                </div>
                            </div>
                            <div class="causes-content p-4">
                                <h4 class="mb-3">Building clean-water system for rural poor.</h4>
                                <p class="mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="causes-item">
                            <div class="causes-img">
                                <img src="img/causes-1.jpg" class="img-fluid w-100" alt="Image">
                                <div class="causes-link pb-2 px-3">
                                    <small class="text-white"><i class="fas fa-chart-bar text-primary me-2"></i>Goal: $3600</small>
                                    <small class="text-white"><i class="fa fa-thumbs-up text-primary me-2"></i>Raised: $4000</small>
                                </div>
                                <div class="causes-dination p-2">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-3" href="#">Donate Now</a>
                                </div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="95" aria-valuemin="0" aria-valuemax="100">
                                    <span>95%</span>
                                </div>
                            </div>
                            <div class="causes-content p-4">
                                <h4 class="mb-3">First environments activity of this summer.</h4>
                                <p class="mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Causes End -->


        <!-- Events Start -->
        <div class="container-fluid event py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Événements à Venir</h5>
                    <h1 class="mb-0">Participez à nos actions pour l'environnement tunisien!</h1>
                </div>
                <div class="event-carousel owl-carousel">
                    <div class="event-item">
                        <img src="img/events-1.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-2.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-3.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-4.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Events End -->

        <!-- Blog Start -->
        <div class="container-fluid blog py-5 mb-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Actualités</h5>
                    <h1 class="mb-0">Restez informés sur la protection de l'environnement en Tunisie
                    </h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-6 col-xl-3">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="img/blog-1.jpg" class="img-fluid w-100" alt="">
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> Dec 01.2024</span>
                                    <div class="d-flex">
                                        <span class="me-3"> 3 <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">0 <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="img/blog-1.jpg" data-lightbox="Blog-1" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark border p-4 ">
                                <h4 class="mb-4">Save The Topic Forests</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="img/blog-2.jpg" class="img-fluid w-100" alt="">
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> Dec 01.2024</span>
                                    <div class="d-flex">
                                        <span class="me-3"> 3 <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">0 <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="img/blog-2.jpg" data-lightbox="Blog-2" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark border p-4 ">
                                <h4 class="mb-4">Save The Topic Forests</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="img/blog-3.jpg" class="img-fluid w-100" alt="">
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> Dec 01.2024</span>
                                    <div class="d-flex">
                                        <span class="me-3"> 3 <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">0 <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="img/blog-3.jpg" data-lightbox="Blog-3" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark border p-4 ">
                                <h4 class="mb-4">Save The Topic Forests</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-xl-3">
                        <div class="blog-item">
                            <div class="blog-img">
                                <img src="img/blog-4.jpg" class="img-fluid w-100" alt="">
                                <div class="blog-info">
                                    <span><i class="fa fa-clock"></i> Dec 01.2024</span>
                                    <div class="d-flex">
                                        <span class="me-3"> 3 <i class="fa fa-heart"></i></span>
                                        <a href="#" class="text-white">0 <i class="fa fa-comment"></i></a>
                                    </div>
                                </div>
                                <div class="search-icon">
                                    <a href="img/blog-4.jpg" data-lightbox="Blog-4" class="my-auto"><i class="fas fa-search-plus btn-primary text-white p-3"></i></a>
                                </div>
                            </div>
                            <div class="text-dark border p-4 ">
                                <h4 class="mb-4">Save The Topic Forests</h4>
                                <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Blog End -->


        <!-- Gallery Start -->
        <div class="container-fluid gallery py-5 px-0">
            <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                <h5 class="text-uppercase text-primary">Notre Travail</h5>
                <h1 class="mb-4">Nous considérons le bien-être environnemental</h1>
                <p class="mb-0">Découvrez nos actions concrètes pour la protection de l'environnement tunisien à travers notre galerie photo.</p>
            </div>
            <div class="row g-0">
                <div class="col-lg-4">
                    <div class="gallery-item">
                        <img src="img/gallery-2.jpg" class="img-fluid w-100" alt="">
                        <div class="search-icon">
                            <a href="img/gallery-2.jpg" data-lightbox="gallery-2" class="my-auto"><i class="fas fa-search-plus btn-hover-color bg-white text-primary p-3"></i></a>
                        </div>
                        <div class="gallery-content">
                            <div class="gallery-inner pb-5">
                                <a href="#" class="h4 text-white">Beauty Of Life</a>
                                <a href="#" class="text-white"><p class="mb-0">Gallery Name</p></a>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="img/gallery-3.jpg" class="img-fluid w-100" alt="">
                        <div class="search-icon">
                            <a href="img/gallery-3.jpg" data-lightbox="gallery-3" class="my-auto"><i class="fas fa-search-plus btn-hover-color bg-white text-primary p-3"></i></a>
                        </div>
                        <div class="gallery-content">
                            <div class="gallery-inner pb-5">
                                <a href="#" class="h4 text-white">Beauty Of Life</a>
                                <a href="#" class="text-white"><p class="mb-0">Gallery Name</p></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="gallery-item">
                        <img src="img/gallery-1.jpg" class="img-fluid w-100" alt="">
                        <div class="search-icon">
                            <a href="img/gallery-1.jpg" data-lightbox="gallery-1" class="my-auto"><i class="fas fa-search-plus btn-hover-color bg-white text-primary p-3"></i></a>
                        </div>
                        <div class="gallery-content">
                            <div class="gallery-inner pb-5">
                                <a href="#" class="h4 text-white">Beauty Of Life</a>
                                <a href="#" class="text-white"><p class="mb-0">Gallery Name</p></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="gallery-item">
                        <img src="img/gallery-4.jpg" class="img-fluid w-100" alt="">
                        <div class="search-icon">
                            <a href="img/gallery-4.jpg" data-lightbox="gallery-4" class="my-auto"><i class="fas fa-search-plus btn-hover-color bg-white text-primary p-3"></i></a>
                        </div>
                        <div class="gallery-content">
                            <div class="gallery-inner pb-5">
                                <a href="#" class="h4 text-white">Beauty Of Life</a>
                                <a href="#" class="text-white"><p class="mb-0">Gallery Name</p></a>
                            </div>
                        </div>
                    </div>
                    <div class="gallery-item">
                        <img src="img/gallery-5.jpg" class="img-fluid w-100" alt="">
                        <div class="search-icon">
                            <a href="img/gallery-5.jpg" data-lightbox="gallery-5" class="my-auto"><i class="fas fa-search-plus btn-hover-color bg-white text-primary p-3"></i></a>
                        </div>
                        <div class="gallery-content">
                            <div class="gallery-inner pb-5">
                                <a href="#" class="h4 text-white">Beauty Of Life</a>
                                <a href="#" class="text-white"><p class="mb-0">Gallery Name</p></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Gallery End -->


        <!-- Volunteers Start -->
        <div class="container-fluid volunteer py-5 mt-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="volunteer-img">
                                    <img src="img/volunteers-1.jpg" class="img-fluid w-100" alt="Image">
                                    <div class="volunteer-title">
                                        <h5 class="mb-2 text-white">Michel Brown</h5>
                                        <p class="mb-0 text-white">Communicator</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="volunteer-img">
                                    <img src="img/volunteers-3.jpg" class="img-fluid w-100" alt="Image">
                                    <div class="volunteer-title">
                                        <h5 class="mb-2 text-white">Michel Brown</h5>
                                        <p class="mb-0 text-white">Communicator</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="volunteer-img">
                                    <img src="img/volunteers-2.jpg" class="img-fluid w-100" alt="Image">
                                    <div class="volunteer-title">
                                        <h5 class="mb-2 text-white">Michel Brown</h5>
                                        <p class="mb-0 text-white">Communicator</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="volunteer-img">
                                    <img src="img/volunteers-4.jpg" class="img-fluid w-100" alt="Image">
                                    <div class="volunteer-title">
                                        <h5 class="mb-2 text-white">Michel Brown</h5>
                                        <p class="mb-0 text-white">Communicator</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <h5 class="text-uppercase text-primary">Devenir Volontaire?</h5>
                        <h1 class="mb-4">Joignez vos mains aux nôtres pour une vie meilleure et un avenir plus vert.</h1>
                        <p class="mb-4">Rejoignez TuniVert et participez à la protection de l'environnement tunisien. Ensemble, nous pouvons faire la différence pour préserver notre patrimoine naturel et construire un avenir durable.
                        </p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> Nous sommes accueillants et bienveillants.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> Formation gratuite pour tous nos volontaires.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> Opportunité d'aider l'environnement tunisien.</p>
                        <p class="text-dark"><i class=" fa fa-check text-primary me-2"></i> Aucune obligation ou exigence particulière.</p>
                        <p class="text-dark mb-5"><i class=" fa fa-check text-primary me-2"></i> L'adhésion est totalement gratuite.</p>
                        <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="#">Rejoignez-nous</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Volunteers End -->


        <!-- Footer Start -->
        <div class="container-fluid footer bg-dark text-body py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Newsletter</h4>
                            <p class="mb-4">Restez informés de nos actions pour la protection de l'environnement tunisien. Recevez nos actualités et participez à nos initiatives.</p>
                            <div class="position-relative mx-auto">
                                <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Votre adresse email">
                                <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">S'inscrire</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Nos Services</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Protection Marine</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Forêts Tunisiennes</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Écologie Sociale</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Sensibilisation</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Qualité de Vie</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Avenir Durable</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Volontaires</h4>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Amira Ben Ali</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Mohamed Trabelsi</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Leila Mansouri</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Ahmed Gharbi</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Fatma Jendoubi</a>
                            <a href=""><i class="fas fa-angle-right me-2"></i> Karim Bouazizi</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-3">
                        <div class="footer-item">
                            <h4 class="mb-4 text-white">Notre Galerie</h4>
                            <div class="row g-2">
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-1.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-1.jpg" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-2.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-2.jpg" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-3.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-3.jpg" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-4.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-4.jpg" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-5.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-5.jpg" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
									<div class="footer-gallery">
										<img src="img/gallery-footer-6.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-6.jpg" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
									</div>
								</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Copyright Start -->
        <div class="container-fluid copyright py-4">
            <div class="container">
                <div class="row g-4 align-items-center">
                    <div class="col-md-4 text-center text-md-start mb-md-0">
                        <span class="text-body"><a href="#"><i class="fas fa-copyright text-light me-2"></i>TuniVert</a>, Tous droits réservés.</span>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="d-flex align-items-center justify-content-center">
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
                            <a href="#" class="btn-hover-color btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                    <div class="col-md-4 text-center text-md-end text-body">
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
                        Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>   

        
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>
        

        <!-- Template Javascript -->
        <script src="js/main.js"></script>

    </body>

</html>