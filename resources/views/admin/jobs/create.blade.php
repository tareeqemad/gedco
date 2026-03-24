@php
    $breadcrumbTitle     = __('admin.jobs.create_title');
    $breadcrumbParent    = __('admin.jobs.title');
    $breadcrumbParentUrl = route('admin.jobs.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.jobs.create_title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-briefcase"
                :title="__('admin.jobs.create_title')"
                :back-route="route('admin.jobs.index')" />

            <div class="card-body p-4">
                <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.jobs.form')
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection
