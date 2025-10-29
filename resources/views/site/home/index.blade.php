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
                                <div class="swiper-inner"
                                     data-bgimage="url({{ asset('storage/'.$s->bg_image) }})">
                                    <div class="sw-caption">
                                        <div class="container">
                                            <div class="row gx-5 align-items-center justify-content-center text-center">
                                                <div
                                                    class="col-lg-10 d-flex justify-content-center align-items-center text-center">
                                                    <div class="sw-text-wrapper">
                                                        @if($s->title)
                                                            <h2 class="animated text-uppercase anim-order-1">
                                                                {{ $s->title }}
                                                            </h2>
                                                        @endif
                                                    </div>
                                                </div>

                                                @if($s->subtitle || $s->button_text)
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

                                    @if(!empty($s->bullets))
                                        <div class="abs w-100 bottom-0 z-2 pb-5 sm-hide">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="d-flex justify-content-between text-center">
                                                            @foreach($s->bullets as $b)
                                                                @if($b)
                                                                    <div class="relative"><h6 class="mb-0">{{ $b }}</h6>
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
        <section class="relative py-5" id="who-us">
            <div class="container">
                <div class="row align-items-center g-4">

                    <!-- العمود الأول: النص -->
                    <div class="col-lg-6 text-end">
                        <div class="d-flex align-items-center justify-content-end mb-3">
                            <span class="badge bg-orange text-white px-3 py-2 fs-6 shadow-sm">من نحن</span>
                        </div>

                        <h3 class="mb-2 fw-bold text-orange">
                            شركة توزيع كهرباء محافظات غزة
                        </h3>

                        <h3 class="mb-3 text-orange fw-semibold fs-5">
                            نبني النور من جديد... ونواصل العطاء بثبات وأمل
                        </h3>

                        <p class="text-muted mb-3">
                            في قلب التحديات والدمار الذي أصاب قطاع غزة، تواصل شركة توزيع كهرباء محافظات غزة أداء رسالتها الوطنية بإصرار لا يلين.
                            نمدّ شرايين النور في كل بيت ومؤسسة، لنقول إن الحياة مستمرة، وإن الأمل لا ينطفئ.
                            نؤمن أن الكهرباء ليست مجرد طاقة، بل رمز للاستمرار والبناء، ولهذا نعمل على تطوير شبكات التوزيع وتحسين جودة الخدمة رغم الظروف الصعبة.
                        </p>

                        <p class="text-muted mb-4">
                            تسعى الشركة للارتقاء بخدماتها إلى المستويات الإقليمية والعالمية، وتحقيق رضا المواطنين من خلال أداء مهني وإبداعي،
                            بروح العمل الجماعي والمسؤولية، وبالتعاون مع مؤسسات المجتمع المحلي والهيئات الدولية.
                        </p>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <ul class="ul-check m-0">
                                    <li>⚡ استمرار النور رغم الصعاب</li>
                                    <li>💡 تطوير مستمر وإعادة إعمار بطاقة الأمل</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="ul-check m-0">
                                    <li>👷‍♂️ كوادر وطنية مخلصة ومؤهلة</li>
                                    <li>🤝 شفافية في العمل وثقة المجتمع</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- العمود الثاني: الصورة -->
                    <div class="col-lg-6">
                        <img src="{{ asset('assets/site/images/c3.webp') }}"
                             class="w-100 rounded-3 shadow-sm"
                             alt="شركة توزيع كهرباء محافظات غزة">
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
                        <h2 class="split">خدمات مصممة خصيصاً لك</h2>
                        <p>نقدم إدارة مشاريع خبيرة وتصميم مبتكر وتجديدات وخدمات بناء مستدامة، لوجي إكسبرس تقدم
                            الجودة.</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="http://213.244.76.228/bill" target="_blank" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s1.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>الخدمات الالكترونية</h4>
                            <p>نقل بري موثوق للشحنات عبر الولايات المتحدة مع خيارات أسطول مرنة.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="#" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s2.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>المواصفات والمقاييس</h4>
                            <p>تسليم سريع للبضائع الحساسة للوقت باستخدام الطرق الجوية العالمية مع التتبع في الوقت
                                الفعلي.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="https://gazaappeal.gedco.ps/" target="_blank" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s3.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>التبرع</h4>
                            <p>حل شحن فعال من حيث التكلفة للبضائع السائبة مع خدمات شحن بحري دولية موثوقة.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.jobs') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/services/s4.png') }}"
                                 class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>الوظائف</h4>
                            <p>خيار نقل بالسكك الحديدية فعال وصديق للبيئة مثالي للشحنات الثقيلة لمسافات طويلة.</p>
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
        <section id="section-why-choose-us" class="text-dark py-5 bg-light">
            <div class="container">
                <!-- العنوان الرئيسي -->
                <div class="row justify-content-center mb-5 text-center">
                    <div class="col-lg-9">
                        <div class="why-subtitle fw-bold mb-3 d-flex justify-content-center align-items-center gap-2">
                            <i class="bi bi-lightning-charge-fill text-orange"></i>
                            <span class="badge bg-orange text-white px-3 py-2 fs-6 shadow-sm">لماذا تختارنا</span>
                        </div>

                        <h2 class="why-tagline mb-3">
                            شريكك الموثوق في الخدمة الكهربائية
                        </h2>

                        <p class="text-muted why-desc">
                            نقدّم لك الأفضل لأننا نؤمن بحقك في خدمة كهربائية آمنة، موثوقة، ومتطورة.
                            في شركة توزيع كهرباء محافظات غزة، نسعى لأن نكون الشريك الذي تعتمد عليه في كل لحظة.
                        </p>
                    </div>
                </div>

                <!-- العناصر -->
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box h-100 position-relative">
                            <div class="icon-wrapper">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div class="content">
                                <h5>خدمة كهرباء مستقرة وآمنة</h5>
                                <p>نضمن تزويد طاقة كهربائية مستقرة وآمنة لجميع المشتركين، وبأسعار اقتصادية تراعي احتياجات المواطنين، مع التزام دائم بالجودة والاستقرار.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box h-100 position-relative">
                            <div class="icon-wrapper">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div class="content">
                                <h5>التزام بالموثوقية والشفافية</h5>
                                <p>نلتزم بالوضوح والشفافية في جميع تعاملاتنا، دون أي رسوم خفية، مع سياسات مالية وإدارية تضمن رضا وثقة المشتركين.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box h-100 position-relative">
                            <div class="icon-wrapper">
                                <i class="bi bi-cpu"></i>
                            </div>
                            <div class="content">
                                <h5>حلول تقنية متقدمة</h5>
                                <p>نواكب التطور في مجال توزيع الكهرباء عبر استخدام أحدث التقنيات العالمية لضمان أداء أفضل واستدامة في الخدمة.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box h-100 position-relative">
                            <div class="icon-wrapper">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div class="content">
                                <h5>كوادر وطنية متميزة</h5>
                                <p>نعتمد على كفاءات وطنية خبيرة ومؤهلة، تمتاز بروح الانتماء والمسؤولية لضمان استمرارية الخدمة في كل الظروف.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="feature-box h-100 position-relative">
                            <div class="icon-wrapper">
                                <i class="bi bi-graph-up-arrow"></i>
                            </div>
                            <div class="content">
                                <h5>تحسين مستمر للخدمة</h5>
                                <p>نسعى دائمًا لتطوير أنظمتنا التشغيلية والفنية بما يعزز رضا المشتركين ويحقق أفضل معايير الجودة في قطاع توزيع الكهرباء.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </div>
