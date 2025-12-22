@php
    $edit = isset($slider);
    $bullets = [];

    if (old('bullets')) {
        $bullets = old('bullets');
    } elseif ($edit && !empty($slider->bullets)) {
        $bullets = is_array($slider->bullets)
            ? $slider->bullets
            : (json_decode($slider->bullets, true) ?? []);
    }

    $bullets = is_array($bullets) ? array_values(array_filter($bullets)) : [];
    $bullets = array_pad($bullets, 4, '');

    $currentDirection = session('direction', 'rtl');
    $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
@endphp

<div class="row g-4">
    <!-- جميع الحقول -->
    <div class="col-12">
        <!-- العنوان والترتيب والحالة -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-light border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle text-primary"></i>
                    المعلومات الأساسية
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold text-dark mb-2">
                            {{ __('admin.slider.form_title') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               name="title" 
                               class="form-control rounded-3 border-0 bg-light @error('title') is-invalid @enderror"
                               value="{{ old('title', $slider->title ?? '') }}" 
                               required
                               style="height: 45px;">
                        @error('title')
                            <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark mb-2">
                            {{ __('admin.slider.form_order') }}
                        </label>
                        @if($edit)
                            <input type="number" 
                                   name="sort_order" 
                                   class="form-control rounded-3 border-0 bg-light @error('sort_order') is-invalid @enderror"
                                   value="{{ old('sort_order', $slider->sort_order ?? 0) }}" 
                                   min="0"
                                   style="height: 45px;">
                            @error('sort_order')
                                <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                            @enderror
                        @else
                            <input type="number" 
                                   class="form-control rounded-3 border-0 bg-light form-control-readonly" 
                                   value="{{ $nextOrder ?? 0 }}" 
                                   readonly
                                   style="height: 45px; cursor: not-allowed !important;">
                            <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">{{ __('admin.slider.form_order_auto') }}</small>
                        @endif
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-semibold text-dark mb-2">
                            {{ __('admin.slider.form_status') }}
                        </label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   value="1"
                                   id="is_active" 
                                   @checked(old('is_active', $slider->is_active ?? true))
                                   style="width: 3rem; height: 1.5rem; cursor: pointer;">
                            <label class="form-check-label fw-semibold mt-1 d-block" for="is_active" style="cursor: pointer;">
                                {{ old('is_active', $slider->is_active ?? true) ? __('admin.slider.form_active') : __('admin.slider.form_inactive') }}
                            </label>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="language" value="{{ old('language', $slider->language ?? $defaultLang) }}">
                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex align-items-center gap-2">
                        <label class="form-label fw-semibold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="bi bi-translate text-primary"></i>{{ __('admin.labels.language') }}:
                        </label>
                        <span class="badge bg-primary rounded-pill px-3 py-1">
                            {{ $defaultLang === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                        </span>
                        <small class="text-muted">({{ __('admin.common.language_default_selected') }})</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- الوصف الفرعي -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-light border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-text-paragraph text-primary"></i>
                    {{ __('admin.slider.form_subtitle') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <textarea name="subtitle" 
                          class="form-control rounded-3 border-0 bg-light @error('subtitle') is-invalid @enderror" 
                          rows="4"
                          placeholder="{{ __('admin.slider.form_subtitle_placeholder') }}">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
                @error('subtitle')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- صورة الخلفية -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-light border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-image text-primary"></i>
                    {{ __('admin.slider.form_bg_image') }} <span class="text-danger">*</span>
                </h6>
            </div>
            <div class="card-body p-4">
                @if($edit && !empty($slider->bg_image))
                    <div class="mb-4 p-4 bg-light-subtle rounded-4 border border-2 border-primary-subtle">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="form-label fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                <i class="bi bi-image text-primary"></i>
                                الصورة الحالية
                            </label>
                            <button type="submit"
                                    form="remove-image-{{ $slider->id }}"
                                    class="btn btn-sm btn-danger rounded-3 shadow-sm"
                                    title="{{ __('admin.slider.form_bg_image_remove') }}"
                                    onclick="return confirm('{{ __('admin.slider.form_bg_image_remove_confirm') }}')">
                                <i class="bi bi-trash me-1"></i>
                                إزالة الصورة
                            </button>
                        </div>
                        <div class="text-center">
                            <img src="{{ $slider->bg_image_url ?? '' }}"
                                 alt="الصورة الحالية"
                                 id="current-image"
                                 class="rounded-4 shadow-lg"
                                 style="max-width: 100%; height: auto; max-height: 400px; border: 3px solid #0066cc; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')"
                                 title="انقر لعرض الصورة بحجم كامل">
                        </div>
                    </div>
                @endif

                <label class="form-label fw-semibold text-dark mb-2">رفع صورة جديدة</label>
                <input type="file"
                       name="bg_image"
                       id="bg_image_input"
                       accept=".webp,.jpg,.jpeg,.png"
                       class="form-control rounded-3 border-0 bg-light @error('bg_image') is-invalid @enderror"
                       {{ !$edit ? 'required' : '' }}
                       style="height: 45px;">
                <small class="text-muted d-block mt-2">
                    <i class="bi bi-info-circle me-1"></i>
                    {{ __('admin.slider.form_bg_image_allowed') }}
                </small>
                @error('bg_image')
                    <div class="invalid-feedback d-block mt-1">{{ $message }}</div>
                @enderror

                <div id="image-preview-container" class="mt-3" style="display: none;">
                    <label class="form-label fw-semibold text-success mb-2 d-flex align-items-center gap-2">
                        <i class="bi bi-eye"></i>{{ __('admin.slider.form_bg_image_preview') }}
                    </label>
                    <img id="image-preview"
                         src="#"
                         alt="معاينة الصورة"
                         class="rounded-3 shadow-sm"
                         style="max-width: 100%; height: auto; max-height: 300px; border: 2px solid #e9ecef;">
                </div>
            </div>
        </div>

        <!-- النقاط البارزة -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-light border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="bi bi-list-ul text-primary"></i>
                    {{ __('admin.slider.form_bullets_title') }}
                </h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    @for($i = 0; $i < 4; $i++)
                        <div class="col-md-6">
                            <input type="text" 
                                   name="bullets[]" 
                                   class="form-control rounded-3 border-0 bg-light"
                                   value="{{ $bullets[$i] ?? '' }}"
                                   placeholder="{{ __('admin.slider.form_bullets_placeholder') }}"
                                   style="height: 45px;">
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الأزرار -->
<div class="card border-0 shadow-sm rounded-4 bg-white mt-4">
    <div class="card-body p-4">
        <div class="d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary rounded-3 px-4 shadow-sm">
                إلغاء
            </a>
            <button type="submit" class="btn btn-primary rounded-3 px-4 shadow-sm d-flex align-items-center gap-2">
                <i class="bi bi-check-circle"></i>
                {{ __('admin.common.form_save') }}
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('bg_image_input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        if (!input || !previewContainer || !previewImage) return;

        input.addEventListener('change', function (e) {
            const file = e.target.files[0];

            if (!file) {
                previewContainer.style.display = 'none';
                return;
            }

            const validTypes = ['image/webp', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                if (window.adminNotifications) {
                    window.adminNotifications.error('{{ __('admin.slider.invalid_image_format') }}');
                } else {
                    alert('{{ __('admin.slider.invalid_image_format') }}');
                }
                input.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            const maxSize = 5 * 1024 * 1024;
            if (file.size > maxSize) {
                if (window.adminNotifications) {
                    window.adminNotifications.error('{{ __('admin.slider.image_too_large') }}');
                } else {
                    alert('{{ __('admin.slider.image_too_large') }}');
                }
                input.value = '';
                previewContainer.style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });

        const parentForm = input.closest('form');
        if (parentForm) {
            parentForm.addEventListener('submit', function () {
                setTimeout(() => previewContainer.style.display = 'none', 500);
            });
        }

        // تحديث نص الحالة عند تغيير التبديل
        const statusSwitch = document.getElementById('is_active');
        if (statusSwitch) {
            statusSwitch.addEventListener('change', function() {
                const label = this.nextElementSibling;
                if (label) {
                    label.textContent = this.checked ? '{{ __('admin.slider.form_active') }}' : '{{ __('admin.slider.form_inactive') }}';
                }
            });
        }

        // منع الإرسال المتكرر للفورم
        const form = document.querySelector('form');
        const submitButton = form?.querySelector('button[type="submit"]');
        
        if (form && submitButton) {
            form.addEventListener('submit', function(e) {
                // التحقق من صحة الفورم أولاً
                if (!form.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }

                // تعطيل الزر وإظهار loading
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>جاري الحفظ...';
                submitButton.classList.add('disabled');
            });
        }
    });
</script>
@endpush
