@extends('layouts.admin')
@section('title', 'الإعلانات والوظائف')

@section('content')
    @php
        $breadcrumbTitle     = 'الإعلانات والوظائف';
        $breadcrumbParent    = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.advertisements.index');
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 d-flex flex-wrap justify-content-between align-items-center gap-3 py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                    <i class="ri-megaphone-line fs-18"></i>
                </div>
                <h5 class="mb-0 fw-semibold">الإعلانات والوظائف</h5>
                <span class="badge bg-primary fs-11" id="total-count">{{ $ads->total() }}</span>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.advertisements.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                    <i class="ri-add-line"></i> إضافة إعلان
                </a>

                <form method="get" class="d-flex flex-wrap gap-2 align-items-center" id="filterForm">
                    <div class="position-relative">
                        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm ps-4" placeholder="بحث..." style="width: 180px;">
                        <i class="ri-search-line position-absolute top-50 start-2 translate-middle-y text-muted fs-14"></i>
                    </div>

                    <select name="user" class="form-select form-select-sm" style="width: 140px;">
                        <option value="">كل المستخدمين</option>
                        @foreach($distinctUsers as $u)
                            <option value="{{ $u }}" @selected($user === $u)>{{ $u }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm" style="width: 135px;">
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm" style="width: 135px;">

                    <select name="sort" class="form-select form-select-sm" style="width: 140px;">
                        <option value="DATE_NEWS" @selected($sort==='DATE_NEWS')>تاريخ الخبر</option>
                        <option value="INSERT_DATE" @selected($sort==='INSERT_DATE')>تاريخ الإدخال</option>
                        <option value="UPDATE_DATE" @selected($sort==='UPDATE_DATE')>تاريخ التحديث</option>
                        <option value="ID_ADVER" @selected($sort==='ID_ADVER')>رقم الإعلان</option>
                    </select>

                    <select name="dir" class="form-select form-select-sm" style="width: 100px;">
                        <option value="desc" @selected($dir==='desc')>تنازلي</option>
                        <option value="asc" @selected($dir==='asc')>تصاعدي</option>
                    </select>

                    <select name="per_page" class="form-select form-select-sm" style="width: 80px;">
                        @foreach([10,20,50,100] as $pp)
                            <option value="{{ $pp }}" @selected($perPage==$pp)>{{ $pp }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="ri-filter-3-line"></i> تصفية
                    </button>

                    @if(request()->query())
                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <i class="ri-refresh-line"></i> إعادة
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- أدوات إظهار/إخفاء أعمدة (اختياري) -->
        <div class="px-3 pt-2">
            <div class="d-flex flex-wrap gap-2">
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="3" checked>
                    <span class="form-check-label">تاريخ الخبر</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="4" checked>
                    <span class="form-check-label">أضيف بواسطة</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="5" checked>
                    <span class="form-check-label">آخر تحديث</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="6" checked>
                    <span class="form-check-label">ملف</span>
                </label>
            </div>
        </div>

        <!-- الجدول -->
        <div class="table-responsive">
            <div id="table-container" aria-live="polite">
                @include('admin.site.advertisements.partials.table')
            </div>
        </div>

        <!-- Pagination -->
        <div class="card-footer bg-white border-top-0 py-3">
            <div id="pagination-container">
                @include('admin.site.advertisements.partials.pagination')
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* جدول يتحول إلى بطاقات على الموبايل */
        .responsive-table { width:100%; }
        @media (max-width: 768px) {
            .responsive-table thead { display:none; }
            .responsive-table tbody tr {
                display:block; margin-bottom:.75rem; border:1px solid #eee; border-radius:.75rem; padding:.5rem .75rem;
            }
            .responsive-table tbody td {
                display:flex; justify-content:space-between; gap:1rem; padding:.35rem 0;
                border-bottom:1px dashed #f1f1f1;
            }
            .responsive-table tbody td:last-child { border-bottom:none; }
            .responsive-table [data-label]::before {
                content: attr(data-label);
                font-weight:600; color:#6c757d;
            }
        }

        /* Sticky header */
        .table-sticky thead th {
            position: sticky; top: 0; z-index: 2;
            background: var(--bs-table-bg, #fff);
            box-shadow: 0 2px 0 rgba(0,0,0,.03);
        }

        /* 2 سطور كحد أقصى للعناوين */
        .line-clamp-2 {
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Skeleton تحميل */
        .skel { position: relative; overflow: hidden; background:#f6f7f8; border-radius:.5rem; }
        .skel::after {
            content:""; position:absolute; inset:0;
            background: linear-gradient(90deg, transparent, rgba(0,0,0,.04), transparent);
            animation: skel 1.2s infinite;
        }
        @keyframes skel { 0% { transform: translateX(-100%);} 100%{ transform: translateX(100%);} }

        /* تأثيرات حذف */
        .soft-dim { opacity:.6; transition: opacity .2s ease; }
        .fade-out {
            opacity:0; height:0!important; padding-top:0!important; padding-bottom:0!important; margin:0!important;
            transition: opacity .25s ease, height .25s ease, padding .25s ease, margin .25s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let activeController = null;
        let debounceTimer;

        const tableContainer = document.getElementById('table-container');
        const paginationContainer = document.getElementById('pagination-container');
        const totalCount = document.getElementById('total-count');
        const filterForm = document.getElementById('filterForm');

        function showLoading() {
            tableContainer.innerHTML = `
    <div class="p-3">
      <div class="skel" style="height:42px; margin-bottom:.5rem;"></div>
      <div class="skel" style="height:42px; margin-bottom:.5rem;"></div>
      <div class="skel" style="height:42px;"></div>
    </div>`;
        }

        function highlightQuery(rootSelector = '#table-container') {
            const url = new URL(location.href);
            const q = url.searchParams.get('q');
            if (!q) return;
            const esc = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const pattern = new RegExp('(' + esc(q) + ')', 'gi');

            document.querySelectorAll(rootSelector + ' td, ' + rootSelector + ' .line-clamp-2').forEach(el => {
                // تجنّب العناصر اللي داخلها أزرار/أيقونات فقط
                if (el.querySelector('button, a.btn, i, svg')) return;
                const txt = el.innerHTML;
                const newHtml = txt.replace(pattern, '<mark>$1</mark>');
                if (newHtml !== txt) el.innerHTML = newHtml;
            });
        }

        function updateTable(data, newUrl = null) {
            tableContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            totalCount.textContent = data.total;
            if (newUrl) history.pushState(null, '', newUrl);
            // إبراز كلمات البحث
            highlightQuery('#table-container');
        }

        function fetchData(relativeUrl, pushUrl = false){
            if (activeController) activeController.abort();
            activeController = new AbortController();

            fetch(relativeUrl, {
                method:'GET',
                signal: activeController.signal,
                headers:{ 'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}
            })
                .then(r=>{ if(!r.ok) throw new Error(`HTTP ${r.status}`); return r.json(); })
                .then(d=>updateTable(d, pushUrl?relativeUrl:null))
                .catch(err=>{ if(err.name==='AbortError') return; console.error('AJAX Error:', err); });
        }

        document.addEventListener('DOMContentLoaded', ()=>{
            // Pagination relative + تعقيم الرابط
            document.body.addEventListener('click', e=>{
                const link = e.target.closest('.pagination-link'); if(!link) return;
                e.preventDefault();
                const raw = link.getAttribute('data-url') || link.getAttribute('href') || '';
                if (!raw) return;
                const u = new URL(raw, location.origin);
                const rel = u.pathname + u.search;
                showLoading();
                fetchData(rel, true);
            });

            // Filters
            if (filterForm) {
                filterForm.querySelectorAll('input, select').forEach(i=>{
                    i.addEventListener('change', submitFilters);
                    if(i.name==='q'){
                        i.addEventListener('input', ()=>{
                            clearTimeout(debounceTimer);
                            debounceTimer = setTimeout(submitFilters, 500);
                        });
                    }
                });
            }

            // Toggle أعمدة
            document.addEventListener('change', (e)=>{
                if(!e.target.classList.contains('col-toggle')) return;
                const idx = parseInt(e.target.dataset.cols,10); // 1-based
                document.querySelectorAll('.responsive-table tr').forEach(tr=>{
                    const cell = tr.querySelector(`:scope > *:nth-child(${idx})`);
                    if(cell){ cell.style.display = e.target.checked ? '' : 'none'; }
                });
            });
        });

        function submitFilters(){
            const params = new URLSearchParams(new FormData(filterForm));
            const url = location.pathname + '?' + params.toString();
            showLoading();
            fetchData(url, true);
        }

        // حذف مع قفل زر + fade + refresh
        window.confirmDelete = function(form, adId = null) {
            Swal.fire({
                title:'هل أنت متأكد؟',
                text:'لن تتمكن من استرجاع هذا الإعلان!',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'نعم، احذف!',
                cancelButtonText:'إلغاء',
                reverseButtons:true,
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary ms-2' },
                buttonsStyling:false
            }).then((result)=>{
                if(!result.isConfirmed) return;

                const btn = form.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = true; btn.setAttribute('data-original-text', btn.innerHTML); btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارِ الحذف'; }

                let rowEl = null;
                if (adId !== null) {
                    rowEl = document.getElementById('ad-row-' + adId);
                    if (rowEl) rowEl.classList.add('soft-dim');
                }

                const formData = new FormData(form);
                fetch(new URL(form.action, location.origin), {
                    method:'DELETE',
                    headers:{
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json'
                    },
                    body: formData
                })
                    .then(r=>r.json())
                    .then(data=>{
                        if (data?.success) {
                            Swal.fire('تم!', data.message || 'تم الحذف', 'success');
                            if (rowEl) {
                                rowEl.classList.add('fade-out');
                                setTimeout(()=>{
                                    rowEl.remove();
                                    const current = location.pathname + location.search;
                                    fetchData(current, false);
                                }, 250);
                            } else {
                                const current = location.pathname + location.search;
                                fetchData(current, false);
                            }
                        } else {
                            Swal.fire('تنبيه', data?.message || 'لم يتم الحذف', 'warning');
                            if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || 'حذف'; btn.removeAttribute('data-original-text'); }
                            if (rowEl) rowEl.classList.remove('soft-dim');
                        }
                    })
                    .catch(()=>{
                        Swal.fire('خطأ', 'فشل في الحذف', 'error');
                        if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || 'حذف'; btn.removeAttribute('data-original-text'); }
                        if (rowEl) rowEl.classList.remove('soft-dim');
                        const current = location.pathname + location.search;
                        fetchData(current, false);
                    });
            });

            return false;
        };
    </script>
@endpush
