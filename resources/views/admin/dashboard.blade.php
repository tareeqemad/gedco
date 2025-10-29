@php
    $breadcrumbTitle = 'الرئيسية';
    $breadcrumbParent = 'لوحة التحكم';
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')

@section('title','لوحة التحكم')
@section('page-title','لوحة التحكم')
@section('content')
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="mb-1">أهلًا {{ auth()->user()->name ?? 'أدمن' }}</h6>
                    <p class="text-muted m-0">هذه عيّنة بطاقة داخل الداشبورد.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
