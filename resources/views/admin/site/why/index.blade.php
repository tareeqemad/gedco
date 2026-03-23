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
        <!-- Header Section -->
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-star-fill"
                :title="__('admin.why_choose_us.title')">
                <x-slot:actions>
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
                </x-slot:actions>
            </x-admin.card-header-index>

            <div class="card-body p-3 p-md-4">
                @if(!$why)
                    <div class="text-center py-5">
                        <i class="bi bi-star" style="font-size: 2.5rem; color: #CDD9E3;"></i>
                        <h6 class="fw-bold mt-3" style="color: #24364A;">{{ __('admin.why_choose_us.no_record') }}</h6>
                        <p class="text-muted mb-3" style="font-size: 0.85rem;">{{ __('admin.why_choose_us.description') }}</p>
                        <a href="{{ route('admin.why.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>{{ __('admin.why_choose_us.create_first_record') }}
                        </a>
                    </div>
                @else
                    {{-- معلومات عامة --}}
                    <div class="mb-4">
                        @if(filled($why->badge))
                            <div class="mb-2">
                                <span class="stat-chip" style="font-size: 0.78rem;">{{ $why->badge }}</span>
                            </div>
                        @endif

                        @if(filled($why->tagline))
                            <h4 class="fw-bold mb-2" style="color: #24364A;">{{ $why->tagline }}</h4>
                        @endif

                        @if(filled($why->description))
                            <p class="text-muted mb-0" style="line-height: 1.8; font-size: 0.92rem;">{{ $why->description }}</p>
                        @endif
                    </div>

                    {{-- الميزات --}}
                    @if(count($features))
                        <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                            <i class="bi bi-stars"></i> الميزات
                        </h6>
                        <div class="row g-3">
                            @foreach($features as $f)
                                @php
                                    $icon = $f['icon'] ?? 'bi bi-lightning-charge-fill';
                                    $title = $f['title'] ?? '';
                                    $text  = $f['text']  ?? '';
                                @endphp
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="h-100 rounded-3 p-3" style="border: 1px solid #E6ECF2;">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(26,188,156,0.08); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                                <i class="{{ $icon }}" style="color: #1ABC9C; font-size: 0.9rem;"></i>
                                            </div>
                                            <h6 class="fw-bold mb-0" style="color: #24364A; font-size: 0.88rem;">{{ $title }}</h6>
                                        </div>
                                        <p class="text-muted mb-0" style="line-height: 1.7; font-size: 0.82rem;">{{ $text }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-3 rounded-3 mt-2" style="background: #F8FBFD; border: 1px solid #E6ECF2;">
                            <i class="bi bi-info-circle me-1" style="color: #7A8CA2;"></i>
                            <span style="font-size: 0.82rem; color: #5B7088;">{{ __('admin.why_choose_us.no_features') }}</span>
                            <a href="{{ route('admin.why.edit', $why) }}" class="fw-semibold" style="font-size: 0.82rem;">{{ __('admin.why_choose_us.edit') }}</a>
                        </div>
                    @endif

                    {{-- Metadata --}}
                    <div class="mt-3 pt-2 d-flex gap-3 flex-wrap" style="border-top: 1px solid #E6ECF2; font-size: 0.72rem; color: #9AA8B6;">
                        <span><i class="bi bi-clock-history me-1"></i>{{ __('admin.why_choose_us.last_updated') }} {{ optional($why->updated_at)->translatedFormat('d M Y - h:i A') ?? '—' }}</span>
                        <span><i class="bi bi-calendar-check me-1"></i>{{ __('admin.why_choose_us.created_at') }} {{ optional($why->created_at)->translatedFormat('d M Y') ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </x-admin.card>
    </div>

@endsection
