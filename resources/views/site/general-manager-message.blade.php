@extends('layouts.site')

@section('title', 'كلمة مدير عام الشركة')
@section('meta_description', 'كلمة مدير عام شركة توزيع كهرباء محافظات غزة د. محمد طه الأسطل')

@push('styles')
    <style>
        .gm-suite {
            position: relative;
            margin: 0;
            min-height: 100vh;
            padding: clamp(120px, 18vw, 168px) 0;
            overflow: hidden;
            background: url('{{ asset('assets/site/images/site3.webp') }}') center center / cover no-repeat;
        }

        .gm-suite::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(185deg, rgba(44, 52, 63, 0.62) 0%, rgba(242, 106, 0, 0.52) 55%, rgba(255, 189, 120, 0.38) 100%);
            pointer-events: none;
        }

        .gm-suite::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 75% 20%, rgba(255, 255, 255, 0.34), transparent 58%),
                radial-gradient(circle at 18% 78%, rgba(255, 255, 255, 0.26), transparent 62%),
                linear-gradient(120deg, rgba(255, 255, 255, 0.2), transparent 68%);
            opacity: 0.45;
            mix-blend-mode: screen;
            pointer-events: none;
        }

        .gm-suite__container {
            position: relative;
            z-index: 1;
        }

        .gm-suite__panel {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(0, 0.85fr);
            gap: clamp(28px, 5vw, 44px);
            align-items: stretch;
            background: rgba(255, 255, 255, 0.92);
            border-radius: clamp(24px, 4vw, 32px);
            padding: clamp(36px, 6vw, 56px);
            box-shadow:
                0 20px 40px rgba(10, 20, 30, 0.18),
                0 6px 18px rgba(10, 20, 30, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(14px);
            transform: translateY(26px);
            opacity: 0;
            animation: gm-suite-fade 0.9s ease-out forwards;
        }

        .gm-suite__overlay {
            position: absolute;
            inset: clamp(18px, 3vw, 26px);
            border-radius: clamp(18px, 3vw, 24px);
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.12), transparent 70%),
                radial-gradient(circle at 20% 25%, rgba(13, 138, 166, 0.06), transparent 65%),
                radial-gradient(circle at 78% 70%, rgba(242, 106, 0, 0.07), transparent 68%);
            border: 1px solid rgba(13, 138, 166, 0.08);
            pointer-events: none;
        }

        .gm-suite__panel::before,
        .gm-suite__panel::after {
            content: "";
            position: absolute;
            left: 50%;
            width: clamp(320px, 80%, 420px);
            height: clamp(7px, 1.4vw, 8px);
            border-radius: 999px;
            pointer-events: none;
            transform: translateX(-50%);
            overflow: hidden;
        }

        .gm-suite__panel::before {
            top: clamp(16px, 2.6vw, 24px);
            background: linear-gradient(90deg, rgba(255, 160, 72, 0.75), rgba(242, 106, 0, 0.92), rgba(255, 235, 204, 0.0));
        }

        .gm-suite__panel::after {
            bottom: clamp(16px, 2.6vw, 24px);
            background: linear-gradient(270deg, rgba(255, 160, 72, 0.75), rgba(242, 106, 0, 0.92), rgba(255, 235, 204, 0.0));
        }

        .gm-suite__panel::before {
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.35),
                0 10px 18px rgba(10, 20, 30, 0.18);
        }

        .gm-suite__panel::after {
            box-shadow:
                0 0 0 1px rgba(255, 255, 255, 0.3),
                0 -10px 18px rgba(10, 20, 30, 0.16);
        }

        .gm-suite__panel::before {
            border: none;
        }

        .gm-suite__panel::before::after,
        .gm-suite__panel::after::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(120deg, rgba(255, 255, 255, 0.0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0.0));
            transform: translateX(-120%);
            animation: gm-suite-gloss 5s ease-in-out infinite;
        }

        .gm-suite__panel::after::after {
            animation-delay: 2.5s;
        }

        @keyframes gm-suite-gloss {
            0% {
                transform: translateX(-120%);
                opacity: 0.5;
            }
            45% {
                transform: translateX(120%);
                opacity: 0.94;
            }
            100% {
                transform: translateX(120%);
                opacity: 0;
            }
        }

        .gm-suite__corner {
            position: absolute;
            width: clamp(54px, 8vw, 72px);
            height: clamp(54px, 8vw, 72px);
            pointer-events: none;
            z-index: 1;
        }

        .gm-suite__corner--tl {
            top: 0;
            left: 0;
            background: linear-gradient(135deg, rgba(242, 106, 0, 0.95), rgba(255, 164, 73, 0.9));
            clip-path: polygon(0 0, 100% 0, 0 100%);
            border-top-left-radius: inherit;
        }

        .gm-suite__corner--br {
            bottom: 0;
            right: 0;
            background: linear-gradient(315deg, rgba(242, 106, 0, 0.95), rgba(255, 164, 73, 0.9));
            clip-path: polygon(100% 100%, 100% 0, 0 100%);
            border-bottom-right-radius: inherit;
        }

        .gm-suite__content {
            display: grid;
            gap: clamp(18px, 3vw, 28px);
            color: #1f2937;
            position: relative;
        }

        .gm-suite__label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 24px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(242, 106, 0, 0.16), rgba(13, 138, 166, 0.18));
            color: #f26a00;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 14px;
        }

        .gm-suite__label svg {
            width: 18px;
            height: 18px;
        }

        .gm-suite__divider {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(12px, 3vw, 24px);
            margin-block: clamp(12px, 2.5vw, 20px);
        }

        .gm-suite__divider::before,
        .gm-suite__divider::after {
            content: "";
            flex: 1;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, rgba(242, 106, 0, 0.6), rgba(13, 138, 166, 0.45));
        }

        .gm-suite__divider::after {
            background: linear-gradient(270deg, rgba(242, 106, 0, 0.6), rgba(13, 138, 166, 0.45));
        }

        .gm-suite__divider span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 clamp(18px, 2.8vw, 26px);
            height: clamp(28px, 4.5vw, 36px);
            border-radius: clamp(18px, 3.5vw, 24px);
            background: linear-gradient(135deg, rgba(13, 138, 166, 0.95), rgba(13, 138, 166, 0.88));
            color: #fff;
            font-weight: 700;
            letter-spacing: 0.12em;
            font-size: clamp(13px, 2.8vw, 17px);
            box-shadow: 0 0 0 4px rgba(13, 138, 166, 0.16), 0 4px 12px rgba(13, 138, 166, 0.2);
            white-space: nowrap;
            text-shadow: 0 2px 6px rgba(10, 20, 30, 0.18);
        }

        .gm-suite__title {
            margin: 0;
            font-size: clamp(32px, 5.6vw, 44px);
            color: #0d8aa6;
            font-weight: 700;
        }

        .gm-suite__text {
            display: grid;
            gap: clamp(16px, 2.4vw, 22px);
            text-align: justify;
            text-justify: inter-word;
            text-wrap: balance;
            text-align-last: right;
        }

        .gm-suite__text p {
            margin: 0;
            font-size: 18px;
            line-height: 2;
            color: rgba(31, 43, 55, 0.9);
            text-align: inherit;
            hyphens: auto;
        }

        .gm-suite__signature {
            display: grid;
            gap: 6px;
            padding-top: clamp(12px, 2vw, 18px);
            border-top: 1px solid rgba(13, 138, 166, 0.14);
        }

        .gm-suite__signature p {
            margin: 0;
            font-size: 18px;
            color: rgba(31, 43, 55, 0.82);
        }

        .gm-suite__signature p:last-child {
            font-weight: 600;
            color: #f26a00;
        }

        .gm-suite__profile {
            position: relative;
            display: grid;
            gap: clamp(16px, 2.5vw, 20px);
            justify-items: center;
            align-content: start;
            padding: clamp(8px, 2vw, 12px);
            margin-top: clamp(48px, 8vw, 90px);
        }

        .gm-suite__photo {
            width: clamp(230px, 22vw, 280px);
            aspect-ratio: 3 / 4;
            border-radius: clamp(18px, 3vw, 24px);
            object-fit: cover;
            box-shadow: 0 18px 40px rgba(15, 35, 55, 0.18);
        }

        .gm-suite__logo {
            width: clamp(96px, 12vw, 116px);
        }

        .gm-suite__nameplate {
            display: grid;
            gap: 6px;
            text-align: center;
            padding: clamp(12px, 2.4vw, 18px);
            border-radius: clamp(18px, 3vw, 24px);
        }

        .gm-suite__nameplate strong {
            font-size: 20px;
            color: #0d8aa6;
            font-weight: 700;
        }

        .gm-suite__nameplate span {
            font-size: 16px;
            color: #f26a00;
            letter-spacing: 0.02em;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(242, 106, 0, 0.08);
            border: 1px solid rgba(242, 106, 0, 0.25);
            display: inline-block;
            margin: 0 auto;
            margin-top: 4px;
        }

        .gm-suite__nameplate img {
            width: clamp(84px, 12vw, 108px);
            justify-self: center;
            margin-top: 4px;
        }

        @media (max-width: 1024px) {
            .gm-suite__panel {
                grid-template-columns: minmax(0, 1fr);
            }

            .gm-suite__profile {
                max-width: 380px;
                margin-inline: auto;
                margin-top: 0;
            }
        }


        @media (max-width: 640px) {
            .gm-suite {
                margin: 0;
                padding: clamp(110px, 25vw, 140px) 0;
            }

            .gm-suite__panel {
                padding: clamp(28px, 8vw, 36px);
                gap: clamp(24px, 6vw, 32px);
            }

            .gm-suite__label {
                justify-content: center;
            }
        }

        @keyframes gm-suite-fade {
            from {
                opacity: 0;
                transform: translateY(34px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@section('content')
    <section class="gm-suite">
        <div class="container gm-suite__container">
            <div class="gm-suite__panel">
                <span aria-hidden="true" class="gm-suite__corner gm-suite__corner--tl"></span>
                <span aria-hidden="true" class="gm-suite__corner gm-suite__corner--br"></span>
                <span class="gm-suite__overlay"></span>
                <div class="gm-suite__content">
                    <div class="gm-suite__label">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                        </svg>
                        كلمة مدير عام الشركة
                    </div>
                    <div class="gm-suite__divider"><span>بسم الله الرحمن الرحيم</span></div>
                    <div class="gm-suite__text">
                        <p>تُعد شركة توزيع كهرباء محافظات غزة إحدى الركائز الأساسية للبنية التحتية في قطاع غزة ، وحلقة الوصل الحيوية بين مصادر الطاقة واحتياجات المواطنين والمؤسسات.</p>
                        <p>وانطلاقًا من رسالتها الوطنية والإنسانية، تواصل كهرباء غزة أداء دورها بكل التزام ومسؤولية، رغم ما تمر به من ظروف استثنائية وتحديات متلاحقة.</p>
                        <p>لقد كان عام 2023 عامًا صعبًا واستثنائيًا بكل المقاييس، حيث واجهت الشركة خلال العدوان على قطاع غزة دمارًا واسعًا طال شبكاتها ومرافقها ومقراتها، وخسرت عددًا من خيرة أبنائها وكوادرها الذين ارتقوا شهداء أثناء أداء واجبهم المهني والوطني؛ وبرغم ذلك، لم تتوقف الجهود، وسعت الشركة لتوسيع مساحة مساهماتها المجتمعية، واستمرت طواقم الشركة في العمل في أصعب الظروف لضمان استمرارية الخدمات الأساسية ودعم المرافق الحيوية وفق الإمكانات المتاحة.</p>
                        <p>ومع انطلاق موقع الشركة الإلكتروني بحُلّته الجديدة، فإننا نجدد التزامنا بمواصلة طريقنا ومساعينا الجادة في إعادة بناء وتأهيل قطاع توزيع الكهرباء وصولا لتوفير الخدمات الكهربائية لكافة المناطق والمرافق، كما نجدد إلتزامنا باستكمال خططنا المتعلقة بالتحول الرقمي المتكامل، وتعزيز كفاءة منظومة التوزيع، وتوسيع استخدام أنظمة التحكم الذكية (SCADA)، بما يواكب متطلبات المرحلة المقبلة ويعزز من جودة الخدمة وعدالتها واستدامتها.</p>
                        <p>إن رؤيتنا للمستقبل تقوم على أسس الاستدامة، والشفافية، والكفاءة التشغيلية، في سبيل خدمة أبناء شعبنا وتعزيز صمود مؤسساته الحيوية.</p>
                        <p>كما نتطلع، بعون الله، إلى أن تكون المرحلة القادمة مرحلة تعافٍ وبناء، تواكبها منظومة كهرباء أكثر تطورًا وقدرةً على تلبية احتياجات المجتمع بكفاءة ومسؤولية.</p>
                        <p>رحم الله شهداء الوطن وشهداء الشركة، ونسأل الله أن يوفقنا جميعًا لخدمة شعبنا وأهلنا في كل مواقعهم.</p>
                    </div>

                </div>

                <aside class="gm-suite__profile">
                    <img src="{{ asset('assets/site/images/dr.mohammed.webp') }}" alt="د. محمد طه الأسطل" class="gm-suite__photo">
                    <div class="gm-suite__nameplate">
                        <strong>د. محمد طه الأسطل</strong>
                        <span>مدير عام شركة توزيع كهرباء محافظات غزة</span>
                        <img src="{{ asset('assets/site/images/logo-dark.webp') }}" alt="شركة توزيع كهرباء محافظات غزة">
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection

