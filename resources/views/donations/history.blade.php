<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <title>My Donations - Tunivert</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="/lib/lightbox/css/lightbox.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
  </head>

  <body>
    @if (session('new_badges'))
      <!-- Badge Modal Styles -->
      <style>
        .badge-modal-header {
          background: linear-gradient(135deg, #ff6b6b, #ffa500);
          border: none;
          padding: 2rem;
          text-align: center;
          position: relative;
          overflow: hidden;
        }
        
        .badge-modal-header::before {
          content: '';
          position: absolute;
          top: -50%;
          left: -50%;
          width: 200%;
          height: 200%;
          background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
          animation: pulse 2s ease-in-out infinite alternate;
        }
        
        .badge-modal-title-container {
          position: relative;
          z-index: 2;
        }
        
        .trophy-icon {
          font-size: 3rem;
          animation: bounce 1s ease-in-out infinite alternate;
          margin-bottom: 0.5rem;
        }
        
        .badge-modal-title {
          color: white;
          font-weight: 800;
          text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
          margin: 0;
        }
        
        .badge-unlock-text {
          font-size: 1.8rem;
          display: block;
          animation: fadeInUp 0.8s ease-out;
        }
        
        .sparkles {
          font-size: 1.2rem;
          margin-top: 0.5rem;
          animation: twinkle 1.5s ease-in-out infinite;
        }
        
        .badge-close-btn {
          position: absolute;
          top: 1rem;
          right: 1rem;
          z-index: 3;
          opacity: 0.8;
          transition: opacity 0.3s ease;
        }
        
        .badge-close-btn:hover {
          opacity: 1;
        }
        
        .badge-modal-body {
          padding: 2rem;
          background: rgba(255,255,255,0.95);
          margin: 0;
        }
        
        .badge-card {
          display: flex;
          align-items: center;
          gap: 1.5rem;
          padding: 1.5rem;
          margin-bottom: 1rem;
          border-radius: 15px;
          background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
          box-shadow: 0 8px 25px rgba(0,0,0,0.1);
          position: relative;
          overflow: hidden;
          animation: slideInLeft 0.6s ease-out;
          transition: transform 0.3s ease;
        }
        
        .badge-card:hover {
          transform: translateY(-5px);
        }
        
        .badge-glow {
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: linear-gradient(45deg, rgba(255,215,0,0.1), rgba(255,140,0,0.1));
          border-radius: 15px;
          animation: glow 2s ease-in-out infinite alternate;
        }
        
        .badge-icon-container {
          position: relative;
          min-width: 80px;
          height: 80px;
          display: flex;
          align-items: center;
          justify-content: center;
          background: linear-gradient(135deg, #ffd700, #ffb347);
          border-radius: 50%;
          box-shadow: 0 4px 15px rgba(255,215,0,0.4);
        }
        
        .badge-icon {
          font-size: 2.5rem;
          animation: rotate 2s ease-in-out infinite;
          z-index: 2;
          position: relative;
        }
        
        .badge-shine {
          position: absolute;
          top: 10%;
          left: 10%;
          width: 30%;
          height: 30%;
          background: radial-gradient(circle, rgba(255,255,255,0.8) 0%, transparent 70%);
          border-radius: 50%;
          animation: shine 2s ease-in-out infinite;
        }
        
        .badge-details {
          flex: 1;
          z-index: 2;
          position: relative;
        }
        
        .badge-name {
          font-size: 1.4rem;
          font-weight: 700;
          color: #2c3e50;
          margin: 0 0 0.5rem 0;
          text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }
        
        .badge-description {
          color: #6c757d;
          margin: 0;
          font-size: 1rem;
          line-height: 1.4;
        }
        
        .badge-celebration {
          font-size: 2rem;
          animation: party 1s ease-in-out infinite alternate;
          z-index: 2;
          position: relative;
        }
        
        .celebration-message {
          text-align: center;
          margin-top: 1rem;
        }
        
        .success-badge {
          background: linear-gradient(135deg, #28a745, #20c997);
          color: white;
          padding: 1rem 2rem;
          border-radius: 25px;
          display: inline-block;
          box-shadow: 0 4px 15px rgba(40,167,69,0.3);
          animation: fadeInUp 1s ease-out 0.5s both;
        }
        
        .success-text {
          font-size: 1.1rem;
          font-weight: 600;
          display: block;
        }
        
        .success-subtitle {
          font-size: 0.9rem;
          margin-top: 0.25rem;
          opacity: 0.9;
        }
        
        .badge-modal-footer {
          background: rgba(255,255,255,0.95);
          border: none;
          padding: 1.5rem 2rem;
          text-align: center;
        }
        
        .badge-awesome-btn {
          background: linear-gradient(135deg, #ff6b6b, #ffa500);
          color: white;
          border: none;
          padding: 0.75rem 2rem;
          border-radius: 25px;
          font-size: 1.1rem;
          font-weight: 600;
          box-shadow: 0 4px 15px rgba(255,107,107,0.4);
          transition: all 0.3s ease;
          display: flex;
          align-items: center;
          gap: 0.5rem;
          margin: 0 auto;
        }
        
        .badge-awesome-btn:hover {
          transform: translateY(-2px);
          box-shadow: 0 6px 20px rgba(255,107,107,0.6);
          background: linear-gradient(135deg, #ff5252, #ff9800);
        }
        
        .btn-icon {
          animation: rocket 1s ease-in-out infinite alternate;
        }
        
        /* Enhanced confetti effect */
        .confetti { 
          position: fixed; 
          top: -10px; 
          width: 12px; 
          height: 16px; 
          opacity: .9; 
          animation: fall 3s linear forwards;
          border-radius: 2px;
        }
        
        /* Animations */
        @keyframes fadeInUp {
          from { opacity: 0; transform: translateY(20px); }
          to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes slideInLeft {
          from { opacity: 0; transform: translateX(-30px); }
          to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes bounce {
          from { transform: translateY(0); }
          to { transform: translateY(-10px); }
        }
        
        @keyframes twinkle {
          0%, 100% { opacity: 1; transform: scale(1); }
          50% { opacity: 0.7; transform: scale(1.1); }
        }
        
        @keyframes pulse {
          from { transform: scale(1); opacity: 0.3; }
          to { transform: scale(1.1); opacity: 0.1; }
        }
        
        @keyframes glow {
          from { opacity: 0.3; }
          to { opacity: 0.6; }
        }
        
        @keyframes rotate {
          0%, 100% { transform: rotate(0deg) scale(1); }
          25% { transform: rotate(-5deg) scale(1.05); }
          75% { transform: rotate(5deg) scale(1.05); }
        }
        
        @keyframes shine {
          0%, 100% { opacity: 0.8; transform: scale(1); }
          50% { opacity: 1; transform: scale(1.2); }
        }
        
        @keyframes party {
          from { transform: rotate(-5deg) scale(1); }
          to { transform: rotate(5deg) scale(1.1); }
        }
        
        @keyframes rocket {
          from { transform: translateY(0); }
          to { transform: translateY(-3px); }
        }
        
        @keyframes fall { 
          to { 
            transform: translateY(110vh) rotate(720deg); 
            opacity: 0; 
          } 
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
          .badge-card {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
          }
          
          .badge-modal-title {
            font-size: 1.5rem;
          }
          
          .trophy-icon {
            font-size: 2.5rem;
          }
        }
      </style>
    @endif
    <!-- Spinner Start -->
    <div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">
      <div class="spinner-grow text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    @include('layouts.navbar')
    <div style="height: 120px"></div>

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
      <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">My Donations</h3>
        <p class="fs-5 text-white mb-4">Thank you for your generosity. Here is your donation history.</p>
        <ol class="breadcrumb justify-content-center mb-0">
          <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="#">Pages</a></li>
          <li class="breadcrumb-item active text-white">Donations History</li>
        </ol>
      </div>
    </div>
    <!-- Header End -->

    <!-- History Section Start -->
    <div class="container py-5">
      <div class="text-center mx-auto pb-4" style="max-width: 800px;">
        <h5 class="text-uppercase text-primary">Donations</h5>
        <h1 class="mb-0">Your contribution makes a difference</h1>
      </div>

      @if (session('status'))
        <div class="alert alert-success small mb-2">{{ session('status') }}</div>
      @endif
      @if (session('points_earned'))
        <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
          <span class="badge bg-success">+{{ (int)session('points_earned') }}</span>
          <strong>points earned</strong> 🎉
        </div>
      @endif

      @isset($totalPoints)
      <div class="row g-3 mb-4">
        <div class="col-md-4">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center justify-content-between">
              <div>
                <div class="text-muted text-uppercase small">Total Points</div>
                <div class="display-6 fw-bold">{{ (int)$totalPoints }}</div>
              </div>
              <i class="fas fa-star text-warning" style="font-size: 2rem;"></i>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <div class="text-muted text-uppercase small">Badges</div>
                <div class="small text-muted">
                  {{ isset($ownedSlugs) ? count($ownedSlugs) : 0 }} / {{ isset($allBadges) ? $allBadges->count() : (($badges??collect())->count()) }} earned
                </div>
              </div>
              <div class="d-flex flex-wrap gap-2">
                @php
                  $ownedSet = isset($ownedSlugs)
                    ? collect($ownedSlugs)
                    : collect(($badges ?? collect())->pluck('slug')->all());
                @endphp
                @foreach(($allBadges ?? collect()) as $ab)
                  @php 
                    $owned = $ownedSet->contains($ab->slug);
                    $progressHint = null;
                    if (!$owned) {
                      if ($ab->slug === 'donor_bronze') { $progressHint = max(0, 50 - (float)($donationSum ?? 0)); }
                      elseif ($ab->slug === 'donor_silver') { $progressHint = max(0, 200 - (float)($donationSum ?? 0)); }
                      elseif ($ab->slug === 'donor_gold') { $progressHint = max(0, 500 - (float)($donationSum ?? 0)); }
                      elseif ($ab->slug === 'protector_oceans') { $progressHint = max(0, 100 - (float)($event2Sum ?? 0)); }
                    }
                  @endphp
                  <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded border position-relative {{ $owned ? 'bg-light badge-owned' : 'bg-white opacity-75' }}" title="{{ $ab->description }}">
                    <span style="font-size:1.1rem" class="{{ $owned ? '' : 'text-muted' }}">{{ $ab->icon }}</span>
                    <span class="{{ $owned ? 'fw-semibold' : 'text-muted' }}">{{ $ab->name }}</span>
                    @unless($owned)
                      <span class="badge bg-secondary-subtle text-secondary">Locked</span>
                      @if(!is_null($progressHint) && $progressHint > 0)
                        <span class="ms-1 small text-muted" title="{{ number_format($progressHint,0) }} TND to unlock">(+{{ number_format($progressHint,0) }} TND)</span>
                      @endif
                    @endunless
                  </div>
                @endforeach
                @if(isset($allBadges) && $allBadges->isEmpty())
                  <div class="text-muted">No badges configured yet.</div>
                @endif
              </div>
              @if(isset($allBadges) && $allBadges->count())
                <div class="mt-2">
                  <a class="small text-decoration-none" data-bs-toggle="collapse" href="#badgeRules" role="button" aria-expanded="false" aria-controls="badgeRules">
                    <i class="fas fa-info-circle me-1 text-primary"></i>How to earn badges?
                  </a>
                  <div class="collapse" id="badgeRules">
                    <div class="card card-body border-0 p-2 small text-muted">
                      <ul class="mb-0 ps-3">
                        <li>Donateur Bronze: total ≥ 50 TND</li>
                        <li>Donateur Argent: total ≥ 200 TND</li>
                        <li>Donateur Or: total ≥ 500 TND</li>
                        <li>Protecteur des Océans: ≥ 100 TND sur l’événement Écosystème</li>
                      </ul>
                    </div>
                  </div>
                </div>
              @endif
              <style>
                .badge-owned { box-shadow: 0 0 0 rgba(255,215,0,0.8); animation: glow 1.5s ease-in-out infinite; }
                @keyframes glow { 0%{box-shadow:0 0 0 rgba(255,215,0,0.6);} 50%{box-shadow:0 0 16px rgba(255,215,0,0.9);} 100%{box-shadow:0 0 0 rgba(255,215,0,0.6);} }
              </style>
            </div>
          </div>
        </div>
      </div>
      @endisset

      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
            <div>
              <span class="badge bg-primary rounded-pill me-2"><i class="fas fa-list me-1"></i> {{ $dons->total() }} donations</span>
              <span class="text-muted">Showing page {{ $dons->currentPage() }} of {{ $dons->lastPage() }}</span>
            </div>
            <a href="{{ route('donation') }}#donate" class="btn-hover-bg btn btn-primary text-white py-2 px-4">
              <i class="fas fa-donate me-2"></i>Donate again
            </a>
          </div>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th scope="col">Date</th>
              <th scope="col">Amount</th>
              <th scope="col">Payment method</th>
              <th scope="col">Event</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($dons as $don)
              <tr>
                <td>
                  <i class="far fa-calendar-alt text-primary me-1"></i>
                  {{ optional($don->date_don)->format('d/m/Y H:i') }}
                </td>
                <td>
                  <span class="badge bg-success fs-6">{{ number_format((float)$don->montant, 2, ',', ' ') }} TND</span>
                </td>
                <td>
                  @php $m = $don->moyen_paiement; @endphp
                  @switch($m)
                    @case('carte')
                      <i class="far fa-credit-card text-primary me-1"></i> Card
                      @break
                    @case('paypal')
                      <i class="fab fa-paypal text-primary me-1"></i> PayPal
                      @break
                    @case('virement_bancaire')
                      <i class="fas fa-university text-primary me-1"></i> Bank transfer
                      @break
                    @case('paymee')
                    @case('test')
                      <i class="far fa-credit-card text-primary me-1"></i> e‑Dinar
                      @break
                    @default
                      <i class="fas fa-money-bill text-primary me-1"></i> {{ ucfirst(str_replace('_',' ', $m)) }}
                  @endswitch
                </td>
                <td>
                  @if ($don->evenement_id)
                    <div class="d-flex align-items-center gap-2">
                      @php $ev = $don->event; @endphp
                      @if ($ev)
                        <a href="{{ route('events.show', $ev) }}" class="text-decoration-none text-dark fw-semibold">{{ $ev->title }}</a>
                      @else
                        <span class="text-dark fw-semibold">Événement #{{ $don->evenement_id }}</span>
                      @endif
                      <a class="btn btn-sm btn-outline-primary" href="{{ route('donation') }}?event={{ $don->evenement_id }}#donate">Donate again</a>
                    </div>
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center py-5">
                  <div class="mb-3">
                    <i class="fas fa-leaf text-primary" style="font-size: 2rem;"></i>
                  </div>
                  <p class="mb-3">You haven't made any donations yet.</p>
                  <a href="{{ route('donation') }}#donate" class="btn-hover-bg btn btn-primary text-white py-2 px-4">Make your first donation</a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-4 d-flex justify-content-center">
        {{ $dons->links('pagination::bootstrap-5-sm') }}
      </div>
    </div>
    <!-- History Section End -->

    @include('layouts.footer')

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <!-- JavaScript Libraries -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/lib/easing/easing.min.js"></script>
    <script src="/lib/waypoints/waypoints.min.js"></script>
    <script src="/lib/counterup/counterup.min.js"></script>
    <script src="/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="/lib/lightbox/js/lightbox.min.js"></script>

    <!-- Template Javascript -->
    <script src="/js/main.js"></script>
    @if (session('new_badges'))
      <script>
        (function(){
          var modalEl = document.getElementById('badgeModal');
          if (modalEl) {
            var m = new bootstrap.Modal(modalEl); m.show();
          }
          
          // Enhanced confetti celebration
          var colors = ['#ffd700', '#ff6b6b', '#4ecdc4', '#45b7d1', '#96ceb4', '#ffeaa7', '#dda0dd', '#98d8c8', '#f7dc6f', '#bb8fce'];
          var shapes = ['▲', '●', '■', '♦', '★', '♥', '♠', '♣'];
          
          // Create multiple waves of confetti
          for (var wave = 0; wave < 3; wave++) {
            setTimeout(function(w) {
              for (var i = 0; i < 80; i++) {
                var div = document.createElement('div');
                div.className = 'confetti';
                
                // Random positioning and styling
                div.style.left = (Math.random() * 100) + 'vw';
                div.style.background = colors[Math.floor(Math.random() * colors.length)];
                
                // Add shapes occasionally
                if (Math.random() > 0.7) {
                  div.textContent = shapes[Math.floor(Math.random() * shapes.length)];
                  div.style.background = 'transparent';
                  div.style.fontSize = '16px';
                  div.style.display = 'flex';
                  div.style.alignItems = 'center';
                  div.style.justifyContent = 'center';
                }
                
                div.style.transform = 'translateY(0) rotate(' + (Math.random() * 360) + 'deg)';
                div.style.animationDelay = (Math.random() * 1.5) + 's';
                div.style.animationDuration = (2 + Math.random() * 2) + 's';
                
                // Add some physics variety
                div.style.setProperty('--random-x', (Math.random() - 0.5) * 100 + 'px');
                
                document.body.appendChild(div);
                setTimeout(function(d) { 
                  if (d && d.parentNode) d.remove(); 
                }, 5000, div);
              }
            }, wave * 500, wave);
          }
          
          // Add some floating stars
          setTimeout(function() {
            for (var i = 0; i < 20; i++) {
              var star = document.createElement('div');
              star.textContent = '✨';
              star.style.position = 'fixed';
              star.style.left = (Math.random() * 100) + 'vw';
              star.style.top = (Math.random() * 100) + 'vh';
              star.style.fontSize = '20px';
              star.style.pointerEvents = 'none';
              star.style.animation = 'twinkle 2s ease-in-out infinite';
              star.style.animationDelay = (Math.random() * 2) + 's';
              star.style.zIndex = '9999';
              
              document.body.appendChild(star);
              setTimeout(function(s) { 
                if (s && s.parentNode) s.remove(); 
              }, 4000, star);
            }
          }, 1000);
        })();
      </script>
    @endif
  </body>

</html>