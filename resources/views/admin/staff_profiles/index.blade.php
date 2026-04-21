@php
    $breadcrumbTitle     = __('admin.staff_profiles.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
    
    $locations = ['1'=>__('admin.staff_profiles_data.location_1'),'2'=>__('admin.staff_profiles_data.location_2'),'3'=>__('admin.staff_profiles_data.location_3'),'4'=>__('admin.staff_profiles_data.location_4'),'6'=>__('admin.staff_profiles_data.location_6'),'7'=>__('admin.staff_profiles_data.location_7'),'8'=>__('admin.staff_profiles_data.location_8')];
    $statusMap = ['resident'=>['label'=>__('admin.staff_profiles_data.status_resident_label'),'class'=>'bg-success-subtle text-success'], 'displaced'=>['label'=>__('admin.staff_profiles_data.status_displaced_label'),'class'=>'bg-danger-subtle text-danger']];
    $readinessMap = ['working'=>['label'=>__('admin.staff_profiles_data.readiness_working_label'),'class'=>'bg-success text-white'], 'ready'=>['label'=>__('admin.staff_profiles_data.readiness_ready_label'),'class'=>'bg-primary text-white'], 'not_ready'=>['label'=>__('admin.staff_profiles_data.readiness_not_ready_label'),'class'=>'bg-warning text-dark']];
@endphp
@extends('layouts.admin')
@section('title', __('admin.staff_profiles.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Main Card -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-person-lines-fill"
                :title="__('admin.staff_profiles.title')">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header">{{ __('admin.staff_profiles_data.employee_count', ['count' => $stats['total']]) }}</span>
                </x-slot:badge>
            </x-admin.card-header-index>

            {{-- الإحصائيات التفاعلية - compact --}}
            <div class="card-body p-3">
                <div id="active-filters" class="mb-2"></div>

                <div class="d-flex gap-2 flex-wrap mb-3">
                    @php
                        $filterCards = [
                            ['filter' => 'status', 'value' => 'displaced', 'label' => __('admin.staff_profiles.status_displaced'), 'icon' => 'bi-truck', 'count' => $stats['displaced'], 'color' => '#DC2626'],
                            ['filter' => 'status', 'value' => 'resident', 'label' => __('admin.staff_profiles.status_resident'), 'icon' => 'bi-house-door-fill', 'count' => $stats['resident'], 'color' => '#16A34A'],
                            ['filter' => 'readiness', 'value' => 'not_ready', 'label' => __('admin.staff_profiles.readiness_not_ready'), 'icon' => 'bi-person-x', 'count' => $stats['not_ready'], 'color' => '#D97706'],
                            ['filter' => 'readiness', 'value' => 'ready', 'label' => __('admin.staff_profiles.readiness_ready'), 'icon' => 'bi-hand-thumbs-up-fill', 'count' => $stats['ready'], 'color' => '#0284C7'],
                            ['filter' => 'readiness', 'value' => 'working', 'label' => __('admin.staff_profiles.readiness_working'), 'icon' => 'bi-person-check-fill', 'count' => $stats['working'], 'color' => '#0D9488'],
                        ];
                    @endphp
                    @foreach($filterCards as $fc)
                        <div class="staff-filter-chip cursor-pointer {{ request($fc['filter']) === $fc['value'] ? 'active' : '' }}"
                             data-filter="{{ $fc['filter'] }}" data-value="{{ $fc['value'] }}" data-label="{{ $fc['label'] }}"
                             style="--chip-color: {{ $fc['color'] }};">
                            <i class="bi {{ $fc['icon'] }}"></i>
                            <strong>{{ $fc['count'] }}</strong>
                            <span>{{ $fc['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- بحث --}}
                @php
                    $currentSort = request('sort', 'date_desc');
                    $sortOptions = [
                        'date_desc' => ['label' => 'الأحدث تسجيلاً', 'icon' => 'bi-clock-history'],
                        'date_asc'  => ['label' => 'الأقدم تسجيلاً', 'icon' => 'bi-clock'],
                        'name_asc'  => ['label' => 'الاسم (أ - ي)', 'icon' => 'bi-sort-alpha-down'],
                        'name_desc' => ['label' => 'الاسم (ي - أ)', 'icon' => 'bi-sort-alpha-up'],
                        'emp_asc'   => ['label' => 'الرقم الوظيفي (تصاعدي)', 'icon' => 'bi-sort-numeric-down'],
                        'emp_desc'  => ['label' => 'الرقم الوظيفي (تنازلي)', 'icon' => 'bi-sort-numeric-up'],
                    ];
                @endphp
                <form method="GET" id="search-form">
                    {{-- الفلاتر المعلّقة (تتحكم فيها الرقاقات - لا تُرسل إلا عند ضغط زر البحث) --}}
                    <input type="hidden" name="status"    id="filter-status"    value="{{ request('status') }}">
                    <input type="hidden" name="readiness" id="filter-readiness" value="{{ request('readiness') }}">

                    {{-- السطر 1: 3 حقول بحث منفصلة (رقم الهوية - الرقم الوظيفي - الاسم) --}}
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <input type="text" name="national_id" value="{{ request('national_id') }}"
                                   class="form-control rounded-3"
                                   placeholder="رقم الهوية">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="employee_number" value="{{ request('employee_number') }}"
                                   class="form-control rounded-3"
                                   placeholder="الرقم الوظيفي">
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="name" value="{{ request('name') }}"
                                   class="form-control rounded-3"
                                   placeholder="الاسم">
                        </div>
                    </div>

                    {{-- السطر 2: فلاتر ثانوية (عمر الأطفال يمين، ترتيب يسار) --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3 secondary-filters">
                        <div class="input-group age-filter-group w-auto">
                            <span class="input-group-text bg-light text-muted small">
                                <i class="bi bi-people-fill me-1"></i> عمر الأطفال
                            </span>
                            <input type="number" name="age_from" value="{{ request('age_from') }}"
                                   class="form-control text-center" style="max-width: 64px;"
                                   placeholder="من" min="0">
                            <span class="input-group-text bg-light text-muted">—</span>
                            <input type="number" name="age_to" value="{{ request('age_to') }}"
                                   class="form-control text-center" style="max-width: 64px;"
                                   placeholder="إلى" min="0">
                            <span class="input-group-text bg-light text-muted small">سنة</span>
                        </div>

                        <div class="input-group sort-group w-auto">
                            <span class="input-group-text bg-light text-muted small">
                                <i class="bi bi-arrow-down-up me-1"></i> ترتيب
                            </span>
                            <select name="sort" class="form-select" style="min-width: 170px;">
                                @foreach($sortOptions as $val => $opt)
                                    <option value="{{ $val }}" {{ $currentSort === $val ? 'selected' : '' }}>
                                        {{ $opt['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- السطر 3: الأزرار في المنتصف --}}
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary rounded-3 px-4" id="search-submit-btn">
                            <span class="btn-label">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </span>
                            <span class="btn-loading d-none">
                                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                جارٍ البحث...
                            </span>
                        </button>

                        <a href="{{ route('admin.staff-profiles.export') }}"
                           id="export-btn"
                           data-base-url="{{ route('admin.staff-profiles.export') }}"
                           class="btn btn-success rounded-3 px-4"
                           title="{{ __('admin.staff_profiles_data.export_excel') }}">
                            <i class="bi bi-file-earmark-spreadsheet me-1"></i>
                            {{ __('admin.staff_profiles_data.export_excel') }}
                        </a>

                        <a href="{{ route('admin.staff-profiles.index') }}"
                           class="btn btn-outline-secondary rounded-3 px-4"
                           title="{{ __('admin.actions.clear') }}">
                            <i class="bi bi-x-lg me-1"></i> {{ __('admin.actions.clear') }}
                        </a>
                    </div>
                </form>
            </div>

            {{-- الجدول --}}
            <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                        <tr class="text-secondary small fw-bold">
                            <th class="ps-4">{{ __('admin.staff_profiles.table_id') }}</th>
                            <th>{{ __('admin.staff_profiles.table_employee') }}</th>
                            <th>{{ __('admin.staff_profiles.table_national_id') }}</th>
                            <th class="d-none d-md-table-cell">{{ __('admin.staff_profiles.table_location') }}</th>
                            <th>{{ __('admin.staff_profiles.table_status') }}</th>
                            <th class="d-none d-lg-table-cell">{{ __('admin.staff_profiles.table_readiness') }}</th>
                            <th class="d-none d-xl-table-cell">{{ __('admin.staff_profiles.table_registration') }}</th>
                            <th class="text-center">{{ __('admin.staff_profiles.table_view') }}</th>
                        </tr>
                        </thead>
                        <tbody class="text-dark" id="profiles-table-body"
                               data-current-page="{{ $profiles->currentPage() }}"
                               data-last-page="{{ $profiles->lastPage() }}"
                               data-total="{{ $profiles->total() }}">
                        @if($profiles->count())
                            @include('admin.staff_profiles._rows', ['profiles' => $profiles])
                        @else
                            <tr id="empty-row">
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open display-5 opacity-25 d-block mb-3"></i>
                                    {{ __('admin.staff_profiles.no_data') }}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

            {{-- شريط التحميل التلقائي + العدّاد --}}
            <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                    <div class="text-muted" id="scroll-counter">
                        {{ __('admin.staff_profiles.showing') }}
                        <strong id="scroll-loaded">{{ $profiles->count() }}</strong>
                        {{ __('admin.staff_profiles.of') }}
                        <strong>{{ $profiles->total() }}</strong>
                    </div>
                    <div id="scroll-status" class="text-muted"></div>
                </div>
            </div>

            {{-- مُستشعر الوصول لنهاية الجدول (IntersectionObserver) --}}
            <div id="scroll-sentinel" style="height:1px;"></div>
        </x-admin.card>
    </div>

    @push('styles')
        <style>
            .cursor-pointer { cursor: pointer; }
            #active-filters { display: flex; flex-wrap: wrap; gap: 0.5rem; }
            .filter-badge {
                display: inline-flex; align-items: center; gap: 0.4rem;
                padding: 0.3rem 0.75rem; background: #F0F4F8; color: #24364A;
                border-radius: 20px; font-size: 0.78rem; font-weight: 500;
            }
            .filter-badge .close-btn { cursor: pointer; font-size: 1.1rem; opacity: 0.5; }
            .filter-badge .close-btn:hover { opacity: 1; }

            /* فلتر عمر الأطفال + قائمة الترتيب - حواف دائرية موحدة */
            .age-filter-group,
            .sort-group {
                border-radius: 0.5rem;
                overflow: hidden;
                border: 1px solid #DEE2E6;
            }
            .age-filter-group > *,
            .sort-group > * {
                border-radius: 0 !important;
                border: none;
                border-right: 1px solid #DEE2E6;
            }
            .age-filter-group > *:last-child,
            .sort-group > *:last-child { border-right: none; }
            .age-filter-group .form-control:focus,
            .sort-group .form-select:focus {
                box-shadow: none;
                background: #F8FAFC;
            }
            .age-filter-group input[type="number"]::-webkit-inner-spin-button,
            .age-filter-group input[type="number"]::-webkit-outer-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            .age-filter-group input[type="number"] { -moz-appearance: textfield; }

            /* نبضة خفيفة على زر البحث عند وجود تغييرات لم تُطبّق */
            @keyframes btn-pulse-anim {
                0%, 100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0.4); }
                50%      { box-shadow: 0 0 0 6px rgba(13, 110, 253, 0); }
            }
            .btn-pulse { animation: btn-pulse-anim 1.4s ease-out infinite; }

            /* رقاقات الإحصائيات - حجم أصغر ومنظّم */
            .staff-filter-chip {
                padding: 0.3rem 0.7rem !important;
                font-size: 0.72rem !important;
                border-radius: 14px !important;
                gap: 4px !important;
            }
            .staff-filter-chip i { font-size: 0.78rem !important; }
            .staff-filter-chip strong { font-size: 0.8rem !important; }

            /* السطر الثاني - فلاتر ثانوية أصغر وبلون محايد */
            .secondary-filters .input-group-text,
            .secondary-filters .form-control,
            .secondary-filters .form-select {
                font-size: 0.8rem;
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }
            .secondary-filters .age-filter-group,
            .secondary-filters .sort-group {
                border-radius: 0.4rem;
            }
        </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statCards     = document.querySelectorAll('.staff-filter-chip');
        const badgesBox     = document.getElementById('active-filters');
        const searchForm    = document.getElementById('search-form');
        const searchBtn     = document.getElementById('search-submit-btn');
        const statusInput   = document.getElementById('filter-status');
        const readyInput    = document.getElementById('filter-readiness');

        // حالة الفلاتر المعلّقة (تعكس ما سيتم إرساله عند الضغط على زر البحث)
        let pendingFilters = {
            status:    statusInput?.value || null,
            readiness: readyInput?.value  || null
        };

        /* ========== قفل/فتح زر البحث أثناء الاستعلام ========== */
        let searchTimeout = null;
        const lockSearchBtn = () => {
            searchBtn.disabled = true;
            searchBtn.classList.add('disabled');
            searchBtn.querySelector('.btn-label')?.classList.add('d-none');
            searchBtn.querySelector('.btn-loading')?.classList.remove('d-none');
        };
        const unlockSearchBtn = () => {
            searchBtn.disabled = false;
            searchBtn.classList.remove('disabled');
            searchBtn.querySelector('.btn-label')?.classList.remove('d-none');
            searchBtn.querySelector('.btn-loading')?.classList.add('d-none');
            if (searchTimeout) { clearTimeout(searchTimeout); searchTimeout = null; }
        };

        if (searchForm && searchBtn) {
            searchForm.addEventListener('submit', function () {
                // تنظيف: لا نرسل حقول فارغة لتبقى الـURL نظيفة
                searchForm.querySelectorAll('input[name], select[name]').forEach(el => {
                    if (el.value === '' || el.value === null) el.disabled = true;
                });
                // لا نُرسل sort إذا كان القيمة الافتراضية
                const sortSel = searchForm.querySelector('select[name="sort"]');
                if (sortSel && sortSel.value === 'date_desc') sortSel.disabled = true;

                lockSearchBtn();
                searchTimeout = setTimeout(() => {
                    unlockSearchBtn();
                    const warn = document.createElement('div');
                    warn.className = 'alert alert-warning alert-dismissible fade show mt-2 small';
                    warn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i> الاستعلام يستغرق وقتاً أطول من المعتاد، يمكنك إعادة المحاولة.<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                    searchForm.parentElement.insertBefore(warn, searchForm);
                }, 10000);
            });

            window.addEventListener('pageshow', unlockSearchBtn);
            window.addEventListener('focus', () => {
                if (searchBtn.disabled && !searchTimeout) unlockSearchBtn();
            });
        }

        /* ========== الرقاقات (Chips) — تعدّل الحالة فقط بدون إعادة تحميل ========== */
        // تفعيل الرقاقات الموافقة لقيم الـURL الحالية عند التحميل
        Object.keys(pendingFilters).forEach(type => {
            if (pendingFilters[type]) {
                document.querySelector(`[data-filter="${type}"][data-value="${pendingFilters[type]}"]`)?.classList.add('active');
            }
        });

        statCards.forEach(card => {
            card.addEventListener('click', function() {
                const filterType  = this.dataset.filter;
                const filterValue = this.dataset.value;

                if (pendingFilters[filterType] === filterValue) {
                    // إلغاء التحديد
                    pendingFilters[filterType] = null;
                    this.classList.remove('active');
                } else {
                    // إلغاء الرقاقات الأخرى من نفس النوع
                    statCards.forEach(c => {
                        if (c.dataset.filter === filterType) c.classList.remove('active');
                    });
                    pendingFilters[filterType] = filterValue;
                    this.classList.add('active');
                }

                // تحديث الحقل المخفي فقط (لن يُرسل إلا عند ضغط زر البحث)
                if (filterType === 'status')    statusInput.value = pendingFilters.status    || '';
                if (filterType === 'readiness') readyInput.value  = pendingFilters.readiness || '';

                renderBadges();
                showPendingHint();
            });
        });

        /* ========== شارات الفلاتر المعلّقة ========== */
        function renderBadges() {
            if (!badgesBox) return;
            badgesBox.innerHTML = '';
            let has = false;

            Object.keys(pendingFilters).forEach(type => {
                if (!pendingFilters[type]) return;
                has = true;
                const card  = document.querySelector(`[data-filter="${type}"][data-value="${pendingFilters[type]}"]`);
                const label = card ? card.dataset.label : pendingFilters[type];

                const badge = document.createElement('span');
                badge.className = 'filter-badge';
                badge.innerHTML = `<span>${label}</span><span class="close-btn">×</span>`;

                badge.querySelector('.close-btn').addEventListener('click', function(e) {
                    e.stopPropagation();
                    const val = pendingFilters[type];
                    pendingFilters[type] = null;
                    document.querySelector(`[data-filter="${type}"][data-value="${val}"]`)?.classList.remove('active');
                    if (type === 'status')    statusInput.value = '';
                    if (type === 'readiness') readyInput.value  = '';
                    renderBadges();
                    showPendingHint();
                });

                badgesBox.appendChild(badge);
            });

            badgesBox.style.display = has ? 'flex' : 'none';
        }

        /* ========== تلميح "اضغط بحث لتطبيق التغييرات" ========== */
        function showPendingHint() {
            let hint = document.getElementById('pending-hint');
            if (!hint) {
                hint = document.createElement('div');
                hint.id = 'pending-hint';
                hint.className = 'small text-warning mt-1';
                hint.innerHTML = '<i class="bi bi-info-circle me-1"></i> اضغط زر <strong>بحث</strong> لتطبيق التغييرات';
                badgesBox?.parentElement.insertBefore(hint, badgesBox.nextSibling);
            }
            hint.style.display = 'block';
            searchBtn?.classList.add('btn-pulse');
        }

        /* ========== Infinite Scroll — تحميل الصفحات التالية تلقائياً ========== */
        const tbody        = document.getElementById('profiles-table-body');
        const sentinel     = document.getElementById('scroll-sentinel');
        const counterEl    = document.getElementById('scroll-loaded');
        const statusEl     = document.getElementById('scroll-status');

        if (tbody && sentinel) {
            let currentPage = parseInt(tbody.dataset.currentPage || '1', 10);
            const lastPage  = parseInt(tbody.dataset.lastPage    || '1', 10);
            const total     = parseInt(tbody.dataset.total       || '0', 10);
            let   loaded    = tbody.querySelectorAll('.profile-row').length;
            let   isLoading = false;
            let   isDone    = currentPage >= lastPage;

            const setStatus = (html) => { if (statusEl) statusEl.innerHTML = html; };
            const updateCounter = () => { if (counterEl) counterEl.textContent = loaded; };

            if (isDone && total > 0) {
                setStatus('<i class="bi bi-check2-circle text-success me-1"></i> تم تحميل كل النتائج');
            }

            async function loadNextPage() {
                if (isLoading || isDone) return;
                isLoading = true;
                setStatus('<span class="spinner-border spinner-border-sm text-primary me-2"></span> جارٍ تحميل المزيد...');

                try {
                    const url  = new URL(window.location.href);
                    currentPage += 1;
                    url.searchParams.set('page', currentPage);
                    url.searchParams.set('ajax', '1');

                    const res = await fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });

                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();

                    if (data.html) {
                        tbody.insertAdjacentHTML('beforeend', data.html);
                        loaded += (data.count || 0);
                        updateCounter();
                    }

                    if (!data.has_more) {
                        isDone = true;
                        setStatus('<i class="bi bi-check2-circle text-success me-1"></i> تم تحميل كل النتائج');
                        observer.disconnect();
                    } else {
                        setStatus('');
                    }
                } catch (err) {
                    setStatus('<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i> خطأ في التحميل — <a href="#" id="retry-load">إعادة المحاولة</a></span>');
                    currentPage -= 1; // رجّع العدّاد لنحاول من جديد
                    document.getElementById('retry-load')?.addEventListener('click', function (e) {
                        e.preventDefault();
                        loadNextPage();
                    });
                } finally {
                    isLoading = false;
                }
            }

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) loadNextPage();
                });
            }, { rootMargin: '200px' }); // ابدأ التحميل قبل 200px من الوصول

            observer.observe(sentinel);
        }

        /* ========== تحديث زر التصدير ليطابق الفلاتر الحالية (بما فيها المعلّقة) ========== */
        const exportBtn = document.getElementById('export-btn');
        if (exportBtn) {
            exportBtn.addEventListener('click', function (e) {
                const params = new URLSearchParams();
                new FormData(searchForm).forEach((val, key) => {
                    if (val === '' || val === null) return;
                    if (key === 'sort' && val === 'date_desc') return;
                    params.set(key, val);
                });
                const base = this.dataset.baseUrl;
                this.href = params.toString() ? `${base}?${params.toString()}` : base;
                // let the link default behavior proceed
            });
        }

        // تهيئة أولية
        renderBadges();
    });
    </script>
    @endpush
@endsection
