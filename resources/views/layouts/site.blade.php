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
    <link rel="stylesheet" href="{{ asset('assets/site/css/menu-mobile.css') }}">

    {{-- CSS إجباري للمنيو --}}
    <style>
        /* إزالة السكرول الأفقي من كل الصفحة */
        html, body {
            overflow-x: hidden !important;
            max-width: 100vw !important;
        }

        /* Mobile Menu Overlay - خلفية شفافة معتمة */
        #mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
            z-index: 999999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s, visibility 0.3s;
            pointer-events: none;
        }

        #mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        /* الخلفية المعتمة خلف الـ sidebar */
        #mobile-menu-overlay::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: -1;
        }

        /* القائمة الجانبية */
        #mobile-menu-content {
            position: fixed;
            top: 0;
            right: -100%;
            width: 300px;
            max-width: 80vw;
            height: 100vh;
            background: #212529;
            overflow-y: auto;
            padding: 60px 15px 20px;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -5px 0 20px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        #mobile-menu-overlay.active #mobile-menu-content {
            right: 0;
        }

        #mobile-menu-close {
            position: absolute;
            top: 15px;
            left: 15px;
            color: #fff;
            font-size: 28px;
            cursor: pointer;
            width: 32px;
            height: 32px;
            text-align: center;
            line-height: 28px;
            z-index: 10;
            transition: transform 0.2s;
        }

        #mobile-menu-close:hover {
            transform: scale(1.1);
        }

        #mobile-mainmenu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #mobile-mainmenu > li {
            display: block;
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        #mobile-mainmenu > li:last-child {
            border-bottom: none;
        }

        #mobile-mainmenu > li > a {
            display: block;
            padding: 11px 18px;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }

        #mobile-mainmenu > li > a:hover {
            background: rgba(255,255,255,0.08);
        }

        /* سهم للعناصر اللي فيها قوائم فرعية */
        #mobile-mainmenu > li:has(ul) > a {
            position: relative;
            padding-left: 40px !important;
        }

        #mobile-mainmenu > li:has(ul) > a::before {
            content: "▼";
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            transition: transform 0.3s ease;
            color: rgba(255,255,255,0.6);
        }

        /* عكس السهم عند الفتح - يصير لفوق */
        #mobile-mainmenu > li.open > a::before {
            content: "▲";
            color: #fff;
        }

        /* القوائم الفرعية - مخفية افتراضياً */
        #mobile-mainmenu > li > ul {
            background: rgba(0,0,0,0.25);
            padding: 0;
            list-style: none;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        /* إظهار القائمة الفرعية عند الفتح */
        #mobile-mainmenu > li.open > ul {
            max-height: 500px;
        }

        #mobile-mainmenu > li > ul li {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #mobile-mainmenu > li > ul li:last-child {
            border-bottom: none;
        }

        #mobile-mainmenu > li > ul li a {
            display: block;
            padding: 10px 18px;
            color: rgba(255,255,255,0.85);
            font-size: 13px;
            text-align: center;
            text-decoration: none;
            transition: background 0.2s;
        }

        #mobile-mainmenu > li > ul li a:hover {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        @media (max-width: 991px) {
            /* محاذاة عناصر الهيدر - الشعار في الوسط، زر القائمة على اليسار */
            header.header-mobile .de-flex {
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                position: relative !important;
            }

            /* الشعار في الوسط */
            header.header-mobile .de-flex-col:first-child {
                flex: 0 0 auto !important;
                margin: 0 auto !important;
            }

            header.header-mobile #logo {
                display: block !important;
            }

            /* زر القائمة على اليسار - position absolute */
            header.header-mobile .de-flex-col:last-child {
                position: absolute !important;
                left: 15px !important;
                top: 50% !important;
                transform: translateY(-50%) !important;
            }

            header.header-mobile .menu_side_area {
                display: flex !important;
                align-items: center !important;
            }

            /* زر القائمة - إلغاء FontAwesome */
            #menu-btn {
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
                width: 22px !important;
                height: 16px !important;
                cursor: pointer !important;
                margin-right: 15px !important;
                background: transparent !important;
                border: none !important;
                padding: 0 !important;
                align-self: center !important;
            }

            /* إلغاء الأيقونة القديمة */
            #menu-btn::before {
                content: none !important;
                display: none !important;
            }

            #menu-btn span {
                display: block !important;
                width: 100% !important;
                height: 2px !important;
                background: #fff !important;
                border-radius: 1.5px !important;
            }

            /* لون الزر عند scroll */
            header.scrolled #menu-btn span {
                background: #333 !important;
            }

            /* إخفاء زر التبرع */
            .donate-btn { display: none !important; }

            /* إخفاء المنيو الأصلي - بنستخدم الـ overlay */
            header.header-mobile #mainmenu,
            header.header-mobile .header-col-mid {
                display: none !important;
            }

            /* خلفية داكنة ثابتة للهيدر على الموبايل */
            header.header-mobile {
                background: #1a1a1a !important;
                height: auto !important;
                min-height: 70px !important;
                padding: 12px 0 !important;
            }

            header.header-mobile.menu-open {
                background: #1a1a1a !important;
                height: auto !important;
                min-height: 70px !important;
                overflow: visible !important;
                padding: 12px 0 !important;
            }

            /* منع تصغير الهيدر عند scroll */
            header.header-mobile.smaller,
            header.header-mobile.scrolled {
                min-height: 70px !important;
                padding: 12px 0 !important;
            }

            /* التأكد من ظهور الشعار كامل - حتى عند scroll */
            header.header-mobile #logo {
                max-height: none !important;
                height: auto !important;
                overflow: visible !important;
            }

            /* إظهار logo-mobile فقط على الموبايل */
            header.header-mobile #logo .logo-main,
            header.header-mobile #logo .logo-scroll {
                display: none !important;
            }

            header.header-mobile #logo .logo-mobile {
                display: block !important;
                max-height: 50px !important;
                height: 50px !important;
                width: auto !important;
            }

            /* عند scroll - الشعار يظل بنفس الحجم */
            header.header-mobile.smaller #logo .logo-mobile,
            header.header-mobile.scrolled #logo .logo-mobile {
                max-height: 50px !important;
                height: 50px !important;
            }
        }
    </style>

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

