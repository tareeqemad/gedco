@extends('layouts.admin')
@section('title', __('admin.why_choose_us.edit_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.why_choose_us.title');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        $route  = route('admin.why.update', $why);
        $method = 'PUT';
        $model  = $why;

        $publicPreviewUrl = route('site.home') . '#why-us';
    @endphp

    <div class="py-4">
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white border-bottom py-3">
                <div class="row w-100 g-0 align-items-center">

                    {{-- Title Left --}}
                    <div class="col">
                        <h5 class="card-title mb-0 text-primary fw-bold" style="font-size: 1.25rem;">
                            {{ __('admin.why_choose_us.edit_title') }}
                        </h5>
                    </div>

                    {{-- Buttons Right --}}
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.why.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-arrow-right me-1"></i>
                                {{ __('admin.why_choose_us.back') }}
                            </a>
                            <a href="{{ $publicPreviewUrl }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>
                                {{ __('admin.why_choose_us.preview_on_site') }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body p-4">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>

@endsection
