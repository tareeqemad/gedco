<div class="row g-4 justify-content-center" id="ads-grid">
    @forelse($advertisements as $ad)
        <div class="col-md-4 col-sm-6 col-12 text-center">
            <a href="{{ route('site.advertisements.show', $ad->ID_ADVER) }}"
               target="_blank"
               class="d-block mb-3 text-decoration-none">
                <div class="ad-card">
                    <img src="{{ $ad->image ? Storage::url($ad->image) : asset('assets/site/images/adv/def_ads.png') }}"
                         class="ad-thumb"
                         alt="{{ strip_tags($ad->TITLE) }}"
                         loading="lazy">
                </div>
            </a>

            <h4 class="mt-3 fw-bold text-dark">{!! \Illuminate\Support\Str::limit($ad->TITLE, 60) !!}</h4>
            <div class="text-muted small mb-2">
                <i class="ri-calendar-line"></i>
                {{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('d/m/Y') }}
            </div>

            @if($ad->PDF)
                <a href="{{ Storage::url($ad->PDF) }}" target="_blank"
                   class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1 mx-auto mt-2">
                    <i class="ri-file-pdf-line"></i> تحميل PDF
                </a>
            @endif
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="ri-inbox-line text-muted" style="font-size: 4rem;"></i>
            <p class="mt-3 text-muted fw-medium">لا توجد إعلانات حاليًا</p>
        </div>
    @endforelse
</div>

<div class="mt-5 d-flex justify-content-center" id="pagination-wrapper">
    {{ $advertisements->withQueryString()->links() }}
</div>
