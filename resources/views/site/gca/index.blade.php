@extends('layouts.site')

@php
    $dir = session('direction', 'rtl');
    $isRtl = $dir === 'rtl';
@endphp

@section('title', $isRtl ? 'مركز التأهيل والمواصفات الفنية | كهرباء غزة' : 'GCA Center | Gaza Electricity')
@section('meta_description', $isRtl ? 'مركز التأهيل والمواصفات الفنية وخدمة الاعتماد GCA' : 'Technical Qualification, Specifications and Certification Center (GCA)')

@push('styles')
<style>
    .gca-page {
        padding-top: clamp(80px, 12vw, 110px);
        padding-bottom: clamp(3rem, 6vw, 5rem);
        flex: 1 0 auto;
        background:
            linear-gradient(180deg,
                rgba(248, 92, 0, 0.60) 0%,
                rgba(248, 140, 60, 0.35) 140px,
                rgba(255, 210, 175, 0.25) 320px,
                #f8fafc 480px,
                #f8fafc 100%
            );
    }

    /* تطابق خلفية الصفحة مع نهاية التدرج لإزالة الفراغ الأبيض */
    body, #wrapper { background-color: #f8fafc !important; }

    .gca-card {
        background: #fff;
        border-radius: 1.5rem;
        border: 1px solid rgba(248, 92, 0, 0.1);
        box-shadow: 0 8px 40px rgba(248, 92, 0, 0.1), 0 2px 12px rgba(10, 20, 30, 0.05);
        overflow: hidden;
        display: block;
    }

    .gca-card-header {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 2.25rem 2.5rem 1.75rem;
        border-bottom: 1px solid rgba(248, 92, 0, 0.08);
        background: linear-gradient(180deg, rgba(248, 92, 0, 0.03) 0%, transparent 100%);
    }

    .gca-card-title {
        display: inline-block;
        font-size: clamp(1.5rem, 3.5vw, 2rem);
        font-weight: 900;
        color: #0f172a;
        margin: 0;
        line-height: 1.3;
        text-align: center;
        width: auto;
    }

    .gca-card-title span { color: #F85C00; }

    /* ===== TABS ===== */
    .gca-tabs-nav {
        display: flex;
        gap: 0;
        border-bottom: 2px solid rgba(248, 92, 0, 0.12);
        padding: 0 2rem;
        background: #fff;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .gca-tabs-nav::-webkit-scrollbar { display: none; }

    .gca-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.4rem;
        font-size: 0.95rem;
        font-weight: 700;
        color: #64748b;
        background: none;
        border: none;
        border-bottom: 3px solid transparent;
        margin-bottom: -2px;
        cursor: pointer;
        white-space: nowrap;
        transition: color 0.25s ease, border-color 0.25s ease;
        text-decoration: none;
    }
    .gca-tab-btn:hover { color: #F85C00; }
    .gca-tab-btn.active {
        color: #F85C00;
        border-bottom-color: #F85C00;
    }
    .gca-tab-btn i { font-size: 0.9rem; }

    .gca-tab-pane { display: none; }
    .gca-tab-pane.active { display: block; }

    /* ===== Content styles ===== */
    .gca-text-section {
        padding: 2rem 2.5rem 1.75rem;
    }

    .gca-lang-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.3rem 0.9rem;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 1.1rem;
        background: linear-gradient(135deg, #F85C00, #ff8f3b);
        color: #fff;
        box-shadow: 0 3px 10px rgba(248, 92, 0, 0.2);
    }

    .gca-prose {
        font-size: 0.97rem;
        line-height: 2;
        color: #4b5563;
    }

    .gca-prose p { margin-bottom: 1rem; }
    .gca-prose p:last-child { margin-bottom: 0; }

    .gca-divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(248, 92, 0, 0.15), rgba(148, 163, 184, 0.2), transparent);
        margin: 0 2.5rem;
    }

    .gca-image-section {
        padding: 1.75rem 2.5rem;
    }

    .gca-image-section img {
        width: 100%;
        border-radius: 1rem;
        display: block;
        box-shadow: 0 8px 32px rgba(10, 20, 30, 0.1);
    }

    .gca-cta-section {
        padding: 1.75rem 2.5rem 2.5rem;
        text-align: center;
        border-top: 1px solid rgba(148, 163, 184, 0.12);
        background: linear-gradient(180deg, transparent, rgba(248, 92, 0, 0.02));
    }

    .gca-cta-desc {
        font-size: 0.95rem;
        color: #64748b;
        margin-bottom: 1.25rem;
        line-height: 1.7;
    }

    .gca-join-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.65rem;
        padding: 0.9rem 2.25rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #F85C00, #ff8f3b);
        color: #fff;
        font-size: 1rem;
        font-weight: 800;
        text-decoration: none;
        box-shadow: 0 6px 22px rgba(248, 92, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .gca-join-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 32px rgba(248, 92, 0, 0.4);
        color: #fff;
        text-decoration: none;
    }

    .gca-join-btn i { transition: transform 0.3s ease; }
    html[dir="rtl"] .gca-join-btn:hover i { transform: translateX(-3px); }
    html[dir="ltr"] .gca-join-btn:hover i { transform: translateX(3px); }

    /* ===== FAQ ===== */
    .gca-faq-section {
        padding: 2rem 2.5rem 2.5rem;
    }

    .gca-faq-item {
        border: 1px solid rgba(248, 92, 0, 0.12);
        border-radius: 0.9rem;
        margin-bottom: 0.85rem;
        overflow: hidden;
        transition: box-shadow 0.25s ease;
    }
    .gca-faq-item:hover { box-shadow: 0 4px 20px rgba(248, 92, 0, 0.1); }

    .gca-faq-question {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.4rem;
        background: none;
        border: none;
        width: 100%;
        text-align: inherit;
        font-size: 0.97rem;
        font-weight: 700;
        color: #0f172a;
        cursor: pointer;
        transition: background 0.2s ease;
    }
    .gca-faq-question:hover { background: rgba(248, 92, 0, 0.04); }
    .gca-faq-question[aria-expanded="true"] { color: #F85C00; }

    .gca-faq-question .faq-icon {
        flex-shrink: 0;
        width: 1.7rem; height: 1.7rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 50%;
        background: rgba(248, 92, 0, 0.08);
        color: #F85C00;
        font-size: 0.75rem;
        transition: transform 0.3s ease, background 0.3s ease;
    }
    .gca-faq-question[aria-expanded="true"] .faq-icon {
        background: #F85C00;
        color: #fff;
        transform: rotate(45deg);
    }

    .gca-faq-answer {
        display: none;
        padding: 0 1.4rem 1.2rem;
        font-size: 0.93rem;
        line-height: 1.9;
        color: #4b5563;
        border-top: 1px solid rgba(248, 92, 0, 0.07);
    }
    .gca-faq-answer.open { display: block; }

    .gca-faq-answer p { margin-bottom: 0.6rem; }
    .gca-faq-answer p:last-child { margin-bottom: 0; }
    .gca-faq-answer ul { margin-top: 0.5rem; margin-bottom: 0; }
    .gca-faq-answer ul li { margin-bottom: 0.4rem; }
    .gca-faq-answer strong { color: #0f172a; }

    /* LTR FAQ section — force all children to follow left direction */
    .gca-faq-section[dir="ltr"],
    .gca-faq-section[dir="ltr"] .gca-faq-question,
    .gca-faq-section[dir="ltr"] .gca-faq-question span,
    .gca-faq-section[dir="ltr"] .gca-faq-answer,
    .gca-faq-section[dir="ltr"] .gca-faq-answer p,
    .gca-faq-section[dir="ltr"] .gca-faq-answer ul,
    .gca-faq-section[dir="ltr"] .gca-faq-answer li {
        direction: ltr !important;
        text-align: left !important;
        unicode-bidi: isolate;
    }

    /* RTL FAQ section — force all children to follow right direction */
    .gca-faq-section[dir="rtl"],
    .gca-faq-section[dir="rtl"] .gca-faq-question,
    .gca-faq-section[dir="rtl"] .gca-faq-question span,
    .gca-faq-section[dir="rtl"] .gca-faq-answer,
    .gca-faq-section[dir="rtl"] .gca-faq-answer p,
    .gca-faq-section[dir="rtl"] .gca-faq-answer ul,
    .gca-faq-section[dir="rtl"] .gca-faq-answer li {
        direction: rtl !important;
        text-align: right !important;
        unicode-bidi: isolate;
    }

    /* ===== PDF Downloads ===== */
    .gca-pdf-section {
        padding: 2rem 2.5rem 2.5rem;
    }

    .gca-pdf-intro {
        font-size: 0.97rem;
        color: #4b5563;
        line-height: 1.8;
        margin-bottom: 2rem;
        text-align: center;
    }

    .gca-pdf-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 1.25rem;
    }

    .gca-pdf-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 2rem 1.5rem;
        border-radius: 1.2rem;
        border: 1.5px solid rgba(248, 92, 0, 0.15);
        background: linear-gradient(145deg, rgba(248, 92, 0, 0.03) 0%, #fff 100%);
        text-decoration: none;
        color: inherit;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        text-align: center;
    }
    .gca-pdf-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 36px rgba(248, 92, 0, 0.15);
        border-color: rgba(248, 92, 0, 0.4);
        text-decoration: none;
        color: inherit;
    }

    .gca-pdf-icon {
        width: 4rem; height: 4rem;
        display: flex; align-items: center; justify-content: center;
        border-radius: 1rem;
        background: linear-gradient(135deg, #F85C00, #ff8f3b);
        color: #fff;
        font-size: 1.6rem;
        box-shadow: 0 6px 18px rgba(248, 92, 0, 0.3);
    }

    .gca-pdf-label {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
    }

    .gca-pdf-sub {
        font-size: 0.8rem;
        color: #94a3b8;
        margin-top: -0.5rem;
    }

    .gca-pdf-dl-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.55rem 1.3rem;
        border-radius: 999px;
        background: linear-gradient(135deg, #F85C00, #ff8f3b);
        color: #fff;
        font-size: 0.84rem;
        font-weight: 700;
        margin-top: 0.25rem;
        transition: box-shadow 0.25s ease;
    }
    .gca-pdf-card:hover .gca-pdf-dl-btn {
        box-shadow: 0 6px 18px rgba(248, 92, 0, 0.35);
    }

    @media (max-width: 767px) {
        .gca-text-section, .gca-image-section, .gca-cta-section,
        .gca-faq-section, .gca-pdf-section { padding-left: 1.25rem; padding-right: 1.25rem; }
        .gca-divider { margin: 0 1.25rem; }
        .gca-card-header { padding-left: 1.25rem; padding-right: 1.25rem; }
        .gca-tabs-nav { padding: 0 0.75rem; }
        .gca-tab-btn { padding: 0.9rem 0.9rem; font-size: 0.88rem; }
    }
</style>
@endpush

@section('content')

<section class="gca-page" dir="{{ $dir }}">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                <div class="gca-card" data-aos="fade-up">

                    {{-- العنوان --}}
                    <div class="gca-card-header">
                        <h1 class="gca-card-title">
                            <span dir="rtl" style="direction:rtl!important; unicode-bidi:isolate;">مركز التأهيل والمواصفات الفنية</span>
                        </h1>
                    </div>

                    {{-- شريط التبويبات --}}
                    <div class="gca-tabs-nav" role="tablist" dir="rtl">
                        <button class="gca-tab-btn active" role="tab" data-tab="intro" aria-selected="true">
                            <i class="fas fa-info-circle"></i>
                            {{ $isRtl ? 'مقدمة عن المركز' : 'About the Center' }}
                        </button>
                        <button class="gca-tab-btn" role="tab" data-tab="faq" aria-selected="false">
                            <i class="fas fa-question-circle"></i>
                            {{ $isRtl ? 'الأسئلة الشائعة' : 'FAQ' }}
                        </button>
                        <button class="gca-tab-btn" role="tab" data-tab="accreditation" aria-selected="false">
                            <i class="fas fa-certificate"></i>
                            {{ $isRtl ? 'التأهيل والاعتماد' : 'Qualification & Accreditation' }}
                        </button>
                    </div>

                    {{-- ==================== التاب الأول: مقدمة عن المركز ==================== --}}
                    <div class="gca-tab-pane active" id="tab-intro">

                        {{-- النص العربي --}}
                        <div class="gca-text-section" dir="rtl" style="direction:rtl!important; text-align:right!important;" data-aos="fade-up" data-aos-delay="80">
                            <div class="gca-lang-pill" style="direction:rtl;">
                                <i class="fas fa-globe"></i> عربي
                            </div>
                            <div class="gca-prose text-end" style="direction:rtl!important; text-align:right!important;">
                                <p>تأسس مركز التأهيل والمواصفات الفنية وخدمة الاعتماد (GCA) عام 1998م كأحد الأذرع الفنية المتخصصة لدى شركة توزيع كهرباء محافظات غزة، ليكون المرجع الفني الضامن لجودة واستدامة مكونات الشبكة الكهربائية. وعلى مدار أكثر من 25 عامًا من الخبرة المتراكمة، اضطلع المركز بدور محوري في حماية الشبكة، وتطوير مواصفاتها، وتعزيز موثوقيتها وفق أفضل الممارسات الهندسية.</p>
                                <p>ويُعد المركز حالياً حجر الاساس في مرحلة إعادة الإعمار المقبلة، والتحول نحو بنية تحتية كهربائية أكثر أمانًا وموثوقية وذكاءً، وخطوة استراتيجية محورية.</p>
                                <p>ينطلق المركز من رؤية واضحة تتمثل في الريادة كمرجع فني مستقل، يُعنى بصياغة المواصفات وتطوير جودة الصناعات الكهربائية، بما يواكب التحول العالمي نحو الشبكات الذكية ويعزز كفاءة الأداء التشغيلي لشبكات التوزيع.</p>
                                <p>يعتمد المركز منظومة فحص وتدقيق رقمية متقدمة، تستند إلى أفضل المعايير والممارسات الدولية المعتمدة من جهات مرجعية مثل IEC وIEEE وISO، لضمان أن جميع المواد والمكونات الكهربائية – من البنية التحتية إلى الأجهزة الطرفية – مطابقة لأعلى متطلبات السلامة والموثوقية والتوافق التشغيلي.</p>
                                <p>وتُعد خدمة Gedco Certification of Approval (GCA) بوابة العبور الفنية نحو السوق، إذ تشكّل شرطًا أساسيًا للمشاركة في مناقصات ومشاريع الشركة، وتمنح الموردين والمصنّعين ميزة تنافسية حقيقية عبر اعتماد منتجاتهم رسميًا، وتعزيز ثقة الجهات المنفذة والمستخدم النهائي بها. كما تسهم الخدمة في حماية الشبكة الكهربائية من المخاطر الفنية، ومنع إدخال مكونات غير مطابقة قد تؤثر على استقرار النظام أو استدامته.</p>
                                <p>إن المركز لا يقدّم اعتمادًا فنيًا فحسب، بل يؤسس لشراكة مهنية طويلة الأمد، قائمة على الشفافية، والالتزام بالمواصفات، والتطوير المستدام، ليكون نقطة الالتقاء بين الجودة، والابتكار، ومتطلبات إعادة الإعمار، وبوابة موثوقة لبناء شبكة كهربائية حديثة تلبّي احتياجات الحاضر وتستشرف المستقبل.</p>
                            </div>
                        </div>

                        <div class="gca-divider"></div>

                        {{-- النص الإنجليزي --}}
                        <div class="gca-text-section" dir="ltr" style="direction:ltr!important; text-align:left!important;" data-aos="fade-up" data-aos-delay="80">
                            <div class="gca-lang-pill" style="direction:ltr;">
                                <i class="fas fa-globe"></i> English
                            </div>
                            <div class="gca-prose text-start" style="direction:ltr!important; text-align:left!important;">
                                <p style="text-align:left;">The Qualification and Technical Specification Center, and Accreditation Service (GCA) was established in 1998 as one of the specialized technical arms of the Gaza Electricity Distribution Company, serving as the authoritative technical reference for ensuring the quality and sustainability of electrical network components. Over more than 25 years of accumulated experience, the Center has played a pivotal role in safeguarding the power network, developing its technical specifications, and enhancing its reliability in accordance with best engineering practices.</p>
                                <p style="text-align:left;">Today, the Center stands at the forefront of the upcoming reconstruction phase and the transition toward a safer, more reliable, and smarter electrical infrastructure, representing a key strategic pillar in this critical stage.</p>
                                <p style="text-align:left;">The Center is driven by a clear vision of leadership as an independent technical reference dedicated to drafting specifications and advancing the quality of electrical industries, in line with the global shift toward smart grids and the enhancement of operational efficiency across distribution networks.</p>
                                <p style="text-align:left;">The Center adopts an advanced digital inspection and auditing system based on internationally recognized standards and best practices issued by reference bodies such as IEC, IEEE, and ISO. This ensures that all electrical materials and components—ranging from infrastructure elements to end-user devices—fully comply with the highest requirements of safety, reliability, and operational interoperability.</p>
                                <p style="text-align:left;">The Gedco Certification of Approval (GCA) service serves as the official technical gateway to market access, constituting a mandatory requirement for participation in the Company's tenders and projects. It provides suppliers and manufacturers with a genuine competitive advantage through formal product certification, while strengthening the confidence of implementing entities and end users alike. The service also plays a vital role in protecting the electrical network from technical risks by preventing the introduction of non-compliant components that could compromise system stability or sustainability.</p>
                                <p style="text-align:left;">The Center does not merely offer technical certification; it establishes long-term professional partnerships founded on transparency, compliance with specifications, and sustainable development. It stands as a convergence point for quality, innovation, and reconstruction requirements, and as a trusted gateway toward building a modern electrical network that meets present needs and anticipates future demands.</p>
                            </div>
                        </div>

                        <div class="gca-divider"></div>

                        {{-- الصورة --}}
                        <div class="gca-image-section" data-aos="fade-up" data-aos-delay="80">
                            <picture>
                                <source srcset="{{ asset('assets/site/images/gca/gca.webp') }}" type="image/webp">
                                <img src="{{ asset('assets/site/images/gca/gca.webp') }}"
                                     alt="{{ $isRtl ? 'مركز التأهيل والمواصفات الفنية' : 'GCA Center' }}">
                            </picture>
                        </div>

                        <div class="gca-divider"></div>

                        {{-- زر طلب الانضمام --}}
                        <div class="gca-cta-section" data-aos="fade-up" data-aos-delay="80">
                            <p class="gca-cta-desc">
                                @if($isRtl)
                                    للاستفسار أو تقديم طلب الانضمام والحصول على شهادة اعتماد GCA، تواصل معنا.
                                @else
                                    For inquiries or to submit a GCA certification application, contact us.
                                @endif
                            </p>
                            <a href="https://gedco.ps/electricalCertification/admin/login"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="gca-join-btn">
                                @if($isRtl)
                                    <span>طلب الانضمام</span>
                                    <i class="fas fa-arrow-left"></i>
                                @else
                                    <span>Apply to Join</span>
                                    <i class="fas fa-arrow-right"></i>
                                @endif
                            </a>
                        </div>

                    </div>{{-- /tab-intro --}}

                    {{-- ==================== التاب الثاني: الأسئلة الشائعة ==================== --}}
                    <div class="gca-tab-pane" id="tab-faq">

                        {{-- ===== الأسئلة الشائعة - عربي ===== --}}
                        <div class="gca-faq-section" dir="rtl" style="direction:rtl!important;">
                            <div class="gca-lang-pill" style="direction:rtl; margin-bottom:1.5rem;">
                                <i class="fas fa-globe"></i> عربي
                            </div>

                            {{-- س1 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>١. ما هو الهدف الأساسي من هذه اللائحة؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>تنظيم واعتماد المواد الكهربائية (الجهد المتوسط والمنخفض وأنظمة الطاقة المتجددة)، لضمان مطابقتها للمعايير الفنية (المحلية والدولية)، وحماية الشبكات والمشتركين من المخاطر، وتقليل الفاقد الكهربائي.</p>
                                </div>
                            </div>

                            {{-- س2 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٢. من هي الجهات المُلزمة بتطبيقها؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>المستوردون، الشركات المصنعة، والمقاولون المشاركون في العطاءات الشركة.</p>
                                </div>
                            </div>

                            {{-- س3 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٣. ما هو الفرق بين "التأهيل العام" للشركات و"التأهيل الخاص" للمواد؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p><strong>التأهيل العام:</strong> عملية تقييم للقدرات الفنية، الإدارية، والمالية للشركات أو المصانع (مثل حجم المبيعات، المعدات، والخبرات السابقة). وهو شرط أساسي مسبق للمشاركة في العطاءات.</p>
                                    <p><strong>التأهيل الخاص:</strong> إجراء فني يختص بـ "المادة الكهربائية" نفسها (مثل العدادات، المحولات، أو الكابلات) لضمان اجتيازها للاختبارات الفنية ومطابقتها للمواصفات المعتمدة قبل إدخالها للشبكة.</p>
                                </div>
                            </div>

                            {{-- س4 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٤. ما هي المواد والأجهزة التي تشملها هذه اللائحة؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>تشمل اللائحة قسمين رئيسيين:</p>
                                    <ul style="padding-right:1.25rem; margin-bottom:0;">
                                        <li><strong>الأجهزة الطرفية:</strong> مثل العدادات، اللوحات، القواطع، والمكثفات.</li>
                                        <li><strong>مكونات البنية التحتية:</strong> مثل المحولات، الكابلات، المفاتيح، أنظمة التحكم (SCADA)، ومكونات أنظمة الطاقة المتجددة.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- س5 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٥. هل يُسمح باستخدام أجهزة كهربائية مستعملة أو مجددة في مشاريع الشبكة؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>يُمنع منعاً باتاً؛ حيث لا تصدر شهادات تأهيل أو اعتماد لأي أجهزة مستعملة أو مجددة، وذلك لضمان أعلى معايير الجودة والسلامة (المادة 11).</p>
                                </div>
                            </div>

                            {{-- س6 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٦. ما هي مدة سريان شهادات التأهيل والاعتماد، وما هي شروط التجديد؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>تختلف مدة السريان حسب نوع التأهيل:</p>
                                    <ul style="padding-right:1.25rem; margin-bottom:0;">
                                        <li><strong>التأهيل العام:</strong> يسري لمدة 3 سنوات.</li>
                                        <li><strong>التأهيل الخاص (للأجهزة الطرفية):</strong> يسري لمدة سنة واحدة أو 3 سنوات (حسب تقييم الجهة المختصة).</li>
                                        <li><strong>التأهيل الخاص (لمكونات البنية التحتية):</strong> يسري لمدة سنة واحدة.</li>
                                        <li><strong>التجديد:</strong> يجب تقديم طلب التجديد الرسمي قبل 30 يوماً على الأقل من تاريخ انتهاء صلاحية الشهادة.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- س7 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٧. ما هي أبرز الخطوات والمستندات المطلوبة للتقدم بطلب الاعتماد للمواد؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>يتم تقديم الطلب عبر الموقع الإلكتروني لمركز التأهيل والمواصفات الفنية بالشركة، مرفقاً بالمستندات التالية:</p>
                                    <ul style="padding-right:1.25rem; margin-bottom:0;">
                                        <li>تفاصيل الضمانات الفنية وشهادة إعلان المطابقة (Declaration of Conformity).</li>
                                        <li>تقارير فحص النوع (Type Test) صادرة عن مختبرات خارجية معتمدة دولياً (ISO/IEC 17025).</li>
                                        <li>سجلات المبيعات لآخر 5 سنوات وخطابات مرجعية من العملاء.</li>
                                        <li>تقديم عينات من الأجهزة لاختبارها فنياً من قبل مختبرات الشركة أو جهات معتمدة للتأكد من التوافق الكهرومغناطيسي وكفاءة الطاقة وسلامة التشغيل.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- س8 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false">
                                    <span>٨. ما هي المرجعيات والمعايير الفنية التي يتم بناءً عليها تقييم واعتماد المواد؟</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer">
                                    <p>تستند الفحوصات إلى معايير عالمية مثل اللجنة الدولية الكهروتقنية (IEC)، ومعهد مهندسي الكهرباء والإلكترونيات (IEEE)، بالإضافة إلى المعايير المحلية المعتمدة من سلطة الطاقة.</p>
                                </div>
                            </div>

                        </div>

                        <div class="gca-divider" style="margin:0 2.5rem;"></div>

                        {{-- ===== الأسئلة الشائعة - English ===== --}}
                        <div class="gca-faq-section" dir="ltr" style="direction:ltr!important; text-align:left!important;">
                            <div class="gca-lang-pill" style="direction:ltr; margin-bottom:1.5rem;">
                                <i class="fas fa-globe"></i> English
                            </div>

                            {{-- Q1 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>1. What is the primary objective of this bylaw?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>To regulate and accredit electrical materials (medium and low voltage, and renewable energy systems) to ensure their compliance with technical standards (local and international), protect networks and subscribers from risks, and reduce electrical losses.</p>
                                </div>
                            </div>

                            {{-- Q2 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>2. Who are the entities obligated to comply with it?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>Importers, manufacturers, and contractors participating in the Company's tenders.</p>
                                </div>
                            </div>

                            {{-- Q3 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>3. What is the difference between "General Qualification" for companies and "Special Qualification" for materials?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p><strong>General Qualification:</strong> An evaluation process of the technical, administrative, and financial capabilities of companies or factories (such as sales volume, equipment, and previous experience). It is a mandatory prerequisite for participating in tenders.</p>
                                    <p><strong>Special Qualification:</strong> A technical procedure specific to the "electrical material" itself (such as meters, transformers, or cables) to ensure it passes technical tests and complies with approved specifications before being integrated into the network.</p>
                                </div>
                            </div>

                            {{-- Q4 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>4. What materials and devices are covered by this bylaw?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>The bylaw covers two main categories:</p>
                                    <ul style="padding-left:1.25rem; margin-bottom:0;">
                                        <li><strong>Terminal Devices (End-User Devices):</strong> Such as meters, panels, circuit breakers, and capacitors.</li>
                                        <li><strong>Infrastructure Components:</strong> Such as transformers, cables, switches, control systems (SCADA), and components of renewable energy systems.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Q5 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>5. Is the use of used or refurbished electrical equipment allowed in network projects?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>It is strictly prohibited; no qualification or accreditation certificates are issued for any used or refurbished devices, in order to ensure the highest standards of quality and safety (Article 11).</p>
                                </div>
                            </div>

                            {{-- Q6 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>6. What is the validity period of the qualification and accreditation certificates, and what are the conditions for renewal?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>The validity period varies depending on the type of qualification:</p>
                                    <ul style="padding-left:1.25rem; margin-bottom:0;">
                                        <li><strong>General Qualification:</strong> Valid for 3 years.</li>
                                        <li><strong>Special Qualification (for Terminal Devices):</strong> Valid for 1 year or 3 years (depending on the assessment by the competent authority).</li>
                                        <li><strong>Special Qualification (for Infrastructure Components):</strong> Valid for 1 year.</li>
                                        <li><strong>Renewal:</strong> An official renewal request must be submitted at least 30 days prior to the certificate's expiration date.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Q7 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>7. What are the key steps and required documents to apply for material accreditation?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>The application is submitted through the website of the Company's Qualification and Technical Specification Center, accompanied by the following documents:</p>
                                    <ul style="padding-left:1.25rem; margin-bottom:0;">
                                        <li>Details of the Technical Guarantees and the Declaration of Conformity.</li>
                                        <li>Type Test reports issued by internationally accredited external laboratories (ISO/IEC 17025).</li>
                                        <li>Sales records for the past 5 years and reference letters from clients.</li>
                                        <li>Submission of device samples for technical testing by the Company's laboratories or accredited entities to ensure electromagnetic compatibility, energy efficiency, and operational safety.</li>
                                    </ul>
                                </div>
                            </div>

                            {{-- Q8 --}}
                            <div class="gca-faq-item">
                                <button class="gca-faq-question" type="button" aria-expanded="false" style="text-align:left;">
                                    <span>8. What are the technical references and standards upon which materials are evaluated and accredited?</span>
                                    <span class="faq-icon"><i class="fas fa-plus"></i></span>
                                </button>
                                <div class="gca-faq-answer" style="text-align:left;">
                                    <p>Testing is based on global standards such as the International Electrotechnical Commission (IEC) and the Institute of Electrical and Electronics Engineers (IEEE), in addition to the local standards approved by the Energy Authority.</p>
                                </div>
                            </div>

                        </div>
                    </div>{{-- /tab-faq --}}

                    {{-- ==================== التاب الثالث: التأهيل والاعتماد ==================== --}}
                    <div class="gca-tab-pane" id="tab-accreditation">
                        <div class="gca-pdf-section" dir="{{ $dir }}">

                            <p class="gca-pdf-intro">
                                {{ $isRtl
                                    ? 'يمكنكم تحميل وثائق التأهيل والاعتماد الخاصة بمركز GCA باللغتين العربية والإنجليزية من خلال الروابط أدناه.'
                                    : 'You can download the GCA qualification and accreditation documents in both Arabic and English using the links below.'
                                }}
                            </p>

                            <div class="gca-pdf-grid">

                                {{-- PDF عربي --}}
                                <a href="{{ asset('assets/site/files/gca/gca-accreditation-ar.pdf') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="gca-pdf-card"
                                   download>
                                    <div class="gca-pdf-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="gca-pdf-label">وثيقة التأهيل والاعتماد</div>
                                    <div class="gca-pdf-sub">النسخة العربية</div>
                                    <span class="gca-pdf-dl-btn">
                                        <i class="fas fa-download"></i>
                                        تحميل
                                    </span>
                                </a>

                                {{-- PDF إنجليزي --}}
                                <a href="{{ asset('assets/site/files/gca/gca-accreditation-en.pdf') }}"
                                   target="_blank"
                                   rel="noopener noreferrer"
                                   class="gca-pdf-card"
                                   download>
                                    <div class="gca-pdf-icon">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="gca-pdf-label">Qualification & Accreditation Document</div>
                                    <div class="gca-pdf-sub">English Version</div>
                                    <span class="gca-pdf-dl-btn">
                                        <i class="fas fa-download"></i>
                                        Download
                                    </span>
                                </a>

                            </div>
                        </div>
                    </div>{{-- /tab-accreditation --}}

                </div>{{-- /gca-card --}}

            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
(function () {
    const tabBtns = document.querySelectorAll('.gca-tab-btn');
    const tabPanes = document.querySelectorAll('.gca-tab-pane');

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            const target = btn.dataset.tab;

            // تحديث الأزرار
            tabBtns.forEach(function (b) {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');

            // تحديث المحتوى
            tabPanes.forEach(function (pane) {
                pane.classList.remove('active');
            });
            const activePane = document.getElementById('tab-' + target);
            if (activePane) activePane.classList.add('active');
        });
    });

    // FAQ accordion
    document.querySelectorAll('.gca-faq-question').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const answer = btn.nextElementSibling;
            const isOpen = btn.getAttribute('aria-expanded') === 'true';

            // أغلق الكل
            document.querySelectorAll('.gca-faq-question').forEach(function (b) {
                b.setAttribute('aria-expanded', 'false');
                b.nextElementSibling.classList.remove('open');
            });

            // افتح الحالي إن لم يكن مفتوحًا
            if (!isOpen) {
                btn.setAttribute('aria-expanded', 'true');
                answer.classList.add('open');
            }
        });
    });
}());
</script>
@endpush

@endsection
