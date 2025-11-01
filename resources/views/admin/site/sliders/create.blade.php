@extends('layouts.admin')
@section('title', 'إضافة شريحة جديدة')

@section('content')
    @php
          $breadcrumbTitle     = 'إضافة شريحة جديدة';
          $breadcrumbParent    = 'لوحةالتحكم';
          $breadcrumbParentUrl = route('admin.dashboard');

          $title      = 'إضافة شريحة جديدة';
          $parent     = 'شرائح السلايدر';
          $parent_url = route('admin.sliders.index');
    @endphp

    <div class="py-4">
        <div class="card border-0 shadow-sm">
            <!-- Card Header -->
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 py-3">
                <h5 class="card-title mb-0 text-dark fw-semibold d-flex align-items-center gap-2">
                    إضافة شريحة جديدة
                </h5>
                <a href="{{ route('admin.sliders.index') }}"
                   class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    عودة إلى القائمة
                </a>
            </div>

            <!-- Card Body -->
            <div class="card-body">
                <form action="{{ route('admin.sliders.store') }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="needs-validation"
                      novalidate>
                    @csrf
                    @include('admin.site.sliders._form', ['slider' => null])
                </form>
            </div>
        </div>
    </div>
@endsection
