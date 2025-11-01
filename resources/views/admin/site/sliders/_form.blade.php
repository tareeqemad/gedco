@php
    /** تأمين وتنظيم المتغيرات */
    $edit = isset($slider);
    $bullets = [];

    // 1) old() بعد فشل الفاليديشن
    if (old('bullets')) {
        $bullets = old('bullets');
    }
    // 2) أو من الموديل (JSON أو مصفوفة)
    elseif ($edit && !empty($slider->bullets)) {
        $bullets = is_array($slider->bullets)
            ? $slider->bullets
            : (json_decode($slider->bullets, true) ?? []);
    }

    // 3) تنظيف وضمان 4 حقول
    $bullets = is_array($bullets) ? array_values(array_filter($bullets)) : [];
    $bullets = array_pad($bullets, 4, '');
@endphp

<div class="row g-3">
    <!-- العنوان -->
    <div class="col-md-6">
        <label class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $slider->title ?? '') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <!-- الترتيب -->
    <div class="col-md-3">
        <label class="form-label fw-semibold">الترتيب</label>
        @if($edit)
            <input type="number" name="sort_order" class="form-control @error('sort_order') is-invalid @enderror"
                   value="{{ old('sort_order', $slider->sort_order ?? 0) }}" min="0">
            @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        @else
            <input type="number" class="form-control bg-light" value="{{ $nextOrder ?? 0 }}" readonly>
            <small class="text-muted">يُحدد تلقائيًا</small>
        @endif
    </div>

    <!-- الحالة -->
    <div class="col-md-3">
        <label class="form-label fw-semibold d-block">الحالة</label>
        <div class="form-check form-switch mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                   id="is_active" @checked(old('is_active', $slider->is_active ?? true))>
            <label class="form-check-label" for="is_active">
                {{ old('is_active', $slider->is_active ?? true) ? 'مفعّل' : 'معطّل' }}
            </label>
        </div>
    </div>
</div>

<!-- الوصف -->
<div class="mt-3">
    <label class="form-label fw-semibold">الوصف الفرعي</label>
    <textarea name="subtitle" class="form-control @error('subtitle') is-invalid @enderror" rows="3"
              placeholder="وصف مختصر يظهر أسفل العنوان...">{{ old('subtitle', $slider->subtitle ?? '') }}</textarea>
    @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<!-- زر العمل -->
{{--

<div class="row g-3 mt-3">
    <div class="col-md-4">
        <label class="form-label fw-semibold">نص الزر (اختياري)</label>
        <input type="text" name="button_text" class="form-control @error('button_text') is-invalid @enderror"
               value="{{ old('button_text', $slider->button_text ?? '') }}"
               placeholder="مثال: احجز الآن">
        @error('button_text')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8">
        <label class="form-label fw-semibold">رابط الزر (اختياري)</label>
        <input type="url" name="button_url" class="form-control @error('button_url') is-invalid @enderror"
               value="{{ old('button_url', $slider->button_url ?? '') }}"
               placeholder="{{ route('site.booking') }}">
        @error('button_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

--}}


<!-- === صورة الخلفية + المعاينة === -->
<div class="mt-4">
    <label class="form-label fw-semibold d-block">صورة الخلفية <span class="text-danger">*</span></label>

    <!-- الصورة الحالية (للتعديل فقط) -->
    @if($edit && !empty($slider?->bg_image))
        <div class="mb-3">
            <div class="position-relative d-inline-block">
                <img src="{{ $slider->bg_image_url ?? '' }}"
                     alt="الصورة الحالية"
                     id="current-image"
                     class="rounded shadow-sm"
                     style="max-width: 280px; height: auto; border: 1px solid #ddd;">

                <!-- زر إخفاء الصورة: يسبمت فورم خارجي بـ id=remove-image-{{ $slider->id }} -->
                <button type="submit"
                        form="remove-image-{{ $slider->id }}"
                        class="btn btn-sm btn-outline-danger border-0 bg-transparent p-1 position-absolute top-0 end-0 mt-1 me-1"
                        title="إخفاء الصورة"
                        aria-label="إخفاء الصورة"
                        onclick="return confirm('هل تريد إخفاء الصورة؟')">
                    X
                </button>
            </div>
        </div>
    @endif

    <!-- حقل رفع الملف -->
    <input type="file"
           name="bg_image"
           id="bg_image_input"
           accept=".webp,.jpg,.jpeg,.png"
           class="form-control @error('bg_image') is-invalid @enderror"
        {{ !$edit ? 'required' : '' }}>

    <small class="text-muted d-block mt-1">
        الصيغ المسموحة: WEBP, JPG, PNG (المقاس المقترح: 1920×1080)
    </small>

    @error('bg_image')
    <div class="invalid-feedback d-block">{{ $message }}</div>
    @enderror

    <!-- المعاينة الفورية -->
    <div id="image-preview-container" class="mt-3" style="display: none;">
        <p class="small text-success fw-semibold mb-2">معاينة الصورة الجديدة:</p>
        <img id="image-preview"
             src="#"
             alt="معاينة الصورة"
             class="rounded shadow-sm"
             style="max-width: 280px; height: auto; border: 1px solid #ddd;">
    </div>
</div>

<!-- Bullets -->
<div class="mt-4">
    <h6 class="fw-bold text-primary mb-3">النقاط البارزة أسفل الشريحة (اختياري - حتى 4)</h6>
    <div class="row g-2">
        @for($i = 0; $i < 4; $i++)
            <div class="col-md-6">
                <input type="text" name="bullets[]" class="form-control form-control-sm"
                       value="{{ $bullets[$i] ?? '' }}"
                       placeholder="مثال: جهودنا لا تنطفئ">
            </div>
        @endfor
    </div>
</div>

<!-- أزرار الحفظ -->
<div class="mt-4 d-flex gap-2">
    <button type="submit" class="btn btn-success px-4">حفظ التغييرات</button>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary px-4">إلغاء</a>
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

                // إخفاء المعاينة إذا لا يوجد ملف
                if (!file) {
                    previewContainer.style.display = 'none';
                    return;
                }

                // الأنواع المسموحة
                const validTypes = ['image/webp', 'image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('الرجاء اختيار صورة بصيغة WEBP, JPG أو PNG فقط.');
                    input.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                // الحجم الأقصى 5MB
                const maxSize = 5 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('حجم الصورة كبير جدًا. الحد الأقصى: 5 ميجابايت.');
                    input.value = '';
                    previewContainer.style.display = 'none';
                    return;
                }

                // قراءة وعرض الصورة
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            });

            // إخفاء المعاينة بعد الإرسال
            const parentForm = input.closest('form');
            if (parentForm) {
                parentForm.addEventListener('submit', function () {
                    setTimeout(() => previewContainer.style.display = 'none', 500);
                });
            }
        });
    </script>
@endpush
