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
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
              <h5 class="modal-title" id="badgeModalLabel">Badge unlocked!</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              @php $nb = (array) session('new_badges'); @endphp
              @foreach ($nb as $b)
                <div class="d-flex align-items-center gap-3 p-3 mb-2 rounded bg-light">
                  <div style="font-size:2rem">{{ $b['icon'] ?? '🏅' }}</div>
                  <div>
                    <div class="fw-bold">{{ $b['name'] ?? ($b['slug'] ?? 'New badge') }}</div>
                    <div class="text-muted small">{{ $b['description'] ?? '' }}</div>
                  </div>
                </div>
              @endforeach
              <div class="text-center mt-2">
                <span class="badge bg-success-subtle text-success">Well done! Keep going 🎉</span>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">Awesome</button>
            </div>
          </div>
        </div>
      </div>
      <style>
        /* Simple confetti effect */
        .confetti { position: fixed; top: -10px; width: 10px; height: 14px; opacity: .9; animation: fall 2.5s linear forwards; }
        @keyframes fall { to { transform: translateY(110vh) rotate(360deg); opacity: 0.8; } }
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
        @php
          // Static event labels used on the public site; adjust as needed if real events are added
          $eventLabels = [
            1 => 'Organic',
            2 => 'Ecosystem',
            3 => 'Recycling',
            4 => 'Awareness Day',
          ];
        @endphp
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
                      <span class="text-dark fw-semibold">{{ $eventLabels[$don->evenement_id] ?? ('Event #'.$don->evenement_id) }}</span>
                      <a class="btn btn-sm btn-outline-primary" href="{{ url('donation.html?event='.$don->evenement_id.'#donate') }}">Donate again</a>
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
        {{ $dons->links('pagination::bootstrap-5') }}
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
          // quick confetti shower
          var colors = ['#28a745','#ffc107','#17a2b8','#dc3545','#6610f2'];
          for (var i=0;i<60;i++) {
            var div = document.createElement('div');
            div.className = 'confetti';
            div.style.left = (Math.random()*100)+'vw';
            div.style.background = colors[Math.floor(Math.random()*colors.length)];
            div.style.transform = 'translateY(0) rotate('+(Math.random()*360)+'deg)';
            div.style.animationDelay = (Math.random()*0.7)+'s';
            document.body.appendChild(div);
            setTimeout(function(d){ d.remove(); }, 3000, div);
          }
        })();
      </script>
    @endif
  </body>

</html>