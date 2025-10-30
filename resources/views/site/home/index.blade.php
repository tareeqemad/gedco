@extends('layouts.site')

@section('title', 'كهرباء غزة')
@section('meta_description', 'الصفحة الرئيسة لكهرباء غزة')

@push('styles')
@endpush

@section('content')
    <div class="no-bottom no-top" id="content">
        <div id="top"></div>

        {{-- ========= Slider ========= --}}
        <section class="text-light no-top no-bottom relative overflow-hidden">
            <div class="mh-800">
                <div class="swiper">
                    <div class="swiper-wrapper">
                        @foreach($sliders as $s)
                            <div class="swiper-slide">
                                <div class="swiper-inner" data-bgimage="url('{{ $s->bg_image_url }}')">
                                    <div class="sw-caption">
                                        <div class="container">
                                            <div class="row gx-5 align-items-center justify-content-center text-center">
                                                <div class="col-lg-10 d-flex justify-content-center align-items-center text-center">
                                                    <div class="sw-text-wrapper">
                                                        @if($s->title)
                                                            <h2 class="animated text-uppercase anim-order-1">
                                                                {{ $s->title }}
                                                            </h2>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($s->subtitle || ($s->button_text && $s->button_url))
                                                    <div class="col-lg-6">
                                                        <div class="animated anim-order-2">
                                                            @if($s->subtitle)
                                                                <p>{{ $s->subtitle }}</p>
                                                            @endif

                                                            @if($s->button_text && $s->button_url)
                                                                <div class="spacer-half"></div>
                                                                <a class="btn-main fx-slide animated fadeInUp anim-order-3"
                                                                   href="{{ $s->button_url }}">
                                                                    <span>{{ $s->button_text }}</span>
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(!empty($s->bullets_array))
                                        <div class="abs w-100 bottom-0 z-2 pb-5 sm-hide">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between text-center">
                                                            @foreach($s->bullets_array as $b)
                                                                @if(!empty($b))
                                                                    <div class="relative">
                                                                        <h6 class="mb-0">{{ $b }}</h6>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="sw-overlay op-4"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
        </section>


        {{-- ========= About ========= --}}
        <section class="relative py-5" id="who-us" dir="rtl">
            <div class="container">
                <div class="row align-items-start g-4">

                    <!-- العمود الأول: النص -->
                    <div class="col-lg-6 text-end">
                        <div class="d-flex align-items-center justify-content-end mb-3">
                            <span class="badge bg-orange text-white px-3 py-2 fs-5 shadow-sm">من نحن</span>
                        </div>

                        <h2 class="mb-1 fw-bold text-orange">
                            {{ $about->title ?? 'كهرباء غزة' }}
                        </h2>

                        <h3 class="mb-2 text-orange fw-semibold fs-5">
                            {{ $about->subtitle ?? 'نبني النور من جديد... ونواصل العطاء بثبات وأمل' }}
                        </h3>

                        <p class="text-muted mb-1">
                            {{ $about->paragraph1 ?? '' }}
                        </p>

                        @if(!empty($about->paragraph2))
                            <p class="text-muted mb-2">
                                {{ $about->paragraph2 }}
                            </p>
                        @endif

                        {{-- ✅ معالجة الـ features سواء كانت JSON أو Array --}}
                        @php
                            $features = is_array($about->features ?? null)
                                ? $about->features
                                : json_decode($about->features ?? '[]', true);
                        @endphp

                        @if(!empty($features) && (count($features[0] ?? []) || count($features[1] ?? [])))
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <ul class="ul-check m-0">
                                        @foreach($features[0] ?? [] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="ul-check m-0">
                                        @foreach($features[1] ?? [] as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- العمود الثاني: الصورة -->
                    <div class="col-lg-6">
                        @php
                            $fallback = asset('assets/site/images/c3.webp');
                            $img = $fallback;

                            if (!empty($about?->image)) {
                                $val = $about->image;

                                if (str_starts_with($val, 'http')) {
                                    $img = $val; // رابط خارجي
                                } elseif (str_starts_with($val, 'assets/')) {
                                    $img = asset($val); // صورة من مجلد الأصول
                                } elseif (str_starts_with($val, 'storage/')) {
                                    $img = asset($val); // لو القيمة مسبوقة بـ storage/
                                } else {
                                    $img = asset('storage/'.$val); // مرفوعة من لوحة التحكم
                                }
                            }
                        @endphp

                        <img src="{{ $img }}"
                             class="w-100 rounded-3 shadow-sm"
                             alt="{{ $about->title ?? 'شركة توزيع كهرباء محافظات غزة' }}">
                    </div>

                </div>
            </div>
        </section>


        {{-- ========= Counters ========= --}}
        <section class="pt-0">
            <div class="container">
                <div class="row g-4">

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="8" data-speed="3000">0</span>
                            </h3>
                            خسائر المباني
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="196.9" data-speed="3000">0</span>
                            </h3>
                            خسائر القطاع التجاري
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="204" data-speed="3000">0</span>
                            </h3>
                            خسائر الشبكات
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="20" data-speed="3000">0</span>
                            </h3>
                            خسائر المستودعات
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="5.6" data-speed="3000">0</span>
                            </h3>
                            خسائر المركبات والآليات
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 text-center">
                        <div class="de_count">
                            <h3 class="fs-40 mb-0">
                                <span class="timer" dir="ltr" data-to="16" data-speed="3000">0</span>
                            </h3>
                            الخسائر التشغيلية
                        </div>
                    </div>

                </div>
            </div>
        </section>


        {{-- ========= Track (split image/form) ========= --}}
        <section class="p-0">
            <div class="container-fluid relative z-1">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('assets/site/images/s5.webp') }}" class="w-100" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="h-100 relative bg-dark p-5 text-light">
                            <div class="subtitle">رحلة جديدة… بجذور قديمة</div>
                            <h2 class="split">نعود لننير غزة</h2>
                            <p class="mb-4 fs-5">
                                منذ 20 عامًا ونحن معكم… واليوم نبدأ مرحلة جديدة من الخدمة،
                                بثبات، وإصرار، وطاقـة لا تنطفئ ⚡
                            </p>

                         </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========= Services ========= --}}
        <section class="pt-50 pb-50" id="services">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-7 text-center">
                        <div class="subtitle">خدماتنا</div>
                        <h3 class="text-center">خدمات مصممة خصيصاً لكم</h3> <br>
                        <p></p>

                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="http://213.244.76.228/bill" target="_blank" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s1.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>الخدمات الالكترونية</h4>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s2.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>المواصفات والمقاييس</h4>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="https://gazaappeal.gedco.ps/" target="_blank" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s3.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>التبرع</h4>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.jobs') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s4.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>اعلانات الوظائف</h4>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========= Video Banner ========= --}}
        <section class="p-0 m-0">
            <a class="d-block js-youtube"
               href="https://www.youtube.com/watch?v=02WimCJ02V8"
               data-yt="02WimCJ02V8">
                <div class="hero-video position-relative overflow-hidden" style="line-height:0; margin:0;">
                    <img
                        src="https://img.youtube.com/vi/02WimCJ02V8/maxresdefault.jpg"
                        alt="Video thumbnail"
                        class="thumb w-100 d-block"
                        style="aspect-ratio:16/9; object-fit:cover; display:block;">
                    <span class="hero-overlay"
                          style="position:absolute; inset:0; background:rgba(0,0,0,.25); pointer-events:none;"></span>
                    <span class="player position-absolute d-flex align-items-center justify-content-center"
                          style="top:50%; left:50%; transform:translate(-50%,-50%);
                   width:80px; height:80px; border-radius:50%;
                   background:rgba(0,0,0,.6); color:#fff; font-size:42px;">
        ▶
      </span>
                </div>
            </a>
        </section>
        {{-- ========= Why Choose Us ========= --}}
        @php
            $why = \App\Models\WhyChooseUs::where('is_active',true)->first();
        @endphp

        @if($why)
            <section id="section-why-choose-us" class="text-dark py-5 bg-light">
                <div class="container">

                    <div class="row justify-content-center mb-5 text-center">
                        <div class="col-lg-9">
                            <div class="why-subtitle fw-bold mb-3 d-flex justify-content-center align-items-center gap-2">
                                <i class="bi bi-lightning-charge-fill text-orange"></i>
                                <span class="badge bg-orange text-white px-3 py-2 fs-6 shadow-sm">{{ $why->badge }}</span>
                            </div>
                            <h2 class="why-tagline mb-3">{{ $why->tagline }}</h2>
                            @if(!empty($why->description))
                                <p class="text-muted why-desc">{{ $why->description }}</p>
                            @endif
                        </div>
                    </div>

                    @if(is_array($why->features) && count($why->features))
                        <div class="row g-4">
                            @foreach($why->features as $f)
                                <div class="col-lg-4 col-md-6">
                                    <div class="feature-box h-100 position-relative">
                                        <div class="icon-wrapper">
                                            <i class="{{ $f['icon'] ?? 'bi bi-lightning-charge-fill' }}"></i>
                                        </div>
                                        <div class="content">
                                            <h5>{{ $f['title'] ?? '' }}</h5>
                                            <p>{{ $f['text'] ?? '' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </section>
        @endif
    </div>
@endsection
@push('scripts')
@endpush
