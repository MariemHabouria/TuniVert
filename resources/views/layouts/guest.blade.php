<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Tunivert')</title>

  {{-- Bootstrap depuis CDN pour éviter les 404 locaux --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- Tes styles (laisse uniquement ceux qui existent vraiment dans /public) --}}
  {{-- <link rel="stylesheet" href="{{ asset('css/style.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

  @stack('styles')
</head>
<body class="auth-bg bg-photo">
  {{-- header/footer optionnels --}}
  {{-- @includeWhen(View::exists('partials.navbar'), 'partials.navbar') --}}

  @yield('content')

  {{-- @includeWhen(View::exists('partials.footer'), 'partials.footer') --}}

  {{-- Bootstrap JS depuis CDN, pas de main.js ici --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  @stack('scripts')
</body>
</html>
