@extends('layouts.admin')
@section('title','تعديل من نحن')
@section('content')
    @php
        $breadcrumbTitle = 'من نحن';
        $breadcrumbParent = 'إعدادات الموقع';
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);
    @endphp
    <div class="py-4">

        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif

        @if($about)
            <div class="card shadow-sm border-0">

                {{-- Card Header: عنوان + أكشنات --}}
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">من نحن</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.about.edit', $about) }}" class="btn btn-sm btn-primary">
                            تعديل
                        </a>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="card-body p-3 p-lg-4">
                    <div class="row g-4 align-items-start flex-lg-row-reverse mx-0">

                        {{-- الصورة --}}
                        <div class="col-lg-4 order-lg-1">
                            @php
                                $fallback = asset('assets/site/images/c3.webp');
                                $img = $fallback;
                                if (!empty($about?->image)) {
                                    $val = $about->image;
                                    if (str_starts_with($val, 'http')) $img = $val;
                                    elseif (str_starts_with($val, 'assets/')) $img = asset($val);
                                    elseif (str_starts_with($val, 'storage/')) $img = asset($val);
                                    else $img = asset('storage/'.$val);
                                }
                            @endphp
                            <img src="{{ $img }}" class="img-fluid rounded-3 shadow-sm w-100" alt="{{ $about->title }}">
                        </div>

                        {{-- النص --}}
                        <div class="col-lg-8 order-lg-2">
                            <h3 class="text-orange fw-bold mb-1">{{ $about->title }}</h3>
                            @if(!empty($about->subtitle))
                                <div class="text-orange fw-semibold mb-2">{{ $about->subtitle }}</div>
                            @endif

                            @if(!empty($about->paragraph1))
                                <p class="text-muted mb-2" style="line-height:1.9">{{ $about->paragraph1 }}</p>
                            @endif
                            @if(!empty($about->paragraph2))
                                <p class="text-muted mb-3" style="line-height:1.9">{{ $about->paragraph2 }}</p>
                            @endif

                            @php
                                $features = is_array($about->features ?? null)
                                    ? $about->features
                                    : json_decode($about->features ?? '[]', true);
                            @endphp

                            @if(!empty($features) && (count($features[0] ?? []) || count($features[1] ?? [])))
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <ul class="m-0 list-unstyled">
                                            @foreach(($features[0] ?? []) as $item)
                                                <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <ul class="m-0 list-unstyled">
                                            @foreach(($features[1] ?? []) as $item)
                                                <li class="mb-1"><i class="bi bi-check2-circle me-1"></i> {{ $item }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">من نحن</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">لا يوجد سجل لقسم "من نحن" بعد.</div>
                    <a href="{{ route('admin.about.create') }}" class="btn btn-success">إنشاء أول سجل</a>
                </div>
            </div>
        @endif
    </div>

@endsection
