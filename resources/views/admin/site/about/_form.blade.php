<form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @if($method !== 'POST') @method($method) @endif

    @php
        $currentDirection = session('direction', 'rtl');
        $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
        $featuresCol1 = old('features_col1', isset($col1) ? implode("\n", (array)$col1) : '');
        $featuresCol2 = old('features_col2', isset($col2) ? implode("\n", (array)$col2) : '');
        $featuresCol1En = old('features_col1_en', isset($col1En) ? implode("\n", (array)$col1En) : '');
        $featuresCol2En = old('features_col2_en', isset($col2En) ? implode("\n", (array)$col2En) : '');
    @endphp

    {{-- اللغة - مخفية --}}
    <input type="hidden" name="language" value="{{ old('language', $model->language ?? $defaultLang) }}">

    {{-- Tabs Navigation --}}
    <ul class="nav nav-tabs nav-tabs-sm border-0 mb-4 px-3 py-1 rounded-3" id="aboutTabs" role="tablist" style="background: rgba(0, 102, 204, 0.1);">
        <li class="nav-item" role="presentation">
            <button class="nav-link active px-3 py-2 rounded-3 text-primary fw-semibold" id="arabic-tab" data-bs-toggle="tab" data-bs-target="#arabic-content" type="button" role="tab" style="background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(0, 102, 204, 0.3);">
                <i class="bi bi-translate me-2"></i>
                <span>{{ __('admin.labels.arabic') }}</span>
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link px-3 py-2 rounded-3 text-primary fw-semibold" id="english-tab" data-bs-toggle="tab" data-bs-target="#english-content" type="button" role="tab" style="background: transparent; border: 1px solid rgba(0, 102, 204, 0.3);">
                <i class="bi bi-globe me-2"></i>
                <span>{{ __('admin.labels.english') }}</span>
            </button>
        </li>
    </ul>

    {{-- Tabs Content --}}
    <div class="tab-content" id="aboutTabContent">
        
        {{-- ========== Arabic Content Tab ========== --}}
        <div class="tab-pane fade show active" id="arabic-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4 rounded-top-4">
                    <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                        <i class="bi bi-translate"></i>
                        {{ __('admin.labels.arabic') }} - {{ __('admin.about_us.content') }}
                    </h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    {{-- العنوان --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-heading text-primary"></i>
                            {{ __('admin.about_us.form_title') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="title" class="form-control rounded-3 border-0 bg-light @error('title') is-invalid @enderror"
                               value="{{ old('title', $model->title ?? '') }}" required
                               style="height: 45px; font-size: 1rem;">
                        @error('title') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- العنوان الفرعي --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-text-paragraph text-primary"></i>
                            {{ __('admin.about_us.form_subtitle') }}
                        </label>
                        <input type="text" name="subtitle" class="form-control rounded-3 border-0 bg-light @error('subtitle') is-invalid @enderror"
                               value="{{ old('subtitle', $model->subtitle ?? '') }}"
                               style="height: 45px; font-size: 1rem;">
                        @error('subtitle') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- الفقرة الأولى --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-text text-primary"></i>
                            {{ __('admin.about_us.form_paragraph1') }} <span class="text-danger">*</span>
                        </label>
                        <textarea name="paragraph1" class="form-control rounded-3 border-0 bg-light @error('paragraph1') is-invalid @enderror" rows="5" required style="font-size: 1rem;">{{ old('paragraph1', $model->paragraph1 ?? '') }}</textarea>
                        @error('paragraph1') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- الفقرة الثانية --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-text text-primary"></i>
                            {{ __('admin.about_us.form_paragraph2') }}
                        </label>
                        <textarea name="paragraph2" class="form-control rounded-3 border-0 bg-light @error('paragraph2') is-invalid @enderror" rows="4" style="font-size: 1rem;">{{ old('paragraph2', $model->paragraph2 ?? '') }}</textarea>
                        @error('paragraph2') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Features العربية --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-list-check text-primary"></i>
                            {{ __('admin.about_us.form_features') }} - {{ __('admin.labels.arabic') }}
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-2">{{ __('admin.about_us.form_features_col1') }}</label>
                                <textarea name="features_col1" class="form-control rounded-3 border-0 bg-light @error('features_col1') is-invalid @enderror" rows="6" 
                                          placeholder="{{ __('admin.about_us.form_features_placeholder') }}" style="font-size: 1rem;">{{ $featuresCol1 }}</textarea>
                                @error('features_col1') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-2">{{ __('admin.about_us.form_features_col2') }}</label>
                                <textarea name="features_col2" class="form-control rounded-3 border-0 bg-light @error('features_col2') is-invalid @enderror" rows="6"
                                          placeholder="{{ __('admin.about_us.form_features_placeholder') }}" style="font-size: 1rem;">{{ $featuresCol2 }}</textarea>
                                @error('features_col2') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الصورة العربية --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-image text-primary"></i>
                            {{ __('admin.about_us.form_image') }} - {{ __('admin.labels.arabic') }}
                        </label>
                        @php
                            $fallback = 'assets/site/images/content/c3.webp';
                            $currentImg = !empty($model?->image) 
                                ? (method_exists($model, 'getImageUrlAttribute') 
                                    ? $model->image_url 
                                    : build_image_url($model->image, $fallback))
                                : asset($fallback);
                        @endphp
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="position-relative d-inline-block w-100">
                                    <img id="about-live-img"
                                         src="{{ $currentImg ?: $fallback }}"
                                         alt="preview"
                                         class="img-fluid rounded shadow-sm border"
                                         style="max-height: 250px; width: 100%; object-fit: cover;">
                                    @if(!empty($model?->id) && !empty($model?->image))
                                        <button type="submit"
                                                form="remove-image-{{ $model->id }}"
                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 rounded-circle"
                                                title="{{ __('admin.common.remove_image') }}"
                                                onclick="return confirm('{{ __('admin.common.remove_image_confirm') }}')">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="image" id="about-image-input" accept=".jpg,.jpeg,.png,.webp"
                                       class="form-control rounded-3 border-0 bg-light @error('image') is-invalid @enderror"
                                       style="height: 45px; font-size: 1rem;">
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('admin.common.form_image_max_size') }}
                                </small>
                                @error('image') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== English Content Tab ========== --}}
        <div class="tab-pane fade" id="english-content" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4 rounded-top-4">
                    <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                        <i class="bi bi-globe"></i>
                        {{ __('admin.labels.english') }} - {{ __('admin.about_us.content') }}
                    </h6>
                </div>
                <div class="card-body p-4 p-md-5">
                    {{-- العنوان الإنجليزي --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-heading text-primary"></i>
                            {{ __('admin.about_us.form_title') }} - {{ __('admin.labels.english') }}
                        </label>
                        <input type="text" name="title_en" class="form-control rounded-3 border-0 bg-light @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en', $model->title_en ?? '') }}"
                               style="height: 45px; font-size: 1rem;">
                        @error('title_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- العنوان الفرعي الإنجليزي --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-text-paragraph text-primary"></i>
                            {{ __('admin.about_us.form_subtitle') }} - {{ __('admin.labels.english') }}
                        </label>
                        <input type="text" name="subtitle_en" class="form-control rounded-3 border-0 bg-light @error('subtitle_en') is-invalid @enderror"
                               value="{{ old('subtitle_en', $model->subtitle_en ?? '') }}"
                               style="height: 45px; font-size: 1rem;">
                        @error('subtitle_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- الفقرة الأولى الإنجليزية --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-text text-primary"></i>
                            {{ __('admin.about_us.form_paragraph1') }} - {{ __('admin.labels.english') }}
                        </label>
                        <textarea name="paragraph1_en" class="form-control rounded-3 border-0 bg-light @error('paragraph1_en') is-invalid @enderror" rows="5" style="font-size: 1rem;">{{ old('paragraph1_en', $model->paragraph1_en ?? '') }}</textarea>
                        @error('paragraph1_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- الفقرة الثانية الإنجليزية --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-file-text text-primary"></i>
                            {{ __('admin.about_us.form_paragraph2') }} - {{ __('admin.labels.english') }}
                        </label>
                        <textarea name="paragraph2_en" class="form-control rounded-3 border-0 bg-light @error('paragraph2_en') is-invalid @enderror" rows="4" style="font-size: 1rem;">{{ old('paragraph2_en', $model->paragraph2_en ?? '') }}</textarea>
                        @error('paragraph2_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                    </div>

                    {{-- Features الإنجليزية --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-list-check text-primary"></i>
                            {{ __('admin.about_us.form_features') }} - {{ __('admin.labels.english') }}
                        </label>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-2">{{ __('admin.about_us.form_features_col1') }}</label>
                                <textarea name="features_col1_en" class="form-control rounded-3 border-0 bg-light @error('features_col1_en') is-invalid @enderror" rows="6"
                                          placeholder="{{ __('admin.about_us.form_features_placeholder') }}" style="font-size: 1rem;">{{ $featuresCol1En }}</textarea>
                                @error('features_col1_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small text-muted mb-2">{{ __('admin.about_us.form_features_col2') }}</label>
                                <textarea name="features_col2_en" class="form-control rounded-3 border-0 bg-light @error('features_col2_en') is-invalid @enderror" rows="6"
                                          placeholder="{{ __('admin.about_us.form_features_placeholder') }}" style="font-size: 1rem;">{{ $featuresCol2En }}</textarea>
                                @error('features_col2_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- الصورة الإنجليزية --}}
                    <div class="mb-0">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-image text-primary"></i>
                            {{ __('admin.about_us.form_image') }} - {{ __('admin.labels.english') }}
                        </label>
                        @php
                            $fallbackEn = 'assets/site/images/content/c3.webp';
                            $currentImgEn = !empty($model?->image_en) 
                                ? build_image_url($model->image_en, $fallbackEn)
                                : asset($fallbackEn);
                        @endphp
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="position-relative d-inline-block w-100">
                                    <img id="about-live-img-en"
                                         src="{{ $currentImgEn ?: $fallbackEn }}"
                                         alt="preview"
                                         class="img-fluid rounded shadow-sm border"
                                         style="max-height: 250px; width: 100%; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <input type="file" name="image_en" id="about-image-input-en" accept=".jpg,.jpeg,.png,.webp"
                                       class="form-control rounded-3 border-0 bg-light @error('image_en') is-invalid @enderror"
                                       style="height: 45px; font-size: 1rem;">
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    {{ __('admin.common.form_image_max_size') }}
                                </small>
                                @error('image_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Form Actions --}}
    <div class="d-flex gap-3 justify-content-end pt-3 border-top mt-4">
        <a href="{{ route('admin.about.index') }}" class="btn btn-outline-secondary px-5 py-2 rounded-3" style="min-width: 150px; font-weight: 600;">
            <i class="bi bi-x-circle me-2"></i>{{ __('admin.common.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 shadow-sm" style="min-width: 150px; font-weight: 600;">
            <i class="bi bi-check-lg me-2"></i>{{ __('admin.common.form_save') }}
        </button>
    </div>
</form>

{{-- فورم إزالة الصورة: خارج الفورم الرئيسي (لازم تضيفه في صفحة edit تحت الجزئي) --}}
{{-- <form id="remove-image-{{ $model->id }}" action="{{ route('admin.about.remove-image', $model) }}" method="POST" class="d-none">
    @csrf
    @method('DELETE')
</form> --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('about-image-input');
            const preview = document.getElementById('about-live-img');
            if(!input || !preview) return;

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if(!file) return;

                const allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
                if(!allowed.includes(file.type)){
                    if (window.adminNotifications) {
                        window.adminNotifications.error('{{ __('admin.common.form_image_invalid') }}');
                    } else {
                        alert('{{ __('admin.common.form_image_invalid') }}');
                    }
                    input.value = '';
                    return;
                }
                const maxSize = 5 * 1024 * 1024; // 5MB
                if(file.size > maxSize){
                    if (window.adminNotifications) {
                        window.adminNotifications.error('{{ __('admin.common.form_image_too_large') }}');
                    } else {
                        alert('{{ __('admin.common.form_image_too_large') }}');
                    }
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = ev => preview.src = ev.target.result;
                reader.readAsDataURL(file);
            }, false);

            // Preview for English image
            const inputEn = document.getElementById('about-image-input-en');
            const previewEn = document.getElementById('about-live-img-en');
            if(inputEn && previewEn) {
                inputEn.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if(!file) return;

                    const allowed = ['image/jpeg','image/jpg','image/png','image/webp'];
                    if(!allowed.includes(file.type)){
                        if (window.adminNotifications) {
                            window.adminNotifications.error('{{ __('admin.common.form_image_invalid') }}');
                        } else {
                            alert('{{ __('admin.common.form_image_invalid') }}');
                        }
                        inputEn.value = '';
                        return;
                    }
                    const maxSize = 5 * 1024 * 1024; // 5MB
                    if(file.size > maxSize){
                        if (window.adminNotifications) {
                            window.adminNotifications.error('{{ __('admin.common.form_image_too_large') }}');
                        } else {
                            alert('{{ __('admin.common.form_image_too_large') }}');
                        }
                        inputEn.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = ev => previewEn.src = ev.target.result;
                    reader.readAsDataURL(file);
                }, false);
            }
        });
    </script>
@endpush
