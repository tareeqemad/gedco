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
                                                <div class="col-lg-10 d-flex justify-content-center align-items-center text-center">
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
                                                                    <div class="relative"><h6 class="mb-0">{{ $b }}</h6></div>
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
        <section class="relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="subtitle s2 mb-3">من نحن</div>
                        <h2 class="split">لوجستيات الشحن البحري العالمية التي يمكنك الوثوق بها</h2>

                        <p>
                            تقدم لوجي إكسبرس حلول شحن بحري موثوقة وفعالة من حيث التكلفة وفي الوقت المحدد
                            للشركات من جميع الأحجام. من الحاويات الكاملة إلى الشحنات المجمعة،
                            نحن نربط الموانئ الأمريكية مثل لونج بيتش وهيوستن وميامي بمراكز التجارة الدولية
                            عبر آسيا وأوروبا والشرق الأوسط.
                        </p>

                        <div class="row g-4">
                            <div class="col-md-5">
                                <ul class="ul-check text-dark">
                                    <li>حاويات كاملة</li>
                                    <li>حاويات جزئية</li>
                                    <li>بضائع مقطعة ومشاريع</li>
                                </ul>
                            </div>

                            <div class="col-md-5">
                                <ul class="ul-check text-dark">
                                    <li>التخليص الجمركي</li>
                                    <li>تسليم من الميناء إلى الباب</li>
                                    <li>تتبع البضائع في الوقت الفعلي</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <img src="{{ asset('assets/site/images/c3.webp') }}" class="w-100" alt="Sea Cargo Logistics Service">
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

                            <img src="{{ asset('assets/site/images/misc/c2.webp') }}" class="w-50 abs bottom-0 end-0" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========= Services ========= --}}
        <section class="pt-50 pb-50">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-7 text-center">
                        <div class="subtitle">خدماتنا</div>
                        <h2 class="split">خدمات مصممة خصيصاً لك</h2>
                        <p>نقدم إدارة مشاريع خبيرة وتصميم مبتكر وتجديدات وخدمات بناء مستدامة، لوجي إكسبرس تقدم الجودة.</p>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.services') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/icons-color/1.png') }}" class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>النقل البري</h4>
                            <p>نقل بري موثوق للشحنات عبر الولايات المتحدة مع خيارات أسطول مرنة.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.services') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/icons-color/2.png') }}" class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>الشحن الجوي</h4>
                            <p>تسليم سريع للبضائع الحساسة للوقت باستخدام الطرق الجوية العالمية مع التتبع في الوقت الفعلي.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.services') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/icons-color/3.png') }}" class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>الشحن البحري</h4>
                            <p>حل شحن فعال من حيث التكلفة للبضائع السائبة مع خدمات شحن بحري دولية موثوقة.</p>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('site.services') }}" class="d-block hover relative">
                            <img src="{{ asset('assets/site/images/icons-color/4.png') }}" class="w-70px mb-3 hover-jello infinite" alt="">
                            <h4>النقل بالسكك الحديدية</h4>
                            <p>خيار نقل بالسكك الحديدية فعال وصديق للبيئة مثالي للشحنات الثقيلة لمسافات طويلة.</p>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- ========= Video Banner ========= --}}
        <section aria-label="section" class="relative p-0 overflow-hidden">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <a class="d-block hover popup-youtube" href="https://www.youtube.com/watch?v=FikkQTfbaOs">
                            <div class="relative overflow-hidden">
                                <div class="absolute start-0 w-100 abs-middle fs-36 text-white text-center z-2">
                                    <div class="player bg-dark border-0 circle"><span></span></div>
                                </div>
                                <div class="absolute w-100 h-100 top-0 bg-dark hover-op-05"></div>
                                <img src="{{ asset('assets/site/images/background/2.webp') }}" class="w-100 hover-scale-1-1" alt="">
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        https://www.facebook.com/reel/850163197536696
        {{-- ========= Why Choose Us ========= --}}
        <section class="bg-dark text-light">
            <div class="container">
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-7 text-center">
                        <div class="subtitle">لماذا تختارنا</div>
                        <h2 class="split">شريك لوجستي موثوق للشحن العالمي</h2>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>لوجستيات خبيرة</h4>
                                <p>يتمتع متخصصونا بعقود من الخبرة في الشحن والتخليص الجمركي، مما يضمن حلول سلسلة توريد سلسة وفعالة عالمياً.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>التزام بالتسليم السريع</h4>
                                <p>نفهم إلحاح كل شحنة ونعمل بسرعة. الطرق المحسنة وتكنولوجيا التتبع تضمن التسليم في الوقت المحدد دون تأخير غير ضروري.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>سياسة تسعير شفافة</h4>
                                <p>تسعيرنا واضح ومتنافس وسهل الفهم لجميع العملاء. نضمن عدم وجود رسوم مخفية وشفافية مالية كاملة في كل مرة.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>معالجة موثوقة للبضائع</h4>
                                <p>كل شحنة يتم إدارتها بدقة وعناية لتجنب المخاطر. من التعبئة إلى التحميل، نضمن وصول بضائعك بأمان وسلامة.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>حلول شاملة</h4>
                                <p>نغطي كل خطوة في اللوجستيات بما في ذلك التخزين والتوزيع والشحن. خدماتنا المتكاملة تجعل إدارة سلسلة التوريد بسيطة وفعالة.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="relative">
                            <i class="abs fs-24 p-4 bg-color icon_check rounded-1 text-light"></i>
                            <div class="ps-100">
                                <h4>ضمان الرضا</h4>
                                <p>ثقتك هي أولويتنا القصوى عبر جميع الشحنات. ندعم خدماتنا بدعم استجابة وضمان قوي لرضا العملاء.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- ========= Callout / Experience ========= --}}
        <section>
            <div class="container relative z-1">
                <div class="row g-4 gx-5">
                    <div class="col-lg-6">
                        <div class="h-100 relative">
                            <div class="subtitle id-color">شريك الشحن الموثوق لك</div>
                            <h1 class="split">خدمات شحن بحري خبيرة، تسليم عالمي بسهولة</h1>

                            <div class="abs ol-lg-12 pos-sm-relative bottom-0">
                                <div class="d-flex align-items-center justify-content-between border-bottom pb-4 mb-4 c">
                                    <a class="btn-main me-5" href="{{ route('site.booking') }}">ابدأ الآن</a>
                                    <div class="d-flex align-items-center">
                                        <div class="me-4">
                                            <img src="{{ asset('assets/site/images/testimonial/1.webp') }}" class="w-50px circle ms-min-10" alt="">
                                            <img src="{{ asset('assets/site/images/testimonial/2.webp') }}" class="w-50px circle ms-min-10" alt="">
                                            <img src="{{ asset('assets/site/images/testimonial/3.webp') }}" class="w-50px circle ms-min-10" alt="">
                                        </div>

                                        <div class="fw-600 fs-14 lh-1-5"><span class="fs-16 fw-bold text-dark">23k</span><br>شحنة ناجحة</div>
                                    </div>
                                </div>
                                <p>نتخصص في حلول الشحن البحري العالمي، ونقدم حاويات كاملة وحاويات جزئية وشحن بضائع سائبة. مع شبكة الناقلين الموثوقة لدينا وسنوات من الخبرة، نضمن عمليات سلسة وجداول موثوقة وتسليم فعال من حيث التكلفة للشركات من جميع الأحجام.</p>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="relative">
                            <div class="abs bottom-0 end-0 m-5">
                                <div class="p-4 mb-4 bg-color text-light rounded-1 text-center">
                                    <h1 class="fs-84 mb-1">15</h1>
                                    <div class="fs-16 lh-1-5">سنة من التميز</div>
                                </div>
                            </div>
                            <img src="{{ asset('assets/site/images/misc/s3.webp') }}" class="w-100 rounded-1" alt="">
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
                <i class="icofont-phone" style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">+929 333 9296</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="icofont-location-pin" style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">100 S Main St, New York, NY</span>
            </div>
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <i class="icofont-envelope" style="color: rgba(255, 255, 255, 0.5); margin-left: 10px; font-size: 16px;"></i>
                <span style="color: #fff;">contact@logixpress.com</span>
            </div>

            <div class="spacer-30-line"></div>

            <h5>من نحن</h5>
            <p>نحن مزود حلول لوجستية وشحن موثوق ملتزم بتسليم بضائعك بأمان وكفاءة وفي الوقت المحدد. مع سنوات من الخبرة في الشحن والتخزين والشحن الدولي.</p>

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
