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

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-pencil-square"
                :title="__('admin.why_choose_us.edit_title')"
                :back-route="route('admin.why.index')"
                :back-label="__('admin.common.back')">
                <x-slot:actions>
                    <a href="{{ $publicPreviewUrl }}" target="_blank" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-box-arrow-up-right me-2"></i>{{ __('admin.common.preview_on_site') }}
                    </a>
                </x-slot:actions>
            </x-admin.card-header-form>
        </x-admin.card>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4 p-md-5">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>

@endsection
