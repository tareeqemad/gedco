@extends('layouts.site')

@php
    $direction = session('direction', 'rtl');
    $isRtl = $direction === 'rtl';
@endphp
@section('title', __('common.ads_page_title_full'))
@section('meta_description', __('common.ads_page_description'))

@push('styles')
    <style>
        /* إخفاء الـ scrollbar */
        ::-webkit-scrollbar {
            width: 0px !important;
            height: 0px !important;
        }

        * {
            scrollbar-width: none !important;
        }

        /* إصلاح مشكلة الهيدر - يظل ثابت بدون نزول */
        header {
            position: fixed !important;
            top: 0 !important;
            width: 100% !important;
            transition: all 0.3s ease !important;
        }

        header.smaller {
            position: fixed !important;
            top: 0 !important;
        }

        /* خط أبيض رفيع فوق الصورة - لمسة جمالية */
        body {
            padding-top: 0 !important;
        }

        section#subheader {
            margin-top: 8px !important; /* خط أبيض أوضح */
            padding-top: 120px !important;
            padding-bottom: 80px !important;
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }

        section#subheader h1 {
            font-size: 2.5rem;
            line-height: 1.2;
        }

        section#subheader .crumb {
            display: inline-flex;
            align-items: center;
            gap: .75rem;
            padding: .55rem 1.35rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.32);
            backdrop-filter: blur(6px);
            list-style: none;
            margin: 0;
        }

        section#subheader .crumb li {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 600;
        }

        section#subheader .crumb a {
            color: rgba(255, 255, 255, 0.75);
            text-decoration: none;
            transition: color .3s ease;
        }

        section#subheader .crumb a:hover {
            color: #fff;
        }

        /* فراغ بين الهيدر والصورة على الموبايل */
        @media (max-width: 991px) {
            body {
                padding-top: 65px !important; /* فراغ للهيدر الأصغر */
                padding-bottom: 0 !important; /* إلغاء أي padding من تحت */
                margin-bottom: 0 !important;
            }

            section#subheader {
                margin-top: 25px !important; /* فراغ أكبر بين الهيدر والصورة */
                padding-top: 60px !important; /* padding أقل */
                padding-bottom: 60px !important;
            }

            /* العنوان ملائم للموبايل */
            section#subheader h1 {
                font-size: 1.6rem !important;
                line-height: 1.3 !important;
                padding: 0 1rem;
                word-wrap: break-word;
            }

            /* الصورة تعرض كاملة على الموبايل */
            section#subheader .container {
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            footer {
                margin-bottom: 0 !important;
            }

            /* تحسين الإعلانات على الموبايل */
            .ad-thumb-wrapper {
                height: 200px !important;
            }

            .ad-card-body {
                padding: 1rem 1.25rem;
            }

            .ad-title {
                font-size: 0.95rem !important;
                min-height: 2.85rem;
                margin-bottom: 0.5rem !important;
            }

            .ad-date {
                font-size: 0.8rem !important;
                margin-bottom: 0.75rem !important;
            }

            .ad-pdf-btn {
                font-size: 0.75rem !important;
                padding: 0.4rem 0.85rem !important;
            }

            #ads-grid {
                row-gap: 1rem !important;
            }
        }

        /* تصميم الكارد المحسّن */
        .ad-card-wrapper {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .ad-card-link {
            display: block;
            height: 100%;
            text-decoration: none;
            color: inherit;
        }

        .ad-card {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            overflow: hidden;
            background: #fff;
            border: 1px solid rgba(0, 0, 0, 0.06);
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .ad-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #1a5490, #ff6b35);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
            z-index: 1;
        }

        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .ad-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(255, 107, 53, 0.25);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .ad-thumb-wrapper {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .ad-thumb {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: block;
        }

        .ad-card:hover .ad-thumb {
            transform: scale(1.15);
        }

        .ad-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to top, rgba(26, 84, 144, 0.85) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.4s ease;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding: 1.5rem;
        }

        .ad-card:hover .ad-overlay {
            opacity: 1;
        }

        .ad-overlay-content {
            color: white;
            text-align: center;
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transform: translateY(10px);
            transition: transform 0.4s ease;
        }

        .ad-card:hover .ad-overlay-content {
            transform: translateY(0);
        }

        .ad-overlay-content i {
            font-size: 1.2rem;
        }

        .ad-card-body {
            padding: 1.25rem 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .ad-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 0.75rem 0;
            line-height: 1.5;
            text-align: center;
            overflow-wrap: break-word;
            word-wrap: break-word;
            min-height: 3.3rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .ad-date {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            font-weight: 500;
        }

        .ad-date i {
            color: #ff6b35;
            font-size: 1.1rem;
        }

        .ad-pdf-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c00 100%);
            color: white;
            border-radius: 0.5rem;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            margin: 0 auto;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .ad-pdf-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            color: white;
        }

        .ad-pdf-btn i {
            font-size: 1.1rem;
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 5rem;
            color: #dee2e6;
            display: block;
        }

        /* تكبير الكاردات على الديسكتوب */
        @media (min-width: 992px) {
            .ad-thumb-wrapper {
                height: 320px !important;
            }
            .ad-title {
                font-size: 1.2rem !important;
                min-height: 3.6rem;
            }
            .ad-date {
                font-size: 0.95rem !important;
            }
            .ad-card-body {
                padding: 1.5rem 1.75rem;
            }
            #ads-grid {
                row-gap: 2rem !important;
            }
        }


        .ads-loading {
            position: absolute; inset: 0; background: rgba(255,255,255,.8);
            backdrop-filter: blur(2px); display: none; align-items: center;
            justify-content: center; z-index: 10; border-radius: .5rem;
        }
        .ads-loading.show { display: flex; }

        .spinner {
            width: 2.5rem; height: 2.5rem;
            border: .3rem solid #f3f3f3; border-top-color: #0d6efd;
            border-radius: 50%; animation: spin 1s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }


        #ads-wrapper {
            position: relative;
        }

        #ads-grid {
            min-height: 600px;
        }

        #pagination-wrapper {
            margin-top: 3rem;
            margin-bottom: 2rem;
        }

        /* تحسين الـ pagination - لون برتقالي */
        .pagination {
            gap: 0.5rem;
            flex-wrap: wrap;
            display: flex !important;
            align-items: center !important;
        }
        .pagination .page-item {
            display: flex;
            align-items: center;
        }
        .pagination .page-link {
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            color: #ff6b35;
            border: 2px solid #e9ecef;
            transition: all .3s ease;
            background: white;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 44px;
            min-height: 44px;
        }
        .pagination .page-link i {
            font-size: 1.2rem;
            line-height: 1;
        }

        /* عكس اتجاه الصندوق للأسهم فقط */
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            transform: scaleX(-1);
        }
        .pagination .page-item:first-child .page-link i,
        .pagination .page-item:last-child .page-link i {
            transform: scaleX(-1);
        }
        .pagination .page-link:hover {
            background: #ff6b35;
            color: white;
            border-color: #ff6b35;
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #ff6b35, #ff8c00);
            border-color: #ff6b35;
            color: white;
            box-shadow: 0 4px 12px rgba(255,107,53,.3);
        }
        .pagination .page-item.disabled .page-link {
            background: #f8f9fa;
            border-color: #e9ecef;
            color: #adb5bd;
        }

        /* تحسين الموبايل - أيقونات فقط */
        @media (max-width: 767px) {
            .pagination {
                gap: 0.35rem;
                justify-content: center !important;
            }
            .pagination .page-item {
                margin: 0 !important;
            }
            .pagination .page-link {
                padding: 0.4rem 0.6rem;
                min-width: 36px;
                min-height: 36px;
                text-align: center;
                display: inline-flex !important;
                align-items: center;
                justify-content: center;
                font-size: 0.85rem;
                border-width: 1.5px;
            }
            #pagination-wrapper {
                margin-top: 2rem !important;
                margin-bottom: 3rem !important;
            }
        }
    </style>
