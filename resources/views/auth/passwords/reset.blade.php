@extends('layouts.guest')
@section('title','Nouveau mot de passe')

@section('content')
<div class="container">
  <div class="login-card">
    <div class="login-inner d-flex justify-content-center">
      <div class="login-left mx-auto" style="max-width:560px;">
        <h1 class="mb-2 text-center">Réinitialiser le mot de passe</h1>
        <p class="muted mb-4 text-center">Choisissez un nouveau mot de passe.</p>

        @if ($errors->any())
          <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" novalidate>
          @csrf
          <input type="hidden" name="token" value="{{ $token }}">

          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l8 6.764 8-6.764V18H4z"/></svg>
            </span>
            <input type="email" name="email" class="form-control" placeholder="Email"
                   value="{{ old('email', $email ?? '') }}" required>
          </div>

          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm-3 8V6a3 3 0 1 1 6 0v3H9z"/></svg>
            </span>
            <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe" required>
          </div>

          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zM9 9V6a3 3 0 1 1 6 0v3H9z"/></svg>
            </span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required>
          </div>

          <button class="btn btn-white w-100">Enregistrer le nouveau mot de passe</button>

          <p class="mt-3 mb-0 text-center">
            <a class="link-soft fw-semibold" href="{{ route('login') }}">Retour au login</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection