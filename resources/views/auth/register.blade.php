@extends('layouts.guest')
@section('title', 'Créer un compte')

@section('content')
<div class="container">
  <div class="login-card">
    <div class="login-inner d-flex justify-content-center">
      <div class="login-left mx-auto" style="max-width:560px;">
        <h1 class="mb-2 text-center">Register</h1>
        <p class="muted mb-4 text-center">Créez votre compte Tunivert</p>

        @if ($errors->any())
          <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" novalidate>
          @csrf

          {{-- Choix du rôle --}}
          <div class="mb-3 text-white">
            <label class="form-label d-block mb-2">Je m’inscris en tant que :</label>

            <div class="btn-group w-100" role="group" aria-label="Choix du type de compte">
              <input type="radio" class="btn-check" name="role" id="role_user" value="user"
                     {{ old('role','user') === 'user' ? 'checked' : '' }}>
              <label class="btn btn-outline-light" for="role_user">Utilisateur</label>

              <input type="radio" class="btn-check" name="role" id="role_asso" value="association"
                     {{ old('role') === 'association' ? 'checked' : '' }}>
              <label class="btn btn-outline-light" for="role_asso">Association</label>
            </div>

            @error('role')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          {{-- Si association : Matricule RNE --}}
          <div id="matriculeWrap" class="mb-3 form-pill" style="display:none;">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M7 3h10a2 2 0 012 2v2H5V5a2 2 0 012-2zm-2 6h14v10a2 2 0 01-2 2H7a2 2 0 01-2-2V9zm7 2a3 3 0 100 6 3 3 0 000-6z"/></svg>
            </span>
            <input
              type="text"
              name="matricule"
              class="form-control @error('matricule') is-invalid @enderror"
              placeholder="Matricule RNE (ex : 1234567A)"
              value="{{ old('matricule') }}"
              maxlength="8"
            >
            @error('matricule')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <small class="text-white-75 d-block mt-1">
              Format attendu : <strong>7 chiffres + 1 lettre majuscule</strong> (RNE).
            </small>
          </div>

          {{-- Nom --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 12a5 5 0 1 0-5-5 5 5 0 0 0 5 5Zm0 2c-5 0-9 2.69-9 6v1h18v-1c0-3.31-4-6-9-6Z"/></svg>
            </span>
            <input type="text" name="name" class="form-control" placeholder="Nom complet" value="{{ old('name') }}" required>
          </div>

          {{-- Email --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 2v.01L12 13 4 6.01V6h16zM4 18V8.236l8 6.764 8-6.764V18H4z"/></svg>
            </span>
            <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
          </div>

          {{-- Password --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zm-3 8V6a3 3 0 1 1 6 0v3H9zm3 4a2 2 0 0 1 1 3.732V19h-2v-2.268A2 2 0 0 1 12 13z"/></svg>
            </span>
            <input type="password" name="password" class="form-control" placeholder="Mot de passe" required>
          </div>

          {{-- Confirm --}}
          <div class="mb-3 form-pill">
            <span class="input-icon" aria-hidden="true">
              <svg viewBox="0 0 24 24"><path d="M12 1a5 5 0 0 0-5 5v3H6a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2h-1V6a5 5 0 0 0-5-5zM9 9V6a3 3 0 1 1 6 0v3H9z"/></svg>
            </span>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirmer le mot de passe" required>
          </div>

          {{-- Terms --}}
          <div class="form-check text-white-75 mb-3">
            <input type="hidden" name="terms" value="0">
            <input
              class="form-check-input @error('terms') is-invalid @enderror"
              type="checkbox"
              id="terms"
              name="terms"
              value="1"
              {{ old('terms') ? 'checked' : '' }}
              required
            >
            <label class="form-check-label" for="terms">J’accepte les conditions d’utilisation</label>
            @error('terms')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <button class="btn btn-white w-100">Créer mon compte</button>

          <p class="mt-3 mb-0 text-center">
            <span class="text-white-75">Déjà un compte ?</span>
            <a class="link-soft fw-semibold" href="{{ route('login') }}">Login</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Petit JS pour afficher/masquer le champ matricule --}}
@push('scripts')
<script>
  (function() {
    const rUser = document.getElementById('role_user');
    const rAsso = document.getElementById('role_asso');
    const wrap = document.getElementById('matriculeWrap');

    function toggleMatricule() {
      wrap.style.display = rAsso.checked ? 'block' : 'none';
    }
    rUser?.addEventListener('change', toggleMatricule);
    rAsso?.addEventListener('change', toggleMatricule);
    toggleMatricule();
  })();
</script>
@endpush
@endsection
