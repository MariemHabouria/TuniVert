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
      <div class="modal fade" id="badgeModal" tabindex="-1" aria-labelledby="badgeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content border-0 shadow-lg badge-modal-content">
            <div class="modal-header badge-modal-header">
              <div class="badge-modal-title-container">
                <div class="trophy-icon">🏆</div>
                <h3 class="modal-title badge-modal-title" id="badgeModalLabel">
                  <span class="badge-unlock-text">Badge Unlocked!</span>
                  <div class="sparkles">✨ ✨ ✨</div>
                </h3>
              </div>
              <button type="button" class="btn-close btn-close-white badge-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body badge-modal-body">
              @php $nb = (array) session('new_badges'); @endphp
              @foreach ($nb as $b)
                <div class="badge-card">
                  <div class="badge-glow"></div>
                  <div class="badge-icon-container">
                    <div class="badge-icon">{{ $b['icon'] ?? '🏅' }}</div>
                    <div class="badge-shine"></div>
                  </div>
                  <div class="badge-details">
                    <h4 class="badge-name">{{ $b['name'] ?? ($b['slug'] ?? 'New Badge') }}</h4>
                    <p class="badge-description">{{ $b['description'] ?? 'Congratulations on your achievement!' }}</p>
                  </div>
                  <div class="badge-celebration">🎉</div>
                </div>
              @endforeach
              <div class="celebration-message">
                <div class="success-badge">
                  <span class="success-text">Outstanding Achievement!</span>
                  <div class="success-subtitle">Keep up the amazing work! �</div>
                </div>
              </div>
            </div>
            <div class="modal-footer badge-modal-footer">
              <button type="button" class="btn badge-awesome-btn" data-bs-dismiss="modal">
                <span class="btn-text">Awesome!</span>
                <span class="btn-icon">🚀</span>
              </button>
            </div>
          </div>
        </div>
      </div>
      <style>
        /* Enhanced Badge Modal Styles */
        .badge-modal-content {
          background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
          border-radius: 20px;
          overflow: hidden;
          position: relative;
        }
        
        .badge-modal-content::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, transparent 50%, rgba(255,255,255,0.1) 100%);
          pointer-events: none;
        }
        
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

    <!-- Navbar start -->
    <div class="container-fluid fixed-top px-0">
      <div class="container px-0">
        <div class="topbar">
          <div class="row align-items-center justify-content-center">
            <div class="col-md-8">
              <div class="topbar-info d-flex flex-wrap">
                <a href="#" class="text-light me-4"><i class="fas fa-envelope text-white me-2"></i>Tunivert@gmail.tn</a>
                <a href="#" class="text-light"><i class="fas fa-phone-alt text-white me-2"></i>+01234567890</a>
              </div>
            </div>
            <div class="col-md-4">
              <div class="topbar-icon d-flex align-items-center justify-content-end">
                <a href="#" class="btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
                <a href="#" class="btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
                <a href="#" class="btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
                <a href="#" class="btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
              </div>
            </div>
          </div>
        </div>
        <nav class="navbar navbar-light bg-light navbar-expand-xl">
          <a href="/index.html" class="navbar-brand ms-3">
            <h1 class="text-primary display-5">Environs</h1>
          </a>
          <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="fa fa-bars text-primary"></span>
          </button>
          <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
            <div class="navbar-nav ms-auto">
              <a href="/index.html" class="nav-item nav-link">Home</a>
              <a href="/about.html" class="nav-item nav-link">About</a>
              <a href="/service.html" class="nav-item nav-link">Services</a>
              <a href="/causes.html" class="nav-item nav-link">Causes</a>
              <a href="/events.html" class="nav-item nav-link">Events</a>
              <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                  <a href="/blog.html" class="dropdown-item">Blog</a>
                  <a href="/gallery.html" class="dropdown-item">Gallery</a>
                  <a href="/volunteer.html" class="dropdown-item">Volunteers</a>
                  <a href="/donation.html" class="dropdown-item">Donation</a>
                  <a href="/404.html" class="dropdown-item">404 Error</a>
                </div>
              </div>
              <a href="/contact.html" class="nav-item nav-link">Contact</a>
            </div>
            <div class="d-flex align-items-center flex-nowrap pt-xl-0" style="margin-left: 15px;">
              @guest
                <a href="{{ route('login') }}" class="btn-hover-bg btn btn-primary text-white py-2 px-4 me-2">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary py-2 px-4">Sign Up</a>
              @endguest

              @auth
                <a href="{{ route('donations.history') }}" class="btn btn-outline-primary me-3 active">My Donations</a>
                <div class="d-inline-flex align-items-center gap-2">
                  <span class="fw-semibold text-dark">Bonjour, {{ Auth::user()->name }}</span>
                  <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger py-2 px-4">Logout</button>
                  </form>
                </div>
              @endauth
            </div>
          </div>
        </nav>
      </div>
    </div>
    <!-- Navbar End -->

    <!-- Header Start -->
    <div class="container-fluid bg-breadcrumb">
      <div class="container text-center py-5" style="max-width: 900px;">
        <h3 class="text-white display-3 mb-4">My Donations</h3>
        <p class="fs-5 text-white mb-4">Thank you for your generosity. Here is your donation history.</p>
        <ol class="breadcrumb justify-content-center mb-0">
          <li class="breadcrumb-item"><a href="/index.html">Home</a></li>
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
            <a href="{{ url('donation.html#donate') }}" class="btn-hover-bg btn btn-primary text-white py-2 px-4">
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
                  <a href="{{ url('donation.html#donate') }}" class="btn-hover-bg btn btn-primary text-white py-2 px-4">Make your first donation</a>
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

    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-body py-5">
      <div class="container py-5">
        <div class="row g-5">
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="footer-item">
              <h4 class="mb-4 text-white">Newsletter</h4>
              <p class="mb-4">Dolor amet sit justo amet elitr clita ipsum elitr est.Lorem ipsum dolor sit amet, consectetur adipiscing elit consectetur adipiscing elit.</p>
              <div class="position-relative mx-auto">
                <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="text" placeholder="Enter your email">
                <button type="button" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">SignUp</button>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="footer-item d-flex flex-column">
              <h4 class="mb-4 text-white">Our Services</h4>
              <a href=""><i class="fas fa-angle-right me-2"></i> Ocean Turtle</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> White Tiger</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Social Ecology</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Loneliness</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Beauty of Life</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Present for You</a>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="footer-item d-flex flex-column">
              <h4 class="mb-4 text-white">Volunteer</h4>
              <a href=""><i class="fas fa-angle-right me-2"></i> Karen Dawson</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Jack Simmons</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Michael Linden</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Simon Green</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Natalie Channing</a>
              <a href=""><i class="fas fa-angle-right me-2"></i> Caroline Gerwig</a>
            </div>
          </div>
          <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="footer-item">
              <h4 class="mb-4 text-white">Our Gallery</h4>
              <div class="row g-2">
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-1.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-1.jpg" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-2.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-2.jpg" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-3.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-3.jpg" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-4.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-4.jpg" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-5.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-5.jpg" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
                <div class="col-4">
                  <div class="footer-gallery">
                    <img src="/img/gallery-footer-6.jpg" class="img-fluid w-100" alt="">
                    <div class="footer-search-icon">
                      <a href="/img/gallery-footer-6.jpg" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Footer End -->

    <!-- Copyright Start -->
    <div class="container-fluid copyright py-4">
      <div class="container">
        <div class="row g-4 align-items-center">
          <div class="col-md-4 text-center text-md-start mb-md-0">
            <span class="text-body"><a href="#"><i class="fas fa-copyright text-light me-2"></i>Your Site Name</a>, All right reserved.</span>
          </div>
          <div class="col-md-4 text-center">
            <div class="d-flex align-items-center justify-content-center">
              <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-twitter"></i></a>
              <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-instagram"></i></a>
              <a href="#" class="btn-hover-color btn-square text-white me-2"><i class="fab fa-pinterest"></i></a>
              <a href="#" class="btn-hover-color btn-square text-white me-0"><i class="fab fa-linkedin-in"></i></a>
            </div>
          </div>
          <div class="col-md-4 text-center text-md-end text-body">
            Designed By <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> Distributed By <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Copyright End -->

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