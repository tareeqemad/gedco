@php($edit = isset($slider))
@csrf
@if($edit) @method('PUT') @endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">العنوان</label>
        <input name="title" class="form-control" value="{{ old('title',$slider->title ?? '') }}">
        @error('title')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">ترتيب</label>
        <input name="sort_order" type="number" class="form-control" value="{{ old('sort_order',$slider->sort_order ?? 0) }}">
        @error('sort_order')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label d-block">نشِط</label>
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$slider->is_active ?? true))>
        </div>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">وصف</label>
    <textarea name="subtitle" class="form-control" rows="3">{{ old('subtitle',$slider->subtitle ?? '') }}</textarea>
    @error('subtitle')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">نص الزر (اختياري)</label>
        <input name="button_text" class="form-control" value="{{ old('button_text',$slider->button_text ?? '') }}">
        @error('button_text')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-8 mb-3">
        <label class="form-label">رابط الزر (اختياري)</label>
        <input name="button_url" class="form-control" value="{{ old('button_url',$slider->button_url ?? '') }}" placeholder="{{ route('site.booking') }}">
        @error('button_url')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label d-block">صورة الخلفية (webp/jpg/png)</label>
    @if(!empty($slider?->bg_image))
        <div class="mb-2">
            <img src="{{ asset('storage/'.$slider->bg_image) }}" alt="" style="max-width:260px;border:1px solid #ddd">
        </div>
    @endif
    <input type="file" name="bg_image" accept=".webp,.jpg,.jpeg,.png" class="form-control">
    @error('bg_image')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<hr>
<h6 class="mb-2">Bullets أسفل الشريحة (حتى 4)</h6>
@for($i=0; $i<4; $i++)
    <div class="mb-2">
        <input name="bullets[]" class="form-control" value="{{ old('bullets.'.$i, $slider->bullets[$i] ?? '') }}" placeholder="مثال: جهودنا لا تنطفئ">
    </div>
@endfor

<button class="btn btn-primary mt-3">حفظ</button>