{{-- Mobile Menu Overlay --}}
<div id="mobile-menu-overlay">
    <div id="mobile-menu-content">
        <div id="mobile-menu-close">×</div>
        <div id="mobile-menu-items">
            <!-- سيتم ملؤه بـ JavaScript -->
        </div>
    </div>
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
    // Mobile Menu System
    (function() {
        const menuBtn = document.getElementById('menu-btn');
        const overlay = document.getElementById('mobile-menu-overlay');
        const menuClose = document.getElementById('mobile-menu-close');
        const mobileContent = document.getElementById('mobile-menu-content');
        const mainMenu = document.getElementById('mainmenu');

        if (!menuBtn || !overlay || !mainMenu) return;

        // نسخ المنيو
        let menuClone = null;

        // إيقاف designesia.js تماماً
        setTimeout(function() {
            // إزالة كل event listeners من designesia.js
            const newMenuBtn = menuBtn.cloneNode(true);
            menuBtn.parentNode.replaceChild(newMenuBtn, menuBtn);

            // إعادة التعريف
            const menuButton = document.getElementById('menu-btn');

            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (!menuClone) {
                    menuClone = mainMenu.cloneNode(true);
                    menuClone.id = 'mobile-mainmenu';
                    const menuItemsContainer = document.getElementById('mobile-menu-items');
                    if (menuItemsContainer) {
                        menuItemsContainer.appendChild(menuClone);
                    }

                    // إضافة accordion functionality
                    const menuItems = menuClone.querySelectorAll(':scope > li');
                    menuItems.forEach(function(li) {
                        const link = li.querySelector(':scope > a');
                        const submenu = li.querySelector(':scope > ul');

                        if (submenu) {
                            // العناصر اللي فيها قوائم فرعية - فقط فتح/إغلاق
                            link.addEventListener('click', function(e) {
                                e.preventDefault();

                                // إغلاق القوائم الأخرى
                                menuItems.forEach(function(otherLi) {
                                    if (otherLi !== li && otherLi.classList.contains('open')) {
                                        otherLi.classList.remove('open');
                                    }
                                });

                                // فتح/إغلاق هذه القائمة
                                li.classList.toggle('open');
                            });

                            // الروابط الفرعية - إغلاق القائمة والذهاب
                            const subLinks = submenu.querySelectorAll('a');
                            subLinks.forEach(function(subLink) {
                                subLink.addEventListener('click', function() {
                                    closeMenu();
                                });
                            });
                        } else {
                            // العناصر بدون قوائم فرعية - إغلاق والذهاب
                            link.addEventListener('click', function(e) {
                                const href = link.getAttribute('href');

                                // إذا كان رابط داخلي مع hash (#)
                                if (href && href.includes('#')) {
                                    e.preventDefault();
                                    closeMenu();

                                    // الانتقال بعد إغلاق القائمة
                                    setTimeout(function() {
                                        window.location.href = href;
                                    }, 300);
                                } else {
                                    closeMenu();
                                }
                            });
                        }
                    });
                }

                overlay.classList.add('active');
                document.body.style.overflow = 'hidden';

                console.log('✅ Mobile menu opened!');
            });

            function closeMenu() {
                overlay.classList.remove('active');
                document.body.style.overflow = '';

                // إغلاق كل القوائم الفرعية المفتوحة
                if (menuClone) {
                    const openItems = menuClone.querySelectorAll('li.open');
                    openItems.forEach(function(item) {
                        item.classList.remove('open');
                    });
                }

                console.log('❌ Mobile menu closed!');
            }

            menuClose.addEventListener('click', closeMenu);

            // إغلاق عند الضغط على الخلفية المعتمة
            overlay.addEventListener('click', function(e) {
                // إذا ضغط على الـ overlay نفسه (مش الـ sidebar)
                if (e.target === overlay || e.target === overlay.querySelector('::before')) {
                    closeMenu();
                }
            });
        }, 100);
    })();

    console.log('✅ Mobile menu system loaded!');
</script>
@stack('scripts')
</body>
</html>
