@extends('layouts.admin')
@section('title', 'تفاصيل الخبر #' . $item->id)

@section('content')
    @php
        $breadcrumbTitle     = 'تفاصيل الخبر #' . $item->id;
        $breadcrumbParent    = 'الأخبار';
        $breadcrumbParentUrl = route('admin.news.index');

        $published   = method_exists($item,'getIsPublishedAttribute') ? $item->is_published : (($item->status ?? 'published') === 'published');
        $publishedAt = optional($item->published_at)->format('Y-m-d') ?? ($item->published_at ?? null);
        $tagsArray   = is_array($item->tags ?? null) ? $item->tags : (is_string($item->tags ?? null) ? json_decode($item->tags, true) : []);
        if (!is_array($tagsArray)) $tagsArray = [];
        $coverUrl    = $item->cover_url ?? ($item->cover_path ? Storage::url($item->cover_path) : null);
        $pdfUrl      = $item->pdf_url   ?? ($item->pdf_path   ? Storage::url($item->pdf_path)   : null);
    @endphp

    <div class="container-fluid p-0">
        <!-- Header -->
        <div class="card border-0 shadow-sm rounded-3 bg-white mb-4">
            <div class="card-header bg-white border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <i class="ri-newspaper-line text-primary fs-5"></i>
                    <h6 class="mb-0 fw-semibold text-dark-emphasis">تفاصيل الخبر #{{ $item->id }}</h6>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-sm">
                        <i class="ri-arrow-go-back-line"></i> رجوع
                    </a>
                    @can('news.edit')
                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-warning btn-sm">
                            <i class="ri-edit-2-line"></i> تعديل
                        </a>
                    @endcan
                    @can('news.delete')
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline" id="deleteForm">
                            @csrf @method('DELETE')
                            <button type="button" id="btnDelete" class="btn btn-outline-danger btn-sm">
                                <i class="ri-delete-bin-line"></i> حذف
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main content -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-4">
                        <!-- Title + meta -->
                        <div class="mb-3">
                            <h4 class="fw-bold mb-2">{{ $item->title }}</h4>
                            <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="ri-calendar-line"></i>
                                    {{ $publishedAt ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d F Y') : '—' }}
                                </span>
                                <span>•</span>
                                <span class="d-inline-flex align-items-center gap-1">
                                    <i class="ri-eye-line"></i>
                                    الحالة:
                                    <span class="badge {{ $published ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                        {{ $published ? 'منشور' : 'مسودة' }}
                                    </span>
                                </span>
                                @if(!empty($item->featured))
                                    <span>•</span>
                                    <span class="d-inline-flex align-items-center gap-1">
                                        <i class="ri-star-smile-line text-warning"></i> مميّز
                                    </span>
                                @endif
                                <span class="ms-auto d-none d-md-inline text-xxs">
                                    آخر تحديث: {{ optional($item->updated_at)->format('Y-m-d H:i') ?? '—' }}
                                </span>
                            </div>
                        </div>

                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-tabs-sm border-0 mb-3" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active px-3 py-1" data-bs-toggle="tab" data-bs-target="#tab-html" type="button">
                                    <i class="ri-article-line me-1"></i> المحتوى (HTML)
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 py-1" data-bs-toggle="tab" data-bs-target="#tab-text" type="button">
                                    <i class="ri-file-text-line me-1"></i> نص مجرد
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link px-3 py-1" data-bs-toggle="tab" data-bs-target="#tab-preview" type="button">
                                    <i class="ri-eye-line me-1"></i> معاينة مقال
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
                                <pre class="border rounded-3 p-3" style="white-space: pre-wrap; word-break: break-word; min-height: 220px;">{{ strip_tags((string)($item->body ?? '')) }}</pre>
                            </div>

                            <div class="tab-pane fade" id="tab-preview" role="tabpanel">
                                <article class="rounded-3 border p-3">
                                    @if($coverUrl)
                                        <img src="{{ $coverUrl }}" class="rounded-3 w-100 mb-3" style="max-height:360px; object-fit:cover;" alt="cover">
                                    @endif
                                    <h3 class="fw-bold mb-2">{{ $item->title }}</h3>
                                    <div class="small text-muted mb-3 d-flex align-items-center gap-2">
                                        <i class="ri-calendar-line"></i>
                                        <span>{{ $publishedAt ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d F Y') : '—' }}</span>
                                        @if($tagsArray)
                                            <span>•</span>
                                            <span class="d-inline-flex align-items-center gap-1">
                                                <i class="ri-price-tag-3-line"></i>
                                                @foreach($tagsArray as $tg)
                                                    <span class="badge bg-light text-secondary border">{{ $tg }}</span>
                                                @endforeach
                                            </span>
                                        @endif
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
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="ri-information-line text-primary"></i>
                            <h6 class="mb-0 fw-semibold">البيانات</h6>
                        </div>
                        <div class="list-group list-group-flush small">
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">ID</span>
                                <span>{{ $item->id }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">الحالة</span>
                                <span class="badge {{ $published ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">{{ $published ? 'منشور' : 'مسودة' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">تاريخ النشر</span>
                                <span>{{ $publishedAt ?: '—' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">مميّز</span>
                                <span>{!! !empty($item->featured) ? '<i class="ri-check-line text-success"></i>' : '<i class="ri-close-line text-danger"></i>' !!}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">أُنشئ</span>
                                <span>{{ optional($item->created_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span class="text-muted">آخر تحديث</span>
                                <span>{{ optional($item->updated_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($tagsArray)
                    <div class="card border-0 shadow-sm rounded-3 bg-white mb-3">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ri-price-tag-3-line text-success"></i>
                                <h6 class="mb-0 fw-semibold">وسوم</h6>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tagsArray as $tg)
                                    <span class="badge bg-light text-secondary border">{{ $tg }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @if($pdfUrl)
                    <div class="card border-0 shadow-sm rounded-3 bg-white">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="ri-file-pdf-line text-danger"></i>
                                <h6 class="mb-0 fw-semibold">المرفق</h6>
                            </div>
                            <a href="{{ $pdfUrl }}" class="btn btn-outline-primary w-100" target="_blank">
                                <i class="ri-eye-line"></i> فتح PDF
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .nav-tabs .nav-link{font-size:.875rem;border:none;color:#6c757d}
        .nav-tabs .nav-link.active{color:#4361ee;font-weight:600;border-bottom:2px solid #4361ee}
        .article-html h1,.article-html h2,.article-html h3{margin-top:1rem}
        .article-html img{max-width:100%;height:auto;border-radius:.5rem}
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/admin/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const btnDelete = document.getElementById('btnDelete');
            if(btnDelete){
                btnDelete.addEventListener('click', function(){
                    Swal.fire({
                        title:'تأكيد الحذف',
                        text:'هل تريد حذف هذا الخبر نهائياً؟',
                        icon:'warning',
                        showCancelButton:true,
                        confirmButtonText:'نعم، حذف',
                        cancelButtonText:'إلغاء'
                    }).then(res=>{
                        if(res.isConfirmed){
                            document.getElementById('deleteForm').submit();
                        }
                    });
                });
            }
        });
    </script>
@endpush
