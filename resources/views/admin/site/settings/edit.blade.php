@php
    $breadcrumbTitle     = __('admin.settings.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
    
    $channels = old('channels');
    if (is_null($channels)) {
        $channels = ($setting->relationLoaded('contactChannels') ? $setting->contactChannels : $setting->contactChannels()->orderBy('position')->get())
            ->map(fn($c) => [
                'id'         => $c->id,
                'position'   => $c->position,
                'label'      => $c->label,
                'email'      => $c->email,
                'phone'      => method_exists($c, 'getAttribute') && $c->phone_formatted ? $c->phone_formatted : $c->phone,
                'address_ar' => $c->address_ar,
                'address_en' => $c->address_en,
            ])->toArray();
    }
    for ($k = count($channels); $k < 2; $k++) {
        $channels[] = ['id'=>null,'position'=>$k+1,'label'=>'','email'=>'','phone'=>'','address_ar'=>'','address_en'=>''];
    }
@endphp
@extends('layouts.admin')
@section('title', __('admin.settings.title'))

@section('content')
    <div class="container-fluid p-0">
        <form method="POST" action="{{ route('admin.site-settings.update', $setting->id) }}">
            @csrf
            @method('PUT')

            <x-admin.card>
                {{-- Header with tabs --}}
                <div class="card-header card-header-form border-0">
                    <div class="d-flex justify-content-between align-items-center w-100 flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-icon-wrap">
                                <i class="bi bi-gear"></i>
                            </div>
                            <h5 class="mb-0 fw-bold" style="font-size: 1.05rem; color: #24364A;">{{ __('admin.settings.title') }}</h5>
                        </div>
                        <div class="news-tabs" id="settingsTabs" role="tablist">
                            <button class="news-tab active" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer-content" type="button" role="tab" aria-selected="true">
                                <i class="bi bi-info-circle"></i> معلومات الفوتر
                            </button>
                            <button class="news-tab" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-content" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-envelope"></i> تواصل معنا
                            </button>
                            <button class="news-tab" id="channels-tab" data-bs-toggle="tab" data-bs-target="#channels-content" type="button" role="tab" aria-selected="false">
                                <i class="bi bi-telephone"></i> قنوات التواصل
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="tab-content" id="settingsTabsContent">
                        {{-- Footer Information Tab --}}
                        <div class="tab-pane fade show active p-3 p-md-4" id="footer-content" role="tabpanel" aria-labelledby="footer-tab">
                            <div class="row g-4">
                                {{-- Footer Title --}}
                                <div class="col-12">
                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                        <i class="bi bi-type-h1"></i> عنوان الفوتر
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">ع</span>
                                        {{ __('admin.settings.footer_title') }} - {{ __('admin.labels.arabic') }}
                                    </label>
                                    <input type="text" name="footer_title_ar" class="form-control rounded-3 border-0 bg-light @error('footer_title_ar') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('footer_title_ar', $setting->footer_title_ar) }}" placeholder="مثال: كهرباء غزة">
                                    @error('footer_title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem;">EN</span>
                                        {{ __('admin.settings.footer_title') }} - {{ __('admin.labels.english') }}
                                    </label>
                                    <input type="text" name="footer_title_en" class="form-control rounded-3 border-0 bg-light @error('footer_title_en') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('footer_title_en', $setting->footer_title_en) }}" placeholder="e.g: Gaza Electricity">
                                    @error('footer_title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Logo Path --}}
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                        <i class="bi bi-image"></i> مسار الشعار
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">ع</span>
                                        {{ __('admin.settings.logo_white_path') }} - {{ __('admin.labels.arabic') }}
                                    </label>
                                    <input type="text" name="logo_white_path_ar" class="form-control rounded-3 border-0 bg-light @error('logo_white_path_ar') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('logo_white_path_ar', $setting->logo_white_path_ar) }}" placeholder="assets/site/images/logo-white-ar.png">
                                    @error('logo_white_path_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>{{ __('admin.settings.logo_white_path_example') }}</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem;">EN</span>
                                        {{ __('admin.settings.logo_white_path') }} - {{ __('admin.labels.english') }}
                                    </label>
                                    <input type="text" name="logo_white_path_en" class="form-control rounded-3 border-0 bg-light @error('logo_white_path_en') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('logo_white_path_en', $setting->logo_white_path_en) }}" placeholder="assets/site/images/logo-white-en.png">
                                    @error('logo_white_path_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1"><i class="bi bi-info-circle me-1"></i>{{ __('admin.settings.logo_white_path_example') }}</small>
                                </div>

                                {{-- Copyright --}}
                                <div class="col-12 mt-4">
                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                        <i class="bi bi-copyright"></i> حقوق النشر
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">ع</span>
                                        حقوق النشر - {{ __('admin.labels.arabic') }}
                                    </label>
                                    <textarea name="copyright_ar" class="form-control rounded-3 border-0 bg-light @error('copyright_ar') is-invalid @enderror" rows="3" placeholder="مثال: © 2025 شركة كهرباء غزة - جميع الحقوق محفوظة">{{ old('copyright_ar', $setting->copyright_ar) }}</textarea>
                                    @error('copyright_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem;">EN</span>
                                        حقوق النشر - {{ __('admin.labels.english') }}
                                    </label>
                                    <textarea name="copyright_en" class="form-control rounded-3 border-0 bg-light @error('copyright_en') is-invalid @enderror" rows="3" placeholder="e.g: © 2025 Gaza Electricity Company - All Rights Reserved">{{ old('copyright_en', $setting->copyright_en) }}</textarea>
                                    @error('copyright_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Contact Us Tab --}}
                        <div class="tab-pane fade p-3 p-md-4" id="contact-content" role="tabpanel" aria-labelledby="contact-tab">
                            <div class="row g-4">
                                <div class="col-12">
                                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                                        <i class="bi bi-envelope-heart"></i> عنوان قسم التواصل
                                    </h6>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 0.7rem;">ع</span>
                                        {{ __('admin.settings.contact_us') }} - {{ __('admin.labels.arabic') }}
                                    </label>
                                    <input type="text" name="contact_us_title_ar" class="form-control rounded-3 border-0 bg-light @error('contact_us_title_ar') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('contact_us_title_ar', $setting->contact_us_title_ar) }}" placeholder="مثال: اتصل بنا">
                                    @error('contact_us_title_ar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1" style="font-size: 0.7rem;">EN</span>
                                        {{ __('admin.settings.contact_us') }} - {{ __('admin.labels.english') }}
                                    </label>
                                    <input type="text" name="contact_us_title_en" class="form-control rounded-3 border-0 bg-light @error('contact_us_title_en') is-invalid @enderror"
                                           style="height: 45px;"
                                           value="{{ old('contact_us_title_en', $setting->contact_us_title_en) }}" placeholder="e.g: Contact Us">
                                    @error('contact_us_title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Contact Channels Tab --}}
                        <div class="tab-pane fade p-3 p-md-4" id="channels-content" role="tabpanel" aria-labelledby="channels-tab">
                            <div class="alert alert-info border-0 rounded-4 mb-4 d-flex align-items-center gap-2">
                                <i class="bi bi-info-circle fs-5"></i>
                                <div>
                                    <strong>ملاحظة:</strong> يمكن إضافة قناتين كحد أقصى. كل قناة تحتوي على معلومات الاتصال الكاملة.
                                </div>
                            </div>
                            
                            <div class="row g-4">
                                @for($i = 0; $i < 2; $i++)
                                    <div class="col-12">
                                        <div class="border border-2 border-primary-subtle rounded-4 p-4 bg-gradient-light mb-3 position-relative" style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.25rem; font-weight: bold;">
                                                        {{ $i + 1 }}
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 fw-bold text-dark">قناة التواصل {{ $i + 1 }}</h6>
                                                        <small class="text-muted">معلومات الاتصال الكاملة</small>
                                                    </div>
                                                </div>
                                                <span class="badge bg-primary rounded-pill px-3 py-2">
                                                    <i class="bi bi-sort-numeric-up me-1"></i>
                                                    {{ __('admin.settings.position') }}: {{ $channels[$i]['position'] ?? ($i + 1) }}
                                                </span>
                                            </div>

                                            <input type="hidden" name="channels[{{ $i }}][id]" value="{{ $channels[$i]['id'] ?? '' }}">
                                            <input type="hidden" name="channels[{{ $i }}][position]" value="{{ $channels[$i]['position'] ?? ($i + 1) }}">

                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                                        <i class="bi bi-tag-fill text-primary"></i>
                                                        {{ __('admin.settings.label') }}
                                                    </label>
                                                    <input type="text" name="channels[{{ $i }}][label]" class="form-control rounded-3 border-0 bg-white shadow-sm"
                                                           style="height: 45px;"
                                                           value="{{ old("channels.$i.label", $channels[$i]['label'] ?? '') }}" placeholder="مثال: المقر الرئيسي">
                                                    @error("channels.$i.label")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                                        <i class="bi bi-envelope-fill text-primary"></i>
                                                        {{ __('admin.settings.email') }}
                                                    </label>
                                                    <input type="email" name="channels[{{ $i }}][email]" class="form-control rounded-3 border-0 bg-white shadow-sm"
                                                           style="height: 45px;"
                                                           value="{{ old("channels.$i.email", $channels[$i]['email'] ?? '') }}" placeholder="info@example.com">
                                                    @error("channels.$i.email")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-4">
                                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                                        <i class="bi bi-telephone-fill text-primary"></i>
                                                        {{ __('admin.settings.phone') }}
                                                    </label>
                                                    <input type="text" name="channels[{{ $i }}][phone]" class="form-control rounded-3 border-0 bg-white shadow-sm tel-input"
                                                           style="height: 45px;"
                                                           inputmode="tel" placeholder="+970 59X XXX XXX"
                                                           value="{{ old("channels.$i.phone", $channels[$i]['phone'] ?? '') }}">
                                                    @error("channels.$i.phone")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1 me-1" style="font-size: 0.7rem;">ع</span>
                                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                                        {{ __('admin.settings.address') }} - {{ __('admin.labels.arabic') }}
                                                    </label>
                                                    <input type="text" name="channels[{{ $i }}][address_ar]" class="form-control rounded-3 border-0 bg-white shadow-sm"
                                                           style="height: 45px;"
                                                           value="{{ old("channels.$i.address_ar", $channels[$i]['address_ar'] ?? '') }}" placeholder="العنوان بالعربية">
                                                    @error("channels.$i.address_ar")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1">
                                                        <span class="badge bg-info-subtle text-info rounded-pill px-2 py-1 me-1" style="font-size: 0.7rem;">EN</span>
                                                        <i class="bi bi-geo-alt-fill text-primary"></i>
                                                        {{ __('admin.settings.address') }} - {{ __('admin.labels.english') }}
                                                    </label>
                                                    <input type="text" name="channels[{{ $i }}][address_en]" class="form-control rounded-3 border-0 bg-white shadow-sm"
                                                           style="height: 45px;"
                                                           value="{{ old("channels.$i.address_en", $channels[$i]['address_en'] ?? '') }}" placeholder="English Address">
                                                    @error("channels.$i.address_en")
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="d-flex gap-2 justify-content-end p-3" style="border-top: 1px solid #E6ECF2;">
                    <a href="{{ route('admin.dashboard') }}" class="form-btn-back" style="text-decoration: none;">
                        <i class="bi bi-x-circle me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold d-flex align-items-center gap-2" style="font-size: 0.85rem;">
                        <i class="bi bi-check-circle"></i>
                        {{ __('admin.settings.save') }}
                    </button>
                </div>
            </x-admin.card>
        </form>
    </div>

    @push('styles')
    <style>
        .border-primary-subtle {
            border-color: #E6ECF2 !important;
        }
    </style>
    @endpush
@endsection

@push('scripts')
<script>
    // تنسيق رقم الهاتف أثناء الكتابة
    document.querySelectorAll('input.tel-input').forEach(function (el) {
        el.addEventListener('input', function () {
            const before = el.value;
            let v = el.value.replace(/[^\d+]/g, '');
            if (v[0] !== '+') v = v.replace(/\+/g, '');

            // نمط فلسطين: +970
            if (v.startsWith('+970')) {
                const digits = v.replace(/\D/g, '');
                let out = '+970';
                const rest = digits.slice(4);
                if (rest.length > 0) out += ' ' + rest.slice(0, 2);
                if (rest.length > 2) out += rest[2];
                if (rest.length > 3) out = out.replace(/(\+970 \d{3})(\d{1,3})/, '$1 $2');
                if (rest.length > 6) out = out.replace(/(\+970 \d{3} \d{3})(\d{1,3})/, '$1 $2');
                el.value = out;
            } else {
                // نمط عام: +CCC XXX XXX XXX
                const d = v.replace(/\D/g, '');
                if (v.startsWith('+') && d.length > 0) {
                    let out = '+' + d.slice(0, 3);
                    let rest = d.slice(3);
                    while (rest.length > 0) {
                        out += ' ' + rest.slice(0, 3);
                        rest = rest.slice(3);
                    }
                    el.value = out.trim();
                } else {
                    el.value = d;
                }
            }

            if (el.value !== before) {
                el.selectionStart = el.selectionEnd = el.value.length;
            }
        });
    });
</script>
@endpush
