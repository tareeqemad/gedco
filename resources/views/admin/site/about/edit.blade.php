@extends('layouts.admin')
@section('title', __('admin.about_us.edit_title'))

@section('content')
    @php
        // متغيّرات الـ breadcrumb للـ layout
        $breadcrumbTitle     = __('admin.about_us.edit_data');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);

        // إعدادات الفورم الجزئي
        $route  = route('admin.about.update', $about);
        $method = 'PUT';
        $model  = $about;

        // رابط المعاينة العامة مع الـ anchor #who-us
        $publicPreviewUrl = route('site.home') . '#who-us';
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 py-md-3 px-3 px-md-4">
                <div class="d-flex justify-content-between align-items-center w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-pencil-square me-2"></i>{{ __('admin.about_us.edit_content') }}
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.about.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i>{{ __('admin.common.back') }}
                        </a>
                        <a href="{{ $publicPreviewUrl }}" target="_blank" rel="noopener" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-box-arrow-up-right me-2"></i>{{ __('admin.common.preview_on_site') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-body p-4 p-md-5">


                {{-- الفورم (جزئي) --}}
                @include('admin.site.about._form', compact('route','method','model','col1','col2','col1En','col2En'))

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

@endsection
