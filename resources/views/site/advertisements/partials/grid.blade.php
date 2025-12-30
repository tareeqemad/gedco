<div class="row g-4 justify-content-center" id="ads-grid">
    @forelse($advertisements as $ad)
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="ad-card-wrapper">
                <a href="{{ route('site.advertisements.show', $ad->ID_ADVER) }}"
                   class="ad-card-link text-decoration-none">
                    <div class="ad-card">
                        <div class="ad-thumb-wrapper">
                            @php
                                $imageUrl = $ad->image;
                                if ($imageUrl) {
                                    if (strpos($imageUrl, 'http') === 0) {
                                        // URL خارجي
                                        $finalImageUrl = $imageUrl;
                                    } elseif (strpos($imageUrl, 'storage/') !== false || strpos($imageUrl, 'advertisements/') !== false) {
                                        // صورة من storage
                                        $finalImageUrl = Storage::url($imageUrl);
                                    } elseif (strpos($imageUrl, 'data:image') === 0) {
                                        // base64
                                        $finalImageUrl = $imageUrl;
                                    } else {
                                        // مسار نسبي
                                        $finalImageUrl = asset($imageUrl);
                                    }
                                } else {
                                    $finalImageUrl = asset('assets/site/images/adv/def_ads.png');
                                }
                            @endphp
                            <img src="{{ $finalImageUrl }}"
                                 class="ad-thumb"
                                 alt="{{ strip_tags($isRtl ? ($ad->TITLE ?: $ad->TITLE_E) : ($ad->TITLE_E ?: $ad->TITLE)) }}"
                                 loading="lazy"
                                 onerror="this.src='{{ asset('assets/site/images/adv/def_ads.png') }}'">
                            <div class="ad-overlay">
                                <div class="ad-overlay-content">
                                    <i class="ri-eye-line"></i>
                                    <span>{{ __('common.ads_view_details') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ad-card-body">
                            <h4 class="ad-title">
                                {!! $isRtl ? ($ad->TITLE ?: $ad->TITLE_E) : ($ad->TITLE_E ?: $ad->TITLE) !!}
                            </h4>
                            <div class="ad-date">
                                <i class="ri-calendar-line"></i>
                                <span>
                                    @php
                                        $locale = $isRtl ? 'ar' : 'en';
                                        $dateFormat = $isRtl ? 'd/m/Y' : 'M d, Y';
                                    @endphp
                                    {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->locale($locale)->translatedFormat($dateFormat) }}
                                </span>
                            </div>
                            @if($ad->PDF)
                                <a href="{{ Storage::url($ad->PDF) }}" 
                                   target="_blank"
                                   class="ad-pdf-btn"
                                   onclick="event.stopPropagation();">
                                    <i class="ri-file-pdf-line"></i>
                                    <span>{{ __('common.ads_download_pdf') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="empty-state">
                <i class="ri-inbox-line"></i>
                <p class="mt-3 text-muted fw-medium">{{ __('common.ads_no_ads') }}</p>
            </div>
        </div>
    @endforelse
</div>

<div class="mt-5 d-flex justify-content-center" id="pagination-wrapper">
    {{ $advertisements->withQueryString()->links() }}
</div>
