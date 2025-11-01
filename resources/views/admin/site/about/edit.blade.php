@extends('layouts.admin')
@section('title','تعديل من نحن')

@section('content')
    @php
        // متغيّرات الـ breadcrumb للـ layout
        $breadcrumbTitle     = 'تعديل البيانات';
        $breadcrumbParent    = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        // إعدادات الفورم الجزئي
        $route  = route('admin.about.update', $about);
        $method = 'PUT';
        $model  = $about;

        // رابط المعاينة العامة مع الـ anchor #who-us
        $publicPreviewUrl = route('site.home') . '#who-us';
    @endphp

    <div class="py-4">
        <div class="card shadow-sm border-0">

            {{-- Header مع أكشنات --}}
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge rounded-pill bg-orange text-white">من نحن</span>
                        <h5 class="card-title mb-0 fw-semibold text-orange">تعديل المحتوى</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.about.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-arrow-right-circle me-1"></i> رجوع
                        </a>
                        <a href="{{ $publicPreviewUrl }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-box-arrow-up-right me-1"></i> معاينة على الموقع
                        </a>
                    </div>
                </div>
            </div>

            {{-- Body --}}
            <div class="card-body p-4">

                {{-- تنبيهات --}}
                @if(session('success'))
                    <div class="alert alert-success shadow-sm mb-3">
                        <i class="bi bi-check2-circle me-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning shadow-sm mb-3">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('warning') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger shadow-sm mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-x-octagon me-2 mt-1"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- الفورم (جزئي) --}}
                @include('admin.site.about._form', compact('route','method','model','col1','col2'))

                {{-- فورم إزالة الصورة (مستقل وخارج الجزئي) --}}
                @if(!empty($about->id) && !empty($about->image))
                    <form id="remove-image-{{ $about->id }}"
                          action="{{ route('admin.about.remove-image', $about) }}"
                          method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                @endif

            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .text-orange   { color:#ff7700 !important; }
            .bg-orange     { background-color:#ff7700 !important; }
            .btn-orange{
                background-color:#ff7700 !important;
                border-color:#ff7700 !important;
                color:#fff !important;
            }
            .btn-orange:hover{ opacity:.9; }
        </style>
    @endpush
@endsection
