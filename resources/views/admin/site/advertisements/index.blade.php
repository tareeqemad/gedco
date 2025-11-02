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

        <!-- الجدول -->
        <div class="table-responsive">
            <div id="table-container">
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const tableContainer = document.getElementById('table-container');
                const paginationContainer = document.getElementById('pagination-container');
                const totalCount = document.getElementById('total-count');
                const filterForm = document.getElementById('filterForm');

                if (!tableContainer || !paginationContainer || !totalCount) {
                    console.error('Missing elements');
                    return;
                }

                function showLoading() {
                    tableContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-3 text-muted">جاري تحميل البيانات...</p>
                    </div>`;
                }

                function updateTable(data) {
                    tableContainer.innerHTML = data.html;
                    paginationContainer.innerHTML = data.pagination;
                    totalCount.textContent = data.total;
                    // لا حاجة لـ AOS.refresh() لأن AOS غير مستخدم
                }

                document.body.addEventListener('click', function (e) {
                    const link = e.target.closest('.pagination-link');
                    if (link) {
                        e.preventDefault();
                        const url = link.getAttribute('data-url');
                        showLoading();
                        fetchData(url);
                    }
                });

                let timeout;
                if (filterForm) {
                    filterForm.querySelectorAll('input, select').forEach(input => {
                        input.addEventListener('change', () => submitFilters());
                        if (input.name === 'q') {
                            input.addEventListener('input', () => {
                                clearTimeout(timeout);
                                timeout = setTimeout(submitFilters, 600);
                            });
                        }
                    });
                }

                function submitFilters() {
                    const params = new URLSearchParams(new FormData(filterForm));
                    showLoading();
                    fetchData(`?${params.toString()}`);
                }

                function fetchData(url) {
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP ${response.status}`);
                            return response.json();
                        })
                        .then(updateTable)
                        .catch(error => {
                            console.error('AJAX Error:', error);
                            tableContainer.innerHTML = `
                        <div class="text-center py-5 text-danger">
                            <i class="ri-error-warning-line fs-48 d-block mb-3"></i>
                            <p class="mb-2">فشل في تحميل البيانات</p>
                            <button class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                <i class="ri-refresh-line"></i> إعادة تحميل
                            </button>
                        </div>`;
                        });
                }

                window.confirmDelete = function(form) {
                    Swal.fire({
                        title: 'هل أنت متأكد؟',
                        text: "لن تتمكن من استرجاع هذا الإعلان!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'نعم، احذف!',
                        cancelButtonText: 'إلغاء',
                        reverseButtons: true,
                        customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary ms-2' },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            showLoading();
                            const formData = new FormData(form);
                            fetch(form.action, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: formData
                            })
                                .then(r => r.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('تم!', data.message, 'success');
                                        fetchData(window.location.href.split('?')[0] + window.location.search);
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('خطأ', 'فشل في الحذف', 'error');
                                    fetchData(window.location.href.split('?')[0] + window.location.search);
                                });
                        }
                    });
                    return false;
                };
            });
        </script>
    @endpush
@endsection
