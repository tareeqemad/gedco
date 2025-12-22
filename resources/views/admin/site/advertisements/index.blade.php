@php
    $breadcrumbTitle     = __('admin.advertisements.title');
    $breadcrumbParent    = __('admin.menu.content_management');
    $breadcrumbParentUrl = route('admin.news.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.advertisements.title'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-megaphone"></i>
                                <span class="d-none d-sm-inline">{{ __('admin.advertisements.title') }}</span>
                                <span class="d-sm-none">{{ __('admin.menu.advertisements_jobs') }}</span>
                                <span class="badge bg-white text-primary rounded-pill" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;" id="total-count">{{ $ads->total() }}</span>
                            </h5>
                        </div>
                    </div>
                    @can('advertisements.create')
                        <a href="{{ route('admin.advertisements.create') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            <span class="d-none d-md-inline">{{ __('admin.advertisements.add_advertisement') }}</span>
                            <span class="d-md-none">{{ __('admin.actions.add') }}</span>
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Filters Section -->
            <div class="card-body p-3">
                <form method="get" id="filterForm" class="row g-2 g-md-3">
                    <div class="col-12 col-lg-4 col-md-6">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-search me-1"></i>{{ __('admin.advertisements.search_placeholder') }}
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" name="q" value="{{ $q }}" 
                                   class="form-control border-0 bg-light rounded-3" 
                                   placeholder="{{ __('admin.advertisements.search_placeholder') }}">
                        </div>
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-person me-1"></i>{{ __('admin.advertisements.all_users') }}
                        </label>
                        <select name="user" class="form-select border-0 bg-light rounded-3">
                            <option value="">{{ __('admin.advertisements.all_users') }}</option>
                            @foreach($distinctUsers as $u)
                                <option value="{{ $u }}" @selected($user === $u)>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-calendar-event me-1 d-none d-sm-inline"></i>
                            <span class="d-sm-none">من</span>
                            <span class="d-none d-sm-inline">من تاريخ</span>
                        </label>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" 
                               class="form-control border-0 bg-light rounded-3">
                    </div>

                    <div class="col-6 col-lg-2 col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-calendar-check me-1 d-none d-sm-inline"></i>
                            <span class="d-sm-none">إلى</span>
                            <span class="d-none d-sm-inline">إلى تاريخ</span>
                        </label>
                        <input type="date" name="date_to" value="{{ $dateTo }}" 
                               class="form-control border-0 bg-light rounded-3">
                    </div>

                    <div class="col-6 col-lg-1 col-md-2">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-list-ol me-1"></i>ترتيب
                        </label>
                        <select name="sort" class="form-select border-0 bg-light rounded-3">
                            <option value="DATE_NEWS" @selected($sort==='DATE_NEWS')>{{ __('admin.advertisements.sort_date_news') }}</option>
                            <option value="INSERT_DATE" @selected($sort==='INSERT_DATE')>{{ __('admin.advertisements.sort_insert_date') }}</option>
                            <option value="UPDATE_DATE" @selected($sort==='UPDATE_DATE')>{{ __('admin.advertisements.sort_update_date') }}</option>
                            <option value="ID_ADVER" @selected($sort==='ID_ADVER')>{{ __('admin.advertisements.sort_id') }}</option>
                        </select>
                    </div>

                    <div class="col-6 col-lg-1 col-md-2">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-arrow-down-up me-1"></i>اتجاه
                        </label>
                        <select name="dir" class="form-select border-0 bg-light rounded-3">
                            <option value="desc" @selected($dir==='desc')>{{ __('admin.advertisements.sort_desc') }}</option>
                            <option value="asc" @selected($dir==='asc')>{{ __('admin.advertisements.sort_asc') }}</option>
                        </select>
                    </div>

                    <div class="col-6 col-lg-1 col-md-2">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-list-ol me-1"></i>/صفحة
                        </label>
                        <select name="per_page" class="form-select border-0 bg-light rounded-3">
                            @foreach([10,20,50,100] as $pp)
                                <option value="{{ $pp }}" @selected($perPage==$pp)>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-lg-3 d-flex align-items-end gap-2 filter-buttons-group">
                        <button type="submit" class="btn btn-primary flex-fill shadow-sm rounded-3" style="min-width: 100px;">
                            <i class="bi bi-funnel me-2"></i>{{ __('admin.advertisements.filter') }}
                        </button>
                        @if(request()->query())
                            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline-secondary flex-fill rounded-3" style="min-width: 100px;">
                                <i class="bi bi-arrow-clockwise me-2"></i>{{ __('admin.advertisements.reset') }}
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Column Toggle Tools -->
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <small class="text-muted fw-semibold">إظهار/إخفاء الأعمدة:</small>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input col-toggle" type="checkbox" data-cols="3" checked>
                            <span class="form-check-label small">{{ __('admin.advertisements.col_toggle_date_news') }}</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input col-toggle" type="checkbox" data-cols="4" checked>
                            <span class="form-check-label small">{{ __('admin.advertisements.col_toggle_added_by') }}</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input col-toggle" type="checkbox" data-cols="5" checked>
                            <span class="form-check-label small">{{ __('admin.advertisements.col_toggle_last_update') }}</span>
                        </label>
                        <label class="form-check form-check-inline">
                            <input class="form-check-input col-toggle" type="checkbox" data-cols="6" checked>
                            <span class="form-check-label small">{{ __('admin.advertisements.col_toggle_file') }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <div id="table-container" aria-live="polite">
                        @include('admin.site.advertisements.partials.table')
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-transparent border-top py-3">
                <div id="pagination-container">
                    @include('admin.site.advertisements.partials.pagination')
                </div>
            </div>
        </div>
    </div>
@endsection

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
                    <div class="skel" style="height:42px; margin-bottom:.5rem; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                    <div class="skel" style="height:42px; margin-bottom:.5rem; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                    <div class="skel" style="height:42px; background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: loading 1.5s infinite;"></div>
                </div>`;
        }

        function highlightQuery(rootSelector = '#table-container') {
            const url = new URL(location.href);
            const q = url.searchParams.get('q');
            if (!q) return;
            const esc = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const pattern = new RegExp('(' + esc(q) + ')', 'gi');

            document.querySelectorAll(rootSelector + ' td, ' + rootSelector + ' .line-clamp-2').forEach(el => {
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
            // Pagination
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

            // Toggle columns
            document.addEventListener('change', (e)=>{
                if(!e.target.classList.contains('col-toggle')) return;
                const idx = parseInt(e.target.dataset.cols,10);
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

        // Delete with SweetAlert
        window.confirmDelete = function(form, adId = null) {
            Swal.fire({
                title:'{{ __('admin.advertisements.delete_confirm_title') }}',
                text:'{{ __('admin.advertisements.delete_confirm_text') }}',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'{{ __('admin.advertisements.delete_confirm_yes') }}',
                cancelButtonText:'{{ __('admin.actions.cancel') }}',
                reverseButtons:true,
                customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary ms-2' },
                buttonsStyling:false
            }).then((result)=>{
                if(!result.isConfirmed) return;

                const btn = form.querySelector('button[type="submit"]');
                if (btn) { btn.disabled = true; btn.setAttribute('data-original-text', btn.innerHTML); btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('admin.advertisements.delete_processing') }}'; }

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
                            if (window.adminNotifications) {
                                window.adminNotifications.success(data.message || '{{ __('admin.advertisements.delete_success_message') }}');
                            } else {
                                Swal.fire('{{ __('admin.advertisements.delete_success') }}', data.message || '{{ __('admin.advertisements.delete_success_message') }}', 'success');
                            }
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
                            if (window.adminNotifications) {
                                window.adminNotifications.error(data?.message || '{{ __('admin.advertisements.delete_warning_message') }}');
                            } else {
                                Swal.fire('{{ __('admin.advertisements.delete_warning') }}', data?.message || '{{ __('admin.advertisements.delete_warning_message') }}', 'warning');
                            }
                            if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                            if (rowEl) rowEl.classList.remove('soft-dim');
                        }
                    })
                    .catch(()=>{
                        if (window.adminNotifications) {
                            window.adminNotifications.error('{{ __('admin.advertisements.delete_error_message') }}');
                        } else {
                            Swal.fire('{{ __('admin.advertisements.delete_error') }}', '{{ __('admin.advertisements.delete_error_message') }}', 'error');
                        }
                        if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                        if (rowEl) rowEl.classList.remove('soft-dim');
                        const current = location.pathname + location.search;
                        fetchData(current, false);
                    });
            });

            return false;
        };
    </script>
    <style>
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        .soft-dim { opacity: 0.6; }
        .fade-out { animation: fadeOut 0.25s ease-out forwards; }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(-10px); }
        }
    </style>
@endpush
