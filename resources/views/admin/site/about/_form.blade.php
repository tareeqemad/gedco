<form action="{{ $route }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="mb-3">
        <label class="form-label">العنوان</label>
        <input type="text" name="title" class="form-control"
               value="{{ old('title', $model->title ?? '') }}" required>
        @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">العنوان الفرعي</label>
        <input type="text" name="subtitle" class="form-control"
               value="{{ old('subtitle', $model->subtitle ?? '') }}">
        @error('subtitle') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">الفقرة الأولى</label>
        <textarea name="paragraph1" class="form-control" rows="4" required>{{ old('paragraph1', $model->paragraph1 ?? '') }}</textarea>
        @error('paragraph1') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">الفقرة الثانية</label>
        <textarea name="paragraph2" class="form-control" rows="3">{{ old('paragraph2', $model->paragraph2 ?? '') }}</textarea>
        @error('paragraph2') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    @php
        $featuresCol1 = old('features_col1', isset($col1) ? implode("\n", (array)$col1) : '');
        $featuresCol2 = old('features_col2', isset($col2) ? implode("\n", (array)$col2) : '');
    @endphp

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">مميّزات العمود الأوّل (سطر لكل نقطة)</label>
            <textarea name="features_col1" class="form-control" rows="5">{{ $featuresCol1 }}</textarea>
            @error('features_col1') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">مميّزات العمود الثاني (سطر لكل نقطة)</label>
            <textarea name="features_col2" class="form-control" rows="5">{{ $featuresCol2 }}</textarea>
            @error('features_col2') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="mb-3 mt-3">
        <label class="form-label d-block">الصورة (jpg/jpeg/png/webp)</label>

        @php
            $preview = null;
            if (!empty($model?->image)) {
                $val = $model->image;
                if (str_starts_with($val, 'http'))        $preview = $val;
                elseif (str_starts_with($val, 'assets/')) $preview = asset($val);
                elseif (str_starts_with($val, 'storage/'))$preview = asset($val);
                else                                      $preview = asset('storage/'.$val);
            }
        @endphp

        @if($preview)
            <div class="mb-2">
                <img src="{{ $preview }}" alt="preview" style="max-height:120px" class="rounded shadow-sm">
            </div>
        @endif

        <input type="file" name="image" class="form-control">
        @error('image') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.about.index') }}" class="btn btn-light">إلغاء</a>
    </div>
</form>
