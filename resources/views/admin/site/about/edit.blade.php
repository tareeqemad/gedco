@extends('layouts.admin')
@section('title', __('admin.about_us.edit_title'))

@section('content')
    @php
        // متغيّرات الـ breadcrumb للـ layout
        $breadcrumbTitle     = __('admin.about_us.edit_data');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        // إعدادات الفورم الجزئي
        $route  = route('admin.about.update', $about);
        $method = 'PUT';
        $model  = $about;

        // رابط المعاينة العامة مع الـ anchor #who-us
        $publicPreviewUrl = route('site.home') . '#who-us';
    @endphp

    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-pencil-square"
                :title="__('admin.about_us.edit_content')"
                :back-route="route('admin.about.index')"
                :back-label="__('admin.common.back')">
                <x-slot:actions>
                    <a href="{{ $publicPreviewUrl }}" target="_blank" rel="noopener" class="form-btn-back" style="text-decoration: none;">
                        <i class="bi bi-box-arrow-up-right me-1"></i>{{ __('admin.common.preview_on_site') }}
                    </a>
                </x-slot:actions>
            </x-admin.card-header-form>

            <div class="card-body p-3 p-md-4">
                @include('admin.site.about._form', compact('route','method','model','col1','col2','col1En','col2En'))

                @if(!empty($about->id) && !empty($about->image))
                    <form id="remove-image-{{ $about->id }}"
                          action="{{ route('admin.about.remove-image', $about) }}"
                          method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif
            </div>
        </x-admin.card>
    </div>

@endsection
