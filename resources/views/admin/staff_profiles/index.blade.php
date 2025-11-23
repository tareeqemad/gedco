
{{-- resources/views/admin/staff_profiles/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'استمارات بيانات الموظفين')

@push('styles')
<style>
    .stat-card {
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        border: 2px solid transparent !important;
        background: white !important;
    }

    .stat-card.bg-gradient {
        background: linear-gradient(135deg, #e67e22, #d35400) !important;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.5s;
    }

    .stat-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
        border-color: currentColor !important;
        background: white !important;
    }

    .stat-card.bg-gradient:hover {
        background: linear-gradient(135deg, #f97316, #ea580c) !important;
        border-color: rgba(255,255,255,0.5) !important;
    }

    .stat-card:hover::before {
        left: 100%;
    }

    .stat-card.active {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 30px rgba(0,0,0,0.2) !important;
        border-width: 3px !important;
        animation: pulse 2s infinite;
        background: white !important;
    }

    .stat-card.bg-gradient.active {
        background: linear-gradient(135deg, #f97316, #ea580c) !important;
        border-color: rgba(255,255,255,0.6) !important;
    }

    .stat-card.active::after {
        content: '✓';
        position: absolute;
        top: 8px;
        left: 8px;
        background: rgba(0,0,0,0.7);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }

    @keyframes pulse {
        0%, 100% { box-shadow: 0 12px 30px rgba(0,0,0,0.2); }
        50% { box-shadow: 0 12px 35px rgba(0,0,0,0.3); }
    }

    .stat-card i {
        transition: transform 0.3s;
    }

    .stat-card:hover i,
    .stat-card.active i {
        transform: scale(1.2) rotate(5deg);
    }

    .table-row-hidden {
        display: none !important;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #e67e22;
        color: white;
        border-radius: 20px;
        font-size: 0.85rem;
        margin: 4px;
    }

    .filter-badge .close-btn {
        cursor: pointer;
        background: rgba(255,255,255,0.3);
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        transition: background 0.2s;
    }

    .filter-badge .close-btn:hover {
        background: rgba(255,255,255,0.5);
    }
</style>
@endpush

@section('content')
    <div class="container-fluid py-4">

        @php
            $locations = ['1'=>'المقر الرئيسي','2'=>'مقر غزة','3'=>'مقر الشمال','4'=>'مقر الوسطى','6'=>'مقر خانيونس','7'=>'مقر رفح','8'=>'مقر الصيانة - غزة'];
            $statusMap = ['resident'=>['label'=>'مقيم','class'=>'bg-success-subtle text-success'], 'displaced'=>['label'=>'نازح','class'=>'bg-danger-subtle text-danger']];
            $readinessMap = ['working'=>['label'=>'باشر العمل','class'=>'bg-success text-white'], 'ready'=>['label'=>'جاهز للعودة','class'=>'bg-primary text-white'], 'not_ready'=>['label'=>'غير جاهز','class'=>'bg-warning text-dark']];
        @endphp

        {{-- الهيدر المصغّر والأنيق --}}
        <div class="rounded-3 shadow-sm mb-4 overflow-hidden" style="background: linear-gradient(135deg, #e67e22, #d35400);">
            <div class="p-3 text-white">
                <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2">
                    <div>
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2 text-white">
                            <i class="bi bi-people-fill"></i>
                            استمارات بيانات الموظفين
                        </h5>
                        <p class="mb-0 opacity-90 small mt-1">متابعة جاهزية الموظفين للعودة بعد الأزمة</p>
                    </div>
                    <div class="rounded-2 px-3 py-1 text-center border border-white border-opacity-40" style="background: rgba(0,0,0,0.25); backdrop-filter: blur(10px);">
                        <div class="fs-5 fw-bold mb-0 text-white">{{ $stats['total'] }}</div>
                        <small class="text-white fw-semibold" style="font-size: 0.75rem; opacity: 1;">إجمالي المسجلين</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- الفلاتر النشطة --}}
        <div id="active-filters" class="mb-3" style="min-height: 40px;"></div>

        {{-- الإحصائيات التفاعلية --}}
        <div class="row g-3 g-md-4 mb-4">
            <div class="col-6 col-md-3 col-lg-2">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center border border-3 border-danger" 
                     data-filter="status" data-value="displaced" data-label="نازحون"
                     style="border-color: #dc2626 !important;">
                    <i class="bi bi-truck fs-4 text-danger mb-1"></i>
                    <div class="fs-4 fw-bold text-danger">{{ $stats['displaced'] }}</div>
                    <small class="text-dark fw-semibold">نازحون</small>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center border border-3 border-success" 
                     data-filter="status" data-value="resident" data-label="مقيمون"
                     style="border-color: #22c55e !important;">
                    <i class="bi bi-house-door-fill fs-4 text-success mb-1"></i>
                    <div class="fs-4 fw-bold text-success">{{ $stats['resident'] }}</div>
                    <small class="text-dark fw-semibold">مقيمون</small>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center border border-3 border-warning" 
                     data-filter="readiness" data-value="not_ready" data-label="غير جاهزين"
                     style="border-color: #f59e0b !important;">
                    <i class="bi bi-person-x fs-4 text-warning mb-1"></i>
                    <div class="fs-4 fw-bold text-warning">{{ $stats['not_ready'] }}</div>
                    <small class="text-dark fw-semibold">غير جاهزين</small>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center border border-3 border-info" 
                     data-filter="readiness" data-value="ready" data-label="جاهزون"
                     style="border-color: #3b82f6 !important;">
                    <i class="bi bi-hand-thumbs-up-fill fs-4 text-info mb-1"></i>
                    <div class="fs-4 fw-bold text-info">{{ $stats['ready'] }}</div>
                    <small class="text-dark fw-semibold">جاهزون</small>
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <div class="stat-card bg-white rounded-3 shadow-sm p-3 text-center border border-3 border-success" 
                     data-filter="readiness" data-value="working" data-label="باشروا العمل"
                     style="border-color: #22c55e !important;">
                    <i class="bi bi-person-check-fill fs-4 text-success mb-1"></i>
                    <div class="fs-4 fw-bold text-success">{{ $stats['working'] }}</div>
                    <small class="text-dark fw-semibold">باشروا العمل</small>
                </div>
            </div>
        </div>

        {{-- شريط البحث المدمج والصغير --}}
        <div class="card rounded-4 shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold small mb-1">بحث في الموظفين</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="الاسم • الهوية • الرقم الوظيفي">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-3">بحث</button>
                    </div>
                    @if(request()->filled('q'))
                        <div class="col-lg-2">
                            <a href="{{ route('admin.staff-profiles.index') }}" class="btn btn-outline-secondary w-100 rounded-3">مسح</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        {{-- الجدول الأنيق والمضغوط --}}
        <div class="card rounded-4 shadow-sm border-0 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                        <tr class="text-secondary small fw-bold">
                            <th class="ps-4">#</th>
                            <th>الموظف</th>
                            <th>الهوية</th>
                            <th>المقر</th>
                            <th>الحالة</th>
                            <th>الجاهزية</th>
                            <th>التسجيل</th>
                            <th class="text-center">عرض</th>
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
                                <td>
                                <span class="badge bg-light text-dark small px-3">
                                    {{ $locations[$profile->location] ?? '—' }}
                                </span>
                                </td>
                                <td>
                                    @php $s = $statusMap[$profile->status] ?? null @endphp
                                    @if($s)<span class="badge {{ $s['class'] }} small">{{ $s['label'] }}</span>@endif
                                </td>
                                <td>
                                    @php $r = $readinessMap[$profile->readiness] ?? null @endphp
                                    @if($r)<span class="badge {{ $r['class'] }} small">{{ $r['label'] }}</span>@endif
                                </td>
                                <td class="small text-muted">
                                    {{ $profile->created_at->format('d/m/Y') }}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.staff-profiles.show', $profile) }}"
                                       class="btn btn-primary btn-sm rounded-circle" style="width:36px;height:36px;">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox display-5 opacity-25 d-block mb-3"></i>
                                    لا توجد بيانات
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($profiles->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            عرض {{ $profiles->firstItem() }} - {{ $profiles->lastItem() }} من {{ $profiles->total() }}
                        </div>
                        {{ $profiles->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

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
            activeFiltersContainer.style.display = 'block';
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
