@extends('layouts.guest')
@section('title','Mot de passe oublié')

@section('content')
<div class="container">
  <div class="login-card">
    <div class="login-inner d-flex justify-content-center">
      <div class="login-left mx-auto" style="max-width:560px;">
        <h1 class="mb-2 text-center">Mot de passe oublié</h1>
        <p class="muted mb-4 text-center">Saisissez votre email pour recevoir le lien de réinitialisation.</p>

        @if (session('status'))
          <div class="alert alert-success small text-center">{{ session('status') }}</div>
        @endif

        @error('email')
          <div class="alert alert-danger small text-center">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('password.email') }}" novalidate>
          @csrf
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l8 6.764 8-6.764V18H4z"/></svg>
            </span>
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
          </div>

          <button class="btn btn-white w-100">Envoyer le lien</button>

          <p class="mt-3 mb-0 text-center">
            <a class="link-soft fw-semibold" href="{{ route('login') }}">Retour au login</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
