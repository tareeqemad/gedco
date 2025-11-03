@extends('layouts.site')

@section('title', 'الإعلانات والوظائف | كهرباء غزة')
@section('meta_description', 'أحدث الإعلانات والوظائف في شركة توزيع كهرباء غزة')

@push('styles')
    <style>
        .ad-card {
            transition: transform .3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            border-radius: .5rem;
            overflow: hidden;
        }
        .ad-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(0,0,0,.15);
        }
        .ad-thumb {
            width: 100%;
            height: clamp(160px, 20vw, 220px);
            object-fit: cover;
            transition: transform .4s ease;
            display: block;
        }
        .ad-card:hover .ad-thumb {
            transform: scale(1.08);
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

        #ads-wrapper { position: relative; min-height: 300px; }
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

    <div class="container py-5" id="ads-wrapper">
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
                const heightBefore = wrapper.offsetHeight;
                wrapper.style.minHeight = heightBefore + 'px';
                loader.classList.add('show');

                try {
                    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                    const data = await res.json();

                    if (data.html) {
                        wrapper.innerHTML = data.html;
                        history.pushState({}, '', url);
                        wrapper.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        location.href = url;
                    }
                } catch (err) {
                    console.error(err);
                    location.href = url;
                } finally {
                    loader.classList.remove('show');
                    wrapper.style.minHeight = '';
                }
            }

            window.addEventListener('popstate', () => location.reload());
        })();
    </script>
@endpush
