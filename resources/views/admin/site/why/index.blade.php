@extends('layouts.admin')
@section('title','لماذا تختارنا')

@section('content')
    @php
        // متغيرات breadcrumb للّـayout (لو بيستخدمها)
        $breadcrumbTitle     = 'لماذا تختارنا';
        $breadcrumbParent    = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        // رابط المعاينة على الموقع العام (اختياري)
        $publicPreviewUrl = route('site.home') . '#section-why-choose-us';

        // تأمين بنية الميزات (قد تأتي JSON أو Array أو null)
        $featuresRaw = $why->features ?? [];
        $features    = is_array($featuresRaw) ? $featuresRaw : (json_decode($featuresRaw ?? '[]', true) ?? []);
    @endphp

    <div class="py-4">
        <div class="card shadow-sm border-0">

            {{-- Header --}}
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-orange text-white">إعدادات</span>
                        <h5 class="card-title mb-0 fw-semibold text-orange">لماذا تختارنا</h5>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        @if($why)
                            <a href="{{ route('admin.why.edit', $why) }}" class="btn btn-orange btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> تعديل
                            </a>
                            <a href="{{ $publicPreviewUrl }}" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                                <i class="bi bi-box-arrow-up-right me-1"></i> معاينة على الموقع
                            </a>
                        @else
                            <a href="{{ route('admin.why.create') }}" class="btn btn-success btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> إنشاء
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body p-3 p-lg-4">
                {{-- Flash messages --}}
                @if(session('success')) <div class="alert alert-success shadow-sm">{{ session('success') }}</div> @endif
                @if(session('warning')) <div class="alert alert-warning shadow-sm">{{ session('warning') }}</div> @endif

                @if(!$why)
                    {{-- لا يوجد سجل --}}
                    <div class="text-center py-5">
                        <div class="mb-3"><i class="bi bi-info-circle text-orange" style="font-size:2rem;"></i></div>
                        <h5 class="fw-bold mb-2">لا يوجد سجل بعد</h5>
                        <p class="text-muted mb-4">ابدأ بإضافة محتوى قسم “لماذا تختارنا” ليظهر في الصفحة الرئيسية.</p>
                        <a href="{{ route('admin.why.create') }}" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i> إنشاء أول سجل
                        </a>
                    </div>
                @else
                    {{-- معلومات عامة --}}
                    <div class="mb-3">
                        @if(filled($why->badge))
                            <div class="mb-2">
                                <span class="badge bg-orange text-white">{{ $why->badge }}</span>
                            </div>
                        @endif

                        @if(filled($why->tagline))
                            <h4 class="fw-bold text-orange mb-1">{{ $why->tagline }}</h4>
                        @endif

                        @if(filled($why->description))
                            <p class="text-muted mb-0" style="line-height:1.9">{{ $why->description }}</p>
                        @endif
                    </div>

                    {{-- الميزات --}}
                    @if(count($features))
                        <div class="row g-3 mt-1">
                            @foreach($features as $f)
                                @php
                                    $icon = $f['icon'] ?? 'bi bi-lightning-charge-fill';
                                    $title = $f['title'] ?? '';
                                    $text  = $f['text']  ?? '';
                                @endphp
                                <div class="col-12 col-md-6 col-lg-4">
                                    <div class="h-100 border rounded-3 p-3 shadow-sm bg-white d-flex flex-column">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <i class="{{ $icon }} text-orange" style="font-size:1.25rem;"></i>
                                            <h6 class="fw-bold mb-0">{{ $title }}</h6>
                                        </div>
                                        <p class="text-muted mb-0" style="line-height:1.8">{{ $text }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info mt-3 mb-0">
                            لا توجد ميزات مضافة بعد. يمكنك إضافتها من شاشة <a href="{{ route('admin.why.edit', $why) }}">التعديل</a>.
                        </div>
                    @endif

                    {{-- ميتاداتا بسيطة --}}
                    <div class="mt-4 small text-muted">
                        <span class="me-3"><i class="bi bi-clock-history me-1"></i>آخر تحديث: {{ optional($why->updated_at)->format('Y-m-d H:i') ?? '—' }}</span>
                        <span><i class="bi bi-calendar-check me-1"></i>تاريخ الإنشاء: {{ optional($why->created_at)->format('Y-m-d H:i') ?? '—' }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .text-orange { color:#ff7700 !important; }
            .bg-orange   { background-color:#ff7700 !important; }
            .btn-orange{
                background-color:#ff7700 !important;
                border-color:#ff7700 !important;
                color:#fff !important;
            }
            .btn-orange:hover{ opacity:.9; }
        </style>
    @endpush
@endsection
