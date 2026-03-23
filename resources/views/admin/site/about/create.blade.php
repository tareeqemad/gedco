@extends('layouts.admin')
@section('title', __('admin.about_us.create_title'))
@section('content')
    @php
        $route  = route('admin.about.store');
        $method = 'POST';
        $model  = null;
    @endphp

    @php
        $breadcrumbTitle     = __('admin.about_us.create_title');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-plus-circle-fill"
                :title="__('admin.about_us.create_title')"
                :back-route="route('admin.about.index')"
                :back-label="__('admin.common.back')" />
        </x-admin.card>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4 p-md-5">
                @include('admin.site.about._form', compact('route','method','model','col1','col2','col1En','col2En'))
            </div>
        </div>
    </div>
@endsection
