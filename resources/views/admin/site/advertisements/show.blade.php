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

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-primary">{{ $ad->TITLE }}</h4>
                <div class="text-muted small d-flex align-items-center gap-2">
                    <i class="bi bi-calendar"></i>
                    <span>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('l، d F Y') }}</span>
                    <span class="text-secondary">| {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('H:i') }}</span>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil"></i> {{ __('admin.advertisements.edit') }}
                </a>
                <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> {{ __('admin.advertisements.back') }}
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- BODY --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-3 bg-white">
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
                <div class="card border-0 shadow-sm rounded-3 bg-white sticky-top" style="top:1rem;">
                    <div class="card-header bg-light py-3 px-4 d-flex justify-content-between">
                        <h6 class="mb-0 fw-semibold text-dark">
                            <i class="bi bi-file-pdf text-danger me-2"></i> {{ __('admin.advertisements.show_pdf_file') }}
                        </h6>

                        @if($pdfRoute)
                            <a href="{{ $pdfRoute }}" class="btn btn-sm btn-outline-success" download>
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
