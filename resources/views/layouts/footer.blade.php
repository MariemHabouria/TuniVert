<!-- Footer Start -->
<div class="container-fluid footer bg-dark text-body py-5">
    <div class="container py-5">
        <div class="row g-5">
            <!-- Newsletter -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item">
                    <h4 class="mb-4 text-white">Newsletter</h4>
                    <p class="mb-4">Inscrivez-vous pour recevoir toutes les nouveautés et événements de Tunivert.</p>
                    <form action="#" method="POST" class="position-relative mx-auto">
                        @csrf
                        <input class="form-control border-0 bg-secondary w-100 py-3 ps-4 pe-5" type="email" name="email" placeholder="Votre e-mail" required>
                        <button type="submit" class="btn-hover-bg btn btn-primary position-absolute top-0 end-0 py-2 mt-2 me-2">S'inscrire</button>
                    </form>
                </div>
            </div>

            <!-- Nos Services -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Nos Services</h4>
                    <a href="{{ route('service') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Formations</a>
                    <a href="{{ route('events.index') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Événements</a>
                    <a href="{{ route('blog') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Forums</a>
                    <a href="{{ route('challenges.index') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Challenges</a>
                    <a href="{{ route('causes') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Donations</a>
                </div>
            </div>

            <!-- Liens Rapides -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item d-flex flex-column">
                    <h4 class="mb-4 text-white">Liens Rapides</h4>
                    <a href="{{ route('about') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>À propos</a>
                    <a href="{{ route('gallery') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Galerie</a>
                  <a href="#" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Devenir Bénévole</a>

                    <a href="{{ route('donation') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Faire un Don</a>
                    <a href="{{ route('contact') }}" class="text-body mb-2"><i class="fas fa-angle-right me-2"></i>Contact</a>
                </div>
            </div>

            <!-- Galerie -->
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="footer-item">
                    <h4 class="mb-4 text-white">Galerie</h4>
                    <div class="row g-2">
                        @for($i = 1; $i <= 6; $i++)
                            <div class="col-4">
                                <div class="footer-gallery position-relative">
                                    <img src="{{ asset('img/gallery-footer-'.$i.'.jpg') }}" class="img-fluid w-100" alt="Galerie {{ $i }}" style="height: 80px; object-fit: cover;">
                                    <div class="footer-search-icon">
                                        <a href="{{ asset('img/gallery-footer-'.$i.'.jpg') }}" data-lightbox="footerGallery" class="my-auto">
                                            <i class="fas fa-search-plus text-white"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Copyright Start -->
<div class="container-fluid copyright py-4">
    <div class="container">
        <div class="row g-4 align-items-center">
            <div class="col-md-4 text-center text-md-start mb-md-0">
                <span class="text-body">&copy; {{ date('Y') }} <a href="{{ route('home') }}" class="text-decoration-none">Tunivert</a>, Tous droits réservés.</span>
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
                Conçu par <a class="border-bottom" href="https://htmlcodex.com">HTML Codex</a> | Distribué par <a class="border-bottom" href="https://themewagon.com">ThemeWagon</a>
            </div>
        </div>
    </div>
</div>
<!-- Copyright End -->