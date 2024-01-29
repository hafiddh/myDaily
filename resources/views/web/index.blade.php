@extends('temp_web')

@section('judul', 'Home - My Diary - ')


@section('tambah_css')
    <style>
        @font-face {
            font-family: font1;
            src: url("{{ asset('/') }}aset/fonts/Audrey-Bold.otf");
        }

        h5 {
            font-family: Arial, Helvetica, sans-serif;
            font-weight: 300;
            font-size: 12px;
            color: black;
        }

        h5.v2 {
            margin-top: -8px;
            font-family: font1;
            font-weight: 300;
            font-size: 12px;
            color: black;
        }



        h6 {
            font-family: font1;
            font-size: 16px;
        }

        hr.solid {
            border-top: 0px solid #bbb;
            color: transparent;
            margin-bottom: -5px;
        }

        /* The heart of the matter */
        .testimonial-group>.row {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

        .testimonial-group>.row>.col-4 {
            display: inline-block;
        }

        /* Decorations */
        .col-4 {
            color: #fff;
            x font-size: 48px;
            padding-bottom: 20px;
            padding-top: 18px;
        }

        .col-4.v2 {
            width: 60px;
            height: 60px;
            background-color: #ffd768;
            border-radius: 50%;
            margin: 4px;
        }

        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        

    </style>
@endsection


@section('isi')
    <div class="full-height no-bottom no-top" id="content">

        <section class="no-bottom no-top" style="background-color:transparent;">
            <div class="container py-3">
                <div class="row align-items-center">
                    <div class="col-lg-12 wow fadeInRight" data-wow-delay=".5s">
                        <h6 class="pl-2">Bulan Ini</h6>
                        {{-- <div class="spacer-10"></div> --}}
                        <div class="skill-bar style-2" style="margin-top: -15px;">
                            <div class="de-progress">
                                <div class="value"></div>
                                <div class="progress-bar" data-value="50%" style="background: #0ce10c">
                                </div>
                            </div>
                            <div class="row" style="margin-top: -15px">
                                <div class="col-6">
                                    <h5 class="pl-2" style="font-style: italic;">Food</h5>
                                </div>
                                <div class="col-6 text-right ">
                                    <h5 class="pr-2">Rp. xxxxxxx</h5>
                                </div>
                            </div>
                        </div>
                        <div class="skill-bar style-2" style="margin-top: -40px;">
                            <div class="de-progress">
                                <div class="value"></div>
                                <div class="progress-bar" data-value="90%" style="background: #dd120f">
                                </div>
                            </div>
                            <div class="row" style="margin-top: -15px">
                                <div class="col-6">
                                    <h5 class="pl-2" style="font-style: italic;">Shoping</h5>
                                </div>
                                <div class="col-6 text-right ">
                                    <h5 class="pr-2">Rp. xxxxxxx</h5>
                                </div>
                            </div>
                        </div>
                        <div class="skill-bar style-2" style="margin-top: -40px;">
                            <div class="de-progress">
                                <div class="value"></div>
                                <div class="progress-bar" data-value="80%" style="background: #ffd768">
                                </div>
                            </div>
                            <div class="row" style="margin-top: -15px">
                                <div class="col-6">
                                    <h5 class="pl-2" style="font-style: italic;">Food</h5>
                                </div>
                                <div class="col-6 text-right ">
                                    <h5 class="pr-2">Rp. xxxxxxx</h5>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-lg-6 offset-lg-1 wow fadeInLeft" data-wow-delay=".5s">
                        <img src="{{ asset('/') }}aset/images/misc/1.png" class="img-fluid" alt="" />
                    </div> --}}
                </div>
            </div>
        </section>

        <hr class="solid">


        <section class="no-bottom no-top" style="background-color:transparent; margin-top:-100px;">
            <div class="container py-3">
                <div class="row align-items-center">
                    <div class="col-lg-12 wow fadeInRight" data-wow-delay=".5s">
                        <h6 class="pl-2">Bulan Ini</h6>
                        {{-- <div class="spacer-10"></div> --}}
                        <div class="testimonial-group mx-1">
                            <div class="row text-center">
                                @php
                                    $date = '2022-05-29';
                                    $end_date = '2022-06-31';
                                @endphp
                                @while (strtotime($date) <= strtotime($end_date))
                                    @php
                                        $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
                                        $date2 = DateTime::createFromFormat('Y-m-d', $date);
                                        $hari = $date2->format('d');
                                        $bulan = $date2->format('M');
                                        $tahun = $date2->format('y');
                                    @endphp
                                    <button class="col-4 v2" style="border:none;">
                                        <h5 class="v2">{{ $hari }}</h5>
                                        <h5 class="v2">{{ $bulan }}</h5>
                                        <h5 class="v2">{{ $tahun }}</h5>
                                    </button>
                                @endwhile
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

@endsection


@section('tambah_js')


@endsection
