@extends('layouts.site')

@section('title', 'كهرباء غزة')
@section('meta_description', 'الصفحة الرئيسة لكهرباء غزة')

@push('styles')
    <style>

        section.p-0 .container-fluid {
            padding-left: 0 !important;
            padding-right: 0 !important;
            max-width: 100% !important;
        }
    #content {
            flex: 1 0 auto;
            overflow: visible;
        }
    </style>
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
                                        <div class="abs w-100 bottom-0 z-2 pb-5 slider-bullets">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between text-center flex-wrap">
                                                            @foreach($s->bullets_array as $b)
                                                                @if(!empty($b))
                                                                    <div class="relative bullet-item">
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
        <section class="relative py-5 bg-white" id="impact-stats">
            <div class="container">

                <!-- العنوان + الجملة + الخط -->
                <div class="mb-5" id="impact-heading" data-aos="fade-down" data-aos-delay="100">
                    <div class="stack">
                        <h3 class="title m-0">
                            <i class="fas fa-chart-line ms-2 text-danger"></i>
                            إحصائيات الخسائر
                        </h3>
                        <span class="subtitle">الأرقام تتحدث عن نفسها</span>
                    </div>
                </div>

                <!-- الإحصائيات -->
                <div class="row g-4 justify-content-center">
                    @foreach($impactStats as $stat)
                        @php
                            $value = $stat->amount_usd;
                            $title = $stat->title_ar;

                            $icon = match(true) {
                                str_contains($title, 'مباني') => 'fa-building',
                                str_contains($title, 'تجاري') => 'fa-store',
                                str_contains($title, 'شبكات') => 'fa-tower-broadcast',
                                str_contains($title, 'مستودعات') => 'fa-warehouse',
                                str_contains($title, 'مركبات') || str_contains($title, 'آليات') => 'fa-truck',
                                str_contains($title, 'تشغيلية') => 'fa-cog',
                                default => 'fa-chart-bar'
                            };

                            $color = $value >= 100_000_000 ? 'text-danger' :
                                     ($value >= 10_000_000 ? 'text-warning' : 'text-success');

                            // تنسيق الرقم + إخفاء الأصفار
                            if ($value >= 1_000_000) {
                                $formatted = rtrim(rtrim(number_format($value / 1_000_000, 2), '0'), '.');
                                $unit = 'M';
                            } elseif ($value >= 1_000) {
                                $formatted = rtrim(rtrim(number_format($value / 1_000, 2), '0'), '.');
                                $unit = 'K';
                            } else {
                                $formatted = number_format($value, 0);
                                $unit = '';
                            }
                        @endphp

                        <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                            <div class="counter-item p-4 rounded-4 h-100 d-flex flex-column justify-content-center shadow-sm bg-white"
                                 style="min-height: 140px; border: 1px solid #eee; transition: all 0.35s ease;"
                                 data-aos="zoom-in-up"
                                 data-aos-delay="{{ $loop->index * 100 }}"
                                 data-aos-duration="600">

                                <div class="mb-2 text-center">
                                    <i class="fas {{ $icon }} fa-2x {{ $color }}"></i>
                                </div>

                                <!-- رقم → M/K → $ -->
                                <h3 class="fs-4 fw-bold text-dark mb-1 text-center" dir="ltr">
                            <span class="timer" data-value="{{ $value }}" data-speed="1500" data-decimals="2">
                                {{ $formatted }}
                            </span>
                                    @if($unit)
                                        <span class="text-muted fs-5 ms-1">{{ $unit }}</span>
                                    @endif
                                    <span class="text-danger fs-5 ms-1">$</span>
                                </h3>

                                <p class="small text-muted mb-0 mt-2 fw-medium text-center" style="font-size: 0.8rem; line-height: 1.4;">
                                    {{ $title }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- الملاحظة -->
                <div class="mt-5" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-muted small text-center">
                        * الأرقام تقريبية وتُحدَّث دوريًا بناءً على التقارير الهندسية والمالية
                    </p>
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
                        <div class="h-100 relative bg-dark p-5 text-light journey-section">
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
        <section class="py-5 bg-white" id="services" dir="rtl">
            <div class="container">

                <!-- العنوان + الجملة + الخط (من اليسار) -->
                <div class="mb-5" id="services-heading" data-aos="fade-right" data-aos-delay="100">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <!-- الأيقونة الحلوة -->
                        <div style="flex-shrink: 0;">
                            <i class="fas fa-bolt fa-3x text-warning" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));"></i>
                        </div>

                        <!-- النصوص -->
                        <div>
                            <div class="subtitle" style="color: #fd7e14; font-weight: 700; font-size: 1.8rem; margin-bottom: 0.25rem;">
                                خدماتنا
                            </div>
                            <h3 style="margin: 0; font-weight: 600; font-size: 2rem;">
                                خدمات مصممة خصيصاً لكم
                            </h3>
                            <div class="subtitle-line" style="position: relative; margin-top: 0.5rem;"></div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-wrap: wrap; gap: 1.5rem;">
                    <!-- خدمة 1 -->
                    <div style="flex: 1 1 250px; max-width: 300px;">
                        <a href="http://213.244.76.228/bill" target="_blank" style="text-decoration: none; color: inherit; display: block;">
                            <div class="service-card" style="text-align: center; padding: 1.5rem; border-radius: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; border: 1px solid #eee; transition: all 0.35s ease;"
                                 data-aos="fade-up" data-aos-delay="100">

                                <img src="{{ asset('assets/site/images/services/s1.png') }}"
                                     style="width: 70px; height: 70px; margin: 0 auto 1rem; object-fit: contain;" alt="الخدمات الإلكترونية">
                                <h4 style="margin: 0; font-weight: 700; color: #212529;" class="text-center">الخدمات الإلكترونية</h4>
                            </div>
                        </a>
                    </div>

                    <!-- خدمة 2 -->
                    <div style="flex: 1 1 250px; max-width: 300px;">
                        <a href="#" style="text-decoration: none; color: inherit; display: block;">
                            <div class="service-card" style="text-align: center; padding: 1.5rem; border-radius: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; border: 1px solid #eee; transition: all 0.35s ease;"
                                 data-aos="fade-up" data-aos-delay="200">

                                <img src="{{ asset('assets/site/images/services/s2.png') }}"
                                     style="width: 70px; height: 70px; margin: 0 auto 1rem; object-fit: contain;" alt="المواصفات والمقاييس">
                                <h4 style="margin: 0; font-weight: 700; color: #212529;" class="text-center">المواصفات والمقاييس</h4>
                            </div>
                        </a>
                    </div>

                    <!-- خدمة 3 -->
                    <div style="flex: 1 1 250px; max-width: 300px;">
                        <a href="https://gazaappeal.gedco.ps/" target="_blank" style="text-decoration: none; color: inherit; display: block;">
                            <div class="service-card" style="text-align: center; padding: 1.5rem; border-radius: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; border: 1px solid #eee; transition: all 0.35s ease;"
                                 data-aos="fade-up" data-aos-delay="300">

                                <img src="{{ asset('assets/site/images/services/s3.png') }}"
                                     style="width: 70px; height: 70px; margin: 0 auto 1rem; object-fit: contain;" alt="التبرع">
                                <h4 style="margin: 0; font-weight: 700; color: #212529;" class="text-center">التبرع</h4>
                            </div>
                        </a>
                    </div>

                    <!-- خدمة 4 -->
                    <div style="flex: 1 1 250px; max-width: 300px;">
                        <a href="{{ route('site.advertisements.index') }}" style="text-decoration: none; color: inherit; display: block;">
                            <div class="service-card" style="text-align: center; padding: 1.5rem; border-radius: 1rem; height: 100%; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 4px 6px rgba(0,0,0,0.1); background: white; border: 1px solid #eee; transition: all 0.35s ease;"
                                 data-aos="fade-up" data-aos-delay="400">

                                <img src="{{ asset('assets/site/images/services/s4.png') }}"
                                     style="width: 70px; height: 70px; margin: 0 auto 1rem; object-fit: contain;" alt="إعلانات الوظائف">
                                <h4 style="margin: 0; font-weight: 700; color: #212529;" class="text-center">إعلانات الوظائف</h4>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========= Video Banner ========= --}}
        @if(($homeVideo['enabled'] ?? false) && !empty($homeVideo['id']))
            @php
                $vid     = $homeVideo['id'];
                $caption = $homeVideo['caption'] ?? 'شاهد فيديو تعريفي عن خدماتنا';
                // ملاحظة: بعض الفيديوهات ما إلها maxres، فبنجرّب maxres وإذا ما اشتغلت بيسحبها المتصفح لـ hqdefault تلقائي
                $thumb   = "https://img.youtube.com/vi/{$vid}/maxresdefault.jpg";
                $watch   = "https://www.youtube.com/watch?v={$vid}";
            @endphp

            <section class="py-0" id="video-section">
                <div class="container p-0">
                    <a class="d-block video-trigger position-relative"
                       href="{{ $watch }}"
                       data-video-id="{{ $vid }}"
                       style="border-radius: 1rem; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15); display: block; cursor: pointer;">

                        <img src="{{ $thumb }}"
                             alt="{{ $caption }}"
                             style="width: 100%; aspect-ratio: 16/9; object-fit: cover; transition: transform 0.4s ease;">

                        <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.5));"></div>

                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 90px; height: 90px; border-radius: 50%; background: rgba(220, 53, 69, 0.9); display: flex; align-items: center; justify-content: center; color: white; font-size: 38px; box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4); transition: all 0.3s ease; animation: pulse-play 2s infinite;">
                            <i class="fas fa-play" style="margin-left: 6px;"></i>
                        </div>

                        <div style="position: absolute; bottom: 1.5rem; left: 1.5rem; color: white; font-weight: 600; font-size: 1.1rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
                            {{ $caption }}
                        </div>
                    </a>
                </div>
            </section>
        @endif

        <!-- Popup (على قد الفيديو فقط) -->
        <div id="video-popup" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; padding: 2rem;">
            <div style="position: relative; width: 100%; max-width: 900px; aspect-ratio: 16/9; border-radius: 1rem; overflow: hidden; box-shadow: 0 20px 50px rgba(0,0,0,0.5); background: #000;">
                <button id="close-video-popup" style="position: absolute; top: -15px; right: -15px; width: 50px; height: 50px; border-radius: 50%; background: #dc3545; color: white; font-size: 24px; font-weight: bold; border: none; display: flex; align-items: center; justify-content: center; z-index: 10000; box-shadow: 0 4px 15px rgba(220,53,69,0.5); cursor: pointer; transition: all 0.3s ease;">
                    ×
                </button>
                <iframe id="youtube-player" width="100%" height="100%" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const trigger = document.querySelector('.video-trigger');
            const popup = document.getElementById('video-popup');
            const iframe = document.getElementById('youtube-player');
            const closeBtn = document.getElementById('close-video-popup');

            if (!trigger || !popup || !iframe || !closeBtn) return;

            const videoId = trigger.dataset.videoId;

            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                iframe.src = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1&playsinline=1&iv_load_policy=3`;
                popup.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });

            const closePopup = () => {
                popup.style.display = 'none';
                iframe.src = '';
                document.body.style.overflow = 'auto';
            };

            closeBtn.addEventListener('click', closePopup);
            popup.addEventListener('click', (e) => {
                if (e.target === popup) closePopup();
            });

            popup.querySelector('div').addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });

    </script>

@endpush
