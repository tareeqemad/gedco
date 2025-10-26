@php($edit = isset($link))
@csrf
@if($edit) @method('PUT') @endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Platform</label>
        <select name="platform" class="form-select">
            @foreach(['facebook','x','instagram','youtube','whatsapp'] as $p)
                <option value="{{ $p }}" @selected(old('platform',$link->platform ?? '')==$p)>{{ $p }}</option>
            @endforeach
        </select>
        @error('platform')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Icon Class</label>
        <input name="icon_class" class="form-control" value="{{ old('icon_class',$link->icon_class ?? '') }}" placeholder="fa-brands fa-facebook-f">
        @error('icon_class')<div class="text-danger">{{ $message }}</div>@enderror
        <small class="text-muted">مثال: fa-brands fa-instagram</small>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Sort Order</label>
        <input name="sort_order" type="number" class="form-control" value="{{ old('sort_order',$link->sort_order ?? 0) }}">
        @error('sort_order')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label">URL</label>
    <input name="url" class="form-control" value="{{ old('url',$link->url ?? '') }}" placeholder="https://...">
    @error('url')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$link->is_active ?? true))>
    <label class="form-check-label">Active</label>
</div>

<button class="btn btn-primary">حفظ</button>
