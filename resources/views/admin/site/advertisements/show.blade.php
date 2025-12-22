@extends('layouts.admin')
@section('title', __('admin.advertisements.show_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.advertisements.show_title');
        $breadcrumbParent    = __('admin.advertisements.title');
        $breadcrumbParentUrl = route('admin.advertisements.index');

        $pdfRoute = ($ad->PDF && Storage::disk('public')->exists($ad->PDF))
            ? route('admin.advertisements.pdf', $ad)
            : null;
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-eye"></i>
                                <span class="text-truncate" style="max-width: 500px;">{{ Str::limit($ad->TITLE, 60) }}</span>
                            </h5>
                            <div class="text-white-50 small d-flex align-items-center gap-2 mt-1">
                                <i class="bi bi-calendar"></i>
                                <span>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('l، d F Y') }}</span>
                                <span>| {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('H:i') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.advertisements.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i>{{ __('admin.advertisements.back') }}
                        </a>
                        <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-pencil me-1"></i>{{ __('admin.advertisements.edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- BODY --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4">
                        <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                            <i class="bi bi-file-text"></i>المحتوى
                        </h6>
                    </div>
                    <div class="card-body p-5">

                        <div class="content-preview lh-lg text-dark" style="font-size:1.1rem; line-height:1.9;">
                            {!! $ad->BODY ?: '<p class="text-muted fst-italic text-center py-5">'.__('admin.advertisements.show_no_content').'</p>' !!}
                        </div>

                        <hr class="my-5">

                        <div class="row text-center text-sm-start small text-muted">
                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-person-plus text-primary"></i>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $ad->INSERT_USER }}</div>
                                        <div>{{ __('admin.advertisements.show_added_by') }} {{ $ad->INSERT_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6 mb-3">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-arrow-clockwise text-success"></i>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $ad->UPDATE_USER ?? '—' }}</div>
                                        <div>{{ __('admin.advertisements.show_last_update') }} {{ $ad->UPDATE_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- PDF --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top:1rem;">
                    <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                            <i class="bi bi-file-pdf"></i>{{ __('admin.advertisements.show_pdf_file') }}
                        </h6>
                        @if($pdfRoute)
                            <a href="{{ $pdfRoute }}" class="btn btn-light btn-sm shadow-sm" download>
                                <i class="bi bi-download"></i>
                            </a>
                        @endif
                    </div>

                    <div class="card-body p-0">

                        @if($pdfRoute)

                            <div class="p-3">
                                <button type="button" id="showPdfBtn" class="btn btn-sm btn-primary w-100">
                                    <i class="bi bi-eye"></i> {{ __('admin.advertisements.show_view_pdf') }}
                                </button>
                            </div>

                            <div class="ratio ratio-16x9 bg-light">
                                <iframe id="pdfFrame" data-src="{{ $pdfRoute }}" style="border:none;"></iframe>
                            </div>

                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-file-x" style="font-size:3rem;"></i>
                                <p class="mt-3 mb-0">{{ __('admin.advertisements.show_no_pdf') }}</p>
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
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('admin.advertisements.show_loading') }}';
                        setTimeout(()=> btn.classList.add('d-none'), 600);
                    });
                }
            });
        </script>
    @endpush

    @push('styles')
    @endpush
@endsection
