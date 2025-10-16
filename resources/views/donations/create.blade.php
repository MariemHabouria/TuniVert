@extends('layouts.guest')
@section('title','Faire un don')

@section('content')
<div class="container scene-3d">
  <div class="login-card card-3d" id="donCard">
    <span class="shine" aria-hidden="true"></span>
    <div class="login-inner d-flex justify-content-center">
      <div class="login-left mx-auto" style="max-width:640px;">
        <h1 class="mb-2 text-center">Soutenir Tunivert</h1>
        <p class="muted mb-4 text-center">Effectuez un don ponctuel ou récurrent</p>

        @if (session('status'))
          <div class="alert alert-success small">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
          <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('donations.store') }}" class="row g-3">
          @csrf
          <div class="col-12 col-md-6">
            <label class="form-label">Montant (TND)</label>
            <input type="number" step="0.01" min="1" name="montant" class="form-control" required>
          </div>

          <div class="col-12 col-md-6">
            <label class="form-label">Moyen de paiement</label>
            @php
              try {
                $methods = \App\Models\PaymentMethod::where('active', true)
                  ->whereNotIn('key', ['virement_bancaire','test'])
                  ->orderBy('sort_order')->get();
              } catch (\Throwable $e) { $methods = collect(); }
            @endphp
            <select name="moyen_paiement" class="form-select" required>
              <option value="virement_bancaire">Virement bancaire</option>
              @if (config('services.testpay.enabled'))
                <option value="test">e‑DINAR (test)</option>
              @endif
              @foreach($methods as $m)
                <option value="{{ $m->key }}" data-type="{{ $m->type }}" data-icon="{{ $m->icon_path ?? $m->icon }}">{{ $m->name }}</option>
              @endforeach
            </select>
          </div>

          {{-- Custom Payment Methods --}}
          @foreach($methods as $cm)
            <div class="col-12">
              <x-custom-payment-method :method="$cm" />
            </div>
          @endforeach

          <div class="col-12">
            <button type="submit" class="btn btn-white w-100" id="submitBtn">Payer maintenant</button>
          </div>
        </form>
      </div>
    </div>
  </div>
@push('scripts')
<script>
  (function(){
    const card = document.getElementById('donCard');
    if(!card) return;
    const maxTilt = 8; let raf=null, rect=null;
    function setTilt(e){
      if(!rect) rect = card.getBoundingClientRect();
      const cx = rect.left + rect.width/2, cy = rect.top + rect.height/2;
      const x = (e.clientX-cx)/(rect.width/2), y=(e.clientY-cy)/(rect.height/2);
      card.style.setProperty('--px', ((e.clientX-rect.left)/rect.width*100).toFixed(2)+'%');
      card.style.setProperty('--py', ((e.clientY-rect.top)/rect.height*100).toFixed(2)+'%');
      card.style.transform = `rotateX(${(y*maxTilt).toFixed(2)}deg) rotateY(${(-x*maxTilt).toFixed(2)}deg)`;
    }
    function onMove(e){ if(raf) cancelAnimationFrame(raf); raf=requestAnimationFrame(()=>setTilt(e)); }
    function reset(){ card.style.transform=''; card.style.removeProperty('--px'); card.style.removeProperty('--py'); rect=null; }
    card.addEventListener('mousemove', onMove); card.addEventListener('mouseleave', reset);
    addEventListener('resize', ()=>rect=null); addEventListener('scroll', ()=>rect=null, {passive:true});
  })();

  // Handle payment method selection
  (function(){
    const methodSelect = document.querySelector('select[name="moyen_paiement"]');
    const submitBtn = document.getElementById('submitBtn');
    if (!methodSelect) return;

    methodSelect.addEventListener('change', function(){
      const selectedMethod = this.value;
      const selectedOption = this.options[this.selectedIndex];
      const methodType = selectedOption.dataset.type;

      // Hide all custom method wrappers first
      document.querySelectorAll('.custom-payment-method-wrapper').forEach(el => el.style.display = 'none');

      // Check if it's a custom method (not built-in)
      if (selectedMethod !== 'virement_bancaire' && selectedMethod !== 'test') {
        const customWrapper = document.querySelector(`.custom-payment-method-wrapper[data-method-key="${selectedMethod}"]`);
        if (customWrapper) {
          if (submitBtn) submitBtn.style.display = 'none';
          customWrapper.style.display = 'block';
        } else {
          // Unknown method, show default submit
          if (submitBtn) submitBtn.style.display = '';
        }
      } else {
        // Built-in method, show default submit
        if (submitBtn) submitBtn.style.display = '';
      }
    });
  })();
</script>
@endpush
</div>
@endsection
