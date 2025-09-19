@extends('layouts.guest')
@section('title', 'Se connecter')

@section('content')
<div class="container">
  <div class="login-card">
    {{-- On centre tout le contenu dans la carte --}}
    <div class="login-inner d-flex justify-content-center">
      <div class="login-left mx-auto" style="max-width:560px;">
        
        <h1 class="mb-2 text-center">Login</h1>
        <p class="muted mb-4 text-center">Accédez à votre espace Tunivert</p>

        @if ($errors->any())
          <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        @if (session('status'))
          <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" novalidate>
          @csrf

          {{-- Email --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l8 6.764 8-6.764V18H4z"/></svg>
            </span>
            <input type="email" name="email" class="form-control" placeholder="Email" required>
          </div>

          {{-- Password --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm-3 8V6a3 3 0 1 1 6 0v3H9zm3 4a2 2 0 0 1 1 3.732V19h-2v-2.268A2 2 0 0 1 12 13z"/></svg>
            </span>
            <input type="password" name="password" class="form-control" placeholder="Password" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check text-white-50">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label" for="remember">Se souvenir de moi</label>
            </div>
<a href="{{ route('password.request') }}" class="small link-soft">Mot de passe oublié ?</a>
          </div>

          <button class="btn btn-white w-100">Login</button>

          <p class="mt-3 mb-0 text-center">
            <span class="text-white-75">Pas de compte ?</span>
            <a class="link-soft fw-semibold" href="{{ route('register') }}">Register</a>
          </p>
        </form>
      </div>
      {{-- (plus de colonne droite) --}}
    </div>
  </div>
</div>
@endsection