@endsection

@section('overlay')
    {{-- ========= Overlay / Extra Content ========= --}}
    <div id="extra-wrap" class="text-light">
        <div id="btn-close">
            <span></span>
            <span></span>
        </div>

        <div id="extra-content">
            <img src="{{ asset('assets/site/images/logo-white.webp') }}" class="w-200px" alt="">

            <div class="spacer-30-line"></div>

            <h5>خدماتنا</h5>
            <ul class="ul-check">
                <li>النقل البري</li>
                <li>الشحن الجوي</li>
                <li>الشحن البحري</li>
                <li>النقل بالسكك الحديدية</li>
                <li>المستودعات</li>
                <li>التخليص الجمركي</li>
                <li>التسليم الأخير</li>
                <li>بضائع المشاريع</li>
            </ul>

            <div class="spacer-30-line"></div>

            <h5 style="display: none;">تواصل معنا</h5>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="icofont-phone"
                   style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">+929 333 9296</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="icofont-location-pin"
                   style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">100 S Main St, New York, NY</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="icofont-envelope"
                   style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">contact@logixpress.com</span>
            </div>

            <div class="spacer-30-line"></div>

            <h5>من نحن</h5>
            <p>نحن مزود حلول لوجستية وشحن موثوق ملتزم بتسليم بضائعك بأمان وكفاءة وفي الوقت المحدد. مع سنوات من الخبرة في
                الشحن والتخزين والشحن الدولي.</p>

            <div class="social-icons">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-youtube"></i></a>
                <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- أي سكربتات إضافية خاصة بالصفحة تحطها هنا --}}
@endpush
