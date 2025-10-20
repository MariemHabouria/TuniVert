<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <title>Events - TuniVert</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="icon" type="image/svg+xml" sizes="16x16" href="{{ asset('favicon-16x16.svg') }}">
        <link rel="icon" type="image/svg+xml" sizes="32x32" href="{{ asset('favicon-32x32.svg') }}">
        <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">

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
        @include('layouts.navbar')
        <!-- space for fixed navbar -->
        <div style="height: 112px"></div>
        <!-- Navbar End -->

        <!-- Header Start -->
        <div class="container-fluid bg-breadcrumb">
            <div class="container text-center py-5" style="max-width: 900px;">
                <h3 class="text-white display-3 mb-4">Events</h3>

        <!-- Events Start -->
        <div class="container-fluid event py-5">
            <div class="container py-5">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h5 class="text-uppercase text-primary">Upcoming Events</h5>
                    <h1 class="mb-0">Help today because tomorrow you may be the one who needs more helping!</h1>
                </div>
                <div class="event-carousel owl-carousel">
                    <div class="event-item">
                        <img src="img/events-1.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn-hover-bg btn btn-outline-primary text-primary py-2 px-4 bg-white" href="#">Read More</a>
                                                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?event=1#donate') }}">Donate</a>
                                                        </div>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-2.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn-hover-bg btn btn-outline-primary text-primary py-2 px-4 bg-white" href="#">Read More</a>
                                                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?event=2#donate') }}">Donate</a>
                                                        </div>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-3.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn-hover-bg btn btn-outline-primary text-primary py-2 px-4 bg-white" href="#">Read More</a>
                                                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?event=3#donate') }}">Donate</a>
                                                        </div>
                        </div>
                    </div>
                    <div class="event-item">
                        <img src="img/events-4.jpg" class="img-fluid w-100" alt="Image">
                        <div class="event-content p-4">
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-body"><i class="fas fa-map-marker-alt me-2"></i>Grand Mahal, Dubai 2100.</span>
                                <span class="text-body"><i class="fas fa-calendar-alt me-2"></i>10 Feb, 2023</span>
                            </div>
                            <h4 class="mb-4">How To Build A Cleaning Plan</h4>
                            <p class="mb-4">Lorem ipsum dolor sit amet consectur adip sed eiusmod amet consectur adip sed eiusmod tempor.</p>
                                                        <div class="d-flex gap-2">
                                                            <a class="btn-hover-bg btn btn-outline-primary text-primary py-2 px-4 bg-white" href="#">Read More</a>
                                                            <a class="btn-hover-bg btn btn-primary text-white py-2 px-4" href="{{ url('donation.html?event=4#donate') }}">Donate</a>
                                                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Events End -->


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

    </body>

</html>