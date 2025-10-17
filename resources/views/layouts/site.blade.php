<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Tunivert')</title>

  {{-- Polices et icônes --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Bootstrap --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Styles locaux --}}
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  @stack('styles')
</head>
<body>

@include('layouts.navbar')

{{-- Espace pour navbar fixe (aligné avec les autres pages) --}}
<div style="height:112px"></div>

{{-- Contenu --}}
@yield('content')

{{-- Footer --}}
<div class="container-fluid footer bg-dark text-body py-5 mt-5">
  <div class="container py-5">
    <div class="row g-5">
      <div class="col-md-6 col-lg-6 col-xl-3">
        <div class="footer-item">
          <h4 class="mb-4 text-white">Newsletter</h4>
          <p class="mb-4">Recevez nos actualités.</p>
          <div class="position-relative mx-auto">
            <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Votre email">
            <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid copyright py-4">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-md-4 text-center text-md-start mb-md-0">
        <span class="text-body"><i class="fas fa-copyright text-light me-2"></i>Tunivert, tous droits réservés.</span>
      </div>
      <div class="col-md-4 text-center">
        <div class="d-flex align-items-center justify-content-center">
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
          <a href="#" class="btn-hover-color btn-square text-white"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="col-md-4 text-center text-md-end text-body">
        Designed by Tunivert
      </div>
    </div>
  </div>
</div>

{{-- JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
