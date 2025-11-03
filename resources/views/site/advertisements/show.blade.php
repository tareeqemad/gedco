@extends('layouts.site')

@section('title', $ad->TITLE)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($ad->BODY), 150))

@push('styles')
    <style>
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
            line-height: 2;
            font-size: 1.15rem;
            color: #2c3e50;
            text-align: justify;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .content-preview p {
            margin-bottom: 1.4rem;
            animation: fadeInUp 0.8s ease-out;
        }

        /* ختم "إعلان رسمي" */
        .official-stamp {
            position: absolute;
            top: -15px;
            right: -15px;
            background: var(--danger);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 6px 18px rgba(220, 53, 69, 0.4);
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 8px;
            animation: pulse 2s infinite;
            transform: rotate(12deg);
        }
        .official-stamp i {
            font-size: 1.1rem;
            animation: stamp 2s infinite;
        }

        .pdf-card {
            position: sticky;
            top: 1.5rem;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,.12);
            transition: all .3s ease;
            border: 1px solid #e9ecef;
        }
        .pdf-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,.18);
        }

        .pdf-header {
            background: var(--orange-gradient);
            color: white;
            padding: 1rem 1.25rem;
            font-weight: 600;
            font-size: 1.05rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .pdf-header::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(45deg, transparent 30%, rgba(255,255,255,.1) 50%, transparent 70%);
            transform: translateX(-100%);
            animation: shimmer 3s infinite;
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

        /* زر الرجوع – واضح + يعمل + ثابت */
        .back-btn {
            position: fixed !important;
            top: 100px;
            left: 20px;
            z-index: 9999;
            background: #fff;
            color: var(--dark);
            border: 2px solid var(--primary);
            border-radius: 50px;
            padding: .75rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 0 8px 25px rgba(13, 110, 253, 0.25);
            transition: all .3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(13, 110, 253, 0.35);
        }
        .back-btn i {
            font-size: 1.2rem;
        }

        .meta-info {
            font-size: .92rem;
            color: #6c757d;
            background: #f8f9fa;
            padding: 1rem;
            border-radius: .75rem;
            margin-top: 1.5rem;
            border-left: 4px solid var(--primary);
        }
        .meta-info i {
            color: var(--primary);
            font-size: 1.2rem;
            width: 32px;
        }

        iframe {
            border: none;
            width: 100%;
            height: 100%;
            min-height: 520px;
            background: #f8f9fa;
        }

        .content-card {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 12px 30px rgba(0,0,0,.1);
            transition: all .3s ease;
        }
        .content-card:hover {
            box-shadow: 0 20px 40px rgba(0,0,0,.15);
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1) rotate(12deg); }
            50% { transform: scale(1.08) rotate(12deg); }
        }
        @keyframes stamp {
            0%, 100% { transform: rotate(0deg); }
            50% { transform: rotate(-5deg); }
        }
        @keyframes shimmer {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @media (max-width: 992px) {
            .pdf-card { position: static; margin-top: 2rem; }
            .back-btn {
                position: fixed !important;
                bottom: 20px;
                top: auto;
                left: 50%;
                transform: translateX(-50%);
                border-radius: 50px;
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
                    <h1 class="split mb-3 fw-bold d-block w-100">
                        {{ \Illuminate\Support\Str::limit($ad->TITLE, 60) }}
                    </h1>
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

    <!-- زر الرجوع – ثابت وواضح 100% -->
    <a href="{{ route('site.advertisements.index') }}" class="back-btn btn d-inline-flex align-items-center">
        <i class="ri-arrow-left-line"></i> رجوع
    </a>

    <!-- المحتوى الرئيسي -->
    <div class="container py-5">
        <div class="row g-4">
            <!-- المحتوى -->
            <div class="col-lg-8">
                <div class="content-card position-relative bg-white">
                    <!-- ختم "إعلان رسمي" -->
                    <div class="official-stamp">
                        <i class="ri-shield-check-line"></i>
                        <span>إعلان رسمي</span>
                    </div>

                    <div class="card-body p-5">
                        <div class="mb-4">
                            <h3 class="fw-bold text-primary mb-2">{{ $ad->TITLE }}</h3>
                            <div class="text-muted small d-flex align-items-center gap-2">
                                <i class="ri-calendar-line"></i>
                                <span>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('l، d F Y') }}</span>
                                <span class="text-secondary">| {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('H:i') }}</span>
                            </div>
                        </div>

                        @if($ad->BODY)
                            <div class="content-preview">
                                {!! $ad->BODY !!}
                            </div>
                        @else
                            <p class="text-center text-muted py-5 fst-italic">لا يوجد محتوى نصي</p>
                        @endif

                        <!-- معلومات إضافية -->
                        <div class="meta-info">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ri-user-add-line"></i>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $ad->INSERT_USER ?? 'غير معروف' }}</div>
                                            <div class="small">أُضيف في {{ $ad->INSERT_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <i class="ri-refresh-line text-success"></i>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $ad->UPDATE_USER ?? '—' }}</div>
                                            <div class="small">آخر تحديث {{ $ad->UPDATE_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
