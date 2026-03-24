@php($edit = isset($link))
@csrf
@if($edit) @method('PUT') @endif

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-dark">{{ __('admin.social_links.platform') }}</label>
        <select name="platform" class="form-select rounded-3">
            @foreach(['facebook','x','instagram','youtube','whatsapp'] as $p)
                <option value="{{ $p }}" @selected(old('platform',$link->platform ?? '')==$p)>{{ $p }}</option>
            @endforeach
        </select>
        @error('platform')<div class="text-danger">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-dark">{{ __('admin.social_links.icon_class') }}</label>
        <input name="icon_class" class="form-control rounded-3" value="{{ old('icon_class',$link->icon_class ?? '') }}" placeholder="{{ __('admin.social_links.icon_class_placeholder') }}">
        @error('icon_class')<div class="text-danger">{{ $message }}</div>@enderror
        <small class="text-muted">{{ __('admin.social_links.icon_class_hint') }}</small>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label fw-semibold text-dark">{{ __('admin.social_links.sort_order') }}</label>
        <input name="sort_order" type="number" class="form-control rounded-3" value="{{ old('sort_order',$link->sort_order ?? 0) }}">
        @error('sort_order')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold text-dark">{{ __('admin.social_links.url') }}</label>
    <input name="url" class="form-control rounded-3" value="{{ old('url',$link->url ?? '') }}" placeholder="{{ __('admin.social_links.url_placeholder') }}">
    @error('url')<div class="text-danger">{{ $message }}</div>@enderror
</div>

<div class="form-check form-switch mb-3">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active',$link->is_active ?? true))>
    <label class="form-check-label">{{ __('admin.social_links.active') }}</label>
</div>

<button class="btn btn-save d-flex align-items-center justify-content-center gap-2">
    <i class="bi bi-check-circle"></i>
    {{ __('admin.common.form_save') }}
</button>
