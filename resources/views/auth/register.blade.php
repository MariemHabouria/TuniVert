@extends('layouts.guest')
@section('title', 'Créer un compte')

@section('content')
<div class="container scene-3d">
  <div class="login-card card-3d" id="authCardReg">
    <span class="shine" aria-hidden="true"></span>
    <span class="orb orb-1" aria-hidden="true"></span>
    <span class="orb orb-2" aria-hidden="true"></span>
    <span class="orb orb-3" aria-hidden="true"></span>
    {{-- on recentre le contenu à l’intérieur de la carte --}}
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

          {{-- Name --}}
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

          {{-- Terms (cocher obligatoire) --}}
          <div class="form-check text-white-75 mb-3">
            {{-- valeur par défaut quand non cochée --}}
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
            <label class="form-check-label" for="terms">
              J’accepte les conditions d’utilisation
            </label>

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
      {{-- (plus de colonne droite) --}}
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
</div>
@endsection
