@extends('layouts.site')

@section('title', 'عطاءات | كهرباء غزة')
@section('meta_description', 'عطاءات كهرباء غزة')

@push('styles')
    {{-- لو عندك CSS إضافي خاص بالصفحة فقط --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/site/css/certifications.css') }}"> --}}
@endpush

@section('content')
    {{-- نفس الـ sections من الصفحة الأولى لكن بدون الهيدر والفوتر --}}
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">عطاءات</h1>

                    {{-- خلي الـ breadcrumb تحتها --}}
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li class="active">عطاءات</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>
    <div class="container">
        <div class="row g-4 justify-content-center">
            {{-- بطاقة 1 --}}
            <div class="col-md-4 col-sm-6 col-12 text-center">
                <a href="{{ asset('assets/site/images/certifications/1.webp') }}"
                   class="image-popup d-block hover mb-3">
                    <div class="relative overflow-hidden rounded-1">
                        <div class="absolute start-0 w-100 hover-op-1 p-5 abs-middle z-2 text-center text-white z-3">
                            عرض
                        </div>
                        <div class="absolute start-0 w-100 h-100 overlay-black-5 hover-op-1 z-2"></div>
                        <img src="{{ asset('assets/site/images/certifications/1.webp') }}"
                             class="w-100 hover-scale-1-2" alt="IATA">
                    </div>
                </a>
                <h4>IATA (الاتحاد الدولي للنقل الجوي)</h4>
                شهادة سلامة مناولة الشحن الجوي والالتزام العالمي.
            </div>


        </div>
    </div>
@endsection

@section('overlay')
    {{-- لو بدك تركّب الـ overlay الموجود بالنص الأول --}}
    <div id="extra-wrap" class="text-light">
        <div id="btn-close">
            <span></span>
            <span></span>
        </div>
        <div id="extra-content">
            <img src="{{ asset('assets/site/images/logo-white.webp') }}" class="w-200px" alt="">
            <div class="spacer-30-line"></div>

            <h5>خدماتنا</h5>
            <ul class="ul-check">
                <li>النقل البري</li>
                <li>الشحن الجوي</li>
                <li>الشحن البحري</li>
                <li>النقل بالسكك الحديدية</li>
                <li>التخزين</li>
                <li>التخليص الجمركي</li>
                <li>التوصيل لآخر ميل</li>
                <li>الشحنات الضخمة</li>
            </ul>

            <div class="spacer-30-line"></div>

            <h5>تواصل معنا</h5>
            <div><i class="icofont-phone me-2 op-5"></i>+929 333 9296</div>
            <div><i class="icofont-location-pin me-2 op-5"></i>100 S Main St, New York, NY</div>
            <div><i class="icofont-envelope me-2 op-5"></i>contact@logixpress.com</div>

            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- سكربتات إضافية خاصة بالصفحة إن وجدت --}}
@endpush
