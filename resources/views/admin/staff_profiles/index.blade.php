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

            {{-- الإحصائيات التفاعلية --}}
            <div class="card-body p-3 pb-0">
                {{-- الفلاتر النشطة --}}
                <div id="active-filters" class="mb-3" style="min-height: 40px;"></div>

                <div class="row g-3 g-md-4 mb-3">
                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="stat-card bg-white rounded-4 shadow-sm p-3 text-center border border-3 border-danger cursor-pointer"
                             data-filter="status" data-value="displaced" data-label="{{ __('admin.staff_profiles.status_displaced') }}"
                             style="border-color: #dc2626 !important; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(220, 38, 38, 0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-truck fs-4 text-danger mb-1"></i>
                            <div class="fs-4 fw-bold text-danger">{{ $stats['displaced'] }}</div>
                            <small class="text-dark fw-semibold">{{ __('admin.staff_profiles.status_displaced') }}</small>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="stat-card bg-white rounded-4 shadow-sm p-3 text-center border border-3 border-success cursor-pointer"
                             data-filter="status" data-value="resident" data-label="{{ __('admin.staff_profiles.status_resident') }}"
                             style="border-color: #22c55e !important; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(34, 197, 94, 0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-house-door-fill fs-4 text-success mb-1"></i>
                            <div class="fs-4 fw-bold text-success">{{ $stats['resident'] }}</div>
                            <small class="text-dark fw-semibold">{{ __('admin.staff_profiles.status_resident') }}</small>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="stat-card bg-white rounded-4 shadow-sm p-3 text-center border border-3 border-warning cursor-pointer"
                             data-filter="readiness" data-value="not_ready" data-label="{{ __('admin.staff_profiles.readiness_not_ready') }}"
                             style="border-color: #f59e0b !important; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(245, 158, 11, 0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-person-x fs-4 text-warning mb-1"></i>
                            <div class="fs-4 fw-bold text-warning">{{ $stats['not_ready'] }}</div>
                            <small class="text-dark fw-semibold">{{ __('admin.staff_profiles.readiness_not_ready') }}</small>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="stat-card bg-white rounded-4 shadow-sm p-3 text-center border border-3 border-info cursor-pointer"
                             data-filter="readiness" data-value="ready" data-label="{{ __('admin.staff_profiles.readiness_ready') }}"
                             style="border-color: #3b82f6 !important; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(59, 130, 246, 0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-hand-thumbs-up-fill fs-4 text-info mb-1"></i>
                            <div class="fs-4 fw-bold text-info">{{ $stats['ready'] }}</div>
                            <small class="text-dark fw-semibold">{{ __('admin.staff_profiles.readiness_ready') }}</small>
                        </div>
                    </div>

                    <div class="col-6 col-md-3 col-lg-2">
                        <div class="stat-card bg-white rounded-4 shadow-sm p-3 text-center border border-3 border-success cursor-pointer"
                             data-filter="readiness" data-value="working" data-label="{{ __('admin.staff_profiles.readiness_working') }}"
                             style="border-color: #22c55e !important; transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 8px 20px rgba(34, 197, 94, 0.2)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="bi bi-person-check-fill fs-4 text-success mb-1"></i>
                            <div class="fs-4 fw-bold text-success">{{ $stats['working'] }}</div>
                            <small class="text-dark fw-semibold">{{ __('admin.staff_profiles.readiness_working') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- شريط البحث --}}
            <div class="card-body p-3" style="border-top: 1px solid #E6ECF2;">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1 mb-1">
                            <i class="bi bi-search text-primary"></i>{{ __('admin.staff_profiles.search_in_employees') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 rounded-start-3"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control rounded-end-3 border-0 bg-light" style="height: 45px;" placeholder="{{ __('admin.staff_profiles.search_placeholder') }}">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="height: 45px;">
                            <i class="bi bi-search"></i><span class="d-none d-md-inline">{{ __('admin.staff_profiles.search') }}</span>
                        </button>
                    </div>
                    @if(request()->filled('q'))
                        <div class="col-lg-2">
                            <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-outline-secondary w-100 rounded-3 d-flex align-items-center justify-content-center gap-2" style="height: 45px;">
                                <i class="bi bi-x-circle"></i><span class="d-none d-md-inline">{{ __('admin.staff_profiles.clear') }}</span>
                            </a>
                        </div>
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
                                    <i class="bi bi-inbox display-5 opacity-25 d-block mb-3"></i>
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
            .cursor-pointer {
                cursor: pointer;
            }
            
            .stat-card.active {
                transform: translateY(-4px) !important;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
            }
            
            #active-filters {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .filter-badge {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                padding: 0.5rem 1rem;
                background: #e3f2fd;
                color: #1976d2;
                border-radius: 1.5rem;
                font-size: 0.875rem;
                font-weight: 500;
            }
            
            .filter-badge .close-btn {
                cursor: pointer;
                font-size: 1.25rem;
                line-height: 1;
                opacity: 0.7;
                transition: opacity 0.2s;
            }
            
            .filter-badge .close-btn:hover {
                opacity: 1;
            }
            
            @media (max-width: 768px) {
                .table thead {
                    display: none;
                }
                
                .table tbody tr {
                    display: block;
                    margin-bottom: 1rem;
                    border: 1px solid #dee2e6;
                    border-radius: 0.5rem;
                    padding: 1rem;
                }
                
                .table tbody td {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0.5rem;
                    border: none;
                }
                
                .table tbody td::before {
                    content: attr(data-label);
                    font-weight: 600;
                    color: #6c757d;
                    margin-inline-end: 1rem;
                }
            }
        </style>
    @endpush

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const statCards = document.querySelectorAll('.stat-card');
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
