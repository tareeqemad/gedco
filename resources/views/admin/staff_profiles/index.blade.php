@php
    $breadcrumbTitle     = __('admin.staff_profiles.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
    
    $locations = ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة'];
    $statusMap = ['resident'=>['label'=>'مقيم','class'=>'bg-success-subtle text-success'], 'displaced'=>['label'=>'نازح','class'=>'bg-danger-subtle text-danger']];
    $readinessMap = ['working'=>['label'=>'باشر العمل','class'=>'bg-success text-white'], 'ready'=>['label'=>'جاهز للعودة','class'=>'bg-primary text-white'], 'not_ready'=>['label'=>'غير جاهز','class'=>'bg-warning text-dark']];
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
                    <span class="badge bg-white text-primary rounded-pill" style="font-size: 0.65rem; padding: 0.15rem 0.4rem;">
                        {{ $stats['total'] }}
                    </span>
                    <span class="text-white-50 small d-none d-md-inline" style="font-size: 0.75rem; opacity: 0.9;">
                        {{ __('admin.staff_profiles.subtitle') }}
                    </span>
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
                <form method="GET" class="d-flex gap-2 align-items-center">
                    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                    @if(request('readiness'))<input type="hidden" name="readiness" value="{{ request('readiness') }}">@endif
                    <div style="flex: 1;">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-3"
                               placeholder="{{ __('admin.staff_profiles.search_placeholder') }}">
                    </div>
                    <button type="submit" class="btn btn-primary rounded-3">
                        <i class="bi bi-search me-1"></i> {{ __('admin.staff_profiles.search') }}
                    </button>
                    @if(request()->hasAny(['q', 'status', 'readiness']))
                        <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-outline-secondary rounded-3">
                            <i class="bi bi-x-circle"></i>
                        </a>
                    @endif
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
                        <tbody class="text-dark" id="profiles-table-body">
                        @forelse($profiles as $profile)
                            <tr class="align-middle profile-row" 
                                data-status="{{ $profile->status }}" 
                                data-readiness="{{ $profile->readiness }}">
                                <td class="ps-4 text-muted small">
                                    {{ $loop->iteration + ($profiles->currentPage()-1)*$profiles->perPage() }}
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $profile->full_name }}</div>
                                    <small class="text-muted">#{{ $profile->employee_number ?: '—' }}</small>
                                </td>
                                <td class="text-primary fw-semibold">{{ $profile->national_id }}</td>
                                <td class="d-none d-md-table-cell">
                                    <span class="badge bg-light text-dark small px-3 rounded-pill">
                                        {{ $locations[$profile->location] ?? '—' }}
                                    </span>
                                </td>
                                <td>
                                    @php $s = $statusMap[$profile->status] ?? null @endphp
                                    @if($s)<span class="badge {{ $s['class'] }} small rounded-pill px-3">{{ $s['label'] }}</span>@endif
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    @php $r = $readinessMap[$profile->readiness] ?? null @endphp
                                    @if($r)<span class="badge {{ $r['class'] }} small rounded-pill px-3">{{ $r['label'] }}</span>@endif
                                </td>
                                <td class="small text-muted d-none d-xl-table-cell">
                                    {{ $profile->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.staff-profiles.show', $profile) }}"
                                       class="btn btn-primary btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-1" style="min-width: 80px;">
                                        <i class="bi bi-eye-fill"></i><span class="d-none d-md-inline">{{ __('admin.staff_profiles.table_view') }}</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder2-open display-5 opacity-25 d-block mb-3"></i>
                                    {{ __('admin.staff_profiles.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            @if($profiles->hasPages())
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            {{ __('admin.staff_profiles.showing') }} {{ $profiles->firstItem() }} - {{ $profiles->lastItem() }} {{ __('admin.staff_profiles.of') }} {{ $profiles->total() }}
                        </div>
                        {{ $profiles->withQueryString()->links() }}
                    </div>
                </div>
            @endif
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
        </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.staff-filter-chip');
        const activeFiltersContainer = document.getElementById('active-filters');
        
        // قراءة الفلاتر من URL
        const urlParams = new URLSearchParams(window.location.search);
        let activeFilters = {
            status: urlParams.get('status') || null,
            readiness: urlParams.get('readiness') || null
        };

        // تطبيق الفلاتر النشطة من URL على البطاقات
        Object.keys(activeFilters).forEach(type => {
            if (activeFilters[type]) {
                const card = document.querySelector(`[data-filter="${type}"][data-value="${activeFilters[type]}"]`);
                if (card) {
                    card.classList.add('active');
                }
            }
        });

        // إضافة حدث النقر على الإحصائيات - فلترة من جانب الخادم
        statCards.forEach(card => {
            card.addEventListener('click', function() {
                const filterType = this.dataset.filter;
                const filterValue = this.dataset.value;

                // تبديل الحالة النشطة
                if (activeFilters[filterType] === filterValue) {
                    // إلغاء التفعيل
                    activeFilters[filterType] = null;
                    this.classList.remove('active');
                } else {
                    // إلغاء تفعيل البطاقات الأخرى من نفس النوع
                    statCards.forEach(c => {
                        if (c.dataset.filter === filterType) {
                            c.classList.remove('active');
                        }
                    });
                    // تفعيل البطاقة الحالية
                    activeFilters[filterType] = filterValue;
                    this.classList.add('active');
                }

                // إعادة تحميل الصفحة مع الفلاتر (server-side filtering)
                applyFilters();
            });
        });

        // تطبيق الفلاتر وإعادة تحميل الصفحة
        function applyFilters() {
            const url = new URL(window.location.href);
            
            // إزالة الفلاتر القديمة
            url.searchParams.delete('status');
            url.searchParams.delete('readiness');
            url.searchParams.delete('page'); // إعادة تعيين الصفحة عند تغيير الفلاتر
            
            // إضافة الفلاتر النشطة
            if (activeFilters.status) {
                url.searchParams.set('status', activeFilters.status);
            }
            if (activeFilters.readiness) {
                url.searchParams.set('readiness', activeFilters.readiness);
            }
            
            // إعادة تحميل الصفحة
            window.location.href = url.toString();
        }

        // تحديث عرض الفلاتر النشطة
        function updateFilters() {
            activeFiltersContainer.innerHTML = '';
            let hasFilters = false;

            Object.keys(activeFilters).forEach(type => {
                if (activeFilters[type]) {
                    hasFilters = true;
                    const card = document.querySelector(`[data-filter="${type}"][data-value="${activeFilters[type]}"]`);
                    const label = card ? card.dataset.label : activeFilters[type];
                    
                    const badge = document.createElement('span');
                    badge.className = 'filter-badge';
                    badge.innerHTML = `
                        <span>${label}</span>
                        <span class="close-btn" data-type="${type}">×</span>
                    `;
                    
                    badge.querySelector('.close-btn').addEventListener('click', function(e) {
                        e.stopPropagation();
                        const filterValue = activeFilters[type];
                        activeFilters[type] = null;
                        document.querySelector(`[data-filter="${type}"][data-value="${filterValue}"]`)?.classList.remove('active');
                        applyFilters();
                    });
                    
                    activeFiltersContainer.appendChild(badge);
                }
            });

            if (!hasFilters) {
                activeFiltersContainer.style.display = 'none';
            } else {
                activeFiltersContainer.style.display = 'flex';
            }
        }

        // دالة لإلغاء جميع الفلاتر
        window.clearAllFilters = function() {
            activeFilters = { status: null, readiness: null };
            statCards.forEach(c => c.classList.remove('active'));
            const url = new URL(window.location.href);
            url.searchParams.delete('status');
            url.searchParams.delete('readiness');
            url.searchParams.delete('page');
            window.location.href = url.toString();
        };

        // تهيئة أولية
        updateFilters();
    });
    </script>
    @endpush
@endsection
