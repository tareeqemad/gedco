@extends('layouts.site')

@section('title', 'الإعلانات والوظائف | كهرباء غزة')
@section('meta_description', 'أحدث الإعلانات والوظائف في شركة توزيع كهرباء غزة')

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
        }

        section#subheader h1 {
            font-size: 2.5rem;
            line-height: 1.2;
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
            .ad-thumb {
                height: 160px !important;
            }

            .ad-card {
                margin-bottom: 0.8rem;
            }

            #ads-grid h4 {
                font-size: 0.85rem !important;
                margin-top: 0.6rem !important;
                line-height: 1.4 !important;
                padding: 0 0.5rem;
            }

            #ads-grid .text-muted {
                font-size: 0.7rem !important;
                margin-bottom: 0.4rem !important;
            }

            #ads-grid .btn-sm {
                font-size: 0.7rem !important;
                padding: 0.3rem 0.6rem !important;
            }

            .col-lg-4.col-md-6.col-sm-6.col-12 {
                padding-left: 0.4rem !important;
                padding-right: 0.4rem !important;
                margin-bottom: 0.8rem !important;
            }

            #ads-grid {
                row-gap: 0.5rem !important;
            }
        }

        /* تصميم الكارد */
        .ad-card {
            transition: all .3s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
            border-radius: .75rem;
            overflow: hidden;
            background: #fff;
            border: 1px solid #f0f0f0;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif;
        }
        .ad-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.2);
            border-color: #dc3545;
        }
        .ad-thumb {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform .4s ease;
            display: block;
            transform: scale(1.08);
        }
        .ad-card:hover .ad-thumb {
            transform: scale(1.13);
        }

        /* تكبير الكاردات على الديسكتوب */
        @media (min-width: 992px) {
            .ad-thumb {
                height: 320px !important;
            }
            #ads-grid h4 {
                font-size: 1.15rem !important;
                margin-top: 1.2rem !important;
                margin-bottom: 0.8rem !important;
                line-height: 1.5 !important;
            }
            #ads-grid .text-muted {
                font-size: 0.95rem !important;
                margin-bottom: 0.8rem !important;
            }
            #ads-grid .btn-sm {
                font-size: 0.85rem !important;
                padding: 0.5rem 1rem !important;
            }
            .ad-card {
                margin-bottom: 2rem;
            }
            .col-lg-4 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
            #ads-grid {
                row-gap: 1.5rem !important;
            }
        }

        /* نصوص موحدة */
        #ads-grid h4 {
            text-align: center !important;
            font-weight: 600 !important;
            overflow-wrap: break-word !important;
            word-wrap: break-word !important;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
            color: #1a202c !important;
        }

        #ads-grid .text-muted {
            text-align: center !important;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
        }


        /* تنسيق المحتوى النصي */
        #ads-grid > div {
            display: flex;
            flex-direction: column;
            align-items: center;
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
            min-height: 1400px !important; /* ارتفاع ثابت كافي لـ 6 إعلانات */
        }

        #ads-grid {
            min-height: 1350px !important; /* ارتفاع ثابت للـ grid */
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
                margin-top: 1.5rem !important;
                margin-bottom: 3rem !important;
            }
            /* تثبيت ارتفاع ثابت على الموبايل */
            #ads-wrapper {
                min-height: 2000px !important;
            }
            #ads-grid {
                min-height: 1950px !important;
            }

            /* تثبيت الـ pagination */
            #pagination-wrapper {
                position: relative !important;
            }
        }
    </style>
@endpush

@section('content')
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="url({{ asset('assets/site/images/site2.webp') }})">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">الإعلانات والوظائف</h1>
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li class="active">الإعلانات والوظائف</li>
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
            <img src="{{ asset('assets/site/images/logo-white.webp') }}" class="w-200px" alt="">
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
