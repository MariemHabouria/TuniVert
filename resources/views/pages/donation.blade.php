<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Environs - Environmental & Nature Website Template</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
        <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet">


          <!-- Customized Bootstrap Stylesheet -->
<link href="css/bootstrap.css" rel="stylesheet">      <!-- ← remplace .min.css par .css -->
<link href="css/style.css" rel="stylesheet">
    </head>

    <body>

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
                    <a href="index.html" class="navbar-brand ms-3">
                        <h1 class="text-primary display-5">Environs</h1>
                    </a>
                    <button class="navbar-toggler py-2 px-3 me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars text-primary"></span>
                    </button>
                    <div class="collapse navbar-collapse bg-light" id="navbarCollapse">
                        <div class="navbar-nav ms-auto">
                            <a href="index.html" class="nav-item nav-link">Home</a>
                            <a href="about.html" class="nav-item nav-link">About</a>
                            <a href="service.html" class="nav-item nav-link">Services</a>
                            <a href="causes.html" class="nav-item nav-link">Causes</a>
                            <a href="events.html" class="nav-item nav-link">Events</a>
                            <div class="nav-item dropdown">
                                <a href="#" class="nav-link dropdown-toggle active" data-bs-toggle="dropdown">Pages</a>
                                <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                    <a href="blog.html" class="dropdown-item">Blog</a>
                                    <a href="gallery.html" class="dropdown-item">Gallery</a>
                                    <a href="volunteer.html" class="dropdown-item">Volunteers</a>
                                    <a href="donation.html" class="dropdown-item active">Donation</a>
                                    <a href="404.html" class="dropdown-item">404 Error</a>
                                </div>
                            </div>
                            <a href="contact.html" class="nav-item nav-link">Contact</a>
                        </div>
                                                <div class="d-flex align-items-center flex-nowrap pt-xl-0" style="margin-left: 15px;">
@guest
  <a href="{{ route('login') }}" class="btn-hover-bg btn btn-primary text-white py-2 px-4 me-2">Log In</a>
  <a href="{{ route('register') }}" class="btn btn-outline-primary py-2 px-4">Sign Up</a>
@endguest

@auth
    <a href="{{ route('donations.history') }}" class="btn btn-outline-primary me-3">My Donations</a>
    <div class="d-inline-flex align-items-center gap-2">
    <span class="fw-semibold text-dark">Bonjour, {{ Auth::user()->name }}</span>
    <form action="{{ route('logout') }}" method="POST" class="d-inline">
      @csrf
      <button type="submit" class="btn btn-danger py-2 px-4">Logout</button>
    </form>
  </div>
