<footer class="footer-modern" dir="rtl" id="contact-footer">
    <style>
        /* عرض الأرقام/الإيميلات LTR داخل RTL */
        .ltr-inline{direction:ltr;unicode-bidi:plaintext}
    </style>

    @php
        /** @var \App\Models\SiteSetting|null $settings */
        $settings = $footerData['settings'] ?? null;

        // القنوات: من $footerData إن وُجد، ثم العلاقة، ثم الحقول القديمة كـ fallback
        $channels = collect($footerData['channels'] ?? [])->map(fn($c) => [
            'label'      => trim($c['label']      ?? ''),
            'email'      => trim($c['email']      ?? ''),
            'phone'      => trim($c['phone']      ?? ''),
            'address_ar' => trim($c['address_ar'] ?? ''),
        ]);

        if ($channels->isEmpty() && $settings && method_exists($settings, 'contactChannels')) {
            $rel = $settings->contactChannels()->orderBy('position')->get();
            if ($rel->isNotEmpty()) {
                $channels = $rel->map(fn($c) => [
                    'label'      => trim($c->label ?? ''),
                    'email'      => trim($c->email ?? ''),
                    'phone'      => trim(($c->phone_formatted ?? $c->phone) ?? ''),
                    'address_ar' => trim($c->address_ar ?? ''),
                ]);
            }
        }

        if ($channels->isEmpty() && $settings) {
            $channels = collect([[
                'label'      => '',
                'email'      => trim($settings->contact_email ?? $settings->email ?? ''),
                'phone'      => trim($settings->contact_phone ?? $settings->phone ?? ''),
                'address_ar' => trim($settings->contact_address ?? $settings->address_ar ?? ''),
            ]]);
        }

        // تجاهل القنوات الفارغة وخُذ حتى قناتين
        $channels = $channels->filter(fn($c) => $c['email'] || $c['phone'] || $c['address_ar'])
                             ->values()->take(2);

        // ====== السوشال من social_links (is_active + sort_order) ======
        use App\Models\SocialLink;
        $socials = SocialLink::active()->ordered()->get(['platform','icon_class','url']);
    @endphp

    <div class="container">
        <div class="row align-items-center g-3">

            {{-- معلومات التواصل: قناة أو قناتين --}}
            <div class="col-lg-5 col-md-6 col-12">
                @foreach($channels as $idx => $ch)
                    @php
                        $email = $ch['email'] ?? '';
                        $phone = $ch['phone'] ?? '';
                        $addr  = $ch['address_ar'] ?? '';
                        $mailtoHref = $email ? ('mailto:' . preg_replace('/\s+/', '', $email)) : null;
                        $telHref    = $phone ? ('tel:'   . preg_replace('/\s+/', '', $phone)) : null;
                    @endphp

                    <div class="footer-contacts @if($idx>0) mt-3 @endif">
                        @if($email)
                            <a href="{{ $mailtoHref }}" class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span class="ltr-inline">{{ $email }}</span>
                            </a>
                        @endif
                        @if($phone)
                            <a href="{{ $telHref }}" class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span class="ltr-inline">{{ $phone }}</span>
                            </a>
                        @endif
                        @if($addr)
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $addr }}</span>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- اللوجو في الوسط --}}
            <div class="col-lg-2 col-12 text-center">
                <div class="footer-logo-wrapper">
                    <img
                        src="{{ asset($settings->logo_white_path ?? 'assets/site/images/logo-white.webp') }}"
                        alt="Logo"
                        class="footer-logo-glow">
                </div>
            </div>

            {{-- السوشال ميديا + الحقوق --}}
            <div class="col-lg-5 col-12 text-center">
                <div class="footer-copy mb-2">
                    {{ $settings->copyright_ar
                        ?? (($settings->footer_title_ar ?? 'كهرباء غزة').' © '.now()->year) }}
                </div>

                @if($socials->count())
                    <div class="footer-social-modern">
                        @foreach($socials as $s)
                            @php
                                $url      = trim($s->url ?? '');
                                $icon     = trim($s->icon_class ?? '');
                                $platform = trim($s->platform ?? '');
                            @endphp
                            @if($url && $icon)
                                <a href="{{ $url }}"
                                   aria-label="{{ $platform ?: 'social' }}"
                                   @if(!str_starts_with($url, '/')) target="_blank" rel="noopener" @endif>
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
