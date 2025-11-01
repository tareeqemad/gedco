<form action="{{ $route }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
    @csrf
    @if($method !== 'POST') @method($method) @endif

    {{-- العنوان --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">العنوان <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $model->title ?? '') }}" required>
        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- العنوان الفرعي --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">العنوان الفرعي</label>
        <input type="text" name="subtitle" class="form-control @error('subtitle') is-invalid @enderror"
               value="{{ old('subtitle', $model->subtitle ?? '') }}">
        @error('subtitle') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- الفقرة الأولى --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">الفقرة الأولى</label>
        <textarea name="paragraph1" class="form-control @error('paragraph1') is-invalid @enderror" rows="4" required>{{ old('paragraph1', $model->paragraph1 ?? '') }}</textarea>
        @error('paragraph1') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- الفقرة الثانية --}}
    <div class="mb-3">
        <label class="form-label fw-semibold">الفقرة الثانية</label>
        <textarea name="paragraph2" class="form-control @error('paragraph2') is-invalid @enderror" rows="3">{{ old('paragraph2', $model->paragraph2 ?? '') }}</textarea>
        @error('paragraph2') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    @php
        $featuresCol1 = old('features_col1', isset($col1) ? implode("\n", (array)$col1) : '');
        $featuresCol2 = old('features_col2', isset($col2) ? implode("\n", (array)$col2) : '');
    @endphp

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold">مميّزات العمود الأوّل (سطر لكل نقطة)</label>
            <textarea name="features_col1" class="form-control @error('features_col1') is-invalid @enderror" rows="5">{{ $featuresCol1 }}</textarea>
            @error('features_col1') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold">مميّزات العمود الثاني (سطر لكل نقطة)</label>
            <textarea name="features_col2" class="form-control @error('features_col2') is-invalid @enderror" rows="5">{{ $featuresCol2 }}</textarea>
            @error('features_col2') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
    </div>

    {{-- الصورة + live preview --}}
    <div class="mb-3 mt-3">
        <label class="form-label d-block fw-semibold">الصورة (WEBP/JPG/PNG)</label>

        @php
            // لو عندك Accessor image_url في الموديل استخدمه، وإلا نبني المسار يدوي
            $currentImg = null;
            if (!empty($model?->image)) {
                if (method_exists($model, 'getImageUrlAttribute')) {
                    $currentImg = $model->image_url;
                } else {
                    $val = $model->image;
                    if (str_starts_with($val, 'http'))        $currentImg = $val;
                    elseif (str_starts_with($val, 'assets/')) $currentImg = asset($val);
                    elseif (str_starts_with($val, 'storage/'))$currentImg = asset($val);
                    else                                      $currentImg = asset('storage/'.$val);
                }
            }
            $fallback = asset('assets/site/images/c3.webp');
        @endphp

        <div class="position-relative d-inline-block mb-2">
            <img id="about-live-img"
                 src="{{ $currentImg ?: $fallback }}"
                 alt="preview"
                 class="rounded shadow-sm"
                 style="max-width:300px; border:1px solid #ddd;">

            @if(!empty($model?->id) && !empty($model?->image))
                <button type="submit"
                        form="remove-image-{{ $model->id }}"
                        class="btn btn-sm btn-outline-danger border-0 bg-transparent position-absolute top-0 end-0"
                        title="إزالة الصورة"
                        onclick="return confirm('إزالة الصورة؟')">X</button>
            @endif
        </div>

        <input type="file" name="image" id="about-image-input" accept=".jpg,.jpeg,.png,.webp"
               class="form-control @error('image') is-invalid @enderror">
        <small class="text-muted d-block mt-1">الحد الأقصى 5MB</small>
        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-orange px-4">حفظ</button>
        <a href="{{ route('admin.about.index') }}" class="btn btn-light">إلغاء</a>
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
                    alert('صيغة غير مسموحة (WEBP/JPG/PNG فقط).');
                    input.value = '';
                    return;
                }
                const maxSize = 5 * 1024 * 1024; // 5MB
                if(file.size > maxSize){
                    alert('الحجم أكبر من 5MB.');
                    input.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = ev => preview.src = ev.target.result;
                reader.readAsDataURL(file);
            }, false);
        });
    </script>
@endpush
