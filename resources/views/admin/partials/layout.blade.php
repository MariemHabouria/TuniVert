{{-- resources/views/admin/partials/layout.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Administration')</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- ===== CSS StarAdmin / Bootstrap / Icons ===== --}}
  <link rel="stylesheet" href="{{ asset('admin/vendors/mdi/css/materialdesignicons.min.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
  <link rel="shortcut icon" href="{{ asset('admin/images/favicon.png') }}" />

  @stack('styles')

  <style>
    /* Footer toujours en bas, même si peu de contenu */
    .main-panel{display:flex;flex-direction:column;min-height:100vh}
    .content-wrapper{flex:1 0 auto}
    .footer{flex-shrink:0}

    /* Empêche les énormes formes décoratives du thème */
    .content-wrapper::before,
    .content-wrapper::after,
    .main-panel::before,
    .main-panel::after,
    .page-body-wrapper::before,
    .page-body-wrapper::after {
      content: none !important;
      display: none !important;
      background: none !important;
    }

    /* Pagination: un peu d’air en bas */
    .pagination { margin: 16px 0; }
  </style>
</head>
<body>
  <div class="container-scroller">
    {{-- TOP NAVBAR --}}
    @include('admin.partials.navbar')

    <div class="container-fluid page-body-wrapper">
      {{-- SIDEBAR GAUCHE --}}
      @include('admin.partials.sidebar')

      {{-- CONTENU PRINCIPAL --}}
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>

        {{-- FOOTER COMMUN --}}
        @include('admin.partials.footer')
      </div>
    </div>
  </div>

  {{-- ===== JS StarAdmin / Bootstrap ===== --}}
  <script src="{{ asset('admin/vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('admin/js/off-canvas.js') }}"></script>
  <script src="{{ asset('admin/js/hoverable-collapse.js') }}"></script>
  <script src="{{ asset('admin/js/template.js') }}"></script>
  <script src="{{ asset('admin/js/settings.js') }}"></script>
  <script src="{{ asset('admin/js/todolist.js') }}"></script>

  @stack('scripts')
</body>
</html>
