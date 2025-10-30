@extends('layouts.admin')
@section('title','لماذا تختارنا')

@section('content')
    @php
        $breadcrumbTitle = 'لماذا تختارنا';
        $breadcrumbParent = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);
    @endphp

    <div class="py-4">

        <div class="card shadow-sm border-0">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">لماذا تختارنا</h5>
                <div class="d-flex gap-2">
                    @if($why)
                        <a href="{{ route('admin.why.edit', $why) }}" class="btn btn-sm btn-primary">تعديل</a>
                    @else
                        <a href="{{ route('admin.why.create') }}" class="btn btn-sm btn-success">إنشاء</a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                @if(session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif

            @if(!$why)
                    <div class="alert alert-info mb-0">لا يوجد سجل بعد.</div>
                @else
                    <div class="mb-2"><span class="badge bg-orange">{{ $why->badge }}</span></div>
                    <h4 class="text-orange">{{ $why->tagline }}</h4>
                    <p class="text-muted">{{ $why->description }}</p>

                    @if(is_array($why->features) && count($why->features))
                        <div class="row g-3">
                            @foreach($why->features as $f)
                                <div class="col-lg-4 col-md-6">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="mb-2"><i class="{{ $f['icon'] ?? 'bi bi-lightning-charge-fill' }}"></i></div>
                                        <h6 class="fw-bold mb-1">{{ $f['title'] ?? '' }}</h6>
                                        <p class="text-muted mb-0">{{ $f['text'] ?? '' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
