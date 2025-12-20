@php
    $cards = $newsItems;
@endphp

@forelse($cards as $news)
    @php
        $cover = $news->cover_url ?? asset('assets/site/images/placeholder.webp');
        $publishedAt = $news->published_at?->timezone('Asia/Hebron');
        $isFeatured = $news->featured;
        $isNew = $publishedAt?->isToday();
        $badgeLabel = $isFeatured ? __('common.news_badge_featured') : ($isNew ? __('common.news_badge_new') : null);
        $badgeModifier = $isFeatured ? 'featured' : ($isNew ? 'new' : null);
        $badgeIcon = $isFeatured ? 'ri-vip-crown-line' : ($isNew ? 'ri-flashlight-fill' : null);
        $isThisWeek = $publishedAt?->isCurrentWeek();
        $publishedDate = optional($news->published_at)->format('Y-m-d');
    @endphp
    <article class="news-card"
             data-featured="{{ $isFeatured ? '1' : '0' }}"
             data-fresh="{{ $isNew ? '1' : '0' }}"
             data-week="{{ $isThisWeek ? '1' : '0' }}"
             data-date="{{ $publishedDate }}">
        <a href="{{ route('site.news.show', $news->id) }}" class="news-card__thumb" aria-label="عرض تفاصيل الخبر {{ $news->title }}">
            @if($badgeLabel && $badgeModifier)
                <span class="news-card__badge news-card__badge--{{ $badgeModifier }}">
                    @if($badgeIcon)
                        <i class="{{ $badgeIcon }}" aria-hidden="true"></i>
                    @endif
                    <span>{{ $badgeLabel }}</span>
                </span>
            @endif
            <img src="{{ $cover }}" alt="{{ $news->title }}" loading="lazy">
        </a>

        <div class="news-card__body">
            <div class="news-card__meta">
                <i class="ri-calendar-line"></i>
                <time datetime="{{ optional($news->published_at)->toDateString() }}">
                    @if($publishedAt)
                        @php
                            $direction = session('direction', 'rtl');
                            $format = $direction === 'rtl' ? 'd F Y' : 'F d, Y';
                            $locale = $direction === 'rtl' ? 'ar' : 'en';
                        @endphp
                        {{ $publishedAt->locale($locale)->translatedFormat($format) }}
                    @else
                        {{ __('common.news_date_not_specified') }}
                    @endif
                </time>
            </div>

            <h3 class="news-card__title">
                <a href="{{ route('site.news.show', $news->id) }}">{{ $news->title }}</a>
            </h3>

            <p class="news-card__excerpt">{{ $news->excerpt(150) }}</p>

            <div class="news-card__footer">
                <a href="{{ route('site.news.show', $news->id) }}" class="news-card__cta">
                    {{ __('common.news_read_more') }}
                    <i class="bi bi-arrow-up-left"></i>
                </a>

                @if($news->pdf_url)
                    <a href="{{ $news->pdf_url }}" target="_blank" rel="noopener" class="news-card__cta news-card__cta--ghost">
                        {{ __('common.news_download_statement') }}
                        <i class="ri-file-text-line"></i>
                    </a>
                @endif
                <span class="news-card__tag">
                    {{ __('common.news_official_news') }}
                    <i class="ri-shield-check-line" aria-hidden="true"></i>
                </span>
            </div>
        </div>
    </article>
@empty
    <div class="news-empty">
        <i class="ri-newspaper-line"></i>
        <p>{{ __('common.news_no_news') }}</p>
    </div>
@endforelse

@if($cards->hasPages())
    <template id="news-pagination-template">
        {{ $cards->withQueryString()->links() }}
    </template>
@endif