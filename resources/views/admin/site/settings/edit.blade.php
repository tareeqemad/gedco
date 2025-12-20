@extends('layouts.admin')
@section('title', __('admin.settings.title'))

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">{{ __('admin.settings.title') }}</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">{{ __('admin.menu.site_settings') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if (session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.site-settings.update', $setting->id) }}">
                @csrf
                @method('PUT')

                {{-- ========== Footer Information Section ========== --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-info-circle me-2"></i>
                            معلومات الفوتر
                        </h6>
                        <small class="opacity-75">الحقول العربية والإنجليزية جنباً إلى جنب</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            {{-- Footer Title --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-translate text-primary me-1"></i>
                                    {{ __('admin.settings.footer_title') }} - {{ __('admin.labels.arabic') }}
                                </label>
                                <input type="text" name="footer_title_ar" class="form-control @error('footer_title_ar') is-invalid @enderror"
                                       value="{{ old('footer_title_ar', $setting->footer_title_ar) }}" placeholder="مثال: كهرباء غزة">
                                @error('footer_title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-globe text-primary me-1"></i>
                                    {{ __('admin.settings.footer_title') }} - {{ __('admin.labels.english') }}
                                </label>
                                <input type="text" name="footer_title_en" class="form-control @error('footer_title_en') is-invalid @enderror"
                                       value="{{ old('footer_title_en', $setting->footer_title_en) }}" placeholder="e.g: Gaza Electricity">
                                @error('footer_title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Logo Path --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-translate text-primary me-1"></i>
                                    {{ __('admin.settings.logo_white_path') }} - {{ __('admin.labels.arabic') }}
                                </label>
                                <input type="text" name="logo_white_path_ar" class="form-control @error('logo_white_path_ar') is-invalid @enderror"
                                       value="{{ old('logo_white_path_ar', $setting->logo_white_path_ar) }}" placeholder="assets/site/images/logo-white-ar.png">
                                @error('logo_white_path_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">{{ __('admin.settings.logo_white_path_example') }}</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-globe text-primary me-1"></i>
                                    {{ __('admin.settings.logo_white_path') }} - {{ __('admin.labels.english') }}
                                </label>
                                <input type="text" name="logo_white_path_en" class="form-control @error('logo_white_path_en') is-invalid @enderror"
                                       value="{{ old('logo_white_path_en', $setting->logo_white_path_en) }}" placeholder="assets/site/images/logo-white-en.png">
                                @error('logo_white_path_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">{{ __('admin.settings.logo_white_path_example') }}</small>
                            </div>

                            {{-- Copyright --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-translate text-primary me-1"></i>
                                    حقوق النشر - {{ __('admin.labels.arabic') }}
                                </label>
                                <textarea name="copyright_ar" class="form-control @error('copyright_ar') is-invalid @enderror" rows="2" placeholder="مثال: © 2025 شركة كهرباء غزة - جميع الحقوق محفوظة">{{ old('copyright_ar', $setting->copyright_ar) }}</textarea>
                                @error('copyright_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-globe text-primary me-1"></i>
                                    حقوق النشر - {{ __('admin.labels.english') }}
                                </label>
                                <textarea name="copyright_en" class="form-control @error('copyright_en') is-invalid @enderror" rows="2" placeholder="e.g: © 2025 Gaza Electricity Company - All Rights Reserved">{{ old('copyright_en', $setting->copyright_en) }}</textarea>
                                @error('copyright_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== Contact Us Section ========== --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-envelope me-2"></i>
                            {{ __('admin.settings.contact_us') }}
                        </h6>
                        <small class="opacity-75">الحقول العربية والإنجليزية جنباً إلى جنب</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-translate text-primary me-1"></i>
                                    {{ __('admin.settings.contact_us') }} - {{ __('admin.labels.arabic') }}
                                </label>
                                <input type="text" name="contact_us_title_ar" class="form-control @error('contact_us_title_ar') is-invalid @enderror"
                                       value="{{ old('contact_us_title_ar', $setting->contact_us_title_ar) }}" placeholder="مثال: اتصل بنا">
                                @error('contact_us_title_ar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-globe text-primary me-1"></i>
                                    {{ __('admin.settings.contact_us') }} - {{ __('admin.labels.english') }}
                                </label>
                                <input type="text" name="contact_us_title_en" class="form-control @error('contact_us_title_en') is-invalid @enderror"
                                       value="{{ old('contact_us_title_en', $setting->contact_us_title_en) }}" placeholder="e.g: Contact Us">
                                @error('contact_us_title_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ========== Contact Channels Section ========== --}}
                @php
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

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header text-white" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-telephone me-2"></i>
                            قنوات التواصل
                        </h6>
                        <small class="opacity-75">يمكن إضافة قناتين كحد أقصى</small>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            @for($i = 0; $i < 2; $i++)
                                <div class="col-12">
                                    <div class="border rounded p-4 bg-light mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="bi bi-hash me-1"></i>
                                                {{ __('admin.settings.channel_number') }}{{ $i + 1 }}
                                            </h6>
                                            <span class="badge bg-primary">{{ __('admin.settings.position') }}: {{ $channels[$i]['position'] ?? ($i + 1) }}</span>
                                        </div>

                                        <input type="hidden" name="channels[{{ $i }}][id]" value="{{ $channels[$i]['id'] ?? '' }}">
                                        <input type="hidden" name="channels[{{ $i }}][position]" value="{{ $channels[$i]['position'] ?? ($i + 1) }}">

                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">{{ __('admin.settings.label') }}</label>
                                                <input type="text" name="channels[{{ $i }}][label]" class="form-control"
                                                       value="{{ old("channels.$i.label", $channels[$i]['label'] ?? '') }}" placeholder="مثال: المقر الرئيسي">
                                                @error("channels.$i.label")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">{{ __('admin.settings.email') }}</label>
                                                <input type="email" name="channels[{{ $i }}][email]" class="form-control"
                                                       value="{{ old("channels.$i.email", $channels[$i]['email'] ?? '') }}" placeholder="info@example.com">
                                                @error("channels.$i.email")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold small">{{ __('admin.settings.phone') }}</label>
                                                <input type="text" name="channels[{{ $i }}][phone]" class="form-control tel-input"
                                                       inputmode="tel" placeholder="+970 59X XXX XXX"
                                                       value="{{ old("channels.$i.phone", $channels[$i]['phone'] ?? '') }}">
                                                @error("channels.$i.phone")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">
                                                    <i class="bi bi-translate text-primary me-1"></i>
                                                    {{ __('admin.settings.address') }} - {{ __('admin.labels.arabic') }}
                                                </label>
                                                <input type="text" name="channels[{{ $i }}][address_ar]" class="form-control"
                                                       value="{{ old("channels.$i.address_ar", $channels[$i]['address_ar'] ?? '') }}" placeholder="العنوان بالعربية">
                                                @error("channels.$i.address_ar")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">
                                                    <i class="bi bi-globe text-primary me-1"></i>
                                                    {{ __('admin.settings.address') }} - {{ __('admin.labels.english') }}
                                                </label>
                                                <input type="text" name="channels[{{ $i }}][address_en]" class="form-control"
                                                       value="{{ old("channels.$i.address_en", $channels[$i]['address_en'] ?? '') }}" placeholder="English Address">
                                                @error("channels.$i.address_en")
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <small class="text-muted d-block mt-3">
                                            <i class="bi bi-info-circle me-1"></i>
                                            {{ __('admin.settings.channel_help') }}
                                        </small>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="d-flex gap-2 justify-content-end mb-4">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-light px-4">
                        <i class="bi bi-x-circle me-2"></i>
                        إلغاء
                    </a>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ __('admin.settings.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
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
