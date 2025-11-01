<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <title>@yield('title', 'كهرباء غزة')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Meta --}}
    <meta name="description" content="@yield('meta_description','كهرباء غزة')">
    <meta name="keywords" content="@yield('meta_keywords','')">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/site/images/icon.ico') }}">

      <link id="bootstrap" rel="stylesheet" href="{{ asset('assets/site/css/bootstrap.rtl.min.css') }}">

     <link rel="stylesheet" href="{{ asset('assets/site/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/swiper-custom-1.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/coloring.css') }}">
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet" >

     <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-fix-simple.css') }}">

     <link id="colors" rel="stylesheet" href="{{ asset('assets/site/css/colors/scheme-01.css') }}">

     <link rel="stylesheet" href="{{ asset('assets/site/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/site/css/rtl-overrides.css') }}">


    @stack('styles')
</head>

<body>
<div id="wrapper">
    <a href="#" id="back-to-top"></a>

    {{-- Loader (اختياري) --}}
    <div id="de-loader"></div>

    {{-- ===== Header ===== --}}
    @include('site.partials.header')
    {{-- ===== /Header ===== --}}

    {{-- ===== Page Content ===== --}}
    @yield('content')
    {{-- ===== /Page Content ===== --}}

    {{-- ===== Footer ===== --}}
    @include('site.partials.footer')
    {{-- ===== /Footer ===== --}}
</div>

@yield('overlay')

{{-- JS --}}
<script src="{{ asset('assets/site/js/plugins.js') }}"></script>
<script src="{{ asset('assets/site/js/designesia.js') }}"></script>
<script src="{{ asset('assets/site/js/swiper.js') }}"></script>
<script src="{{ asset('assets/site/js/custom-swiper-1.js') }}"></script>
<script src="{{ asset('assets/site/js/custom-marquee.js') }}"></script>
<script src="{{ asset('assets/site/js/custom.js') }}"></script>
<script>
    (function () {
        const header   = document.getElementById('site-header');
        const menuBtn  = document.getElementById('menu-btn');
        const mainMenu = document.getElementById('mainmenu');
        if (!header || !menuBtn || !mainMenu) return;

        const mqDesktop = window.matchMedia('(min-width: 992px)');

        function setHeaderH() {
            const h = header.offsetHeight || 64;
            document.documentElement.style.setProperty('--header-h', h + 'px');
        }

        function updateHeaderState() {
            const scrolled = window.scrollY > 40;
            const desktop  = mqDesktop.matches;

            header.classList.toggle('scrolled', desktop && scrolled);
            header.classList.toggle('at-top',   desktop && !scrolled);

            setHeaderH();
            document.body.style.paddingTop = scrolled ? (header.offsetHeight + 'px') : '0px';
        }

        function toggleNav(force) {
            const open = (typeof force === 'boolean') ? force : !header.classList.contains('nav-open');
            header.classList.toggle('nav-open', open);
            menuBtn.setAttribute('aria-expanded', String(open));
            document.documentElement.classList.toggle('navlock', open);
            document.body.classList.toggle('navlock', open);

            setHeaderH();

            if (!open) {
                mainMenu.querySelectorAll('.open-sub').forEach(li => li.classList.remove('open-sub'));
            }
        }

        menuBtn.addEventListener('click', () => toggleNav());

        // أكورديون
        function bindMobileSubmenus() {
            const isMobile = !mqDesktop.matches;
            mainMenu.querySelectorAll(':scope > li > a.menu-item').forEach(a => a.onclick = null);
            if (!isMobile) return;

            mainMenu.querySelectorAll(':scope > li').forEach(li => {
                const a   = li.querySelector(':scope > a.menu-item');
                const sub = li.querySelector(':scope > ul');
                if (!a || !sub) return;

                a.addEventListener('click', e => {
                    if (!li.classList.contains('open-sub')) {
                        e.preventDefault();
                        li.classList.add('open-sub');
                        mainMenu.querySelectorAll(':scope > li.open-sub').forEach(o => { if (o !== li) o.classList.remove('open-sub'); });
                    } else {
                        const href = a.getAttribute('href');
                        if (href && href !== '#') toggleNav(false);
                    }
                });
            });
        }

        // إغلاق عند الضغط خارج
        document.addEventListener('click', e => {
            if (!mqDesktop.matches && header.classList.contains('nav-open') && !header.contains(e.target)) {
                toggleNav(false);
            }
        });

        // إغلاق عند الضغط على X
        header.addEventListener('click', e => {
            if (e.target === header || e.target.closest('.site-header::after')) {
                toggleNav(false);
            }
        });

        // تشغيل
        bindMobileSubmenus();
        updateHeaderState();
        window.addEventListener('scroll', updateHeaderState, { passive: true });
        window.addEventListener('resize', () => {
            bindMobileSubmenus();
            updateHeaderState();
            if (mqDesktop.matches) toggleNav(false);
        });
        window.addEventListener('load', updateHeaderState);
        setTimeout(updateHeaderState, 150);

        if ('ResizeObserver' in window) {
            new ResizeObserver(updateHeaderState).observe(header);
        }
    })();
</script>
@stack('scripts')
</body>
</html>
