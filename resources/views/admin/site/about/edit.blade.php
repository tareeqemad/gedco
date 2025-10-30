@extends('layouts.admin')
@section('title','تعديل من نحن')

@section('content')
    @php
        $breadcrumbTitle = 'تعديل البيانات';
        $breadcrumbParent = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        $route  = route('admin.about.update', $about);
        $method = 'PUT';
        $model  = $about;
    @endphp

    <div class="py-4">

        {{-- الكارد الرئيسي --}}
        <div class="card shadow-sm border-0">

            {{-- عنوان الكارد فقط --}}
            <div class="card-header">
                <h5 class="card-title mb-0">تعديل - من نحن</h5>
            </div>

            {{-- جسم الكارد --}}
            <div class="card-body p-4">

                {{-- تنبيهات --}}
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- الفورم --}}
                @include('admin.site.about._form', compact('route','method','model','col1','col2'))

            </div>
        </div>
    </div>
@endsection
