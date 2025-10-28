<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">العنوان *</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $job->title ?? '') }}" required>
        @error('title')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label class="form-label">الترتيب</label>
        <input type="number" name="sort" class="form-control" value="{{ old('sort', $job->sort ?? 0) }}">
    </div>

    <div class="col-md-3 d-flex align-items-center">
        <div class="form-check mt-4">
            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                {{ old('is_active', ($job->is_active ?? true)) ? 'checked' : '' }}>
            <label class="form-check-label">مفعل</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label">الرابط (اختياري)</label>
        <input type="url" name="link" class="form-control" value="{{ old('link', $job->link ?? '') }}" placeholder="https://...">
        @error('link')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">الوصف</label>
        <textarea name="description" class="form-control" rows="4">{{ old('description', $job->description ?? '') }}</textarea>
        @error('description')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label">الصورة (webp/png/jpg)</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @error('image')<div class="text-danger small">{{ $message }}</div>@enderror
        @isset($job->image)
            <div class="mt-2">
                <img src="{{ asset('storage/'.$job->image) }}" style="height:80px">
            </div>
        @endisset
    </div>

    <div class="col-12">
        <button class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
</div>
