@extends('layouts.site')

@section('title', __('common.news_page_title') . ' | ' . __('common.home'))
@section('meta_description', __('common.news_page_description'))

@push('styles')
    <style>
        :root {
            --news-primary: #0f172a;
            --news-accent: #ff6b35;
            --news-muted: #64748b;
            --news-border: rgba(148, 163, 184, 0.25);
            --news-surface: #ffffff;
            --news-background: #f8fafc;
        }

        section#subheader {
            margin-top: 8px !important;
            padding-top: 140px !important;
            padding-bottom: 100px !important;
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }

        section#subheader h1 {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            line-height: 1.3;
            letter-spacing: .8px;
            margin-bottom: 0;
            padding: 0 1.25rem;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
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

        .news-section {
            position: relative;
            padding: clamp(4rem, 7vw, 5rem) 0 clamp(3rem, 8vw, 4.5rem);
            background: var(--news-background);
        }

        .news-filters {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            justify-content: center;
            align-items: center;
            margin-bottom: 2.25rem;
        }

        .news-filters__button {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .55rem 1.35rem;
            border-radius: 999px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            background: rgba(255, 255, 255, 0.9);
            color: var(--news-primary);
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease, color .25s ease, background .25s ease;
        }

        .news-filters__button i {
            font-size: 1rem;
        }

        .news-filters__button:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 28px rgba(15, 23, 42, 0.12);
        }

        .news-filters__button.is-active {
            color: #fff;
            background: linear-gradient(135deg, var(--news-accent), #ff8f3b);
            border-color: transparent;
            box-shadow: 0 18px 34px rgba(255, 107, 53, 0.2);
        }

        .news-filters__button.is-active i {
            color: #fff;
        }

        .news-filters__search {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            padding: .45rem .5rem;
            border-radius: 999px;
            border: 1px solid rgba(148, 163, 184, 0.3);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
            order: 1;
        }

        .news-filters__search input {
            border: none;
            outline: none;
            background: transparent;
            padding: 0 .25rem;
            font-size: .86rem;
            font-weight: 600;
            min-width: 220px;
            color: var(--news-primary);
        }

        .news-filters__search button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .35rem;
            padding: .45rem 1rem;
            border-radius: 999px;
            border: none;
            background: linear-gradient(135deg, var(--news-primary), rgba(15, 23, 42, 0.85));
            color: #fff;
            font-weight: 700;
            font-size: .8rem;
            cursor: pointer;
            transition: transform .25s ease, box-shadow .25s ease;
        }

        .news-filters__search button:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 20px rgba(15, 23, 42, 0.2);
        }

        .news-card {
            opacity: 1;
            transform: none;
        }

        #news-wrapper {
            position: relative;
        }

        #news-grid {
            position: relative;
        }

        #news-wrapper.news-wrapper--expanded {
            min-height: clamp(62vh, calc(100vh - 240px), 1000px);
        }

        #news-grid.news-grid--expanded {
            min-height: clamp(48vh, calc(100vh - 320px), 860px);
        }

        .news-heading {
            text-align: right;
            margin-bottom: 3rem;
        }

        .news-heading .news-heading__kicker {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .4rem 1.1rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.06);
            color: var(--news-accent);
            font-weight: 600;
            justify-content: center;
            margin: 0 0 .6rem auto;
        }

        .news-heading h2 {
            margin: .35rem 0 .5rem;
            font-size: clamp(1.75rem, 2.5vw, 2.25rem);
            font-weight: 800;
            color: var(--news-primary);
            display: block;
            text-align: right;
        }
        
        [dir="ltr"] .news-heading h2 {
            text-align: left;
        }

        .news-heading p {
            margin: 0 0 0 auto;
            max-width: none;
            color: var(--news-muted);
            font-size: 1rem;
            line-height: 1.8;
            text-align: right;
            white-space: nowrap;
        }
        
        [dir="ltr"] .news-heading p {
            margin: 0 auto 0 0;
            text-align: left;
        }
        
        [dir="ltr"] .news-heading .news-heading__kicker {
            margin: 0 0 .6rem 0;
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: clamp(1rem, 2.5vw, 1.5rem);
            align-items: stretch;
        }

        .news-card {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: var(--news-surface);
            border-radius: 20px;
            border: 1px solid var(--news-border);
            overflow: hidden;
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
            transition: transform .35s ease, box-shadow .35s ease;
        }

        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 32px 60px rgba(15, 23, 42, 0.12);
        }

        .news-card__thumb {
            position: relative;
            display: block;
            aspect-ratio: 4 / 3;
            overflow: hidden;
        }

        .news-card__thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .45s ease;
        }

        .news-card__badge {
            position: absolute;
            top: .82rem;
            right: .82rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .32rem .9rem;
            border-radius: 10px;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .3px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.92), rgba(248, 250, 252, 0.88));
            color: var(--news-primary);
            border: 1px solid rgba(148, 163, 184, 0.3);
            z-index: 2;
            backdrop-filter: blur(4px);
        }

        .news-card__badge i {
            font-size: .8rem;
        }

        .news-card__badge--featured {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.92), rgba(30, 41, 59, 0.9));
            color: #fff;
            border-color: rgba(15, 23, 42, 0.45);
        }

        .news-card__badge--new {
            background: linear-gradient(135deg, var(--news-accent), #ff7a2b);
            color: #fff;
            border-color: rgba(255, 133, 74, 0.4);
        }

        .news-card:hover .news-card__thumb img {
            transform: scale(1.08);
        }

        .news-card__body {
            flex: 1 1 auto;
            display: flex;
            flex-direction: column;
            gap: .6rem;
            padding: 1.05rem 1.2rem .85rem;
        }

        .news-card__meta {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: var(--news-muted);
            font-size: .85rem;
        }

        .news-card__meta i {
            color: var(--news-accent);
            font-size: 1.05rem;
        }

        .news-card__title {
            margin: 0;
            font-size: .98rem;
            font-weight: 700;
            color: var(--news-primary);
            line-height: 1.35;
        }

        .news-card__title a {
            color: inherit;
            text-decoration: none;
            transition: color .3s ease;
        }

        .news-card:hover .news-card__title a {
            color: var(--news-accent);
        }

        .news-card__excerpt {
            margin: 0;
            color: var(--news-muted);
            font-size: .86rem;
            line-height: 1.5;
            flex: 1 1 auto;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .news-card__footer {
            margin-top: auto;
            display: flex;
            flex-wrap: wrap;
            gap: .65rem;
            align-items: center;
        }

        .news-card__cta {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .5rem 1.15rem;
            border-radius: 999px;
            font-size: .82rem;
            font-weight: 700;
            text-decoration: none;
            transition: transform .25s ease, box-shadow .25s ease, background .25s ease, color .25s ease;
            background: var(--news-accent);
            color: #fff;
        }

        .news-card__cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 107, 53, 0.25);
        }

        .news-card__cta--ghost {
            background: rgba(255, 107, 53, 0.14);
            color: var(--news-accent);
        }

        .news-card__cta--ghost:hover {
            background: rgba(255, 107, 53, 0.2);
            color: var(--news-primary);
        }

        .news-card__tag {
            margin-inline-start: auto;
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            font-weight: 700;
            color: var(--news-accent);
            letter-spacing: .35px;
        }

        .news-card__tag i {
            color: var(--news-accent);
            font-size: .85rem;
        }

        .news-pagination {
            margin-top: 3rem;
            margin-bottom: 2rem;
            display: flex;
            justify-content: center;
        }

        .news-pagination .pagination {
            gap: 0.5rem;
            flex-wrap: wrap;
            display: flex !important;
            align-items: center !important;
        }

        .news-pagination .page-item {
            display: flex;
            align-items: center;
        }

        .news-pagination .page-link {
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
            font-weight: 600;
            border-radius: 12px;
            padding: 0.55rem 1.2rem;
            color: var(--news-accent);
            border: 2px solid #e9ecef;
            background: #fff;
            min-width: 46px;
            min-height: 46px;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            transition: all .3s ease;
        }

        .news-pagination .page-link i {
            font-size: 1.1rem;
            line-height: 1;
        }

        .news-pagination .page-item:first-child .page-link,
        .news-pagination .page-item:last-child .page-link {
            transform: scaleX(-1);
        }

        .news-pagination .page-item:first-child .page-link i,
        .news-pagination .page-item:last-child .page-link i {
            transform: scaleX(-1);
        }

        .news-pagination .page-link:hover {
            background: var(--news-accent);
            color: #fff;
            border-color: var(--news-accent);
        }

        .news-pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #ff6b35, #ff8c00);
            border-color: #ff6b35;
            color: #fff;
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }

        .news-pagination .page-item.disabled .page-link {
            background: #f8f9fa;
            border-color: #e9ecef;
            color: #adb5bd;
        }

        .news-empty {
            text-align: center;
            padding: 4rem 2rem;
            border-radius: 22px;
            background: linear-gradient(145deg, #ffffff, #f1f5f9);
            border: 1px solid rgba(148, 163, 184, 0.25);
            color: var(--news-muted);
            box-shadow: 0 24px 44px rgba(15, 23, 42, 0.08);
        }

        .news-empty i {
            font-size: 3.2rem;
            color: rgba(15, 23, 42, 0.2);
        }

        .news-empty p {
            margin-top: 1.2rem;
            font-size: 1.05rem;
            font-weight: 600;
        }

        @media (max-width: 991.98px) {
            section#subheader {
                margin-top: 25px !important;
                padding-top: 110px !important;
                padding-bottom: 80px !important;
            }

            section#subheader h1 {
                font-size: 1.85rem !important;
                line-height: 1.35 !important;
                padding: 0 1rem;
            }

            .news-section {
                padding: 3.5rem 0 3rem;
            }
        }

        @media (max-width: 767.98px) {
            .news-grid {
                gap: 1.5rem;
            }

            .news-filters {
                gap: .5rem;
                margin-bottom: 1.75rem;
            }

            .news-filters__button {
                flex: 1 1 calc(50% - .5rem);
                justify-content: center;
                padding: .5rem 1rem;
                font-size: .82rem;
                order: 2;
            }

            .news-filters__search {
                flex-basis: 100%;
                justify-content: space-between;
                order: 0;
            }

            .news-filters__search input {
                min-width: 0;
                flex: 1 1 auto;
                font-size: .82rem;
            }

            .news-filters__search button {
                padding: .45rem .9rem;
                font-size: .78rem;
            }

            .news-heading {
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: .35rem;
            }

            .news-heading .news-heading__kicker {
                margin: 0;
            }

            .news-heading h2 {
                text-align: center;
                margin: .35rem auto .5rem;
                max-width: 100%;
                display: block;
            }

            .news-heading p {
                margin: 0 auto;
                text-align: center;
                max-width: 640px;
                white-space: normal;
            }

            .news-card {
                border-radius: 18px;
            }

            .news-card__thumb {
                aspect-ratio: 4 / 3;
            }

            .news-card__body {
                padding: 1rem 1rem .75rem;
            }

            section#subheader .container {
                max-width: 100% !important;
                padding-left: 15px !important;
                padding-right: 15px !important;
            }

            section#subheader .crumb {
                padding: .5rem 1rem;
            }
        }

        @media (max-width: 575.98px) {
            .news-section {
                padding: 3rem 0 2.5rem;
            }

            .news-pagination {
                margin-top: 1.5rem;
                margin-bottom: 2.5rem;
            }

            .news-pagination .page-link {
                padding: 0.4rem 0.6rem;
                min-width: 36px;
                min-height: 36px;
                font-size: 0.85rem;
                border-width: 1.5px;
            }

        }

        @media (max-width: 420px) {
            section#subheader h1 {
                font-size: 1.55rem !important;
                line-height: 1.32 !important;
                padding: 0 .75rem;
                max-width: 95%;
            }

            .news-pagination {
                gap: 0.35rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $currentDir = session('direction', 'rtl');
        $isRtl = $currentDir === 'rtl';
    @endphp
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             dir="{{ $currentDir }}">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">{{ __('common.news_page_title') }}</h1>
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ route('site.home') }}">{{ __('common.home') }}</a></li>
                            <li class="active">{{ __('common.news_page_title') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>

    <section class="news-section" dir="{{ $currentDir }}">
        <div class="container news-wrapper--expanded" id="news-wrapper">
            @php
                $activeFilter = $activeFilter ?? request('filter', 'all');
            @endphp
            <div class="news-heading">
                <span class="news-heading__kicker"><i class="ri-bar-chart-fill"></i> {{ __('common.news_page_subtitle') }}</span>
                <h2>{{ __('common.news_page_heading') }}</h2>
                <p>{{ __('common.news_page_description') }}</p>
            </div>

            <div class="news-filters" id="news-filters" aria-label="مرشحات الأخبار">
                <form class="news-filters__search" id="news-search-form" action="{{ route('site.news') }}" method="post">
                    @csrf
                    <input
                        type="text"
                        name="search"
                        id="news-search-input"
                        value="{{ $searchTerm }}"
                        placeholder="{{ __('common.news_search_placeholder') }}"
                        aria-label="{{ __('common.news_search_placeholder') }}"
                        dir="auto"
                        autocomplete="off"
                    >
                    <input type="hidden" name="filter" value="{{ $activeFilter }}">
                    <button type="submit" id="news-search-btn">
                        <i class="ri-search-line"></i>
                        {{ __('common.search') }}
                    </button>
                </form>
                <button type="button" class="news-filters__button {{ $activeFilter === 'all' ? 'is-active' : '' }}" data-filter="all">
                    <i class="ri-apps-2-line"></i>
                    {{ __('common.news_filter_all') }}
                </button>
                <button type="button" class="news-filters__button {{ $activeFilter === 'featured' ? 'is-active' : '' }}" data-filter="featured">
                    <i class="ri-vip-crown-line"></i>
                    {{ __('common.news_filter_featured') }}
                </button>
                <button type="button" class="news-filters__button {{ $activeFilter === 'fresh' ? 'is-active' : '' }}" data-filter="fresh">
                    <i class="ri-flashlight-fill"></i>
                    {{ __('common.news_filter_fresh') }}
                </button>
                <button type="button" class="news-filters__button {{ $activeFilter === 'week' ? 'is-active' : '' }}" data-filter="week">
                    <i class="ri-calendar-todo-line"></i>
                    {{ __('common.news_filter_week') }}
                </button>
            </div>

            <div class="news-grid news-grid--expanded" id="news-grid">
                @include('site.news.partials.grid', ['newsItems' => $newsItems])
            </div>

            <div class="news-pagination" id="pagination-wrapper">
                @if($newsItems->hasPages())
                    {{ $newsItems->onEachSide(1)->links() }}
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const searchForm = document.getElementById('news-search-form');
            const searchInput = document.getElementById('news-search-input');
            const searchBtn = document.getElementById('news-search-btn');
            const newsGrid = document.getElementById('news-grid');
            const paginationWrapper = document.getElementById('pagination-wrapper');
            let searchTimeout;

            // AJAX Search - فقط عند الضغط على زر البحث
            if (searchForm && searchInput) {
                // البحث فقط عند الضغط على Submit (زر البحث أو Enter)
                searchForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const activeFilter = document.querySelector('.news-filters__button.is-active')?.dataset.filter || 'all';
                    performSearch(searchInput.value.trim(), 1, activeFilter);
                });
            }

            function performSearch(searchTerm, page = 1, filter = null) {
                // استخدام الفلتر المحدد أو الفلتر النشط الحالي
                const activeFilter = filter || document.querySelector('.news-filters__button.is-active')?.dataset.filter || 'all';
                
                // إظهار loading
                newsGrid.style.opacity = '0.5';
                if (searchBtn) {
                    searchBtn.disabled = true;
                    searchBtn.innerHTML = '<i class="ri-loader-4-line"></i>';
                }

                // AJAX Request
                fetch('{{ route("site.news") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        search: searchTerm,
                        filter: activeFilter,
                        page: page
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // تحديث الـgrid
                    newsGrid.innerHTML = data.html;
                    
                    // تحديث الـpagination
                    paginationWrapper.innerHTML = data.pagination || '';
                    
                    // إعادة ربط event listeners للـpagination
                    attachPaginationListeners();
                    
                    // Scroll to top
                    window.scrollTo({ top: newsGrid.offsetTop - 100, behavior: 'smooth' });
                })
                .catch(error => {
                    console.error('Search error:', error);
                    alert('حدث خطأ أثناء البحث. يرجى المحاولة مرة أخرى.');
                })
                .finally(() => {
                    newsGrid.style.opacity = '1';
                    if (searchBtn) {
                        searchBtn.disabled = false;
                        searchBtn.innerHTML = '<i class="ri-search-line"></i> {{ __('common.search') }}';
                    }
                });
            }

            // AJAX Pagination
            function attachPaginationListeners() {
                const paginationLinks = paginationWrapper.querySelectorAll('a.page-link, a[href*="page="]');
                paginationLinks.forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // استخراج رقم الصفحة من الرابط
                        const url = new URL(this.href);
                        const page = url.searchParams.get('page') || 1;
                        const currentSearch = searchInput ? searchInput.value.trim() : '';
                        const activeFilter = document.querySelector('.news-filters__button.is-active')?.dataset.filter || 'all';
                        
                        // جلب الصفحة المطلوبة
                        performSearch(currentSearch, page, activeFilter);
                    });
                });
            }

            // AJAX Filter Buttons
            const filterButtons = document.querySelectorAll('.news-filters__button[data-filter]');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // إزالة active من كل الأزرار
                    filterButtons.forEach(btn => btn.classList.remove('is-active'));
                    
                    // إضافة active للزر المضغوط
                    this.classList.add('is-active');
                    
                    // جلب النتائج مع الفلتر الجديد
                    const filter = this.dataset.filter;
                    const currentSearch = searchInput ? searchInput.value.trim() : '';
                    performSearch(currentSearch, 1, filter);
                });
            });

            // ربط event listeners عند تحميل الصفحة
            attachPaginationListeners();
        })();
    </script>
@endpush

