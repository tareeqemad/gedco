@php
    $edit = isset($slider);
    $bullets = [];
    if (old('bullets')) {
        $bullets = old('bullets');
    } elseif ($edit && !empty($slider->bullets)) {
        $bullets = is_array($slider->bullets) ? $slider->bullets : (json_decode($slider->bullets, true) ?? []);
    }
    $bullets = is_array($bullets) ? array_values(array_filter($bullets)) : [];
    $bullets = array_pad($bullets, 4, '');
    $currentDirection = session('direction', 'rtl');
    $defaultLang = $currentDirection === 'rtl' ? 'ar' : 'en';
@endphp

<x-admin.card>
    <x-admin.card-header-form
        icon="bi-images"
        :title="$edit ? __('admin.slider.edit_slide') : __('admin.slider.add_slide')"
        :back-route="route('admin.sliders.index')"
        :back-label="__('admin.slider.slider_items')">
        <x-slot:actions>
            <span class="badge bg-light text-dark rounded-pill px-3 py-1">
                <i class="bi bi-globe me-1"></i>{{ $defaultLang === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
            </span>
        </x-slot:actions>
    </x-admin.card-header-form>

    <div class="card-body p-4">
        {{-- المعلومات الأساسية --}}
        <h6 class="section-title">
            <i class="bi bi-info-circle"></i>
            المعلومات الأساسية
        </h6>
        <div class="row g-3 mb-4">
            <div class="col-md-8">
                <label class="form-label fw-semibold">
                    {{ __('admin.slider.form_title') }} <span class="text-danger">*</span>
                </label>
                <input type="text" name="title"
                       class="form-control rounded-3 @error('title') is-invalid @enderror"
                       value="{{ old('title', $slider->title ?? '') }}" required>
                @error('title')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">{{ __('admin.slider.form_order') }}</label>
                @if($edit)
                    <input type="number" name="sort_order"
                           class="form-control rounded-3 @error('sort_order') is-invalid @enderror"
                           value="{{ old('sort_order', $slider->sort_order ?? 0) }}" min="0">
                    @error('sort_order') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                @else
                    <input type="number" class="form-control rounded-3" value="{{ $nextOrder ?? 0 }}" readonly>
                    <small class="text-muted mt-1 d-block">{{ __('admin.slider.form_order_auto') }}</small>
                @endif
            </div>
            <div class="col-md-2">
                <label class="form-label fw-semibold">{{ __('admin.slider.form_status') }}</label>
                <div class="form-check form-switch mt-2">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                           @checked(old('is_active', $slider->is_active ?? true))
                           style="width: 2.5rem; height: 1.25rem; cursor: pointer;">
                    <label class="form-check-label fw-semibold d-block" for="is_active" style="cursor: pointer;">
                        {{ old('is_active', $slider->is_active ?? true) ? __('admin.slider.form_active') : __('admin.slider.form_inactive') }}
                    </label>
                </div>
            </div>
        </div>

        <input type="hidden" name="language" value="{{ old('language', $slider->language ?? $defaultLang) }}">

        {{-- الوصف الفرعي --}}
        <h6 class="section-title">
            <i class="bi bi-text-paragraph"></i>
            {{ __('admin.slider.form_subtitle') }}
        </h6>
        <div class="mb-4">
            <textarea name="subtitle" class="form-control rounded-3 @error('subtitle') is-invalid @enderror"
                      rows="3" placeholder="{{ __('admin.slider.form_subtitle_placeholder') }}">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
            @error('subtitle') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        {{-- صورة الخلفية --}}
        <h6 class="section-title">
            <i class="bi bi-image"></i>
            {{ __('admin.slider.form_bg_image') }} @if(!$edit)<span class="text-danger">*</span>@endif
        </h6>
        <div class="mb-4">
            @if($edit && !empty($slider->bg_image))
                <div class="mb-3 p-3 rounded-3 border d-flex align-items-start gap-3">
                    <img src="{{ $slider->bg_image_url ?? '' }}" alt="الصورة الحالية"
                         class="rounded-3 shadow-sm" style="max-width: 200px; height: auto; cursor: pointer;"
                         onclick="window.open(this.src, '_blank')">
                    <div>
                        <p class="mb-2 fw-semibold" style="color: #24364A;">الصورة الحالية</p>
                        <button type="submit" form="remove-image-{{ $slider->id }}"
                                class="btn btn-sm btn-outline-danger rounded-3"
                                onclick="return confirm('{{ __('admin.slider.form_bg_image_remove_confirm') }}')">
                            <i class="bi bi-trash me-1"></i>إزالة
                        </button>
                    </div>
                </div>
            @endif

            <input type="file" name="bg_image" id="bg_image_input" accept=".webp,.jpg,.jpeg,.png"
                   class="form-control rounded-3 @error('bg_image') is-invalid @enderror"
                   {{ !$edit ? 'required' : '' }}>
            <small class="text-muted mt-1 d-block">
                <i class="bi bi-info-circle me-1"></i>{{ __('admin.slider.form_bg_image_allowed') }}
            </small>
            @error('bg_image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

            <div id="image-preview-container" class="mt-3 d-none">
                <img id="image-preview" src="#" alt="معاينة" class="rounded-3 shadow-sm" style="max-width: 100%; max-height: 250px;">
            </div>
        </div>

        {{-- النقاط البارزة --}}
        <h6 class="section-title">
            <i class="bi bi-list-ul"></i>
            {{ __('admin.slider.form_bullets_title') }}
        </h6>
        <div class="row g-3 mb-4">
            @for($i = 0; $i < 4; $i++)
                <div class="col-md-6">
                    <input type="text" name="bullets[]" class="form-control rounded-3"
                           value="{{ $bullets[$i] ?? '' }}"
                           placeholder="{{ __('admin.slider.form_bullets_placeholder') }}">
                </div>
            @endfor
        </div>

        {{-- أزرار الإجراءات --}}
        <div class="d-flex justify-content-end gap-3 pt-3 border-top">
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-cancel">
                <i class="bi bi-x-circle me-1"></i>إلغاء
            </a>
            <button type="submit" class="btn btn-save">
                <i class="bi bi-check-circle me-1"></i>{{ __('admin.common.form_save') }}
            </button>
        </div>
    </div>
</x-admin.card>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('bg_image_input');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');

        input?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) { previewContainer?.classList.add('d-none'); return; }

            const validTypes = ['image/webp', 'image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('{{ __('admin.slider.invalid_image_format') }}');
                input.value = '';
                previewContainer?.classList.add('d-none');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('{{ __('admin.slider.image_too_large') }}');
                input.value = '';
                previewContainer?.classList.add('d-none');
                return;
            }

            const reader = new FileReader();
            reader.onload = e => { previewImage.src = e.target.result; previewContainer?.classList.remove('d-none'); };
            reader.readAsDataURL(file);
        });

        const statusSwitch = document.getElementById('is_active');
        statusSwitch?.addEventListener('change', function() {
            const label = this.nextElementSibling;
            if (label) label.textContent = this.checked ? '{{ __('admin.slider.form_active') }}' : '{{ __('admin.slider.form_inactive') }}';
        });

        const form = document.querySelector('form');
        const submitBtn = form?.querySelector('[type="submit"]');
        form?.addEventListener('submit', function(e) {
            if (!form.checkValidity()) { e.preventDefault(); return; }
            if (submitBtn) { submitBtn.disabled = true; submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>جاري الحفظ...'; }
        });
    });
</script>
@endpush
