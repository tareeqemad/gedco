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


        /* إخفاء الـ scrollbar في كل المتصفحات */
        /* For Chrome, Safari, Edge */
        ::-webkit-scrollbar {
            width: 0px;
            height: 0px;
        }

        /* For Firefox */
        * {
            scrollbar-width: none;
        }

        /* For IE and Edge (old) */
        body {
            -ms-overflow-style: none;
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
            padding: 48px 18px 28px;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -5px 0 20px rgba(0,0,0,0.3);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            gap: 20px;
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

        #mobile-menu-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
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

            /* خلفية داكنة ثابتة للهيدر على الموبايل - حجم أصغر */
            header.header-mobile {
                background: #1a1a1a !important;
                height: auto !important;
                min-height: 55px !important; /* حجم أصغر */
                padding: 8px 0 !important; /* padding أقل */
            }

            header.header-mobile.menu-open {
                background: #1a1a1a !important;
                height: auto !important;
                min-height: 55px !important;
                overflow: visible !important;
                padding: 8px 0 !important;
            }

            /* منع تصغير الهيدر عند scroll */
            header.header-mobile.smaller,
            header.header-mobile.scrolled {
                min-height: 55px !important;
                padding: 8px 0 !important;
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
                max-height: 40px !important; /* شعار أصغر */
                height: 40px !important;
                width: auto !important;
            }

            /* عند scroll - الشعار يظل بنفس الحجم */
            header.header-mobile.smaller #logo .logo-mobile,
            header.header-mobile.scrolled #logo .logo-mobile {
                max-height: 40px !important;
                height: 40px !important;
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

    (function headerMiniSearch() {
        const configs = [
            {
                toggle: document.getElementById('header-search-toggle'),
                popover: document.getElementById('header-search-popover'),
                form: document.getElementById('header-search-form'),
                input: document.getElementById('header-search-input'),
                message: document.getElementById('header-search-message'),
                resultsList: document.getElementById('header-search-results'),
            },
            {
                form: document.getElementById('mobile-search-form'),
                input: document.getElementById('mobile-search-input'),
                message: document.getElementById('mobile-search-message'),
                resultsList: document.getElementById('mobile-search-results'),
            },
        ].filter(cfg => cfg.form && cfg.input && cfg.message && cfg.resultsList);

        if (!configs.length) return;

        const debounce = (fn, delay = 220) => {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => fn.apply(null, args), delay);
            };
        };

        configs.forEach(cfg => initSearch(cfg));

        function initSearch(cfg) {
            const {form, input, resultsList, message, toggle, popover} = cfg;

            const suggestionsEndpoint = form.dataset.suggestionsEndpoint;
            let suggestionController = null;
            let suppressClick = false;
            let isOpen = !toggle;
            let activeItem = null;
            let globalCloseListener = null;

            const setMessage = (text = '', tone = 'info') => {
                message.textContent = text || '';
                message.classList.remove('is-error', 'is-success');
                if (tone === 'error') message.classList.add('is-error');
                if (tone === 'success') message.classList.add('is-success');
            };

            const clearMessage = () => setMessage('');

            const setActiveItem = item => {
                if (activeItem === item) return;
                if (activeItem) {
                    activeItem.classList.remove('is-active');
                }
                activeItem = item;
                if (activeItem) {
                    activeItem.classList.add('is-active');
                }
            };

            const renderSuggestions = items => {
                resultsList.innerHTML = '';
                setActiveItem(null);
                if (!items.length) {
                    resultsList.classList.remove('is-visible');
                    return;
                }

                items.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'header-search-mini__result';
                    li.setAttribute('data-url', item.url);
                    li.setAttribute('role', 'option');
                    li.setAttribute('tabindex', '0');
                    li.innerHTML = `
                        <span class="header-search-mini__result-title">${item.label}</span>
                        <span class="header-search-mini__result-meta">${item.type}</span>
                    `;
                    resultsList.appendChild(li);
                });

                resultsList.scrollTop = 0;
                resultsList.classList.add('is-visible');
            };

            const fetchSuggestions = debounce(async term => {
                if (!suggestionsEndpoint) return;

                if (term.length < 2) {
                    renderSuggestions([]);
                    clearMessage();
                    return;
                }

                try {
                    suggestionController?.abort();
                    suggestionController = new AbortController();

                    const response = await fetch(`${suggestionsEndpoint}?q=${encodeURIComponent(term)}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        signal: suggestionController.signal,
                    });

                    if (!response.ok) {
                        throw new Error('Suggestion request failed');
                    }

                    const payload = await response.json();

                    if (Array.isArray(payload.suggestions)) {
                        renderSuggestions(payload.suggestions);
                        if (payload.suggestions.length) {
                            clearMessage();
                        } else {
                            setMessage('لم يتم العثور على نتائج مطابقة.', 'error');
                        }
                    }
                } catch (error) {
                    if (error.name === 'AbortError') return;
                    console.error(error);
                    renderSuggestions([]);
                    setMessage('تعذر تحميل النتائج الآن. حاول لاحقاً.', 'error');
                }
            });

            const activateResult = url => {
                if (!url) return;
                window.location.href = url;
            };

            input.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            input.addEventListener('input', () => {
                const term = input.value.trim();
                fetchSuggestions(term);
            });

            form.addEventListener('submit', event => {
                event.preventDefault();
            });

            const handleActivate = item => {
                if (!item) return;
                activateResult(item.dataset.url);
            };

            resultsList.addEventListener('pointerdown', event => {
                const item = event.target.closest('[data-url]');
                if (!item) return;
                handleActivate(item);
            });

            resultsList.addEventListener('click', event => {
                const item = event.target.closest('[data-url]');
                if (!item) return;
                handleActivate(item);
            });

            const handleHover = event => {
                const item = event.target.closest('[data-url]');
                if (!item) return;
                setActiveItem(item);
            };

            resultsList.addEventListener('pointermove', handleHover);
            resultsList.addEventListener('mousemove', handleHover);
            resultsList.addEventListener('mouseover', handleHover);
            resultsList.addEventListener('touchmove', event => {
                const touch = event.touches[0];
                if (!touch) return;
                const element = document.elementFromPoint(touch.clientX, touch.clientY);
                if (!element) return;
                const item = element.closest('[data-url]');
                if (!item) return;
                setActiveItem(item);
            }, {passive: true});

            resultsList.addEventListener('pointerleave', () => {
                setActiveItem(null);
            });

            resultsList.addEventListener('keydown', event => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            const handleWheelScroll = event => {
                if (event.ctrlKey) return;
                const item = event.target.closest('.header-search-mini__result');
                if (item && !event.defaultPrevented) {
                    setActiveItem(item);
                }
                const atTop = resultsList.scrollTop <= 0;
                const atBottom = resultsList.scrollTop + resultsList.clientHeight >= resultsList.scrollHeight;
                const scrollingUp = event.deltaY < 0;
                const scrollingDown = event.deltaY > 0;

                let consumed = false;

                if ((scrollingUp && !atTop) || (scrollingDown && !atBottom)) {
                    resultsList.scrollTop += event.deltaY;
                    consumed = true;
                }

                if (consumed) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                if (consumed || (!consumed && resultsList.matches(':hover'))) {
                    resultsList.classList.add('is-wheel-active');
                    clearTimeout(resultsList._wheelTimeout);
                    resultsList._wheelTimeout = setTimeout(() => {
                        resultsList.classList.remove('is-wheel-active');
                    }, 160);
                }
            };

            ['wheel', 'mousewheel', 'DOMMouseScroll'].forEach(evt => {
                resultsList.addEventListener(evt, handleWheelScroll, {passive: false});
            });

            if (toggle && popover) {
                const openPopover = () => {
                    if (isOpen) return;
                    popover.classList.add('is-visible');
                    toggle.setAttribute('aria-expanded', 'true');
                    isOpen = true;
                    renderSuggestions([]);
                    clearMessage();
                    requestAnimationFrame(() => input.focus());
                };

                const closePopover = () => {
                    if (!isOpen) return;
                    popover.classList.remove('is-visible');
                    toggle.setAttribute('aria-expanded', 'false');
                    isOpen = false;
                    form.reset();
                    clearMessage();
                    renderSuggestions([]);
                };

                const togglePopover = event => {
                    event.preventDefault();
                    if (isOpen) {
                        closePopover();
                    } else {
                        openPopover();
                    }
                };

                toggle.addEventListener('pointerdown', event => {
                    suppressClick = true;
                    togglePopover(event);
                });

                toggle.addEventListener('click', event => {
                    if (suppressClick) {
                        suppressClick = false;
                        return;
                    }
                    togglePopover(event);
                });

                toggle.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        togglePopover(event);
                    }
                });

                const handleDocPointer = event => {
                    if (!isOpen) return;
                    if (!popover.contains(event.target) && !toggle.contains(event.target)) {
                        closePopover();
                    }
                };

                const handleDocKey = event => {
                    if (isOpen && event.key === 'Escape') {
                        closePopover();
                    }
                };

                document.addEventListener('pointerdown', handleDocPointer);
                document.addEventListener('keydown', handleDocKey);
            } else {
                isOpen = true;
                renderSuggestions([]);
                clearMessage();
                globalCloseListener = event => {
                    if (form.contains(event.target)) return;
                    form.reset();
                    clearMessage();
                    renderSuggestions([]);
                };
                document.addEventListener('pointerdown', globalCloseListener);
                document.addEventListener('touchstart', globalCloseListener);
            }
        }
    })();

    console.log('✅ Mobile menu system loaded!');

    // إصلاح مشكلة التحويل بين الموبايل والديسكتوب
    let lastWidth = window.innerWidth;
    window.addEventListener('resize', function() {
        const currentWidth = window.innerWidth;

        // إذا تغير حجم الشاشة بشكل كبير (من موبايل لديسكتوب أو العكس)
        if ((lastWidth <= 991 && currentWidth > 991) || (lastWidth > 991 && currentWidth <= 991)) {
            // إعادة ضبط الستايلات
            document.body.style.overflow = '';
            document.documentElement.style.overflow = '';

            // إعادة السكرول للأعلى
            window.scrollTo(0, 0);

            // إغلاق القائمة إذا كانت مفتوحة
            const overlay = document.getElementById('mobile-menu-overlay');
            if (overlay) {
                overlay.classList.remove('active');
            }

            // تحديث كلاس الهيدر
            const header = document.querySelector('header');
            if (header) {
                if (currentWidth <= 991) {
                    header.classList.add('header-mobile');
                } else {
                    header.classList.remove('header-mobile');
                }
            }

            // إعادة حساب ارتفاع الصفحة
            setTimeout(function() {
                document.body.style.minHeight = '';
                document.documentElement.style.minHeight = '';
            }, 100);
        }

        lastWidth = currentWidth;
    });
</script>
@stack('scripts')
</body>
</html>
