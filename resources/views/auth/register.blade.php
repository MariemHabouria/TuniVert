@extends('layouts.guest')
@section('title', 'Créer un compte')

@section('content')
<div class="container scene-3d">
  <div class="login-card card-3d" id="authCardReg">
    <span class="shine" aria-hidden="true"></span>
    <span class="orb orb-1" aria-hidden="true"></span>
    <span class="orb orb-2" aria-hidden="true"></span>
    <span class="orb orb-3" aria-hidden="true"></span>
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
            <label class="form-label d-block mb-2">Je m'inscris en tant que :</label>

            <div class="btn-group w-100" role="group" aria-label="Choix du type de compte">
              <input type="radio" class="btn-check" name="role" id="role_user" value="user"
                     {{ old('role','user') === 'user' ? 'checked' : '' }}>
              <label class="btn btn-outline-light" for="role_user">
                <i class="fas fa-user me-2"></i>Utilisateur
              </label>

              <input type="radio" class="btn-check" name="role" id="role_asso" value="association"
                     {{ old('role') === 'association' ? 'checked' : '' }}>
              <label class="btn btn-outline-light" for="role_asso">
                <i class="fas fa-building me-2"></i>Association
              </label>
            </div>

            @error('role')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          {{-- Alerte pour les associations --}}
          <div id="assoAlert" class="alert-association mb-3" style="display:none;">
            <div class="alert-icon">
              <i class="fas fa-info-circle"></i>
            </div>
            <div class="alert-content">
              <strong>Information importante pour les associations</strong>
              <p class="mb-2 mt-1">
                Votre compte sera <strong>temporairement bloqué</strong> après l'inscription 
                jusqu'à ce qu'un administrateur vérifie vos informations et votre matricule RNE.
              </p>
              <ul class="mb-0 small">
                <li>Vous recevrez un email de confirmation après validation</li>
                <li>La vérification prend généralement 24-48h</li>
                <li>Assurez-vous de fournir un matricule RNE valide</li>
              </ul>
            </div>
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
              <i class="fas fa-exclamation-circle me-1"></i>
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
            <label class="form-check-label" for="terms">J'accepte les conditions d'utilisation</label>
            @error('terms')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>

          <button class="btn btn-white w-100">
            <i class="fas fa-user-plus me-2"></i>Créer mon compte
          </button>

          <p class="mt-3 mb-0 text-center">
            <span class="text-white-75">Déjà un compte ?</span>
            <a class="link-soft fw-semibold" href="{{ route('login') }}">Login</a>
          </p>
        </form>
      </div>
    </div>
  </div>

@push('scripts')
<script>
  (function(){
    const card = document.getElementById('authCardReg');
    if(!card) return;
    const maxTilt = 10; // deg
    let raf = null;
    let rect = null;
    function setTilt(e){
      if(!rect) rect = card.getBoundingClientRect();
      const cx = rect.left + rect.width/2;
      const cy = rect.top + rect.height/2;
      const x = (e.clientX - cx) / (rect.width/2);
      const y = (e.clientY - cy) / (rect.height/2);
      const rx = (+y * maxTilt).toFixed(2);
      const ry = (-x * maxTilt).toFixed(2);
      const px = ((e.clientX - rect.left)/rect.width*100).toFixed(2);
      const py = ((e.clientY - rect.top)/rect.height*100).toFixed(2);
      card.style.setProperty('--px', px+'%');
      card.style.setProperty('--py', py+'%');
      card.style.transform = `rotateX(${rx}deg) rotateY(${ry}deg)`;
    }
    function reset(){
      card.style.transform = '';
      card.style.removeProperty('--px');
      card.style.removeProperty('--py');
      rect = null;
    }
    function onMove(e){
      if(raf) cancelAnimationFrame(raf);
      raf = requestAnimationFrame(()=> setTilt(e));
    }
    card.addEventListener('mousemove', onMove);
    card.addEventListener('mouseleave', reset);
    window.addEventListener('scroll', ()=> rect=null, {passive:true});
    window.addEventListener('resize', ()=> rect=null);
  })();
</script>
@endpush

{{-- JS pour afficher/masquer le champ matricule et l'alerte --}}
@push('scripts')
<script>
  (function() {
    const rUser = document.getElementById('role_user');
    const rAsso = document.getElementById('role_asso');
    const matriculeWrap = document.getElementById('matriculeWrap');
    const assoAlert = document.getElementById('assoAlert');

    function toggleAssociationFields() {
      const isAssociation = rAsso && rAsso.checked;
      
      if (matriculeWrap) {
        matriculeWrap.style.display = isAssociation ? 'block' : 'none';
      }
      
      if (assoAlert) {
        assoAlert.style.display = isAssociation ? 'flex' : 'none';
      }
    }

    rUser?.addEventListener('change', toggleAssociationFields);
    rAsso?.addEventListener('change', toggleAssociationFields);
    
    // Initial check
    toggleAssociationFields();
  })();
</script>
@endpush

@push('styles')
<style>
  /* Alerte pour associations */
  .alert-association {
    background: linear-gradient(135deg, rgba(246, 194, 62, 0.15) 0%, rgba(221, 162, 10, 0.15) 100%);
    border: 2px solid rgba(246, 194, 62, 0.5);
    border-radius: 12px;
    padding: 16px;
    display: flex;
    gap: 12px;
    animation: slideInDown 0.5s ease-out;
  }

  .alert-association .alert-icon {
    font-size: 24px;
    color: #f6c23e;
    flex-shrink: 0;
  }

  .alert-association .alert-content {
    color: #fff;
    flex: 1;
  }

  .alert-association .alert-content strong {
    color: #f6c23e;
    font-size: 15px;
  }

  .alert-association .alert-content p {
    font-size: 14px;
    line-height: 1.5;
  }

  .alert-association ul {
    padding-left: 20px;
    margin-bottom: 0;
  }

  .alert-association li {
    font-size: 13px;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
  }

  @keyframes slideInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Style amélioré pour les boutons radio */
  .btn-group .btn-outline-light {
    transition: all 0.3s ease;
  }

  .btn-group .btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.6);
  }

  .btn-check:checked + .btn-outline-light {
    background: rgba(255, 255, 255, 0.2);
    border-color: #fff;
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
  }

  /* Animation du champ matricule */
  #matriculeWrap {
    animation: slideInDown 0.5s ease-out;
  }
</style>
@endpush
@endsection