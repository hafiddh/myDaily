<!DOCTYPE html>
<html style="width: 100%; height: 100%;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.5">
    <title>My Daily</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Bluetec Saas Software Startup Website Template" name="description">
    <meta content="" name="keywords">
    <meta content="" name="author">
    <link rel="icon" href="{{ asset('/') }}aset/images/logo_idil.png" type="image/x-icon">

    <!--[if lt IE 9]>
    <script src="{{ asset('/') }}aset/js/html5shiv.js"></script>
    <![endif]-->

    <!-- CSS Files
    ================================================== -->
    <link href="{{ asset('/') }}aset/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/bootstrap-grid.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/bootstrap-reboot.min.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/animate.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/owl.carousel.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/owl.theme.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/owl.transitions.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/magnific-popup.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/jquery.countdown.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/style.css" rel="stylesheet" type="text/css">

    <!-- color scheme -->
    <link id="colors" href="{{ asset('/') }}aset/css/colors/scheme-01.css" rel="stylesheet" type="text/css">
    <link href="{{ asset('/') }}aset/css/coloring.css" rel="stylesheet" type="text/css">

    <link href="{{ asset('/') }}aset/js/toastr.min.css" rel="stylesheet" type="text/css">

    <link href="{{ asset('/') }}aset/css/mycss.css" rel="stylesheet" type="text/css">

</head>

<body style="background-color: transparent;">
    <div class="container text-center" id="cont_1">
        <div class="row">
            <div class="col-md-12">
                <form action='blank.php' class="row" id='form_subscribe' method="post" name="myForm">
                    <div class="col-md-12 text-center">
                        <img src="{{ asset('/') }}aset/images/logo_idil.png" id="logo_1" alt="">
                        <h4 class="mt-4" style="font-weight: 750; color: #ef6b33; font-family: arial;">Hello !
                        </h4>
                        <h5 style="font-weight: 750; color: #ffc956;  font-family: arial; margin-top: -10px;">Ceritakan
                            Keseharian Mu</h5>
                    </div>
                    <div class="clearfix"></div>
                </form>
            </div>
            <div class="col-md-12">
                <div id="f_b_login" class="row text-center">
                    <div class="col-md-12">
                        <button class="btn btn-1" id="btn_login" style="width: 200px; color: #ef6b33"><b
                                style=" color: #ff0000; font-family: font1;">Masuk</b> </button>
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-2" id="btn_daftar"
                            style="width: 200px; font-family: font1;"><b>Daftar</b>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="preloader">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>

    <div class="modal fade text-dark text-dark" data-backdrop="static" id="modal_login" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body">
                    <div class="desktop">
                        <img src="{{ asset('/') }}aset/images/logo_idil.png" width="150px;" alt=""
                            style="margin-top: -80px; margin-left: 10px;">
                    </div>
                    <button type="button" id="btn_login_close" class="close" style=" color: #ef6b33;"><span
                            aria-hidden="true">&times;</span></button>
                    <div class="mx-3 my-3">

                        <form method="post" action='{{ route('postLogin') }}'>
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label class="font-custom01 ml-3" for="email">Username / Email</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder=""
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="font-custom01  ml-3" for="pass">Password</label>
                                <input id="password-field" type="password" class="form-control" name="password"
                                    value="">
                                <span toggle="#password-field"
                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>

                            <div class="row text-center">

                                <div class="col-6" style="text-align: left">
                                    <input type="checkbox" value="" id="rem">
                                    <label class="font-custom02" for="rem">Ingat Saya</label>
                                </div>
                                <div class="col-6" style="text-align: right">
                                    <a class="font-custom02" href="#">
                                        Lupa Password?
                                    </a>
                                </div>
                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-1" id="btn_login2"
                                        style="width: 200px; color: #ef6b33"><b
                                            style=" color: #ff0000; font-family: font1;">Masuk</b> </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-dark text-dark" data-backdrop="static" id="modal_daftar" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content animate-bottom">
                <div class="modal-body">
                    <div class="desktop">
                        <img src="{{ asset('/') }}aset/images/logo_idil.png" alt="" width="150px;"
                            style="margin-top: -80px; margin-left: 10px;">

                    </div>
                    <button type="button" id="btn_daftar_close" class="close" style=" color: #ef6b33;"><span
                            aria-hidden="true">&times;</span></button>
                    <div class="mx-3 my-3">
                        <form method="POST" action='{{ route('postRegis') }}'>
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="font-custom01  ml-3" for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder=""
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="font-custom01 ml-3" for="email2">Email</label>
                                <input type="email" class="form-control" id="email2" name="email" placeholder=""
                                    required>
                            </div>
                            <div class="form-group">
                                <label class="font-custom01  ml-3" for="pass">Password</label>
                                <input id="password-field2" type="password" class="form-control" name="password"
                                    required value="">
                                <span toggle="#password-field2"
                                    class="fa fa-fw fa-eye field-icon toggle-password"></span>
                            </div>

                            <div class="row text-center">
                                <div class="col-md-12 mt-4">
                                    <button class="btn btn-1" id="btn_login"
                                        style="width: 200px; color: #ef6b33"><b
                                            style=" color: #ff0000; font-family: font1;">Daftar</b> </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
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

    <script src="{{ asset('/') }}aset/js/toastr.min.js"></script>

    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-full-width",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }
    </script>

    @if (session()->has('error'))
        <script>
            toastr.error("{{ session()->get('error') }}");
        </script>
        @php
            session()->forget('error');
        @endphp
    @elseif(session()->has('success'))
        <script>
            toastr.success("{{ session()->get('success') }}");
        </script>
    @elseif(session()->has('message'))
        <script>
            toastr.info("{{ session()->get('message') }}");
        </script>
    @endif

    <script>
        $(document).ready(function() {
            if ($(window).width() < 600) { // if width is less than 600px
                $('.desktop').hide();
            } else { // if width is more than 600px
                $('.desktop').show();
            }

            $(".modal-dialog").css({
                'background-size': ''
            });

        });

        $("input#username").on("keydown", function(e) {
            return e.which !== 32;
        });

        $("input#email").on("keydown", function(e) {
            return e.which !== 32;
        });

        $("input#email2").on("keydown", function(e) {
            return e.which !== 32;
        });

        $('#btn_login').on('click', function() {
            $('#modal_login').modal("show");

            $('#f_b_login').slideToggle(300);
            // show when the button is clicked


        });

        $('#btn_daftar').on('click', function() {
            $('#modal_daftar').modal("show");

            $('#f_b_login').slideToggle(300);
        });


        $('#btn_daftar_close').on('click', function() {
            $('#modal_daftar').modal("hide");

            $('#f_b_login').slideToggle(300);

            
            // toastr.success("Test Data Keluarnya Bro");
            // toastr.error("Test Data Keluarnya Bro");
            // toastr.warning("Test Data Keluarnya Bro");
            // toastr.info("Test Data Keluarnya Bro");
        });

        $('#btn_login_close').on('click', function() {
            $('#modal_login').modal("hide");

            $('#f_b_login').slideToggle(300);
        });

        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye fa-eye-slash");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>



</body>

</html>
