@extends('layouts.admin')
@section('title', __('admin.news.show_title') . ' #' . $item->id)

@section('content')
    @php
        $breadcrumbTitle = __('admin.news.show_title') . ' #' . $item->id;
        $breadcrumbParent = __('admin.menu.news');
        $breadcrumbParentUrl = route('admin.news.index');
        $published = $item->is_published ?? (($item->status ?? 'published') === 'published');
        $publishedAt = optional($item->published_at)->format('Y-m-d') ?? '—';
        $coverUrl = $item->cover_url;
        $pdfUrl = $item->pdf_url;
    @endphp

    <div class="container-fluid p-0">
        <!-- Header -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-newspaper"
                :title="__('admin.news.show_title') . ' #' . $item->id"
                :back-route="route('admin.news.index')"
                :back-label="__('admin.news.back')">
                <x-slot:subtitle>
                    <small class="text-white opacity-75 d-none d-md-block" style="font-size: 0.8rem; line-height: 1.2;">{{ \Illuminate\Support\Str::limit($item->title, 50) }}</small>
                </x-slot:subtitle>
                <x-slot:actions>
                    @can('news.edit')
                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-warning btn-sm shadow-sm">
                            <i class="bi bi-pencil-square me-1"></i> <span class="d-none d-md-inline">{{ __('admin.news.edit_news') }}</span>
                        </a>
                    @endcan
                    @can('news.delete')
                        <button type="button" id="btnDelete" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-trash me-1"></i> <span class="d-none d-sm-inline">{{ __('admin.news.delete') }}</span>
                        </button>
                    @endcan
                </x-slot:actions>
            </x-admin.card-header-form>
        </x-admin.card>

        <div class="row g-3 g-md-4">
            <!-- Main content -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 p-md-4">
                        <!-- Title + meta -->
                        <div class="mb-3 mb-md-4">
                            <h3 class="fw-bold mb-2 mb-md-3 text-primary fs-4 fs-md-3">{{ $item->title }}</h3>
                            <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                <span class="badge {{ $published ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                    <i class="bi bi-{{ $published ? 'check-circle' : 'clock' }} me-1"></i>
                                    {{ $published ? 'منشور' : 'مسودة' }}
                                </span>
                                @if($item->featured)
                                    <span class="badge bg-warning text-dark px-3 py-2">
                                        <i class="bi bi-star-fill me-1"></i> مميّز
                                    </span>
                                @endif
                                <span class="text-muted d-flex align-items-center gap-1">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $publishedAt !== '—' ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d F Y') : '—' }}
                                </span>
                                <span class="text-muted d-flex align-items-center gap-1">
                                    <i class="bi bi-clock-history"></i>
                                    آخر تحديث: {{ optional($item->updated_at)->format('Y-m-d H:i') ?? '—' }}
                                </span>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-pills nav-pills-custom mb-3 mb-md-4 border-bottom pb-2 flex-column flex-sm-row" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active px-3 px-md-4 py-2 rounded-pill w-100 w-sm-auto mb-2 mb-sm-0" data-bs-toggle="tab" data-bs-target="#tab-html" type="button" role="tab" aria-selected="true">
                                    <i class="bi bi-file-earmark-text me-1 me-md-2"></i> <span class="d-none d-md-inline">المحتوى (HTML)</span><span class="d-md-none">HTML</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 px-md-4 py-2 rounded-pill w-100 w-sm-auto mb-2 mb-sm-0" data-bs-toggle="tab" data-bs-target="#tab-text" type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-file-text me-1 me-md-2"></i> <span class="d-none d-md-inline">نص مجرد</span><span class="d-md-none">نص</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 px-md-4 py-2 rounded-pill w-100 w-sm-auto" data-bs-toggle="tab" data-bs-target="#tab-preview" type="button" role="tab" aria-selected="false">
                                    <i class="bi bi-eye me-1 me-md-2"></i> <span class="d-none d-md-inline">معاينة مقال</span><span class="d-md-none">معاينة</span>
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab-html" role="tabpanel">
                                <div class="article-html border rounded-3 p-3" style="direction: rtl; text-align: right; line-height:1.9; font-size: 0.98rem;">
                                    {!! $item->body !!}
                                </div>
                            </div>
                            <div class="tab-pane fade" id="tab-text" role="tabpanel">
                                <pre class="border rounded-3 p-3" style="white-space: pre-wrap; word-break: break-word; min-height: 220px;">
{{ strip_tags($item->body ?? '') }}</pre>
                            </div>
                            <div class="tab-pane fade" id="tab-preview" role="tabpanel">
                                <article class="rounded-3 border p-3">
                                    @if($coverUrl)
                                        <img src="{{ $coverUrl }}" class="rounded-3 w-100 mb-3" style="max-height:360px; object-fit:cover;" alt="cover">
                                    @endif
                                    <h3 class="fw-bold mb-2">{{ $item->title }}</h3>
                                    <div class="small text-muted mb-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-calendar"></i>
                                        <span>{{ $publishedAt !== '—' ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d F Y') : '—' }}</span>
                                    </div>
                                    <div class="lh-lg" style="direction: rtl; text-align: right;">
                                        {!! $item->body !!}
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar meta -->
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-3">
                    <div class="card-header bg-light border-0 py-3 px-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2">
                                <i class="bi bi-info-circle text-primary"></i>
                            </div>
                            <h6 class="mb-0 fw-semibold">البيانات</h6>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">ID</span>
                                <span class="badge bg-primary">{{ $item->id }}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">الحالة</span>
                                <span class="badge {{ $published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $published ? 'منشور' : 'مسودة' }}
                                </span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">تاريخ النشر</span>
                                <span class="fw-medium">{{ $publishedAt }}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">مميّز</span>
                                <span>{!! $item->featured ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>' : '<i class="bi bi-x-circle text-muted fs-5"></i>' !!}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">أُنشئ</span>
                                <span class="fw-medium small">{{ optional($item->created_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">آخر تحديث</span>
                                <span class="fw-medium small">{{ optional($item->updated_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">أضاف</span>
                                <span class="fw-medium">{{ $item->creator?->name ?? 'غير معروف' }}</span>
                            </div>
                            <div class="list-group-item border-0 px-4 py-3 d-flex justify-content-between align-items-center">
                                <span class="text-muted small">عدّل</span>
                                <span class="fw-medium">{{ $item->updater?->name ?? 'غير معروف' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($pdfUrl)
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-header bg-danger bg-opacity-10 border-0 py-3 px-4">
                            <div class="d-flex align-items-center gap-2">
                                <div class="icon-wrapper bg-danger bg-opacity-20 rounded-circle p-2">
                                    <i class="bi bi-file-pdf text-danger"></i>
                                </div>
                                <h6 class="mb-0 fw-semibold">المرفق</h6>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <a href="{{ $pdfUrl }}" class="btn btn-danger w-100 shadow-sm" target="_blank">
                                <i class="bi bi-file-earmark-pdf me-2"></i> فتح PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/news.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnDelete = document.getElementById('btnDelete');
            if (!btnDelete) return;

            btnDelete.addEventListener('click', function () {
                Swal.fire({
                    title: 'تأكيد الحذف',
                    text: 'هل تريد حذف هذا الخبر نهائياً؟ لا يمكن التراجع!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-secondary px-4 me-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('admin.news.destroy', $item) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                            .then(r => r.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('تم!', data.message, 'success').then(() => {
                                        window.location.href = '{{ route('admin.news.index') }}';
                                    });
                                } else {
                                    Swal.fire('خطأ', data.message || 'فشل الحذف', 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('خطأ', 'حدث خطأ في الاتصال', 'error');
                            });
                    }
                });
            });
        });
    </script>
@endpush