@endpush

@section('content')
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="url({{ asset('assets/site/images/backgrounds/site2.webp') }})">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">{{ __('common.ads_page_title') }}</h1>
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ url('/') }}">{{ __('common.home') }}</a></li>
                            <li class="active">{{ __('common.ads_page_title') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>

    <div class="container py-3" id="ads-wrapper">
        <div class="ads-loading" id="ads-loading"><div class="spinner"></div></div>
        @include('site.advertisements.partials.grid')
    </div>
@endsection

@section('overlay')
    <div id="extra-wrap" class="text-light">
        <div id="btn-close"><span></span><span></span></div>
        <div id="extra-content">
            <img src="{{ asset('assets/site/images/logos/logo-white.webp') }}" class="w-200px" alt="">
            <div class="spacer-30-line"></div>
            <h5>خدماتنا</h5>
            <ul class="ul-check">
                <li>النقل البري</li><li>الشحن الجوي</li><li>الشحن البحري</li><li>النقل بالسكك الحديدية</li>
                <li>التخزين</li><li>التخليص الجمركي</li><li>التوصيل لآخر ميل</li><li>الشحنات الضخمة</li>
            </ul>
            <div class="spacer-30-line"></div>
            <h5>تواصل معنا</h5>
            <div><i class="icofont-phone me-2 op-5"></i>+929 333 9296</div>
            <div><i class="icofont-location-pin me-2 op-5"></i>100 S Main St, New York, NY</div>
            <div><i class="icofont-envelope me-2 op-5"></i>contact@logixpress.com</div>
            <div class="social-icons mt-3">
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
    <script>
        (function () {
            const wrapper = document.getElementById('ads-wrapper');
            const loader = document.getElementById('ads-loading');

            document.addEventListener('click', async function (e) {
                const link = e.target.closest('#pagination-wrapper a');
                if (!link) return;

                const url = link.getAttribute('href');
                if (!url || url === '#') return;

                e.preventDefault();
                await loadPage(url);
            });

            async function loadPage(url) {
                loader.classList.add('show');

                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();

                    if (data.html) {
                        wrapper.innerHTML = data.html;
                        history.pushState({}, '', url);

                        // السكرول للأعلى
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    } else {
                        location.href = url;
                    }
                } catch (err) {
                    console.error(err);
                    location.href = url;
                } finally {
                    loader.classList.remove('show');
                }
            }

            window.addEventListener('popstate', () => location.reload());
        })();

        // إصلاح الفراغ تحت الـ footer عند تصغير/تكبير المتصفح على الموبايل
        if (window.innerWidth <= 991) {
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const wrapper = document.getElementById('ads-wrapper');
                    const grid = document.getElementById('ads-grid');

                    if (wrapper && grid && window.innerWidth <= 991) {
                        const vh = window.innerHeight;
                        wrapper.style.minHeight = Math.max(vh - 300, 1100) + 'px';
                        grid.style.minHeight = Math.max(vh - 350, 1050) + 'px';
                    }
                }, 150);
            });
        }
    </script>
@endpush
