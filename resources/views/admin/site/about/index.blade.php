@extends('layouts.admin')
@section('title', __('admin.about_us.edit_title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.about_us.title');
        $breadcrumbParent    = __('admin.menu.site_settings');
        $breadcrumbParentUrl = route('admin.site-settings.edit', 1);


        // preview link هنا:
        $publicPreviewUrl = route('site.home') . '#who-us';
    @endphp

    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-info-circle-fill"></i>
                                <span class="d-none d-sm-inline">{{ __('admin.about_us.title') }}</span>
                                <span class="d-sm-none">{{ __('admin.menu.about_us') }}</span>
                            </h5>
                        </div>
                    </div>
                    @if($about)
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.about.edit', $about) }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-pencil-square me-2"></i><span class="d-none d-md-inline">{{ __('admin.about_us.edit') }}</span><span class="d-md-none">{{ __('admin.actions.edit') }}</span>
                            </a>
                            <a href="{{ $publicPreviewUrl }}" class="btn btn-light btn-sm shadow-sm" target="_blank">
                                <i class="bi bi-box-arrow-up-right me-2"></i><span class="d-none d-md-inline">{{ __('admin.common.preview_on_site') }}</span><span class="d-md-none">{{ __('admin.common.preview') }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if($about)
            @php
                // تحديد الصورة والـ features حسب اللغة
                $adminDirection = session('direction', 'rtl');
                $isRtl = $adminDirection === 'rtl';
                $imagePath = $isRtl ? ($about->image ?? null) : ($about->image_en ?? null);
                $img = build_image_url($imagePath, 'assets/site/images/content/c3.webp');
                
                // Features حسب اللغة (بدون fallback)
                if ($isRtl) {
                    $featuresRaw = $about->features ?? '[]';
                } else {
                    $featuresRaw = $about->features_en ?? '[]';
                }
                $featuresArr = is_array($featuresRaw) ? $featuresRaw : (json_decode($featuresRaw, true) ?? []);
                $colA = array_values($featuresArr[0] ?? []);
                $colB = array_values($featuresArr[1] ?? []);
            @endphp

            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4 p-md-5">
                    <div class="row g-4 align-items-start flex-lg-row-reverse">

                        {{-- الصورة --}}
                        <div class="col-lg-5">
                            <div class="rounded-3 overflow-hidden shadow-sm border bg-white">
                                <img src="{{ $img }}" alt="{{ $about->title }}" class="img-fluid w-100 d-block">
                            </div>

                            <div class="d-flex gap-2 mt-3">
                                <button type="button" class="btn btn-outline-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#aboutImageModal">
                                    <i class="bi bi-arrows-fullscreen me-1"></i>{{ __('admin.about_us.zoom_image') }}
                                </button>
                                <a href="{{ $img }}" class="btn btn-outline-secondary btn-sm shadow-sm" target="_blank">
                                    <i class="bi bi-download me-1"></i>{{ __('admin.about_us.open_original') }}
                                </a>
                            </div>
                        </div>

                        {{-- النص --}}
                        <div class="col-lg-7">
                            @php
                                $adminDirection = session('direction', 'rtl');
                                $isRtl = $adminDirection === 'rtl';
                                
                                // في لوحة التحكم: فقط الأعمدة المخصصة للغة (بدون fallback)
                                if ($isRtl) {
                                    $displayTitle = $about->title ?? '';
                                    $displaySubtitle = $about->subtitle ?? '';
                                    $displayP1 = $about->paragraph1 ?? '';
                                    $displayP2 = $about->paragraph2 ?? '';
                                    $displayImage = $about->image ?? null;
                                } else {
                                    $displayTitle = $about->title_en ?? '';
                                    $displaySubtitle = $about->subtitle_en ?? '';
                                    $displayP1 = $about->paragraph1_en ?? '';
                                    $displayP2 = $about->paragraph2_en ?? '';
                                    $displayImage = $about->image_en ?? null;
                                }
                                
                                $p1 = trim($displayP1);
                                $p2 = trim($displayP2);
                                $long = (mb_strlen($p1.$p2) > 450);
                            @endphp

                            <h3 class="fw-bold mb-1 text-primary">{{ $displayTitle }}</h3>

                            @if(filled($displaySubtitle))
                                <div class="fw-semibold text-primary mb-3">{{ $displaySubtitle }}</div>
                            @endif

                            <div id="about-text-wrapper" class="{{ $long ? 'collapsed-text' : '' }}">
                                @if(filled($p1))
                                    <p class="text-muted" style="line-height:1.9">{{ $p1 }}</p>
                                @endif
                                @if(filled($p2))
                                    <p class="text-muted" style="line-height:1.9">{{ $p2 }}</p>
                                @endif
                            </div>

                            @if($long)
                                <button id="toggle-text" type="button" class="btn btn-link p-0 mt-1">
                                    {{ __('admin.about_us.show_more') }}
                                </button>
                            @endif

                            @if(count($colA) || count($colB))
                                <div class="mt-4">
                                    <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                        <i class="bi bi-stars"></i>{{ __('admin.about_us.our_features') }}
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled m-0">
                                                @foreach($colA as $item)
                                                    @if(filled($item))
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-check-circle-fill me-2 mt-1 text-primary"></i>
                                                            <span class="text-dark">{{ $item }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled m-0">
                                                @foreach($colB as $item)
                                                    @if(filled($item))
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-check-circle-fill me-2 mt-1 text-primary"></i>
                                                            <span class="text-dark">{{ $item }}</span>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
            <div class="modal fade" id="aboutImageModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4">
                        <div class="modal-header bg-gradient-primary text-white border-0 rounded-top-4">
                            <h6 class="modal-title fw-bold text-white">
                                <i class="bi bi-image me-2"></i>{{ __('admin.about_us.full_image_preview') }}
                            </h6>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img src="{{ $img }}" class="img-fluid w-100">
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-5 text-center">
                    <i class="bi bi-info-circle text-muted" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-muted">{{ __('admin.about_us.no_content') }}</h5>
                    <a href="{{ route('admin.about.create') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('admin.about_us.create_content') }}
                    </a>
                </div>
            </div>
        @endif
    </div>


    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded',()=>{
                const w=document.getElementById('about-text-wrapper');
                const b=document.getElementById('toggle-text');
                if(w && b){
                    b.onclick=()=>{
                        const c=w.classList.toggle('collapsed-text');
                        b.textContent = c ? '{{ __('admin.about_us.show_more') }}' : '{{ __('admin.about_us.show_less') }}';
                    }
                }
            })
        </script>
    @endpush
@endsection
