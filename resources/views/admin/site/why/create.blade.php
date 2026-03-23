@extends('layouts.admin')
@section('title', __('admin.why_choose_us.create_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.why_choose_us.create_title');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);
        
        $route = route('admin.why.store');
        $method = 'POST';
        $model = null;
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-plus-circle-fill"
                :title="__('admin.why_choose_us.create_title')"
                :back-route="route('admin.why.index')"
                :back-label="__('admin.common.back')" />
        </x-admin.card>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4 p-md-5">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>
@endsection
