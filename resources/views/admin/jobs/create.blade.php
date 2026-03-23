@php
    $breadcrumbTitle     = 'إضافة وظيفة';
    $breadcrumbParent    = 'الوظائف';
    $breadcrumbParentUrl = route('admin.jobs.index');
@endphp
@extends('layouts.admin')
@section('title', 'إضافة وظيفة')

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-briefcase"
                title="إضافة وظيفة"
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
