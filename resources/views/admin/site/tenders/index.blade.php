@php
    $breadcrumbTitle     = __('admin.tenders.title');
    $breadcrumbParent    = __('admin.menu.content_management');
    $breadcrumbParentUrl = route('admin.news.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-file-earmark-text"
                :title="__('admin.tenders.title')"
                :create-route="route('admin.tenders.create')"
                :create-label="__('admin.tenders.add_tender')"
                create-permission="tenders.create">
                <x-slot:badge>
                    <span class="badge bg-white text-primary rounded-pill" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;" id="total-count">{{ $tenders->total() }}</span>
                </x-slot:badge>
            </x-admin.card-header-index>

            <!-- Filters Section -->
            <div class="card-body p-3 admin-filters">
                <form id="filter-form">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div class="filter-field-wide">
                            <input type="text" name="q" class="form-control rounded-3"
                                   placeholder="{{ __('admin.tenders.search_placeholder') }}"
                                   value="{{ $q ?? '' }}">
                        </div>
                        <div class="filter-field-medium">
                            <select name="user" class="form-select rounded-3">
                                <option value="">{{ __('admin.tenders.all_users') }}</option>
                                @foreach($distinctUsers as $u)
                                    <option value="{{ $u }}" @selected(($user ?? '') === $u)>{{ $u }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field-date">
                            <label class="date-field-label">{{ __('admin.tenders.date_from') }}</label>
                            <input type="date" name="date_from" class="form-control rounded-3"
                                   value="{{ $dateFrom ?? '' }}">
                        </div>
                        <div class="filter-field-date">
                            <label class="date-field-label">{{ __('admin.tenders.date_to') }}</label>
                            <input type="date" name="date_to" class="form-control rounded-3"
                                   value="{{ $dateTo ?? '' }}">
                        </div>
                        <div class="filter-field-auto">
                            <button type="submit" class="btn btn-primary rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.actions.query') }}
                            </button>
                            <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.actions.clear') }}
                            </a>
                        </div>
                    </div>

                    {{-- Advanced filters --}}
                    <div class="mt-2">
                        <button type="button" class="btn btn-link btn-sm text-muted p-0"
                                data-bs-toggle="collapse" data-bs-target="#advancedFilters">
                            <i class="bi bi-sliders me-1"></i> {{ __('admin.tenders.advanced_options') }}
                        </button>
                        <div class="collapse {{ ($sort !== 'id' || $dir !== 'desc' || $perPage != 20) ? 'show' : '' }}" id="advancedFilters">
                            <div class="d-flex gap-2 align-items-center flex-wrap mt-2 admin-advanced-filters">
                                <div class="filter-field-auto">
                                    <label class="form-label mb-0 me-1">{{ __('admin.tenders.sort_by') }}</label>
                                    <select name="sort" class="form-select form-select-sm rounded-3 d-inline-block" style="width: auto;">
                                        <option value="id" @selected(($sort ?? 'id') === 'id')>ID</option>
                                        <option value="mnews_id" @selected(($sort ?? '') === 'mnews_id')>{{ __('admin.tenders.tender_number') }}</option>
                                        <option value="the_date_1" @selected(($sort ?? '') === 'the_date_1')>{{ __('admin.tenders.form_date') }}</option>
                                        <option value="created_at" @selected(($sort ?? '') === 'created_at')>{{ __('admin.tenders.table_created_at') }}</option>
                                    </select>
                                </div>
                                <div class="filter-field-auto">
                                    <select name="dir" class="form-select form-select-sm rounded-3" style="width: auto;">
                                        <option value="desc" @selected(($dir ?? 'desc')==='desc')>{{ __('admin.tenders.sort_desc') }}</option>
                                        <option value="asc" @selected(($dir ?? '')==='asc')>{{ __('admin.tenders.sort_asc') }}</option>
                                    </select>
                                </div>
                                <div class="filter-field-auto">
                                    <label class="form-label mb-0 me-1">{{ __('admin.tenders.per_page') }}</label>
                                    <select name="per_page" class="form-select form-select-sm rounded-3 d-inline-block" style="width: auto;">
                                        @foreach([10, 20, 50, 100] as $pp)
                                            <option value="{{ $pp }}" @selected(($perPage ?? 20) == $pp)>{{ $pp }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div id="tenders-table-wrapper">
                <div class="table-responsive">
                    @include('admin.site.tenders.partials.table', ['tenders' => $tenders])
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;" id="tenders-pagination">
                @include('admin.site.tenders.partials.pagination', ['tenders' => $tenders])
            </div>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
    <script>
        (function(){
            const form = document.getElementById('filter-form');
            const wrapper = document.getElementById('tenders-table-wrapper');
            const pagination = document.getElementById('tenders-pagination');
            const totalCount = document.getElementById('total-count');

            // Delete with SweetAlert
            window.delTender = function(id) {
                Swal.fire({
                    title: '{{ __('admin.tenders.delete_confirm') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('admin.tenders.delete') }}',
                    cancelButtonText: '{{ __('admin.tenders.form_cancel') }}',
                    reverseButtons: true,
                    customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary ms-2' },
                    buttonsStyling: false
                }).then((result) => {
                    if (!result.isConfirmed) return;
                    fetch("{{ url('admin/tenders') }}/" + id, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({_method:'DELETE'})
                    }).then(r => r.json())
                      .then(json => {
                          if (json.success) {
                              if (window.adminNotifications) window.adminNotifications.success('{{ __('admin.tenders.delete_success') }}');
                              submitAjax();
                          } else {
                              if (window.adminNotifications) window.adminNotifications.error(json.message || '{{ __('admin.tenders.delete_failed') }}');
                          }
                      }).catch(() => {
                          if (window.adminNotifications) window.adminNotifications.error('{{ __('admin.tenders.delete_connection_error') }}');
                      });
                });
            };

            // Debounce
            let t;
            const debounce = (fn, d=400) => (...args) => { clearTimeout(t); t = setTimeout(()=>fn(...args), d); };

            form.addEventListener('input', debounce(() => submitAjax()));
            form.addEventListener('change', () => submitAjax());
            form.addEventListener('submit', function(e) { e.preventDefault(); submitAjax(); });

            // Pagination clicks
            document.addEventListener('click', function(e) {
                const a = e.target.closest('#tenders-pagination a.page-link');
                if (!a) return;
                e.preventDefault();
                submitAjax(a.getAttribute('href'));
            });

            function submitAjax(url = null) {
                const params = new URLSearchParams(new FormData(form));
                const fetchUrl = url || ("{{ route('admin.tenders.index') }}?" + params.toString());
                fetch(fetchUrl, {
                    headers: {'X-Requested-With':'XMLHttpRequest'}
                }).then(r => r.json())
                  .then(data => {
                      if (data.html) wrapper.querySelector('.table-responsive').innerHTML = data.html;
                      if (data.pagination) pagination.innerHTML = data.pagination;
                      if (data.total !== undefined && totalCount) totalCount.textContent = data.total;
                  }).catch(() => {});
            }
        })();
    </script>
@endpush
