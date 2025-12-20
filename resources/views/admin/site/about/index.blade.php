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

    <div class="py-4">

        @if(session('success')) <div class="alert alert-success shadow-sm">{{ session('success') }}</div> @endif
        @if(session('warning')) <div class="alert alert-warning shadow-sm">{{ session('warning') }}</div> @endif

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

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">

                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-orange text-white">{{ __('admin.about_us.title') }}</span>
                            <h5 class="mb-0 fw-semibold text-orange">{{ __('admin.about_us.content_details') }}</h5>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.about.edit', $about) }}" class="btn btn-orange btn-sm">
                                <i class="bi bi-pencil-square me-1"></i> {{ __('admin.about_us.edit') }}
                            </a>
                            <a href="{{ $publicPreviewUrl }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                <i class="bi bi-box-arrow-up-right me-1"></i> {{ __('admin.common.preview_on_site') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-start flex-lg-row-reverse">

                        {{-- الصورة --}}
                        <div class="col-lg-5">
                            <div class="rounded-3 overflow-hidden shadow-sm border bg-white">
                                <img src="{{ $img }}" alt="{{ $about->title }}" class="img-fluid w-100 d-block">
                            </div>

                            <div class="d-flex gap-2 mt-2">
                                <button type="button" class="btn btn-light btn-sm border" data-bs-toggle="modal" data-bs-target="#aboutImageModal">
                                    <i class="bi bi-arrows-fullscreen me-1"></i> {{ __('admin.about_us.zoom_image') }}
                                </button>

                                <a href="{{ $img }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                                    <i class="bi bi-download me-1"></i> {{ __('admin.about_us.open_original') }}
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

                            <h3 class="fw-bold mb-1 text-orange">{{ $displayTitle }}</h3>

                            @if(filled($displaySubtitle))
                                <div class="fw-semibold text-orange mb-3">{{ $displaySubtitle }}</div>
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
                                    <h6 class="fw-bold text-orange mb-2">
                                        <i class="bi bi-stars me-1"></i> {{ __('admin.about_us.our_features') }}
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled m-0">
                                                @foreach($colA as $item)
                                                    @if(filled($item))
                                                        <li class="mb-2 d-flex align-items-start">
                                                            <i class="bi bi-check-circle-fill me-2 mt-1 text-orange"></i>
                                                            <span>{{ $item }}</span>
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
                                                            <i class="bi bi-check-circle-fill me-2 mt-1 text-orange"></i>
                                                            <span>{{ $item }}</span>
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
                    <div class="modal-content border-0">
                        <div class="modal-header">
                            <h6 class="modal-title">{{ __('admin.about_us.full_image_preview') }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body p-0">
                            <img src="{{ $img }}" class="img-fluid w-100">
                        </div>
                    </div>
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
