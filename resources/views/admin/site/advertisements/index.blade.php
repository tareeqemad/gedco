@extends('layouts.admin')
@section('title', __('admin.advertisements.title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.advertisements.title');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.advertisements.index');
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 d-flex flex-wrap justify-content-between align-items-center gap-3 py-3">
            <div class="d-flex align-items-center gap-2">
                <div class="avatar avatar-sm bg-primary text-white rounded-circle">
                    <i class="bi bi-megaphone fs-18"></i>
                </div>
                <h5 class="mb-0 fw-semibold">{{ __('admin.advertisements.title') }}</h5>
                <span class="badge bg-primary fs-11" id="total-count">{{ $ads->total() }}</span>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.advertisements.create') }}" class="btn btn-success btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-plus-circle"></i> {{ __('admin.advertisements.add_advertisement') }}
                </a>

                <form method="get" class="d-flex flex-wrap gap-2 align-items-center" id="filterForm">
                    <div class="position-relative">
                        <input type="text" name="q" value="{{ $q }}" class="form-control form-control-sm ps-4" placeholder="{{ __('admin.advertisements.search_placeholder') }}" style="width: 180px;">
                        <i class="bi bi-search position-absolute top-50 start-2 translate-middle-y text-muted fs-14"></i>
                    </div>

                    <select name="user" class="form-select form-select-sm" style="width: 140px;">
                        <option value="">{{ __('admin.advertisements.all_users') }}</option>
                        @foreach($distinctUsers as $u)
                            <option value="{{ $u }}" @selected($user === $u)>{{ $u }}</option>
                        @endforeach
                    </select>

                    <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control form-control-sm" style="width: 135px;">
                    <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control form-control-sm" style="width: 135px;">

                    <select name="sort" class="form-select form-select-sm" style="width: 140px;">
                        <option value="DATE_NEWS" @selected($sort==='DATE_NEWS')>{{ __('admin.advertisements.sort_date_news') }}</option>
                        <option value="INSERT_DATE" @selected($sort==='INSERT_DATE')>{{ __('admin.advertisements.sort_insert_date') }}</option>
                        <option value="UPDATE_DATE" @selected($sort==='UPDATE_DATE')>{{ __('admin.advertisements.sort_update_date') }}</option>
                        <option value="ID_ADVER" @selected($sort==='ID_ADVER')>{{ __('admin.advertisements.sort_id') }}</option>
                    </select>

                    <select name="dir" class="form-select form-select-sm" style="width: 100px;">
                        <option value="desc" @selected($dir==='desc')>{{ __('admin.advertisements.sort_desc') }}</option>
                        <option value="asc" @selected($dir==='asc')>{{ __('admin.advertisements.sort_asc') }}</option>
                    </select>

                    <select name="per_page" class="form-select form-select-sm" style="width: 80px;">
                        @foreach([10,20,50,100] as $pp)
                            <option value="{{ $pp }}" @selected($perPage==$pp)>{{ $pp }}</option>
                        @endforeach
                    </select>

                    <button type="submit" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-funnel"></i> {{ __('admin.advertisements.filter') }}
                    </button>

                    @if(request()->query())
                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                            <i class="bi bi-arrow-clockwise"></i> {{ __('admin.advertisements.reset') }}
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- Column Toggle Tools -->
        <div class="px-3 pt-2">
            <div class="d-flex flex-wrap gap-2">
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="3" checked>
                    <span class="form-check-label">{{ __('admin.advertisements.col_toggle_date_news') }}</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="4" checked>
                    <span class="form-check-label">{{ __('admin.advertisements.col_toggle_added_by') }}</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="5" checked>
                    <span class="form-check-label">{{ __('admin.advertisements.col_toggle_last_update') }}</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input col-toggle" type="checkbox" data-cols="6" checked>
                    <span class="form-check-label">{{ __('admin.advertisements.col_toggle_file') }}</span>
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

        // Delete with button lock + fade + refresh
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
                            Swal.fire('{{ __('admin.advertisements.delete_success') }}', data.message || '{{ __('admin.advertisements.delete_success_message') }}', 'success');
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
                            Swal.fire('{{ __('admin.advertisements.delete_warning') }}', data?.message || '{{ __('admin.advertisements.delete_warning_message') }}', 'warning');
                            if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                            if (rowEl) rowEl.classList.remove('soft-dim');
                        }
                    })
                    .catch(()=>{
                        Swal.fire('{{ __('admin.advertisements.delete_error') }}', '{{ __('admin.advertisements.delete_error_message') }}', 'error');
                        if (btn) { btn.disabled = false; btn.innerHTML = btn.getAttribute('data-original-text') || '{{ __('admin.advertisements.delete') }}'; btn.removeAttribute('data-original-text'); }
                        if (rowEl) rowEl.classList.remove('soft-dim');
                        const current = location.pathname + location.search;
                        fetchData(current, false);
                    });
            });

            return false;
        };
    </script>
@endpush
