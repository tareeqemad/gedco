<footer class="footer-modern" dir="rtl" id="contact-footer">
    @php
        $settings  = $footerData['settings'] ?? null;
        $email     = $settings->contact_email    ?? $settings->email     ?? null;
        $phone     = $settings->contact_phone    ?? $settings->phone     ?? null;
        $address   = $settings->contact_address  ?? $settings->address_ar ?? null;
        $mailtoHref = $email ? ('mailto:' . trim($email)) : null;
        $telClean   = $phone ? preg_replace('/\s+/', '', $phone) : null;
        $telHref    = $telClean ? ('tel:' . $telClean) : null;
        $socials    = $footerData['socials'] ?? [];
    @endphp

    <div class="container">
        <div class="row align-items-center g-3">

            <!-- معلومات التواصل -->
            <div class="col-lg-5 col-md-6 col-12">
                <div class="footer-contacts">
                    @if($email)
                        <a href="{{ $mailtoHref }}" class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span dir="ltr">{{ $email }}</span>
                        </a>
                    @endif
                    @if($phone)
                        <a href="{{ $telHref }}" class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span dir="ltr">{{ $phone }}</span>
                        </a>
                    @endif
                    @if($address)
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $address }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- لوقو في الوسط -->
            <div class="col-lg-2 col-12 text-center">
                <div class="footer-logo-wrapper">
                    <img src="{{ asset('assets/site/images/logo-white.webp') }}" alt="GEDCO" class="footer-logo-glow">
                </div>
            </div>

            <!-- سوشيال ميديا + حقوق -->
            <div class="col-lg-5 col-12 text-center">
                <div class="footer-copyright mb-2">
                    {{ $settings->copyright_ar ?? ('كهرباء غزة © ' . now()->year) }}
                </div>
                @if(!empty($socials))
                    <div class="footer-social-modern">
                        @foreach($socials as $s)
                            @php
                                $icon = trim($s->icon_class ?? '');
                                $url  = trim($s->url ?? '');
                            @endphp
                            @if($url && $icon)
                                <a href="{{ $url }}" @if(!str_starts_with($url, '/')) target="_blank" rel="noopener" @endif>
                                    <i class="{{ $icon }}"></i>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</footer>
