<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <title></title>

    <script language="JavaScript" type="text/javascript">
        var txt = "@yield('judul')";
        var speed = 250;
        var refresh = null;

        function move() {
            document.title = txt;
            txt = txt.substring(1, txt.length) + txt.charAt(0);
            refresh = setTimeout("move()", speed);
        }
        move();
    </script>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Bluetec Saas Software Startup Website Template" name="description" />
    <meta content="" name="keywords" />
    <meta content="" name="idil" />
    <link rel="icon" href="{{ asset('/') }}aset/images/logo_idil.png" type="image/x-icon">

    <!--[if lt IE 9]>
            <script src="{{ asset('/') }}aset/js/html5shiv.js"></script>
        <![endif]-->

    <!-- CSS Files
    ================================================== -->
    <link href="{{ asset('/') }}aset/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/animate.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/owl.carousel.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/owl.theme.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/owl.transitions.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/magnific-popup.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/jquery.countdown.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/style.css" rel="stylesheet" type="text/css" />

    <!-- color scheme -->
    <link id="colors" href="{{ asset('/') }}aset/css/colors/scheme-01.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('/') }}aset/css/coloring.css" rel="stylesheet" type="text/css" />

    @yield('tambah_css')

</head>

<body>
    <div id="wrapper">
        <!-- header begin -->
        <header class="header-dark transparent scroll-dark" style="height: 50px;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <div class="align-self-center header-col-left">
                                <!-- logo begin -->
                                <!-- logo begin -->
                                <div id="logo">
                                    <a href="index.html">
                                        <img alt="" src="{{ asset('/') }}aset/images/logo_idil.png" style="margin-top: -30px;" width="70px;"/>
                                    </a>
                                </div>
                                <!-- logo close -->
                                <!-- logo close -->
                            </div>
                            <div class="align-self-center ml-auto header-col-mid">
                                <!-- mainmenu begin -->
                                <ul id="mainmenu">
                                    <li>
                                        <a href="index.html">Home</a>
                                        <ul>
                                            <li><a href="index.html">Main</a></li>
                                            <li><a href="index-startup.html">Startup</a></li>
                                            <li><a href="index-coworking.html">Co-working</a></li>
                                            <li><a href="index-agency.html">Agency</a></li>
                                            <li><a href="index-apps.html">Apps</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Company</a>
                                        <ul>
                                            <li><a href="about-us.html">About Us</a></li>
                                            <li><a href="our-team.html">Our Team</a></li>
                                            <li><a href="our-history.html">Our History</a></li>
                                            <li><a href="faq.html">FAQs</a></li>
                                            <li><a href="careers.html">Careers</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Services</a>
                                        <ul>
                                            <li><a href="service-single.html">Service Single</a></li>
                                            <li><a href="services-image.html">Services Images</a></li>
                                            <li><a href="services-icon.html">Services Icon</a></li>
                                            <li><a href="services-icon-boxed.html">Services Icon Boxed</a></li>
                                            <li><a href="services-carousel.html">Services Carousel</a></li>
                                            <li><a href="pricing-plans.html">Pricing Plans</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Pages</a>
                                        <ul>
                                            <li><a href="news.html">News</a></li>
                                            <li><a href="gallery.html">Gallery</a></li>
                                            <li><a href="login.html">Login</a></li>
                                            <li><a href="login-2.html">Login 2</a></li>
                                            <li><a href="register.html">Register</a></li>
                                            <li><a href="contact-us.html">Contact Us</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="#">Elements</a>
                                        <ul>
                                            <li><a href="icons-font-awesome.html">Font Awesome Icons</a></li>
                                            <li><a href="icons-elegant.html">Elegant Icons</a></li>
                                            <li><a href="icons-etline.html">Etline Icons</a></li>
                                            <li><a href="alerts.html">Alerts</a></li>
                                            <li><a href="accordion.html">Accordion</a></li>
                                            <li><a href="modal.html">Modal</a></li>
                                            <li><a href="progress-bar.html">Progress Bar</a></li>
                                            <li><a href="tabs.html">Tabs</a></li>
                                            <li><a href="tabs.html">Timeline</a></li>
                                            <li><a href="counters.html">Counters</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            {{-- <div class="align-self-center ml-auto header-col-right" style="margin-top: -35px;" >
                                <span id="menu-btn"></span>
                            </div> --}}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- header close -->
        <!-- content begin -->

        @yield('isi')


        <div id="preloader">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </div>

    <!-- Javascript Files
    ================================================== -->
    <script src="{{ asset('/') }}aset/js/jquery.min.js"></script>
    <script src="{{ asset('/') }}aset/js/bootstrap.min.js"></script>
    <script src="{{ asset('/') }}aset/js/wow.min.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.isotope.min.js"></script>
    <script src="{{ asset('/') }}aset/js/easing.js"></script>
    <script src="{{ asset('/') }}aset/js/owl.carousel.js"></script>
    <script src="{{ asset('/') }}aset/js/validation.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ asset('/') }}aset/js/enquire.min.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.stellar.min.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.plugin.js"></script>
    <script src="{{ asset('/') }}aset/js/typed.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.countTo.js"></script>
    <script src="{{ asset('/') }}aset/js/jquery.countdown.js"></script>
    <script src="{{ asset('/') }}aset/js/designesia.js"></script>

    @yield('tambah_js')


</body>

</html>
