@php
    $breadcrumbTitle     = 'تعديل: ' . $job->title;
    $breadcrumbParent    = 'الوظائف';
    $breadcrumbParentUrl = route('admin.jobs.index');
@endphp
@extends('layouts.admin')
@section('title', 'تعديل: ' . $job->title)

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-briefcase"
                title="تعديل: {{ $job->title }}"
                :back-route="route('admin.jobs.index')" />

            <div class="card-body p-4">
                <form action="{{ route('admin.jobs.update',$job) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    @include('admin.jobs.form')
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection
