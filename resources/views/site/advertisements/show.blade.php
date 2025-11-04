@extends('layouts.site')

@section('title', $ad->TITLE)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($ad->BODY), 150))

@push('styles')
    <style>
        /* نفس إعدادات الهيدر والصورة من صفحة الإعلانات */
        header { position: fixed !important; top: 0 !important; width: 100% !important; transition: all .3s ease !important; }
        header.smaller { position: fixed !important; top: 0 !important; }
        section#subheader { margin-top: 8px !important; padding-top: 120px !important; padding-bottom: 80px !important; }
        @media (max-width: 991px) {
            section#subheader { margin-top: 25px !important; padding-top: 60px !important; padding-bottom: 60px !important; }
            section#subheader .container { max-width: 100% !important; padding-left: 15px !important; padding-right: 15px !important; }
        }

        :root {
            --primary: #0d6efd;
            --danger: #dc3545;
            --success: #198754;
            --warning: #ffc107;
            --dark: #212529;
            --light: #f8f9fa;
            --gray: #6c757d;
            --orange-gradient: linear-gradient(135deg, #ff8c00, #ff6b35);
            --blue-gradient: linear-gradient(135deg, #007bff, #0056b3);
        }

        .content-preview {
            line-height: 2.1 !important;
            font-size: 1.15rem !important;
            color: #1a202c !important;
            text-align: right !important;
            direction: rtl !important;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
            font-weight: 500 !important;
        }
        .content-preview * {
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
            color: #1a202c !important;
        }
        .content-preview p {
            margin-bottom: 1.5rem !important;
            text-align: justify !important;
            font-size: 1.15rem !important;
            line-height: 2.1 !important;
        }
        .content-preview span,
        .content-preview div {
            font-size: 1.15rem !important;
            line-height: 2.1 !important;
        }
        .content-preview ul, .content-preview ol {
            list-style: none;
            padding: 0;
            margin: 1.5rem 0;
        }
        .content-preview li {
            margin-bottom: 1rem;
            padding: 0.8rem 1.2rem;
            padding-right: 3rem;
            position: relative;
            background: linear-gradient(to left, rgba(26,84,144,0.03), transparent);
            border-right: 3px solid #ff6b35;
            border-radius: 0 8px 8px 0;
            transition: all 0.3s ease;
            font-size: 1.15rem !important;
            line-height: 2.1 !important;
            font-family: 'Cairo', 'Tajawal', 'Segoe UI', sans-serif !important;
        }
        .content-preview li:hover {
            background: linear-gradient(to left, rgba(26,84,144,0.05), transparent);
            border-right-color: #1a5490;
            transform: translateX(-3px);
        }
        .content-preview li::before {
            content: '✓';
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #ff6b35;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .content-preview strong,
        .content-preview b {
            color: #1a5490;
            font-weight: 800;
            background: linear-gradient(120deg, #1a5490 0%, #2980b9 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.05em;
        }
        .content-preview h2,
        .content-preview h3,
        .content-preview h4 {
            color: #1a5490;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        .content-preview h2 { font-size: 1.5rem; }
        .content-preview h3 { font-size: 1.3rem; }
        .content-preview h4 { font-size: 1.15rem; }

        /* نص إعلان رسمي أنيق */
        .official-label {
            display: inline-block;
            padding: 6px 18px;
            background: linear-gradient(135deg, rgba(220,53,69,0.1), rgba(255,107,53,0.1));
            border: 2px solid #dc3545;
            border-radius: 25px;
            color: #dc3545;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1rem;
            box-shadow: 0 4px 15px rgba(220,53,69,0.15);
        }
        .official-label::before {
            content: '✓';
            margin-left: 6px;
            font-weight: bold;
        }

        .pdf-card {
            position: sticky;
            top: 1.5rem;
            border-radius: 20px;
            overflow: hidden;
            background: linear-gradient(145deg, #ffffff, #f8fbff);
            box-shadow:
                0 20px 60px rgba(255,107,53,.12),
                0 0 0 1px rgba(255,107,53,.1),
                inset 0 1px 0 rgba(255,255,255,.9);
            transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255,107,53,.1);
        }
        .pdf-card:hover {
            transform: translateY(-12px) scale(1.02);
            box-shadow:
                0 30px 80px rgba(255,107,53,.2),
                0 0 0 1px rgba(255,107,53,.2),
                inset 0 1px 0 rgba(255,255,255,1);
        }

        .pdf-header {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c00 50%, #ff6b35 100%);
            background-size: 200% 100%;
            animation: headerGradient 3s ease infinite;
            color: white;
            padding: 1.2rem 1.5rem;
            font-weight: 700;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(255,107,53,.3);
        }
        .pdf-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,.2) 0%, transparent 70%);
            animation: shine 4s linear infinite;
        }
        @keyframes headerGradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        @keyframes shine {
            0% { transform: translate(-50%, -50%) rotate(0deg); }
            100% { transform: translate(-50%, -50%) rotate(360deg); }
        }

        .pdf-info {
            background: #fff;
            padding: .75rem 1rem;
            font-size: .85rem;
            color: #495057;
            border-top: 1px dashed #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* توحيد أنيميشن السوشال بالفوتر */
        .footer-social-modern a { transition: all .3s ease !important; }
        .footer-social-modern a:hover { animation: pulse .8s ease-in-out infinite !important; }

        iframe {
            border: none;
            width: 100%;
            height: 100%;
            min-height: 520px;
            background: #f8f9fa;
        }

        .content-card {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            background:
                radial-gradient(circle at 20% 30%, rgba(255,107,53,0.04) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(26,84,144,0.04) 0%, transparent 40%),
                linear-gradient(135deg, rgba(255,247,237,0.3) 0%, rgba(240,248,255,0.3) 100%),
                #ffffff;
            box-shadow:
                0 25px 70px rgba(26,84,144,.1),
                0 10px 25px rgba(255,107,53,.05),
                0 0 0 1px rgba(26,84,144,.06),
                inset 0 1px 0 rgba(255,255,255,1);
            transition: all .4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(26,84,144,.08);
        }
        .content-card:hover {
            transform: translateY(-8px);
            box-shadow:
                0 30px 80px rgba(26,84,144,.15),
                0 15px 35px rgba(255,107,53,.1),
                0 0 0 1px rgba(255,107,53,.15),
                inset 0 1px 0 rgba(255,255,255,1);
        }
        .content-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35, #1a5490, #ff6b35);
            background-size: 200% 100%;
            animation: gradientMove 3s linear infinite;
            border-radius: 20px 20px 0 0;
            z-index: 3;
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        /* Animation pulse للسوشال */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @media (max-width: 992px) {
            .pdf-card { position: static; margin-top: 2rem; }
            .content-card::before { height: 3px; }
            .content-card { border-radius: 15px; }
            .official-label {
                font-size: .7rem;
                padding: 5px 15px;
            }
            .card-body { padding: 2rem 1.5rem !important; padding-top: 2.5rem !important; }
            h1 { font-size: 1.6rem !important; }
            .content-preview { font-size: 1.05rem !important; line-height: 2 !important; }
            .content-preview * { font-size: 1.05rem !important; }
            .content-preview p { margin-bottom: 1.2rem !important; font-size: 1.05rem !important; line-height: 2 !important; }
            .content-preview span,
            .content-preview div { font-size: 1.05rem !important; line-height: 2 !important; }
            .content-preview li {
                padding: 0.7rem 1rem;
                padding-right: 2.5rem;
                margin-bottom: 0.8rem;
                font-size: 1.05rem !important;
                line-height: 2 !important;
            }
            .content-preview li::before {
                right: 0.8rem;
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <!-- Subheader -->
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="url({{ asset('assets/site/images/site2.webp') }})">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">الإعلانات والوظائف</h1>
                    <div class="w-100 mt-2">
                        <ul class="crumb">
                            <li><a href="{{ url('/') }}">الرئيسية</a></li>
                            <li><a href="{{ route('site.advertisements.index') }}">الإعلانات والوظائف</a></li>
                            <li class="active">التفاصيل</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>

    {{-- زر الرجوع محذوف حسب الطلب --}}

    <!-- المحتوى الرئيسي -->
    <div class="container py-4">
        <div class="row g-3">
            <!-- المحتوى -->
            <div class="col-lg-8">
                <div class="content-card position-relative bg-white">
                    <div class="card-body p-5" style="padding-top: 3rem !important; position: relative; z-index: 2;">
                        <!-- العنوان الخرافي -->
                        <div class="mb-4 text-center" style="position:relative;">
                            <!-- نص إعلان رسمي -->
                            <div class="official-label">إعلان رسمي</div>

                            <h1 class="fw-bold mb-3" style="
                                color: #1a5490;
                                font-size: 2rem;
                                line-height: 1.4;
                                text-align: center;
                                margin: 0 auto;
                                background: linear-gradient(135deg, #1a5490 0%, #2980b9 50%, #ff6b35 100%);
                                -webkit-background-clip: text;
                                -webkit-text-fill-color: transparent;
                                background-clip: text;
                                text-shadow: 0 4px 10px rgba(26,84,144,.15);
                                animation: titleFade 1s ease-out;
                                letter-spacing: -0.5px;
                            ">
                                {{ $ad->TITLE }}
                            </h1>

                            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill" style="
                                background: linear-gradient(135deg, rgba(255,107,53,.1), rgba(26,84,144,.1));
                                border: 1.5px solid rgba(255,107,53,.3);
                                font-size: .85rem;
                                color: #2c3e50;
                                font-weight: 600;
                                box-shadow: 0 4px 15px rgba(255,107,53,.15);
                            ">
                                <i class="ri-calendar-event-fill" style="color:#ff6b35; font-size:1rem;"></i>
                                <span>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('d') }} {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->locale('ar')->translatedFormat('F') }} {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('Y') }}</span>
                            </div>
                        </div>

                        <!-- فاصل فني -->
                        <div class="mb-4 d-flex align-items-center justify-content-center gap-2">
                            <div style="width:40px; height:2px; background:linear-gradient(to right, transparent, #ff6b35); border-radius:2px;"></div>
                            <div style="width:8px; height:8px; background:#ff6b35; border-radius:50%; box-shadow: 0 0 10px rgba(255,107,53,.5);"></div>
                            <div style="width:100px; height:3px; background:linear-gradient(90deg, #ff6b35, #1a5490, #ff6b35); border-radius:2px;"></div>
                            <div style="width:8px; height:8px; background:#1a5490; border-radius:50%; box-shadow: 0 0 10px rgba(26,84,144,.5);"></div>
                            <div style="width:40px; height:2px; background:linear-gradient(to left, transparent, #1a5490); border-radius:2px;"></div>
                        </div>

                        <style>
                            @keyframes titleFade {
                                from { opacity: 0; transform: translateY(-20px); }
                                to { opacity: 1; transform: translateY(0); }
                            }
                        </style>

                        @if($ad->BODY)
                            <div class="content-preview">
                                {!! $ad->BODY !!}
                            </div>
                        @else
                            <p class="text-center text-muted py-4 fst-italic">لا يوجد محتوى نصي</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PDF -->
            <div class="col-lg-4">
                <div class="pdf-card">
                    <div class="pdf-header">
                        <span><i class="ri-file-pdf-line me-2"></i> الملف الرسمي</span>
                        @if($ad->PDF)
                            <a href="{{ Storage::url($ad->PDF) }}" download class="text-white">
                                <i class="ri-download-line"></i>
                            </a>
                        @endif
                    </div>
                    <div>
                        @if($ad->PDF)
                            <div class="ratio ratio-16x9">
                                <iframe src="{{ Storage::url($ad->PDF) }}#toolbar=0&navpanes=0&scrollbar=0"
                                        loading="lazy"></iframe>
                            </div>
                            <div class="pdf-info">
                                <span class="text-truncate" style="max-width: 160px;">{{ basename($ad->PDF) }}</span>
                                <span>
                                    {{ number_format(\Illuminate\Support\Facades\Storage::disk('public')->size($ad->PDF) / 1024, 1) }} ك.ب
                                </span>
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="ri-file-close-line" style="font-size: 3rem;"></i>
                                <p class="mt-3 mb-0">لا يوجد ملف PDF</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($ad->PDF)
                    <div class="text-center mt-3">
                        <a href="{{ Storage::url($ad->PDF) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">
                            <i class="ri-eye-line"></i> عرض
                        </a>
                        <a href="{{ Storage::url($ad->PDF) }}" download class="btn btn-success btn-sm">
                            <i class="ri-download-line"></i> تحميل
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
