<footer class="text-light section-dark">
    @php
        $settings = $footerData['settings'] ?? null;

        $logoPath   = $settings->logo_white_path ?? 'assets/site/images/logo-white.webp';
        $titleAr    = $settings->footer_title_ar  ?? 'تواصل معنا';

        $email      = $settings->contact_email    ?? $settings->email     ?? null;
        $phone      = $settings->contact_phone    ?? $settings->phone     ?? null;
        $address    = $settings->contact_address  ?? $settings->address_ar?? null;

        $mailtoHref = $email ? ('mailto:' . trim($email)) : null;
        $telClean   = $phone ? preg_replace('/\s+/', '', $phone) : null; // يشيل المسافات للـ tel:
        $telHref    = $telClean ? ('tel:' . $telClean) : null;
    @endphp

    <div class="container">
        <div class="row g-4 justify-content-between">

            <div class="col-md-6">
                <img src="{{ asset($logoPath) }}" class="w-170px mb-2" alt="logo">
                <div class="spacer-single"></div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="widget">
                            <h5>الخدمات</h5>
                            <ul>
                                @foreach(($footerData['services'] ?? []) as $link)
                                    @php
                                        $hasRoute = $link->route_name && \Illuminate\Support\Facades\Route::has($link->route_name);
                                        $label    = e($link->label_ar);
                                    @endphp

                                    @if($hasRoute)
                                        <li><a href="{{ route($link->route_name) }}">{{ $label }}</a></li>
                                    @elseif(!empty($link->url))
                                        <li>
                                            <a href="{{ $link->url }}"
                                               @if(!str_starts_with($link->url,'/')) target="_blank" rel="noopener" @endif>
                                                {{ $label }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="widget">
                            <h5>الشركة</h5>
                            <ul>
                                @foreach(($footerData['company'] ?? []) as $link)
                                    @php
                                        $hasRoute = $link->route_name && \Illuminate\Support\Facades\Route::has($link->route_name);
                                        $label    = e($link->label_ar);
                                    @endphp

                                    @if($hasRoute)
                                        <li><a href="{{ route($link->route_name) }}">{{ $label }}</a></li>
                                    @elseif(!empty($link->url))
                                        <li>
                                            <a href="{{ $link->url }}"
                                               @if(!str_starts_with($link->url,'/')) target="_blank" rel="noopener" @endif>
                                                {{ $label }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="social-icons mb-sm-30 text-center">
                    @foreach(($footerData['socials'] ?? []) as $s)
                        @php
                            $icon = trim($s->icon_class ?? '');
                            $url  = trim($s->url ?? '');
                        @endphp
                        @if($url && $icon)
                            <a href="{{ $url }}"
                               title="{{ ucfirst($s->platform ?? 'social') }}"
                               @if(!str_starts_with($url,'/')) target="_blank" rel="noopener" @endif>
                                <i class="{{ $icon }}"></i>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="col-md-6" id="contact-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <h2 style="display:none;">تواصل معنا</h2>
                    <img src="{{ asset('assets/site/images/ui/up-right-arrow.webp') }}" class="w-60px op-5" alt="" style="display:none;">
                </div>

                <div class="widget">
                    <h2 style="font-size:36px;font-weight:700;color:#fff;margin-bottom:20px;line-height:1.1;letter-spacing:1px;">
                        {{ $titleAr }}
                    </h2>

                    <div class="op-5 fs-15">البريد الإلكتروني</div>
                    <h3>
                        @if($email)
                            <a href="{{ $mailtoHref }}" class="text-light text-decoration-none">{{ $email }}</a>
                        @else
                            —
                        @endif
                    </h3>

                    <div class="spacer-20"></div>

                    <div class="op-5 fs-15">الهاتف</div>
                    <h3>
                        @if($phone)
                            <a href="{{ $telHref }}" class="text-light text-decoration-none">{{ $phone }}</a>
                        @else
                            —
                        @endif
                    </h3>

                    <div class="spacer-20"></div>

                    <div class="op-5 fs-15">موقع المكتب</div>
                    <h3>{{ $address ?? '—' }}</h3>

                    <div class="spacer-20"></div>
                </div>
            </div>

        </div>
    </div>

    <div class="subfooter">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    {{ $settings->copyright_ar ?? ('حقوق النشر © ' . now()->year . ' كهرباء غزة') }}
                </div>
            </div>
        </div>
    </div>
</footer>
