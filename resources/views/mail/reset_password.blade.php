@extends('temp/temp_web')

@section('judul', 'Reset Password - PPID Kabupaten Morowali - ')


@section('tambah_css')
@endsection


@section('isi')

<div class="no-bottom no-top" id="content">
    <div id="top"></div>

    <section class="full-height relative no-top no-bottom vertical-center">
        <div style="position: absolute; top: 0; width: 100%; height: 100%; z-index: -1;">
            <video loop muted autoplay preload=""
                style="position: absolute;  top: 50%;  left: 50%;  min-width: 100%;  min-height: 100%;  width: auto;  height: auto;  z-index: 0;  -ms-transform: translateX(-50%) translateY(-50%);  -moz-transform: translateX(-50%) translateY(-50%);  -webkit-transform: translateX(-50%) translateY(-50%);  transform: translateX(-50%) translateY(-50%);"
                poster="{{ asset('') }}assets/web/images/background/1.jpg">
                <source src="{{ asset('') }}assets/web/images/background2.mp4" type="video/mp4">
            </video>
        </div>

        <div class="overlay-gradient t50">
            <div class="center-y relative">
                <div class="container">



                    <div class="row align-items-center">
                        <div class="col-lg-7 col-md-12 text-light wow fadeIn" data-wow-delay=".5s">
                            <div class="box-rounded pt30 pb30" style="padding-left: 30px ; padding-right: 30px;"
                                data-bgcolor="#ffffff">
                                <h3 class="wow fadeInUp id-color" data-wow-delay=".5s"><i class="fa  fa-bullhorn"></i>
                                    Informasi Publik</h3>
                                <p class="lead wow fadeInUp" style="text-align: justify; color:black"
                                    data-wow-delay=".75s">Informasi Publik
                                    Informasi yang dihasilkan, disimpan, dikelola, dikirim, dan/ atau diterima oleh
                                    suatu badan publik yang berkaitan dengan penyelenggara dan penyelenggaraan negara
                                    dan/atau penyelenggara dan penyelenggaraan badan publik lainnya yang sesuai dengan
                                    Undang-Undang Nomor 14 Tahun 2008 tentang Keterbukaan Informasi Publik serta
                                    informasi lain yang berkaitan dengan kepentingan publik..</p>
                            </div>
                            <br><br>
                        </div>
                        <div class="col-lg-4 offset-lg-1 text-center wow fadeIn" data-wow-delay=".5s">

                            <div class="box-rounded padding40" data-bgcolor="#ffffff">
                                <h3 class="mb10">Reset Password</h3>
                                <br>
                                @if (session()->has('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong> {{ session()->get('error') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @elseif(session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong> {{ session()->get('success') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @elseif(session()->has('message'))
                                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                        <strong> {{ session()->get('message') }}</strong>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                                <form name="contactForm" id='contact_form' class="form-border" method="post" action='{{ route('postPassword') }}'>
                                    {{ csrf_field() }}

                                    <div class="field-set">
                                        <input type='text' name='email' id='email' class="form-control"
                                            placeholder="email" required>
                                    </div>

                                    <p style="text-align: justify">
                                     <small style="color: red">Sistem akan mengirimkan verifikasi ke email diatas, klik verifikasi pada email maka password akan direset. <br> 
                                        Password yang tereset akan diambil dari email anda, email dan password akan sama. <br>
                                        Segera ubah password setelah anda login pada menu di profil anda</small>
                                    </p>

                                    <div class="field-set">
                                        <input type='submit' value='Submit' class="btn btn-custom btn-fullwidth color-2">
                                    </div>

                                    <div class="clearfix"></div>

                                    <!-- social icons close -->
                                </form>
                            </div>
                            <br>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<footer class="fixed-bottom" style="padding-top: 10px; padding-bottom: 10px; background-color: darkblue;">
    <div class="container">
        <div class="row align-items-center">
            <div class="text-sm-left text-sm-center">
                <h5 style="font-size: 12px;" class="no-bottom">© 2021. Pejabat Pengelola Informasi dan Dokumentasi
                    (PPID) Kabupaten Morowali
                </h5>
            </div>
        </div>
    </div>
</footer>

@endsection



@section('tambah_js')
{{--  --}}
@endsection
