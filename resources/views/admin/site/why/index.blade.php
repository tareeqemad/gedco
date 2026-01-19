@extends('layouts.admin')
@section('title', __('admin.menu.why_choose_us'))

@section('content')
    @php
        // متغيرات breadcrumb للّـayout (لو بيستخدمها)
        $breadcrumbTitle     = __('admin.menu.why_choose_us');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        // رابط المعاينة على الموقع العام (اختياري)
        $publicPreviewUrl = route('site.home') . '#section-why-choose-us';

        // تأمين بنية الميزات (قد تأتي JSON أو Array أو null)
        $featuresRaw = $why->features ?? [];
        $features    = is_array($featuresRaw) ? $featuresRaw : (json_decode($featuresRaw ?? '[]', true) ?? []);
    @endphp

    <div class="container-fluid p-0">
        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <!-- Header Section -->
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-star-fill"></i>
                                <span class="d-none d-sm-inline">{{ __('admin.why_choose_us.title') }}</span>
                                <span class="d-sm-none">{{ __('admin.menu.why_choose_us') }}</span>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        @if($why)
                            <a href="{{ route('admin.why.edit', $why) }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-pencil-square me-2"></i><span class="d-none d-md-inline">{{ __('admin.why_choose_us.edit') }}</span><span class="d-md-none">{{ __('admin.actions.edit') }}</span>
                            </a>
                            <a href="{{ $publicPreviewUrl }}" class="btn btn-light btn-sm shadow-sm" target="_blank" rel="noopener">
                                <i class="bi bi-box-arrow-up-right me-2"></i><span class="d-none d-md-inline">{{ __('admin.common.preview_on_site') }}</span><span class="d-md-none">{{ __('admin.common.preview') }}</span>
                            </a>
                        @else
                            <a href="{{ route('admin.why.create') }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-plus-circle me-2"></i><span class="d-none d-md-inline">{{ __('admin.why_choose_us.create') }}</span><span class="d-md-none">{{ __('admin.actions.add') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4 p-md-5">
                {{-- Flash messages --}}

                @if(!$why)
                    {{-- No Record --}}
                    <div class="text-center py-5">
                        <div class="mb-3"><i class="bi bi-star text-primary" style="font-size:4rem; opacity: 0.5;"></i></div>
                        <h5 class="fw-bold mb-2 text-dark">{{ __('admin.why_choose_us.no_record') }}</h5>
                        <p class="text-muted mb-4">{{ __('admin.why_choose_us.description') }}</p>
                        <a href="{{ route('admin.why.create') }}" class="btn btn-primary px-5 py-2 rounded-3 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>{{ __('admin.why_choose_us.create_first_record') }}
                        </a>
                    </div>
                @else
                    {{-- معلومات عامة --}}
                    <div class="mb-4">
                        @if(filled($why->badge))
                            <div class="mb-3">
                                <span class="badge bg-primary rounded-pill px-3 py-2" style="font-size: 0.95rem;">{{ $why->badge }}</span>
                            </div>
                        @endif

                        @if(filled($why->tagline))
                            <h4 class="fw-bold text-primary mb-2">{{ $why->tagline }}</h4>
                        @endif

                        @if(filled($why->description))
                            <p class="text-muted mb-0" style="line-height:1.9; font-size: 1.05rem;">{{ $why->description }}</p>
                        @endif
                    </div>

                    {{-- الميزات --}}
                    @if(count($features))
                        <div class="row g-4 mt-2">
                            @foreach($features as $f)
                                @php
                                    $icon = $f['icon'] ?? 'bi bi-lightning-charge-fill';
                                    $title = $f['title'] ?? '';
                                    $text  = $f['text']  ?? '';
                                @endphp
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="h-100 border-0 rounded-4 p-4 shadow-sm bg-white d-flex flex-column transition-all" style="transition: all 0.3s ease;">
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <i class="{{ $icon }} text-primary" style="font-size:1.5rem;"></i>
                                            <h6 class="fw-bold mb-0 text-dark">{{ $title }}</h6>
                                        </div>
                                        <p class="text-muted mb-0" style="line-height:1.8;">{{ $text }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mt-3 mb-0 rounded-4 border-0 shadow-sm">
                            <i class="bi bi-info-circle me-2"></i>
                            {{ __('admin.why_choose_us.no_features') }} <a href="{{ route('admin.why.edit', $why) }}" class="fw-bold">{{ __('admin.why_choose_us.edit') }}</a>.
                        </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="mt-4 pt-3 border-top small text-muted">
                        <span class="me-3"><i class="bi bi-clock-history me-1"></i>{{ __('admin.why_choose_us.last_updated') }} {{ optional($why->updated_at)->format('Y-m-d H:i') ?? '—' }}</span>
                        <span><i class="bi bi-calendar-check me-1"></i>{{ __('admin.why_choose_us.created_at') }} {{ optional($why->created_at)->format('Y-m-d H:i') ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
