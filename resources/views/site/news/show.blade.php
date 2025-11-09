@extends('layouts.site')

@section('title', 'أخبار الشركة | كهرباء غزة')
@section('meta_description', 'اكتشف آخر مستجدات شركة توزيع كهرباء غزة يومياً مع صور وتغطيات متنوعة.')

@push('styles')
    <style>
        :root {
            --news-accent: #ff6b35;
            --news-dark: #1b1f3b;
        }

        section#subheader {
            margin-top: 8px !important;
            padding-top: 140px !important;
            padding-bottom: 100px !important;
        }

        section#subheader h1 {
            font-size: clamp(1.6rem, 4.2vw, 2.6rem);
            letter-spacing: .8px;
            margin-bottom: 0;
            line-height: 1.35;
            padding: 0 1.25rem;
            word-break: break-word;
            white-space: normal;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        #news-article {
            padding: 0 0 4rem;
        }

        #news-article .cover-wrapper {
            position: relative;
            border-radius: 1.5rem;
            overflow: hidden;
            margin-bottom: 2.5rem;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.18);
        }

        #news-article .cover-wrapper img {
            width: 100%;
            height: auto;
            display: block;
        }

        #news-article .article-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2rem;
            margin-bottom: 1.5rem;
            color: #6c757d;
            font-size: .95rem;
        }

        #news-article .article-meta span {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
        }

        #news-article .article-body {
            font-size: 1.05rem;
            line-height: 1.9;
            color: #2c2f3a;
        }

        #news-article .article-body h2,
        #news-article .article-body h3,
        #news-article .article-body h4 {
            color: var(--news-dark);
            margin-top: 1.8rem;
            margin-bottom: 1rem;
        }

        #news-article .article-body img {
            max-width: 100%;
            height: auto;
            border-radius: .75rem;
            margin: 1.5rem auto;
            display: block;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.12);
        }

        #news-article .article-body a {
            color: var(--news-accent);
            text-decoration: underline;
        }

        .news-sidebar {
            position: sticky;
            top: 120px;
        }

        .news-sidebar h5 {
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--news-dark);
            text-align: center;
            width: 100%;
        }

        .news-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .news-sidebar a {
            display: block;
            padding: .9rem 1rem;
            border-radius: 1rem;
            background: #fff;
            border: 1px solid #eef0f6;
            box-shadow: 0 12px 25px rgba(15, 23, 42, 0.08);
            font-weight: 600;
            color: #373c4f;
            text-decoration: none;
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
            text-align: center;
        }

        .news-sidebar a:hover {
            transform: translateY(-6px);
            border-color: rgba(255, 107, 53, 0.45);
            box-shadow: 0 18px 40px rgba(255, 107, 53, 0.22);
            color: var(--news-accent);
        }

        .news-pdf-link {
            margin-top: 2rem;
            display: inline-flex;
            align-items: center;
            gap: .6rem;
            padding: .85rem 1.4rem;
            border-radius: 999px;
            background: var(--news-accent);
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: transform .3s ease, box-shadow .3s ease;
        }

        .news-pdf-link:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 30px rgba(255, 107, 53, .35);
            color: #fff;
        }

        @media (max-width: 991px) {
            .news-sidebar {
                position: static;
                margin-top: 3rem;
                text-align: center;
            }

            section#subheader {
                padding-top: 110px !important;
                padding-bottom: 80px !important;
            }

            section#subheader h1 {
                font-size: 1.65rem;
                line-height: 1.4;
                padding: 0 .75rem;
            }

            #news-article .article-meta {
                justify-content: center;
                text-align: center;
            }

            #news-article .article-body {
                text-align: center;
            }

            #news-article .article-body p {
                text-align: inherit;
            }

            .news-pdf-link {
                margin-left: auto;
                margin-right: auto;
            }

            .news-sidebar ul {
                text-align: center;
            }
        }

        @media (max-width: 420px) {
            section#subheader h1 {
                font-size: 1.5rem;
                line-height: 1.35;
                padding: 0 .5rem;
                max-width: 95%;
            }
        }
    </style>
@endpush

@section('content')
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             data-bgimage="url({{ asset('assets/site/images/site3.webp') }}) center center / cover">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">{{ $news->title }}</h1>
                </div>
            </div>
        </div>
        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>

    <section id="news-article">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cover-wrapper">
                        <img src="{{ $news->cover_url ?? asset('assets/site/images/placeholder.webp') }}"
                             alt="{{ $news->title }}">
                    </div>

                    <div class="article-meta">
                        <span>
                            <i class="ri-calendar-line"></i>
                            {{ $news->published_at?->timezone('Asia/Hebron')->translatedFormat('l d F Y') ?? 'غير محدد' }}
                        </span>
                        <span>
                            <i class="ri-fire-line"></i>
                            {{ number_format($news->views) }} مشاهدة
                        </span>
                    </div>

                    <div class="article-body">
                        {!! $news->body !!}
                    </div>

                    @if($news->pdf_url)
                        <a href="{{ $news->pdf_url }}" target="_blank" rel="noopener" class="news-pdf-link">
                            <i class="ri-file-pdf-fill"></i>
                            تحميل البيان الرسمي (PDF)
                        </a>
                    @endif

                </div>

                <div class="col-lg-4">
                    <aside class="news-sidebar">
                        <h5>اكتشف المزيد</h5>
                        <ul>
                            @foreach($recentNews->take(4) as $item)
                                <li>
                                <a href="{{ route('site.news.show', $item->id) }}">
                                        {{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a href="{{ route('site.news') }}">
                                    العودة لجميع الأخبار
                                </a>
                            </li>
                        </ul>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection

