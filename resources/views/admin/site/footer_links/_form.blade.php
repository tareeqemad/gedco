@php($edit = isset($link))
@csrf
@if($edit) @method('PUT') @endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Group</label>
        <select name="group" class="form-select">
            @foreach(['services'=>'services','company'=>'company'] as $val=>$text)
                <option value="{{ $val }}" @selected(old('group',$link->group ?? '')==$val)>{{ $text }}</option>
            @endforeach
        </select>
        @error('group')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Label (AR)</label>
        <input name="label_ar" class="form-control" value="{{ old('label_ar',$link->label_ar ?? '') }}">
        @error('label_ar')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Sort Order</label>
        <input name="sort_order" type="number" class="form-control" value="{{ old('sort_order',$link->sort_order ?? 0) }}">
        @error('sort_order')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Route Name (اختياري)</label>
        <input name="route_name" class="form-control" value="{{ old('route_name',$link->route_name ?? '') }}" placeholder="مثال: site.about">
        @error('route_name')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">URL (اختياري/خارجي)</label>
        <input name="url" class="form-control" value="{{ old('url',$link->url ?? '') }}" placeholder="https://...">
        @error('url')<div class="text-danger">{{ $message }}</div>@enderror
        <small class="text-muted">إن وُجد route_name سيُستخدم هو أولاً.</small>
    </div>
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$link->is_active ?? true))>
    <label class="form-check-label">Active</label>
</div>

<button class="btn btn-primary">حفظ</button>