@endauth                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <!-- Navbar End -->

        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Donation</h1>
                <p class="fs-5 text-white mb-4">Help today because tomorrow you may be the one who needs more helping!</p>
                <ol class="breadcrumb justify-content-center mb-0">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Pages</a></li>
                    <li class="breadcrumb-item active text-white">Donation</li>
                </ol>    
            </div>
        </div>
        <!-- Header End -->

        <!-- Donation Start -->
        <div class="container-fluid donation py-5">
            <div class="container py-5">
                <div class="text-center mx-auto pb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Donation</h5>
                    <h1 class="mb-0">Your money will save our life</h1>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/donation-1.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Organic</h5>
                                <a href="{{ url('donation.html?campaign=organic#donate') }}" class="btn-hover-color display-6 text-white">Help Us More</a>
                                <h4 class="text-white mb-4">Protect Environments</h4>
                                <p class="text-white mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?campaign=organic#donate') }}">Donate !</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/service-2.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Ecosystem</h5>
                                <a href="{{ url('donation.html?campaign=ecosystem#donate') }}" class="btn-hover-color display-6 text-white">Help Us More</a>
                                <h4 class="text-white mb-4">Protect Environments</h4>
                                <p class="text-white mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?campaign=ecosystem#donate') }}">Donate !</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="donation-item">
                            <img src="img/donation-3.jpg" class="img-fluid w-100" alt="Image">
                            <div class="donation-content d-flex flex-column">
                                <h5 class="text-uppercase text-primary mb-4">Recycling</h5>
                                <a href="{{ url('donation.html?campaign=recycling#donate') }}" class="btn-hover-color display-6 text-white">Help Us More</a>
                                <h4 class="text-white mb-4">Protect Environments</h4>
                                <p class="text-white mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</p>
                                <div class="donation-btn d-flex align-items-center justify-content-start">
                                    <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?campaign=recycling#donate') }}">Donate !</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex align-items-center justify-content-center">
                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ route('donations.history') }}">All Donation</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Donation End -->


                <!-- Inline Donation Form Start -->
        <div id="donate" class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="p-4 p-md-5 bg-light rounded shadow-sm">
                                                @php
                                                    $campaigns = [
                                                        'organic' => [
                                                            'title' => 'Organic',
                                                            'headline' => 'Help Us More',
                                                            'subtitle' => 'Protect Environments',
                                                            'desc' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's",
                                                            'img' => 'img/donation-1.jpg',
                                                        ],
                                                        'ecosystem' => [
                                                            'title' => 'Ecosystem',
                                                            'headline' => 'Help Us More',
                                                            'subtitle' => 'Protect Environments',
                                                            'desc' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's",
                                                            'img' => 'img/service-2.jpg',
                                                        ],
                                                        'recycling' => [
                                                            'title' => 'Recycling',
                                                            'headline' => 'Help Us More',
                                                            'subtitle' => 'Protect Environments',
                                                            'desc' => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's",
                                                            'img' => 'img/donation-3.jpg',
                                                        ],
                                                    ];
                                                    $slug = request('campaign');
                                                    $sel = $slug && isset($campaigns[$slug]) ? $campaigns[$slug] : null;
                                                    // Resolve an event id from either explicit ?event or from selected campaign
                                                    $campaignEventIds = [ 'organic' => 1, 'ecosystem' => 2, 'recycling' => 3 ];
                                                    $eventParam = request('event');
                                                    $resolvedEventId = $eventParam ? (int)$eventParam : ($slug && isset($campaignEventIds[$slug]) ? $campaignEventIds[$slug] : null);
                                                    $eventLabels = [ 1=>'Organic', 2=>'Ecosystem', 3=>'Recycling', 4=>'Awareness Day' ];
                                                    $resolvedEventTitle = $sel['title'] ?? ($resolvedEventId ? ($eventLabels[$resolvedEventId] ?? ('Event #'.$resolvedEventId)) : null);
                                                @endphp

                                                <h2 class="mb-3">Make a Donation</h2>
                                                <p class="text-muted mb-4">Support Tunivert with a one-time or recurring donation.</p>

                                                @if ($sel)
                                                    <div class="card border-0 shadow-sm mb-4">
                                                        <div class="row g-0 align-items-center">
                                                            <div class="col-md-4">
                                                                <img src="/{{ $sel['img'] }}" class="img-fluid rounded-start" alt="{{ $sel['title'] }}">
                                                            </div>
                                                            <div class="col-md-8">
                                                                <div class="card-body">
                                                                    <div class="d-flex justify-content-between align-items-start">
                                                                        <div>
                                                                            <h5 class="card-title text-primary mb-1">{{ $sel['title'] }}</h5>
                                                                            <h6 class="card-subtitle mb-2">{{ $sel['subtitle'] }}</h6>
                                                                        </div>
                                                                        <a href="{{ url('donation.html#donate') }}" class="small">change</a>
                                                                    </div>
                                                                    <p class="card-text mb-1 fw-semibold">{{ $sel['headline'] }}</p>
                                                                    <p class="card-text text-muted">{{ $sel['desc'] }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                        @if (config('services.testpay.enabled'))
                        <!-- E-Dinar Payment Modal (Design Upgrade) -->
                        <div class="modal fade" id="edinarModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content edinar-modal">
                                    <form method="POST" action="{{ route('payments.test.complete') }}" id="edinar-form">
                                        @csrf
                                        <input type="hidden" name="token" id="edinar-token" />
                                        <input type="hidden" name="amount" id="edinar-amount" value="0" />
                                        <input type="hidden" name="order_id" id="edinar-order-id" value="" />
                                        <input type="hidden" name="is_anonymous" id="edinar-is-anonymous" value="0" />
                                        
                                        <div class="modal-header edinar-header">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="edinar-logo">
                                                    <i class="fas fa-credit-card"></i>
                                                </div>
                                                <div>
                                                    <div class="d-flex align-items-center gap-2 mb-1">
                                                        <span class="badge edinar-badge">e‑DINAR</span>
                                                        <span class="badge d17-badge">D17</span>
                                                    </div>
                                                    <h5 class="modal-title mb-0">Paiement Sécurisé</h5>
                                                </div>
                                            </div>
                                            <div class="payment-amount-display">
                                                <span id="edinar-amount-label">0.000 TND</span>
                                            </div>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body edinar-body">
                                            <!-- D17 Logo Section -->
                                            <div class="d17-logo-section">
                                                <div class="d17-logo-container">
                                                    <img src="{{ asset('img/d17.png') }}" alt="D17" class="d17-logo-img">
                                                    <div class="d17-text">
                                                        <h6 class="mb-1">Paiement via D17</h6>
                                                        <small class="text-muted">Application mobile sécurisée</small>
                                                    </div>
                                                </div>
                                                <div class="security-indicators">
                                                    <span class="security-badge"><i class="fas fa-shield-alt"></i> SSL</span>
                                                    <span class="security-badge"><i class="fas fa-lock"></i> 3D Secure</span>
                                                </div>
                                            </div>

                                            <!-- Card Preview -->
                                            <div class="edinar-card-preview" id="edinarCardPreview">
                                                <div class="card-chip"></div>
                                                <div class="card-number" id="cardNumberPreview">•••• •••• •••• ••••</div>
                                                <div class="card-details">
                                                    <div class="card-holder">
                                                        <small>TITULAIRE</small>
                                                        <div id="cardHolderPreview">VOTRE NOM</div>
                                                    </div>
                                                    <div class="card-expiry">
                                                        <small>EXPIRE</small>
                                                        <div id="cardExpiryPreview">MM/YY</div>
                                                    </div>
                                                </div>
                                                <div class="card-brand">e‑DINAR</div>
                                            </div>

                                            <!-- Form Fields -->
                                            <div class="edinar-form-section">
                                                <div class="form-group mb-3">
                                                    <label class="form-label" for="edinar_card">
                                                        <i class="fas fa-credit-card me-2"></i>Numéro de carte e‑DINAR
                                                    </label>
                                                    <input type="text" class="form-control edinar-input" id="edinar_card" name="card_number" 
                                                           placeholder="1234 5678 9012 3456" maxlength="23" inputmode="numeric" 
                                                           autocomplete="off" required>
                                                    <div class="form-text">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Saisissez votre numéro de carte e‑DINAR (12 à 19 chiffres)
                                                    </div>
                                                    <div class="invalid-feedback" id="cardNumberError"></div>
                                                </div>

                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="edinar_exp">
                                                            <i class="fas fa-calendar-alt me-2"></i>Date d'expiration
                                                        </label>
                                                        <input type="text" class="form-control edinar-input" id="edinar_exp" name="expiry" 
                                                               placeholder="MM/YY" maxlength="5" autocomplete="off" required>
                                                        <div class="invalid-feedback" id="expiryError"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="edinar_cvv">
                                                            <i class="fas fa-lock me-2"></i>Code CVV
                                                            <span class="cvv-help" data-bs-toggle="tooltip" title="Code à 3 chiffres au dos de votre carte">
                                                                <i class="fas fa-question-circle"></i>
                                                            </span>
                                                        </label>
                                                        <input type="password" class="form-control edinar-input" id="edinar_cvv" name="cvv" 
                                                               placeholder="***" maxlength="4" autocomplete="off" required>
                                                        <div class="invalid-feedback" id="cvvError"></div>
                                                    </div>
                                                </div>

                                                <div class="row g-3 mt-2">
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="edinar_pin">
                                                            <i class="fas fa-key me-2"></i>Code PIN
                                                        </label>
                                                        <input type="password" class="form-control edinar-input" id="edinar_pin" name="pin" 
                                                               placeholder="****" maxlength="6" inputmode="numeric" autocomplete="off" required>
                                                        <div class="form-text">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Code PIN de votre carte (4 à 6 chiffres)
                                                        </div>
                                                        <div class="invalid-feedback" id="pinError"></div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label" for="edinar_cin">
                                                            <i class="fas fa-id-card me-2"></i>Numéro CIN
                                                        </label>
                                                        <input type="text" class="form-control edinar-input" id="edinar_cin" name="cin" 
                                                               placeholder="01234567" maxlength="12" inputmode="numeric" autocomplete="off" required>
                                                        <div class="form-text">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Numéro de votre carte d'identité (8 chiffres)
                                                        </div>
                                                        <div class="invalid-feedback" id="cinError"></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Security Notice -->
                                            <div class="security-notice">
                                                <div class="security-icon">
                                                    <i class="fas fa-shield-alt"></i>
                                                </div>
                                                <div class="security-text">
                                                    <strong>Paiement 100% sécurisé</strong>
                                                    <p class="mb-0">Vos données sont cryptées et protégées selon les standards bancaires tunisiens.</p>
                                                </div>
                                            </div>

                                            <div id="edinar-modal-error" class="alert alert-danger" style="display:none;">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <span id="error-message"></span>
                                            </div>
                                        </div>

                                        <div class="modal-footer edinar-footer">
                                            <button type="button" class="btn btn-outline-light" id="edinar-cancel-btn" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-2"></i>Annuler
                                            </button>
                                            <button type="submit" class="btn btn-success edinar-pay-btn" id="edinar-submit-btn" disabled>
                                                <span class="btn-content">
                                                    <i class="fas fa-lock me-2"></i>Payer <span id="pay-amount">0.000 TND</span>
                                                </span>
                                                <span class="btn-loading" style="display: none;">
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Traitement...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <style>
                        .edinar-modal { border: none; border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); overflow: hidden; }
                        .edinar-header { background: linear-gradient(135deg, #dc2626, #b91c1c); color: white; border-bottom: none; padding: 1.5rem 2rem; position: relative; }
                        .edinar-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="0.5" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); pointer-events: none; }
                        .edinar-logo { width: 50px; height: 50px; background: rgba(255, 255, 255, 0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; backdrop-filter: blur(10px); }
                        .edinar-badge { background: rgba(255, 255, 255, 0.2) !important; color: white !important; border: 1px solid rgba(255, 255, 255, 0.3) !important; backdrop-filter: blur(10px); font-weight: 600; font-size: 0.75rem; }
                        .d17-badge { background: rgba(16, 185, 129, 0.2) !important; color: #10b981 !important; border: 1px solid rgba(16, 185, 129, 0.3) !important; backdrop-filter: blur(10px); font-weight: 600; font-size: 0.75rem; }
                        .payment-amount-display { font-size: 1.5rem; font-weight: 700; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); }
                        .edinar-body { padding: 2rem; background: #f8fafc; }
                        .d17-logo-section { display: flex; justify-content: space-between; align-items: center; background: white; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
                        .d17-logo-container { display: flex; align-items: center; gap: 1rem; }
                        .d17-logo-img { max-width: 80px; max-height: 50px; object-fit: contain; }
                        .security-indicators { display: flex; gap: 0.5rem; }
                        .security-badge { background: #f0f9ff; color: #0369a1; padding: 0.25rem 0.5rem; border-radius: 6px; font-size: 0.7rem; font-weight: 600; border: 1px solid #bae6fd; }
                        .edinar-card-preview { background: linear-gradient(135deg, #1f2937, #374151); color: white; padding: 1.5rem; border-radius: 16px; margin-bottom: 1.5rem; position: relative; overflow: hidden; min-height: 200px; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2); }
                        .edinar-card-preview::before { content: ''; position: absolute; top: -50%; right: -50%; width: 100%; height: 100%; background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent); transform: rotate(45deg); pointer-events: none; }
                        .card-chip { width: 40px; height: 30px; background: linear-gradient(135deg, #fbbf24, #f59e0b); border-radius: 6px; margin-bottom: 1rem; position: relative; }
                        .card-chip::before { content: ''; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 20px; height: 15px; background: rgba(0, 0, 0, 0.2); border-radius: 2px; }
                        .card-number { font-family: 'Courier New', monospace; font-size: 1.3rem; letter-spacing: 0.2em; margin: 1.5rem 0; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3); }
                        .card-details { display: flex; justify-content: space-between; align-items: end; margin-top: 2rem; }
                        .card-holder small, .card-expiry small { font-size: 0.7rem; opacity: 0.8; text-transform: uppercase; letter-spacing: 0.05em; }
                        .card-holder div, .card-expiry div { font-size: 0.9rem; font-weight: 600; margin-top: 0.25rem; text-transform: uppercase; }
                        .card-brand { position: absolute; top: 1.5rem; right: 1.5rem; font-weight: 700; font-size: 1.2rem; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5); color: #fbbf24; }
                        .edinar-form-section { background: white; padding: 1.5rem; border-radius: 12px; border: 1px solid #e5e7eb; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05); }
                        .form-label { font-weight: 600; color: #374151; margin-bottom: 0.5rem; display: flex; align-items: center; }
                        .form-label i { color: #dc2626; }
                        .edinar-input { border: 2px solid #e5e7eb; border-radius: 8px; padding: 0.75rem 1rem; font-size: 1rem; transition: all 0.3s ease; background: #f9fafb; }
                        .edinar-input:focus { border-color: #dc2626; background: white; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1); }
                        .edinar-input.is-valid { border-color: #10b981; background: #f0fdf4; }
                        .edinar-input.is-invalid { border-color: #ef4444; background: #fef2f2; }
                        .cvv-help { margin-left: 0.5rem; color: #6b7280; cursor: help; }
                        .security-notice { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); border: 1px solid #bae6fd; border-radius: 12px; padding: 1rem; margin-top: 1.5rem; display: flex; align-items: center; gap: 1rem; }
                        .security-icon { width: 40px; height: 40px; background: #0369a1; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
                        .security-text strong { color: #0369a1; font-size: 0.9rem; }
                        .security-text p { font-size: 0.8rem; color: #0369a1; margin-top: 0.25rem; }
                        .edinar-footer { background: linear-gradient(135deg, #374151, #1f2937); border-top: none; padding: 1.5rem 2rem; }
                        .edinar-pay-btn { background: linear-gradient(135deg, #10b981, #059669) !important; border: none !important; padding: 0.75rem 1.5rem; font-weight: 600; border-radius: 8px; transition: all 0.3s ease; position: relative; overflow: hidden; }
                        .edinar-pay-btn:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4); }
                        .edinar-pay-btn:disabled { background: linear-gradient(135deg, #9ca3af, #6b7280) !important; cursor: not-allowed; transform: none; box-shadow: none; }
                        .btn-outline-light { border-color: rgba(255, 255, 255, 0.3); color: rgba(255, 255, 255, 0.8); }
                        .btn-outline-light:hover { background: rgba(255, 255, 255, 0.1); border-color: rgba(255, 255, 255, 0.5); color: white; }
                        @media (max-width: 768px) { .modal-dialog { margin: 1rem; } .edinar-header, .edinar-body, .edinar-footer { padding: 1rem; } .d17-logo-section { flex-direction: column; gap: 1rem; text-align: center; } .security-indicators { justify-content: center; } .payment-amount-display { font-size: 1.2rem; margin-top: 0.5rem; } }
                        .shake { animation: shake 0.5s ease-in-out; }
                        @keyframes shake { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-5px); } 75% { transform: translateX(5px); } }
                        .pulse { animation: pulse 2s infinite; }
                        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); } }
                        </style>
                        @endif

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

                        @auth
                                                <form method="POST" action="{{ route('donations.store') }}" class="row g-3">
                            @csrf
                                                        @if ($sel)
                                                            <input type="hidden" name="campaign" value="{{ $slug }}">
                                                        @endif
                            <div class="col-12 col-md-6">
                                <label class="form-label">Amount (TND)</label>
                                                                <input type="number" step="0.01" min="1" name="montant" class="form-control" value="{{ request('amount') ?? old('montant') }}" required>
                                                                <div class="mt-2 d-flex flex-wrap gap-2">
                                                                    @foreach ([10,20,50,100] as $s)
                                                                        <button type="button" class="btn btn-sm btn-outline-secondary amount-suggest" data-amount="{{ $s }}">{{ $s }} TND</button>
                                                                    @endforeach
                                                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Payment method</label>
                                <select name="moyen_paiement" class="form-select" required>
                                    @if (config('services.testpay.enabled'))
                                    <option value="test">e‑DINAR</option>
                                    @endif
                                    <option value="virement_bancaire">Bank transfer</option>
                                </select>
                            </div>
                                                        @if ($resolvedEventId)
                                                            <div class="col-12">
                                                                <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
                                                                    <div>
                                                                        <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                                        Donating to event {{ $resolvedEventTitle }}
                                                                    </div>
                                                                    <a href="{{ url('donation.html#donate') }}" class="small">change</a>
                                                                </div>
                                                                <input type="hidden" name="evenement_id" value="{{ $resolvedEventId }}">
                                                            </div>
                                                        @else
                                                            <div class="col-12">
                                                                <label for="evenement_id" class="form-label">Événement (optionnel)</label>
                                                                <select class="form-select" id="evenement_id" name="evenement_id">
                                                                    <option value="">Don général</option>
                                                                    <option value="1" {{ request('event') == '1' ? 'selected' : '' }}>Campagne Organique - Tunis</option>
                                                                    <option value="2" {{ request('event') == '2' ? 'selected' : '' }}>Écosystème Durable - Sousse</option>
                                                                    <option value="3" {{ request('event') == '3' ? 'selected' : '' }}>Recyclage Communautaire - Sfax</option>
                                                                    <option value="4" {{ request('event') == '4' ? 'selected' : '' }}>Journée de Sensibilisation - Monastir</option>
                                                                    <option value="5" {{ request('event') == '5' ? 'selected' : '' }}>Forêts Urbaines - Tunis</option>
                                                                    <option value="6" {{ request('event') == '6' ? 'selected' : '' }}>Nettoyage des Plages - Sousse</option>
                                                                </select>
                                                                <small class="text-muted">Choisissez un événement spécifique ou laissez vide pour un don général</small>
                                                            </div>
                                                        @endif
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="donate-anon" name="is_anonymous">
                                    <label class="form-check-label" for="donate-anon">
                                        Je souhaite que mon don soit anonyme (mon nom n'apparaitra pas publiquement)
                                    </label>
                                </div>
                                <button id="donate-submit" class="btn-hover-bg btn btn-primary text-white py-2 px-4" data-method="standard">Donate now</button>
                            </div>
                        </form>
                                                @auth
                                                <div id="card-payment-wrapper" class="mt-4" style="display:none;">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-3"><i class="far fa-credit-card me-2 text-primary"></i>Secure Card Payment</h5>
                                                            <div id="payment-element"></div>
                                                            <div class="d-flex justify-content-end mt-3">
                                                                <button id="card-pay-btn" class="btn btn-primary"><i class="fas fa-lock me-2"></i>Pay securely</button>
                                                            </div>
                                                            <div id="payment-message" class="text-danger small mt-2" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="paypal-wrapper" class="mt-4" style="display:none;">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <div id="paypal-button-container"></div>
                                                            <div id="paypal-message" class="text-danger small mt-2" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if (config('services.paymee.api_key'))
                                                <div id="paymee-wrapper" class="mt-4" style="display:none;">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-3"><i class="fas fa-university me-2 text-primary"></i>e‑DINAR via Paymee</h5>
                                                            <p class="text-muted small">You'll be redirected to Paymee to complete a secure payment using e‑DINAR card or supported methods.</p>
                                                            <button id="paymee-pay-btn" class="btn btn-primary">Continue to Paymee</button>
                                                            <div id="paymee-message" class="text-danger small mt-2" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if (config('services.testpay.enabled'))
                                                <div id="testpay-wrapper" class="mt-4" style="display:none;">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-1"><i class="fas fa-university me-2 text-primary"></i>e‑DINAR (Mock) Test</h5>
                                                            <p class="text-muted small mb-3">Academic testing without a merchant account. Simulates the e‑DINAR redirect flow.</p>
                                                            <button id="testpay-pay-btn" class="btn btn-primary">Continue to TestPay</button>
                                                            <div id="testpay-message" class="text-danger small mt-2" style="display:none;"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <div id="bank-wrapper" class="mt-4" style="display:none;">
                                                    <div class="card border-0 shadow-sm">
                                                        <div class="card-body">
                                                            <h5 class="card-title mb-2"><i class="fas fa-university me-2 text-primary"></i>Bank transfer (Virement bancaire)</h5>
                                                            <p class="text-muted mb-3">Payez par virement bancaire et recevez votre reçu par email. Cliquez sur le bouton pour confirmer votre don et obtenir les instructions bancaires.</p>
                                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                                <button id="bank-open-modal" class="btn btn-primary">
                                                                    <i class="fas fa-money-check-alt me-2"></i>Procéder au virement
                                                                </button>
                                                                <small class="text-muted">Le montant et l’événement sélectionnés seront confirmés dans la fenêtre.</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endauth
                        @endauth

                        @guest
                        <div class="alert alert-info d-flex align-items-center justify-content-between" role="alert">
                            <div>
                                Please log in to make a donation.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('login') }}" class="btn btn-primary">Log In</a>
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Sign Up</a>
                            </div>
                        </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
        <!-- Inline Donation Form End -->


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
                                        <img src="img/gallery-footer-1.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-1.jpg" data-lightbox="footerGallery-1" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-2.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-2.jpg" data-lightbox="footerGallery-2" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-3.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-3.jpg" data-lightbox="footerGallery-3" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-4.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-4.jpg" data-lightbox="footerGallery-4" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                                <div class="col-4">
                                    <div class="footer-gallery">
                                        <img src="img/gallery-footer-5.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-5.jpg" data-lightbox="footerGallery-5" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
                                        </div>
                                    </div>
                               </div>
                               <div class="col-4">
									<div class="footer-gallery">
										<img src="img/gallery-footer-6.jpg" class="img-fluid w-100" alt="">
                                        <div class="footer-search-icon">
                                            <a href="img/gallery-footer-6.jpg" data-lightbox="footerGallery-6" class="my-auto"><i class="fas fa-search-plus text-white"></i></a>
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
                        <!--/*** This template is free as long as you keep the below author’s credit link/attribution link/backlink. ***/-->
                        <!--/*** If you'd like to use the template without the below author’s credit link/attribution link/backlink, ***/-->
                        <!--/*** you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". ***/-->
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
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/lightbox/js/lightbox.min.js"></script>
        

        <!-- Template Javascript -->
                        <script src="js/main.js"></script>
                        @if (config('services.stripe.key'))
                        <script src="https://js.stripe.com/v3/"></script>
                        @endif
                        
                                                @if (config('services.testpay.enabled'))
                                                <!-- TestPay Modal (e‑DINAR Mock) -->
                                                                        <div class="modal fade" id="testpayModal" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        <div class="modal-content">
                                                            <form method="POST" action="{{ route('payments.test.complete') }}" id="testpay-form">
                                                                @csrf
                                                                <input type="hidden" name="token" id="testpay-token" />
                                                                <div class="modal-header">
                                                                                            <div class="d-flex align-items-center gap-2">
                                                                                                <span class="badge bg-success-subtle text-success border border-success">e‑DINAR</span>
                                                                                                <h5 class="modal-title mb-0">Paiement — <span id="testpay-amount-label"></span></h5>
                                                                                            </div>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                                            <div class="p-2 mb-3 rounded" style="background: #ffffff url('{{ asset('img/d17.png') }}') center/contain no-repeat; border:1px solid #fde68a; min-height: 90px;"></div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for="tp_card">Numéro de carte</label>
                                                                        <input type="text" class="form-control" id="tp_card" name="card_number" placeholder="1234 5678 9012 3456" maxlength="23" inputmode="numeric" autocomplete="off" required>
                                                                        <div class="form-text">12 à 19 chiffres (simulation).</div>
                                                                    </div>
                                                                    <div class="row g-3">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label" for="tp_exp">Date d’expiration</label>
                                                                            <input type="text" class="form-control" id="tp_exp" name="expiry" placeholder="MM/YY" maxlength="5" autocomplete="off">
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label" for="tp_cvv">CVV</label>
                                                                            <input type="password" class="form-control" id="tp_cvv" name="cvv" placeholder="***" maxlength="4" autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                    <div class="row g-3 mt-1">
                                                                        <div class="col-md-6">
                                                                            <label class="form-label" for="tp_pin">Code PIN</label>
                                                                            <input type="password" class="form-control" id="tp_pin" name="pin" placeholder="****" maxlength="6" inputmode="numeric" autocomplete="off" required>
                                                                            <div class="form-text">4 à 6 chiffres (simulation).</div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <label class="form-label" for="tp_cin">CIN</label>
                                                                            <input type="text" class="form-control" id="tp_cin" name="cin" placeholder="01234567" maxlength="12" inputmode="numeric" autocomplete="off" required>
                                                                            <div class="form-text">8 chiffres (simulation).</div>
                                                                        </div>
                                                                    </div>
                                                                    <div id="testpay-modal-error" class="text-danger small mt-2" style="display:none;"></div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-secondary" id="testpay-cancel-btn" data-bs-dismiss="modal">Annuler</button>
                                                                    <button type="submit" class="btn btn-primary" id="testpay-submit-btn">Payer</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                <!-- Bank Transfer Modal -->
                <div class="modal fade" id="bankTransferModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content border-0 rounded-3 overflow-hidden">
                            <form method="POST" action="{{ route('donations.store') }}" id="bank-transfer-form">
                                @csrf
                                <input type="hidden" name="moyen_paiement" value="virement_bancaire">
                                <input type="hidden" name="evenement_id" id="bank-evenement-id" value="">
                                <input type="hidden" name="montant" id="bank-montant" value="">
                                <input type="hidden" name="is_anonymous" id="bank-is-anonymous" value="0">

                                <div class="bank-payment-container">
                                    <!-- Payment Information Side -->
                                    <div class="bank-payment-info">
                                        <div>
                                            <div class="bank-logo">
                                                <i class="fas fa-university" style="font-size: 2rem;"></i>
                                                <h1>TuniVert</h1>
                                            </div>
                                            <div class="payment-details">
                                                <div class="amount-display" id="bank-amount-display">0.000 TND</div>
                                                <div class="transaction-info">
                                                    <div class="info-row">
                                                        <span>Merchant:</span>
                                                        <strong>TuniVert Association</strong>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Mode:</span>
                                                        <strong>Virement bancaire</strong>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Date:</span>
                                                        <strong>{{ date('d/m/Y H:i') }}</strong>
                                                    </div>
                                                    <div class="info-row">
                                                        <span>Devise:</span>
                                                        <strong>Tunisian Dinar (TND)</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="security-badges">
                                            <div class="badge"><i class="fas fa-shield-alt"></i> SSL Secured</div>
                                            <div class="badge"><i class="fas fa-lock"></i> Privacy First</div>
                                            <div class="badge"><i class="fas fa-check-circle"></i> Verified</div>
                                        </div>
                                    </div>

                                    <!-- Confirmation Side -->
                                    <div class="bank-payment-form">
                                        <div class="form-header">
                                            <h2>Confirmer votre don</h2>
                                            <p>Après confirmation, vous recevrez un email avec la référence et les instructions de virement.</p>
                                        </div>

                                        <div class="card-preview">
                                            <div class="card-brand"><i class="fas fa-receipt"></i> Référence</div>
                                            <div class="card-number-display">Générée après confirmation</div>
                                            <div class="card-info-row">
                                                <div>
                                                    <div style="font-size: 0.7rem; opacity: 0.7;">Bénéficiaire</div>
                                                    <div class="card-holder-name">TuniVert</div>
                                                </div>
                                                <div>
                                                    <div style="font-size: 0.7rem; opacity: 0.7;">Devise</div>
                                                    <div class="card-expiry">TND</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bank-instructions">
                                            <h6>Coordonnées bancaires</h6>
                                            <ul class="mb-2">
                                                <li><strong>Bénéficiaire:</strong> {{ config('services.bank.beneficiary') }}</li>
                                                <li><strong>IBAN:</strong> {{ config('services.bank.iban') }}</li>
                                                <li><strong>SWIFT:</strong> {{ config('services.bank.swift') }}</li>
                                            </ul>
                                            <small class="text-muted">{{ config('services.bank.note') }}</small>
                                        </div>

                                        <div class="mt-3">
                                            <h6 class="mb-2">Vos informations</h6>
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_full_name"><i class="fas fa-user me-1"></i>Nom complet</label>
                                                    <input type="text" class="form-control" id="bank_full_name" name="bank_full_name" placeholder="Votre nom et prénom">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_email"><i class="fas fa-envelope me-1"></i>Email</label>
                                                    <input type="email" class="form-control" id="bank_email" name="bank_email" placeholder="votre@email.com">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_phone"><i class="fas fa-phone me-1"></i>Téléphone</label>
                                                    <input type="tel" class="form-control" id="bank_phone" name="bank_phone" placeholder="+216 .. .. .. ..">
                                                </div>
                                            </div>
                                            <div class="row g-3 mt-1">
                                                <div class="col-md-6">
                                                    <label class="form-label" for="bank_card"><i class="fas fa-credit-card me-1"></i>Numéro de carte</label>
                                                    <input type="text" class="form-control" id="bank_card" name="bank_card" placeholder="1234 5678 9012 3456" maxlength="23" inputmode="numeric" autocomplete="off">
                                                    <div class="form-text">Facultatif — pour faciliter le rapprochement du virement.</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="bank_exp"><i class="fas fa-calendar-alt me-1"></i>Expiration</label>
                                                    <input type="text" class="form-control" id="bank_exp" name="bank_exp" placeholder="MM/YY" maxlength="5" autocomplete="off">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label" for="bank_cvv"><i class="fas fa-lock me-1"></i>CVV</label>
                                                    <input type="password" class="form-control" id="bank_cvv" name="bank_cvv" placeholder="***" maxlength="4" autocomplete="off">
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1">Ces informations ne servent qu'à associer votre virement à votre don. Aucun débit par carte n'est effectué.</small>
                                        </div>

                                        <div class="form-check my-3">
                                            <input class="form-check-input" type="checkbox" value="1" id="bank-ack" />
                                            <label class="form-check-label" for="bank-ack">
                                                J’ai bien noté les instructions et je confirme mon don.
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" value="1" id="bank-anon" />
                                            <label class="form-check-label" for="bank-anon">
                                                Rendre ce don anonyme
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-end gap-2">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                                            <button type="submit" class="btn btn-success px-4" id="bank-submit-btn" disabled>
                                                <i class="fas fa-check me-2"></i>Confirmer le don
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Scoped styles for bank modal design -->
                <style>
                    #bankTransferModal .bank-payment-container {
                        display: grid; grid-template-columns: 1fr 1fr; min-height: 540px; background: #fff;
                    }
                    #bankTransferModal .bank-payment-info {
                        background: linear-gradient(135deg, #1e3a8a, #3730a3); color: #fff; padding: 2rem; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;
                    }
                    #bankTransferModal .bank-payment-info::before { content: ''; position: absolute; top: -50%; right: -50%; width: 200%; height: 200%; opacity: .08; pointer-events: none; background: radial-gradient(circle at 30% 30%, rgba(255,255,255,.15), transparent 30%); }
                    #bankTransferModal .bank-logo { display:flex; align-items:center; gap: .75rem; margin-bottom: 1.5rem; z-index:1; }
                    #bankTransferModal .bank-logo h1 { font-size: 1.5rem; font-weight: 700; margin:0; }
                    #bankTransferModal .payment-details { z-index:1; }
                    #bankTransferModal .amount-display { font-size: 2.2rem; font-weight: 800; margin-bottom: .75rem; text-shadow: 0 2px 4px rgba(0,0,0,.25); }
                    #bankTransferModal .transaction-info { background: rgba(255,255,255,.12); padding: 1rem; border-radius: 12px; backdrop-filter: blur(10px); }
                    #bankTransferModal .info-row { display:flex; justify-content:space-between; font-size: .9rem; margin-bottom: .35rem; }
                    #bankTransferModal .security-badges { display:flex; gap:.5rem; z-index:1; margin-top:1rem; flex-wrap:wrap; }
                    #bankTransferModal .security-badges .badge { background: rgba(255,255,255,.2); padding:.35rem .75rem; border-radius: 999px; font-size:.75rem; font-weight:600; }
                    #bankTransferModal .bank-payment-form { padding: 2rem; display:flex; flex-direction:column; justify-content:center; }
                    #bankTransferModal .form-header { text-align:center; margin-bottom: 1rem; }
                    #bankTransferModal .form-header h2 { margin:0 0 .25rem; font-weight:700; }
                    #bankTransferModal .form-header p { color:#6b7280; margin:0; }
                    #bankTransferModal .card-preview { background: linear-gradient(135deg, #1f2937, #4b5563); border-radius: 12px; padding: 1rem; color:#fff; margin-bottom: 1rem; position:relative; min-height: 160px; overflow:hidden; }
                    #bankTransferModal .card-preview::before { content:''; position:absolute; top:-50%; right:-50%; width:100%; height:100%; background: linear-gradient(45deg, transparent, rgba(255,255,255,.1), transparent); transform: rotate(45deg); }
                    #bankTransferModal .card-brand { position:absolute; top:.75rem; right:.75rem; font-weight:700; font-size:1rem; }
                    #bankTransferModal .card-number-display { font-family:'Courier New', monospace; font-size:1.05rem; margin: .75rem 0; letter-spacing: .06em; }
                    #bankTransferModal .card-info-row { display:flex; justify-content:space-between; align-items:end; }
                    #bankTransferModal .card-holder-name, #bankTransferModal .card-expiry { font-size: .9rem; }
                    #bankTransferModal .bank-instructions { border: 1px solid #e5e7eb; border-radius: 12px; padding: 1rem; background:#fafafa; }
                    #bankTransferModal .bank-instructions h6 { margin:0 0 .5rem; }
                    #bankTransferModal .bank-instructions ul { padding-left: 1rem; margin: 0 0 .5rem; }
                    @media (max-width: 992px){ #bankTransferModal .bank-payment-container { grid-template-columns: 1fr; } }
                </style>

                <script>
                    const PAYMEE_ENABLED = {{ config('services.paymee.api_key') ? 'true' : 'false' }};
                    const TESTPAY_ENABLED = {{ config('services.testpay.enabled') ? 'true' : 'false' }};
                    document.addEventListener('DOMContentLoaded', function(){
                        const btns = document.querySelectorAll('.amount-suggest');
                        const input = document.querySelector('input[name="montant"]');
                        btns.forEach(b=> b.addEventListener('click', ()=> { if(input){ input.value = b.dataset.amount; input.focus(); } }));

                                const methodSelect = document.querySelector('select[name="moyen_paiement"]');
                                const donateSubmit = document.getElementById('donate-submit');
                                const cardWrapper = document.getElementById('card-payment-wrapper');
                                const paypalWrapper = document.getElementById('paypal-wrapper');
                                const paymeeWrapper = document.getElementById('paymee-wrapper');
                                const bankWrapper = document.getElementById('bank-wrapper');
                                const testpayWrapper = document.getElementById('testpay-wrapper');
                                let stripe, elements, paymentElement, currentPaymentIntentId;
                                async function ensureStripeSetup(){
                                    if (stripe) return;
                                    const pk = "{{ config('services.stripe.key') }}";
                                    if (!pk || pk === '' || pk.includes('pk_test_') === false && pk.includes('pk_live_') === false){
                                        throw new Error('Stripe publishable key missing. Ask admin to set STRIPE_KEY.');
                                    }
                                    stripe = Stripe(pk);
                                }
                                async function mountPaymentElement(clientSecret){
                                    await ensureStripeSetup();
                                    elements = stripe.elements({clientSecret});
                                    paymentElement = elements.create('payment');
                                    paymentElement.mount('#payment-element');
                                }
                                async function createIntent(){
                                    const amount = parseFloat(input?.value || '0');
                                    if (!amount || amount < 1){ throw new Error('Please enter a valid amount'); }
                                    const event = document.querySelector('input[name="evenement_id"]')?.value || new URLSearchParams(window.location.search).get('event');
                                    const res = await fetch("{{ route('payments.stripe.intent') }}", {
                                        method:'POST',
                                        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                        body: JSON.stringify({ amount, event })
                                    });
                                    const isJson = (res.headers.get('content-type') || '').includes('application/json');
                                    const data = isJson ? await res.json().catch(()=>({error:'Unexpected server response'})) : { error: 'Not authorized or session expired. Please log in again.' };
                                    if (!res.ok || !data?.clientSecret || !data?.paymentIntentId){
                                        throw new Error(data?.error || 'Failed to initialize payment');
                                    }
                                    currentPaymentIntentId = data.paymentIntentId;
                                    return data;
                                }

                                function toggleCardUI(show){
                                    if (!cardWrapper) return;
                                    cardWrapper.style.display = show ? '' : 'none';
                                    if (!show && paymentElement){
                                        try { paymentElement.unmount(); } catch(e){}
                                        paymentElement = null; elements = null; currentPaymentIntentId = null;
                                    }
                                }

                                function togglePayPalUI(show){
                                    if (!paypalWrapper) return;
                                    paypalWrapper.style.display = show ? '' : 'none';
                                }

                                function togglePaymeeUI(show){
                                    if (!paymeeWrapper) return;
                                    paymeeWrapper.style.display = show ? '' : 'none';
                                }

                                function toggleTestPayUI(show){
                                    if (!testpayWrapper) return;
                                    testpayWrapper.style.display = show ? '' : 'none';
                                }

                                async function ensurePayPalButtons(){
                                    if (document.getElementById('paypal-sdk-loaded')) return;
                                    const script = document.createElement('script');
                                    script.id = 'paypal-sdk-loaded';
                                    // Client ID not exposed; we use server-side order creation. SDK still needs a client-id; 'test' works for buttons-only + server create.
                                    script.src = 'https://www.paypal.com/sdk/js?client-id=test&currency={{ strtoupper(config('services.paypal.currency', 'USD')) }}&intent=capture';
                                    document.body.appendChild(script);
                                    await new Promise((res, rej)=>{ script.onload = res; script.onerror = ()=>rej(new Error('Failed to load PayPal SDK')); });
                                }

                                async function renderPayPalButtons(){
                                    await ensurePayPalButtons();
                                    const container = document.getElementById('paypal-button-container');
                                    const pmsg = document.getElementById('paypal-message');
                                    pmsg.style.display = 'none'; pmsg.textContent = '';
                                    container.innerHTML = '';
                                    // @ts-ignore
                                    paypal.Buttons({
                                        createOrder: async () => {
                                            try{
                                                const amount = parseFloat(input?.value || '0');
                                                if (!amount || amount < 1) throw new Error('Please enter a valid amount');
                                                const event = document.querySelector('input[name="evenement_id"]')?.value || new URLSearchParams(window.location.search).get('event');
                                                const res = await fetch("{{ route('payments.paypal.create') }}", {
                                                    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                                    body: JSON.stringify({ amount, event })
                                                });
                                                const data = await res.json();
                                                if (!res.ok || !data?.id) throw new Error(data?.error || 'Failed to create PayPal order');
                                                return data.id;
                                            }catch(e){
                                                pmsg.textContent = e.message || 'PayPal error'; pmsg.style.display = 'block';
                                                throw e;
                                            }
                                        },
                                        onApprove: async (data) => {
                                            try{
                                                const event = document.querySelector('input[name="evenement_id"]')?.value || new URLSearchParams(window.location.search).get('event') || null;
                                                const res = await fetch("{{ route('payments.paypal.capture') }}", {
                                                    method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                                    body: JSON.stringify({ orderId: data.orderID, evenement_id: event })
                                                });
                                                const out = await res.json();
                                                if (!res.ok) throw new Error(out?.error || 'Failed to capture PayPal order');
                                                window.location.href = "{{ route('donations.history') }}";
                                            }catch(e){ pmsg.textContent = e.message || 'Capture failed'; pmsg.style.display = 'block'; }
                                        },
                                        onError: (err) => { pmsg.textContent = err?.message || 'PayPal error'; pmsg.style.display = 'block'; },
                                    }).render('#paypal-button-container');
                                }

                                if (methodSelect){
                                    methodSelect.addEventListener('change', async ()=>{
                                        if (methodSelect.value === 'carte'){
                                            if (donateSubmit) donateSubmit.style.display = 'none';
                                            try{
                                                toggleCardUI(true);
                                                togglePayPalUI(false);
                                                togglePaymeeUI(false);
                                                const { clientSecret } = await createIntent();
                                                await mountPaymentElement(clientSecret);
                                            }catch(e){
                                                toggleCardUI(false);
                                                alert(e.message || 'Card payment unavailable');
                                            }
                                        } else if (methodSelect.value === 'paypal'){
                                            if (donateSubmit) donateSubmit.style.display = 'none';
                                            toggleCardUI(false);
                                            togglePayPalUI(true);
                                            togglePaymeeUI(false);
                                            try { await renderPayPalButtons(); } catch(e){ alert(e.message || 'PayPal unavailable'); togglePayPalUI(false); }
                                            if (bankWrapper) bankWrapper.style.display = 'none';
                                        } else if (methodSelect.value === 'paymee'){
                                            if (!PAYMEE_ENABLED){
                                                alert('e‑DINAR (Paymee) is not configured. Please set PAYMEE_API_KEY in .env and restart the server.');
                                                methodSelect.value = 'virement_bancaire';
                                                if (bankWrapper) bankWrapper.style.display = '';
                                                return;
                                            }
                                            if (donateSubmit) donateSubmit.style.display = 'none';
                                            toggleCardUI(false);
                                            togglePayPalUI(false);
                                            togglePaymeeUI(true);
                                            if (bankWrapper) bankWrapper.style.display = 'none';
                                        } else if (methodSelect.value === 'test'){
                                            if (!TESTPAY_ENABLED){
                                                alert('Test payments are disabled.');
                                                methodSelect.value = 'virement_bancaire';
                                                if (bankWrapper) bankWrapper.style.display = '';
                                                return;
                                            }
                                            if (donateSubmit) donateSubmit.style.display = 'none';
                                            toggleCardUI(false);
                                            togglePayPalUI(false);
                                            togglePaymeeUI(false);
                                            toggleTestPayUI(true);
                                            if (bankWrapper) bankWrapper.style.display = 'none';
                                        } else if (methodSelect.value === 'virement_bancaire'){
                                            if (donateSubmit) donateSubmit.style.display = '';
                                            toggleCardUI(false);
                                            togglePayPalUI(false);
                                            togglePaymeeUI(false);
                                            toggleTestPayUI(false);
                                            if (bankWrapper) bankWrapper.style.display = '';
                                        } else {
                                            if (donateSubmit) donateSubmit.style.display = '';
                                            toggleCardUI(false);
                                            togglePayPalUI(false);
                                            togglePaymeeUI(false);
                                            toggleTestPayUI(false);
                                            if (bankWrapper) bankWrapper.style.display = 'none';
                                        }
                                    });
                                }

                                // If amount changes after selecting card, recreate intent & remount
                                let amountDebounce;
                                if (input){
                                    input.addEventListener('input', ()=>{
                                        if (methodSelect?.value !== 'carte') return;
                                        clearTimeout(amountDebounce);
                                        amountDebounce = setTimeout(async ()=>{
                                            try{
                                                const { clientSecret } = await createIntent();
                                                if (paymentElement){ try{ paymentElement.unmount(); }catch(e){} }
                                                await mountPaymentElement(clientSecret);
                                            }catch(e){ /* silently ignore typing-time errors */ }
                                        }, 450);
                                    });
                                }

                                const payBtn = document.getElementById('card-pay-btn');
                                const msg = document.getElementById('payment-message');
                                const standardSubmit = document.getElementById('donate-submit');
                                if (payBtn){
                                    payBtn.addEventListener('click', async (e)=>{
                                        e.preventDefault();
                                        msg.style.display='none';
                                        try{
                                            if (!stripe || !elements) throw new Error('Payment not initialized');
                                                const {error} = await stripe.confirmPayment({
                                                elements,
                                                confirmParams: { return_url: window.location.origin + '/donations/history' },
                                                redirect: 'if_required'
                                            });
                                            if (error){ throw error; }
                                            // Use stored PaymentIntent id from init
                                            const pi = currentPaymentIntentId;
                                            if (!pi) throw new Error('Missing payment intent reference');
                                            const amount = parseFloat(input?.value || '0');
                                            const event = document.querySelector('input[name="evenement_id"]')?.value || new URLSearchParams(window.location.search).get('event') || null;
                                            const resp = await fetch("{{ route('payments.stripe.confirm') }}", {
                                                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                                body: JSON.stringify({ paymentIntentId: pi, montant: amount, evenement_id: event })
                                            });
                                            if (!resp.ok){ const data = await resp.json().catch(()=>({})); throw new Error(data?.error || 'Confirm failed'); }
                                            window.location.href = "{{ route('donations.history') }}";
                                        } catch(err){ msg.textContent = err.message || 'Payment failed'; msg.style.display='block'; }
                                    });
                                }

                                // Paymee redirect
                                const paymeeBtn = document.getElementById('paymee-pay-btn');
                                const paymeeMsg = document.getElementById('paymee-message');
                                if (paymeeBtn){
                                    paymeeBtn.addEventListener('click', async (e)=>{
                                        e.preventDefault();
                                        paymeeMsg.style.display='none'; paymeeMsg.textContent='';
                                        try{
                                            const amount = parseFloat(input?.value || '0');
                                            if (!amount || amount < 1) throw new Error('Please enter a valid amount');
                                            const params = new URLSearchParams(window.location.search);
                                            const event = document.querySelector('input[name="evenement_id"]')?.value || params.get('event') || null;
                                            const res = await fetch("{{ route('payments.paymee.create') }}", {
                                                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                                body: JSON.stringify({ amount, evenement_id: event })
                                            });
                                            const data = await res.json();
                                            if (!res.ok || !data?.payment_url) throw new Error(data?.error || 'Failed to initialize Paymee');
                                            window.location.href = data.payment_url;
                                        } catch(err){ paymeeMsg.textContent = err.message || 'Paymee error'; paymeeMsg.style.display='block'; }
                                    });
                                }

                                // Hide standard submit for hosted methods, show only for bank transfer
                                function refreshStandardSubmit(){
                                    if (!standardSubmit) return;
                                    const m = methodSelect?.value;
                                    // Always hide standard submit; each method uses its own flow
                                    standardSubmit.style.display = 'none';
                                }
                                // TestPay redirect
                                const testBtn = document.getElementById('testpay-pay-btn');
                                const testMsg = document.getElementById('testpay-message');
                                let testpayToken = null;
                                let testpaySubmitted = false;
                                if (testBtn){
                                    testBtn.addEventListener('click', async (e)=>{
                                        e.preventDefault();
                                        testMsg.style.display='none'; testMsg.textContent='';
                                        try{
                                            const amount = parseFloat(input?.value || '0');
                                            if (!amount || amount < 1) throw new Error('Please enter a valid amount');
                                            const params = new URLSearchParams(window.location.search);
                                            const event = document.querySelector('input[name="evenement_id"]')?.value || params.get('event') || null;
                                            const res = await fetch("{{ route('payments.test.create') }}", {
                                                method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                                                body: JSON.stringify({ amount, evenement_id: event })
                                            });
                                            const data = await res.json();
                                            if (!res.ok || !data?.token) throw new Error(data?.error || 'Failed to initialize TestPay');
                                            testpayToken = data.token;
                                            // Populate e‑Dinar modal fields
                                            const tokenEl = document.getElementById('edinar-token');
                                            const amountEl = document.getElementById('edinar-amount');
                                            const amountLbl = document.getElementById('edinar-amount-label');
                                            const payLbl = document.getElementById('pay-amount');
                                            if (tokenEl) tokenEl.value = testpayToken;
                                            if (amountEl) amountEl.value = amount;
                                            if (amountLbl) amountLbl.textContent = amount.toFixed(3) + ' TND';
                                            if (payLbl) payLbl.textContent = amount.toFixed(3) + ' TND';
                                            // Carry anonymous preference into the modal hidden field
                                            const anonMain = document.getElementById('donate-anon');
                                            const anonEd = document.getElementById('edinar-is-anonymous');
                                            if (anonEd) anonEd.value = anonMain?.checked ? '1' : '0';
                                            // Show e‑Dinar modal
                                            const modalEl = document.getElementById('edinarModal');
                                            const modal = new bootstrap.Modal(modalEl);
                                            modal.show();
                                        } catch(err){ testMsg.textContent = err.message || 'TestPay error'; testMsg.style.display='block'; }
                                    });
                                }

                                // e‑Dinar (TestPay) validation/formatting and modal lifecycle
                                function edFormatCard(val){
                                    const v = (val||'').replace(/\s+/g,'').replace(/[^0-9]/g,'');
                                    return (v.match(/.{1,4}/g) || []).join(' ').substring(0,23);
                                }
                                function edFormatExpiry(val){
                                    let v = (val||'').replace(/[^0-9]/g,'');
                                    if (v.length >= 3) v = v.substring(0,2) + '/' + v.substring(2,4);
                                    return v.substring(0,5);
                                }
                                function edValidateCard(number){ const c = (number||'').replace(/\s/g,''); return c.length>=12 && c.length<=19 && /^\d+$/.test(c); }
                                function edValidateExpiry(exp){ if(!/^\d{2}\/\d{2}$/.test(exp)) return false; const [m,y]=exp.split('/').map(Number); const d=new Date(); const cy=d.getFullYear()%100; const cm=d.getMonth()+1; return m>=1 && m<=12 && (y>cy || (y===cy && m>=cm)); }
                                function edValidateCVV(cvv){ return /^\d{3,4}$/.test(cvv||''); }
                                function edValidatePIN(pin){ return /^\d{4,6}$/.test(pin||''); }
                                function edValidateCIN(cin){ return /^\d{8}$/.test(cin||''); }
                                function edShowState(input, ok){ input?.classList?.toggle('is-valid', !!ok); input?.classList?.toggle('is-invalid', !ok && (input?.value||'').length>0); }

                                const edCard = document.getElementById('edinar_card');
                                const edExp = document.getElementById('edinar_exp');
                                const edCVV = document.getElementById('edinar_cvv');
                                const edPIN = document.getElementById('edinar_pin');
                                const edCIN = document.getElementById('edinar_cin');
                                const edSubmit = document.getElementById('edinar-submit-btn');
                                const edForm = document.getElementById('edinar-form');
                                const edCardPrev = document.getElementById('cardNumberPreview');
                                const edExpPrev = document.getElementById('cardExpiryPreview');

                                function edCheckForm(){
                                    const ok = edValidateCard(edCard?.value)||false;
                                    const ok2 = edValidateExpiry(edExp?.value)||false;
                                    const ok3 = edValidateCVV(edCVV?.value)||false;
                                    const ok4 = edValidatePIN(edPIN?.value)||false;
                                    const ok5 = edValidateCIN(edCIN?.value)||false;
                                    if (edSubmit) edSubmit.disabled = !(ok && ok2 && ok3 && ok4 && ok5);
                                }
                                edCard?.addEventListener('input', (e)=>{ e.target.value = edFormatCard(e.target.value); edShowState(e.target, edValidateCard(e.target.value)); edCardPrev && (edCardPrev.textContent = e.target.value || '•••• •••• •••• ••••'); edCheckForm(); });
                                edExp?.addEventListener('input', (e)=>{ e.target.value = edFormatExpiry(e.target.value); edShowState(e.target, edValidateExpiry(e.target.value)); edExpPrev && (edExpPrev.textContent = e.target.value || 'MM/YY'); edCheckForm(); });
                                edCVV?.addEventListener('input', (e)=>{ e.target.value = (e.target.value||'').replace(/[^0-9]/g,''); edShowState(e.target, edValidateCVV(e.target.value)); edCheckForm(); });
                                edPIN?.addEventListener('input', (e)=>{ e.target.value = (e.target.value||'').replace(/[^0-9]/g,''); edShowState(e.target, edValidatePIN(e.target.value)); edCheckForm(); });
                                edCIN?.addEventListener('input', (e)=>{ e.target.value = (e.target.value||'').replace(/[^0-9]/g,''); edShowState(e.target, edValidateCIN(e.target.value)); edCheckForm(); });

                                edForm?.addEventListener('submit', ()=>{
                                    testpaySubmitted = true;
                                    if (edSubmit){
                                        edSubmit.querySelector('.btn-content')?.style?.setProperty('display','none');
                                        edSubmit.querySelector('.btn-loading')?.style?.setProperty('display','inline-flex');
                                        edSubmit.disabled = true;
                                    }
                                });

                                const edModalEl = document.getElementById('edinarModal');
                                edModalEl?.addEventListener('hidden.bs.modal', async ()=>{
                                    if (!testpaySubmitted && testpayToken){
                                        try{ await fetch("{{ route('payments.test.cancel') }}" + '?t=' + encodeURIComponent(testpayToken), { method:'GET' }); }catch(_){ }
                                    }
                                    testpayToken = null; testpaySubmitted = false;
                                    if (edSubmit){
                                        edSubmit.disabled = true;
                                        edSubmit.querySelector('.btn-content')?.style?.setProperty('display','inline-flex');
                                        edSubmit.querySelector('.btn-loading')?.style?.setProperty('display','none');
                                    }
                                    edForm?.reset(); edCheckForm();
                                });
                                // Bank transfer modal wiring
                                const bankOpenBtn = document.getElementById('bank-open-modal');
                                const bankModalEl = document.getElementById('bankTransferModal');
                                let bankModal;
                                if (bankModalEl) { bankModal = new bootstrap.Modal(bankModalEl); }

                                function openBankModal(){
                                    if (!bankModal) return;
                                    // Prefill montant and event id
                                    const montant = document.querySelector('input[name="montant"]')?.value || '';
                                    const hiddenEvent = document.querySelector('input[name="evenement_id"]')?.value || '';
                                    const selectEvent = document.getElementById('evenement_id')?.value || '';
                                    document.getElementById('bank-montant').value = montant;
                                    const disp = document.getElementById('bank-amount-display');
                                    if (disp) { const n = parseFloat(montant||'0'); disp.textContent = (isNaN(n)?0:n).toFixed(3) + ' TND'; }
                                    document.getElementById('bank-evenement-id').value = hiddenEvent || selectEvent || '';
                                    bankModal.show();
                                }

                                bankOpenBtn?.addEventListener('click', (e)=>{ e.preventDefault(); openBankModal(); });

                                methodSelect?.addEventListener('change', ()=>{
                                    refreshStandardSubmit();
                                    if (methodSelect.value === 'virement_bancaire') {
                                        openBankModal();
                                    }
                                });
                                // Enable/disable submit by ack checkbox
                                const bankAck = document.getElementById('bank-ack');
                                const bankSubmitBtn = document.getElementById('bank-submit-btn');
                                bankAck?.addEventListener('change', ()=>{ if (bankSubmitBtn) bankSubmitBtn.disabled = !bankAck.checked; });

                                // Light formatting for optional card fields in bank modal
                                function bankFormatCard(val){
                                    const v = (val||'').replace(/\s+/g,'').replace(/[^0-9]/g,'');
                                    return (v.match(/.{1,4}/g) || []).join(' ').substring(0,23);
                                }
                                function bankFormatExpiry(val){
                                    let v = (val||'').replace(/[^0-9]/g,'');
                                    if (v.length >= 3) v = v.substring(0,2) + '/' + v.substring(2,4);
                                    return v.substring(0,5);
                                }
                                const bCard = document.getElementById('bank_card');
                                const bExp = document.getElementById('bank_exp');
                                bCard?.addEventListener('input', (e)=>{ e.target.value = bankFormatCard(e.target.value); });
                                bExp?.addEventListener('input', (e)=>{ e.target.value = bankFormatExpiry(e.target.value); });
                                // Sync anonymous checkbox to hidden input for bank form
                                const bankAnon = document.getElementById('bank-anon');
                                const bankAnonHidden = document.getElementById('bank-is-anonymous');
                                bankAnon?.addEventListener('change', ()=>{ if (bankAnonHidden) bankAnonHidden.value = bankAnon.checked ? '1' : '0'; });
                                refreshStandardSubmit();
                    });
                </script>

    </body>

</html>