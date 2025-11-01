@extends('layouts.admin')
@section('title','تعديل - لماذا تختارنا')

@section('content')
    @php
        $breadcrumbTitle     = 'لماذا تختارنا';
        $breadcrumbParent    = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        $route  = route('admin.why.update', $why);
        $method = 'PUT';
        $model  = $why;

        $publicPreviewUrl = route('site.home') . '#why-us';
    @endphp

    <div class="py-4">
        <div class="card shadow-sm border-0">

            <div class="card-header bg-white border-bottom py-3">
                <div class="row w-100 g-0 align-items-center">

                    {{-- العنوان يسار --}}
                    <div class="col">
                        <h5 class="card-title mb-0 text-orange fw-semibold">
                            تعديل - لماذا تختارنا
                        </h5>
                    </div>

                    {{-- الأزرار يمين --}}
                    <div class="col-auto">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.why.index') }}" class="btn btn-ghost-orange btn-sm">
                                رجوع
                            </a>
                            <a href="{{ $publicPreviewUrl }}" target="_blank" class="btn btn-ghost-orange btn-sm">
                                معاينة على الموقع
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-body p-4">
                @include('admin.site.why._form', compact('route','method','model','items'))
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .text-orange   { color:#ff7700 !important; }
            .bg-orange     { background-color:#ff7700 !important; }

            .border-orange-soft {
                border-color: rgba(255,119,0,.35) !important;
            }
            .feature-card {
                border:2px solid rgba(255,119,0,.25);
                background: rgba(255,119,0,.05);
                border-radius: .50rem;
                padding:1rem;
                margin-bottom:1rem;
            }
            .btn-ghost-orange{
                background:#fff !important;
                color:#ff7700 !important;
                border:1px solid rgba(255,119,0,.35) !important;
            }
            .btn-ghost-orange:hover{
                background:rgba(255,119,0,.10) !important;
                color:#ff7700 !important;
                border-color:rgba(255,119,0,.55) !important;
            }

        </style>
    @endpush
@endsection
