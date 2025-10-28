<footer class="footer-compact section-dark text-light" dir="rtl">
    @php
        $settings  = $footerData['settings'] ?? null;
        $titleAr   = $settings->footer_title_ar  ?? 'تواصل معنا';
        $email     = $settings->contact_email    ?? $settings->email     ?? null;
        $phone     = $settings->contact_phone    ?? $settings->phone     ?? null;
        $address   = $settings->contact_address  ?? $settings->address_ar ?? null;

        $mailtoHref = $email ? ('mailto:' . trim($email)) : null;
        $telClean   = $phone ? preg_replace('/\s+/', '', $phone) : null;
        $telHref    = $telClean ? ('tel:' . $telClean) : null;
        $socials    = $footerData['socials'] ?? [];
    @endphp

    <div class="container">
        <div class="row justify-content-between align-items-start gy-4">
            <!-- العمود الأيسر: شعار + وصف + سوشيال -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="pe-md-4">
                    <!-- شعار أبيض (تأكد من وجوده في public/images/logo-white.svg) -->
                    <img src="{{ asset('assets/site/images/logo-white.webp') }}" alt="شعار الشركة" class="mb-3" style="height:36px;">

                    <!-- وصف مختصر -->
                    <p class="small opacity-80 mb-3" style="max-width: 280px; line-height: 1.6;">
                        شركة كهرباء غزة - ملتزمون بتقديم أفضل خدمات الطاقة بكفاءة واستدامة.
                    </p>

                    <!-- أيقونات السوشيال (مرة واحدة فقط) -->
                    @if(!empty($socials))
                        <div class="footer-social mt-2">
                            @foreach($socials as $s)
                                @php
                                    $icon = trim($s->icon_class ?? '');
                                    $url  = trim($s->url ?? '');
                                @endphp
                                @if($url && $icon)
                                    <a href="{{ $url }}"
                                       @if(!str_starts_with($url, '/')) target="_blank" rel="noopener" @endif
                                       class="text-light me-2">
                                        <i class="{{ $icon }}"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- العمود الأيمن: معلومات التواصل -->
            <div class="col-12 col-md-6 col-lg-5" id="contact-footer">
                <h3 class="footer-title mb-3">{{ $titleAr }}</h3>

                <ul class="contact-list list-unstyled m-0">
                    <!-- البريد الإلكتروني -->
                    <li class="pb-3 border-bottom border-secondary-subtle">
                        <span class="label d-inline-flex align-items-center gap-2 opacity-70 small mb-1">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5L4 8"></path>
                            </svg>
                            البريد الإلكتروني
                        </span>
                        @if($email)
                            <a href="{{ $mailtoHref }}" class="value d-block link-contrast keep-ltr">
                                <bdi dir="ltr">{{ $email }}</bdi>
                            </a>
                        @else
                            <span class="value d-block text-muted">—</span>
                        @endif
                    </li>

                    <!-- الهاتف -->
                    <li class="py-2 border-bottom border-secondary-subtle">
                        <span class="label d-inline-flex align-items-center gap-2 opacity-70 small mb-1">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07A19.5 19.5 0 0 1 3.15 12.8 19.86 19.86 0 0 1 .08 4.37 2 2 0 0 1 2.06 2h3a2 2 0 0 1 2 1.72c.12.9.34 1.78.66 2.62a2 2 0 0 1-.45 2.11L6.1 9.91a16 16 0 0 0 8 8l1.46-1.15a2 2 0 0 1 2.11-.45 12.36 12.36 0 0 0 2.62.66A2 2 0 0 1 22 16.92Z"></path>
                            </svg>
                            الهاتف
                        </span>
                        @if($phone)
                            <a href="{{ $telHref }}" class="value d-block link-contrast keep-ltr">
                                <bdi dir="ltr">{{ $phone }}</bdi>
                            </a>
                        @else
                            <span class="value d-block text-muted">—</span>
                        @endif
                    </li>

                    <!-- العنوان -->
                    <li class="pt-2">
                        <span class="label d-inline-flex align-items-center gap-2 opacity-70 small mb-1">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 22s8-4.5 8-12a8 8 0 1 0-16 0c0 7.5 8 12 8 12Zm0-9a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"></path>
                            </svg>
                            موقع المكتب
                        </span>
                        <span class="value d-block">{{ $address ?? '—' }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- السطر السفلي (حقوق النشر) -->
    <div class="subfooter-compact">
        <div class="container text-center small opacity-75">
            {{ $settings->copyright_ar ?? ('حقوق النشر © ' . now()->year . ' كهرباء غزة') }}
        </div>
    </div>
</footer>
