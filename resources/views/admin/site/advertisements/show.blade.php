@extends('layouts.admin')
@section('title', 'عرض الإعلان')

@section('content')
    @php
        $breadcrumbTitle     = 'عرض الإعلان';
        $breadcrumbParent    = 'الإعلانات والوظائف';
        $breadcrumbParentUrl = route('admin.advertisements.index');

        $pdfRoute = ($ad->PDF && Storage::disk('public')->exists($ad->PDF))
            ? route('admin.advertisements.pdf', $ad)
            : null;
    @endphp

    <div class="container-fluid p-0">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-primary">{{ $ad->TITLE }}</h4>
                <div class="text-muted small d-flex align-items-center gap-2">
                    <i class="ri-calendar-line"></i>
                    <span>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('l، d F Y') }}</span>
                    <span class="text-secondary">| {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('H:i') }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-warning btn-sm">
                    <i class="ri-edit-line"></i> تعديل
                </a>
                <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary btn-sm">
                    <i class="ri-arrow-left-line"></i> رجوع
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- BODY --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
                    <div class="card-body p-5">

                        <div class="content-preview lh-lg text-dark" style="font-size:1.1rem; line-height:1.9;">
                            {!! $ad->BODY ?: '<p class="text-muted fst-italic text-center py-5">لا يوجد محتوى نصي</p>' !!}
                        </div>

                        <hr class="my-5">

                        <div class="row text-center text-sm-start small text-muted">
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-user-add-line text-primary"></i>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $ad->INSERT_USER }}</div>
                                        <div>أُضيف في {{ $ad->INSERT_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-refresh-line text-success"></i>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $ad->UPDATE_USER ?? '—' }}</div>
                                        <div>آخر تحديث {{ $ad->UPDATE_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- PDF --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top:1rem;">
                    <div class="card-header bg-light py-3 px-4 d-flex justify-content-between">
                        <h6 class="mb-0 fw-semibold text-dark">
                            <i class="ri-file-pdf-line text-danger me-2"></i> ملف PDF
                        </h6>

                        @if($pdfRoute)
                            <a href="{{ $pdfRoute }}" class="btn btn-sm btn-outline-success" download>
                                <i class="ri-download-line"></i>
                            </a>
                        @endif
                    </div>

                    <div class="card-body p-0">

                        @if($pdfRoute)

                            <div class="p-3">
                                <button type="button" id="showPdfBtn" class="btn btn-sm btn-primary w-100">
                                    <i class="ri-eye-line"></i> عرض PDF
                                </button>
                            </div>

                            <div class="ratio ratio-16x9 bg-light">
                                <iframe id="pdfFrame" data-src="{{ $pdfRoute }}" style="border:none;"></iframe>
                            </div>

                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="ri-file-close-line" style="font-size:3rem;"></i>
                                <p class="mt-3 mb-0">لا يوجد ملف PDF</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btn = document.getElementById('showPdfBtn');
                const frame = document.getElementById('pdfFrame');

                if (btn && frame) {
                    btn.addEventListener('click', () => {
                        if (!frame.src) frame.src = frame.dataset.src;
                        btn.disabled = true;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> جارِ التحميل...';
                        setTimeout(()=> btn.classList.add('d-none'), 600);
                    });
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .content-preview p{ margin-bottom:1.2rem; }
            iframe{ width:100%; height:100%; }
        </style>
    @endpush
@endsection
