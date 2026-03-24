@extends('layouts.site')

@section('title', __('common.contact') . ' | ' . __('common.home'))
@section('meta_description', 'تواصل معنا - شركة توزيع كهرباء غزة')

@push('styles')
    <style>
        :root {
            --contact-primary: #0f172a;
            --contact-accent: #ff6b35;
            --contact-muted: #64748b;
            --contact-border: rgba(148, 163, 184, 0.25);
            --contact-surface: #ffffff;
            --contact-background: #f8fafc;
        }

        section#subheader {
            margin-top: 8px !important;
            padding-top: 140px !important;
            padding-bottom: 100px !important;
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;
        }

        section#subheader h1 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            line-height: 1.3;
            letter-spacing: .5px;
            margin-bottom: 0;
            padding: 0 1.25rem;
            max-width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .contact-section {
            position: relative;
            padding: clamp(4rem, 7vw, 5rem) 0 clamp(3rem, 8vw, 4.5rem);
            background: var(--contact-background);
        }

        .contact-card {
            background: var(--contact-surface);
            border-radius: 20px;
            border: 1px solid var(--contact-border);
            padding: 2.5rem;
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
        }

        .contact-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .contact-info h3 {
            font-size: 1.35rem;
            font-weight: 700;
        }

        .contact-form .form-label {
            font-weight: 600;
            color: var(--contact-primary);
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .contact-form .form-control,
        .contact-form .form-select {
            border: 1px solid var(--contact-border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .contact-form .form-control:focus,
        .contact-form .form-select:focus {
            border-color: var(--contact-accent);
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .contact-form textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }

        .contact-form .btn-submit {
            background: var(--contact-accent);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .contact-form .btn-submit:hover {
            background: #e55a2b;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
        }

        .contact-info {
            background: var(--contact-surface);
            border-radius: 20px;
            border: 1px solid var(--contact-border);
            padding: 2.5rem;
            box-shadow: 0 18px 32px rgba(15, 23, 42, 0.08);
            height: 100%;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--contact-border);
        }

        .contact-info-item:last-child {
            border-bottom: none;
        }

        .contact-info-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            background: rgba(255, 107, 53, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: var(--contact-accent);
            font-size: 1.25rem;
        }

        .contact-info-content h5 {
            font-weight: 600;
            color: var(--contact-primary);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .contact-info-content p {
            color: var(--contact-muted);
            margin: 0;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .contact-info-content a {
            color: var(--contact-accent);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .contact-info-content a:hover {
            color: #e55a2b;
            text-decoration: underline;
        }

        @media (max-width: 991px) {
            .contact-card,
            .contact-info {
                padding: 2rem 1.5rem;
            }

            section#subheader {
                padding-top: 110px !important;
                padding-bottom: 80px !important;
            }

            section#subheader h1 {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 576px) {
            .contact-card,
            .contact-info {
                padding: 1.5rem 1rem;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $currentDir = session('direction', 'rtl');
        $isRtl = $currentDir === 'rtl';
    @endphp
    <section id="subheader"
             class="text-light relative rounded-1 overflow-hidden m-3 d-flex align-items-center justify-content-center text-center"
             dir="{{ $currentDir }}">
        <div class="container relative z-2">
            <div class="row justify-content-center text-center">
                <div class="col-12">
                    <h1 class="split mb-3 fw-bold d-block w-100">{{ __('common.contact') }}</h1>
                </div>
            </div>
        </div>
        <div class="gradient-edge-bottom color op-7 h-80"></div>
        <div class="sw-overlay op-7"></div>
    </section>

    <section class="contact-section" dir="{{ $currentDir }}">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="contact-card">
                        <h2 class="mb-4" style="color: var(--contact-primary); font-weight: 700;">
                            {{ $isRtl ? 'أرسل لنا رسالة' : 'Send us a message' }}
                        </h2>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="ri-check-line me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="ri-error-warning-line me-2"></i>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('site.contact.store') }}" method="POST" class="contact-form" id="contactForm">
                            @csrf

                            {{-- Honeypot: hidden field to catch bots --}}
                            <div style="position:absolute;left:-9999px;opacity:0;height:0;overflow:hidden;" aria-hidden="true">
                                <label for="website">Website</label>
                                <input type="text" name="website" id="website" value="" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">
                                        {{ $isRtl ? 'الاسم' : 'Name' }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="name-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        {{ $isRtl ? 'البريد الإلكتروني' : 'Email' }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="email-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">
                                        {{ $isRtl ? 'الهاتف' : 'Phone' }} <span class="text-muted">({{ $isRtl ? 'اختياري' : 'Optional' }})</span>
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="phone-error"></div>
                                </div>

                                <div class="col-md-6">
                                    <label for="subject" class="form-label">
                                        {{ $isRtl ? 'الموضوع' : 'Subject' }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('subject') is-invalid @enderror" 
                                           id="subject" 
                                           name="subject" 
                                           value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="subject-error"></div>
                                </div>

                                <div class="col-12">
                                    <label for="message" class="form-label">
                                        {{ $isRtl ? 'الرسالة' : 'Message' }} <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" 
                                              id="message" 
                                              name="message" 
                                              rows="6">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="message-error"></div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-submit" id="submitBtn">
                                        <i class="ri-send-plane-fill me-2"></i>
                                        {{ $isRtl ? 'إرسال الرسالة' : 'Send Message' }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="contact-info">
                        <h3 class="mb-4" style="color: var(--contact-primary); font-weight: 700;">
                            {{ $isRtl ? 'معلومات الاتصال' : 'Contact Information' }}
                        </h3>

                        @php
                            $settings = $settings ?? \App\Models\SiteSetting::first();
                            $channels = $settings?->contactChannels ?? collect();
                            
                            if ($channels->isEmpty() && $settings) {
                                $channels = collect([[
                                    'label' => $isRtl ? 'المكتب الرئيسي' : 'Main Office',
                                    'email' => $settings->contact_email ?? $settings->email,
                                    'phone' => $settings->contact_phone ?? $settings->phone,
                                    'address' => $isRtl ? ($settings->contact_address ?? $settings->address_ar) : $settings->address_en,
                                ]])->filter(fn($c) => $c['email'] || $c['phone'] || $c['address']);
                            }
                        @endphp

                        @if($channels->isNotEmpty())
                            @foreach($channels as $channel)
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="ri-building-line"></i>
                                    </div>
                                    <div class="contact-info-content">
                                        <h5>{{ $channel['label'] ?? ($isRtl ? 'معلومات الاتصال' : 'Contact Information') }}</h5>
                                        
                                        @if(!empty($channel['email']))
                                            <p>
                                                <i class="ri-mail-line me-2"></i>
                                                <a href="mailto:{{ $channel['email'] }}">{{ $channel['email'] }}</a>
                                            </p>
                                        @endif
                                        
                                        @if(!empty($channel['phone']))
                                            <p>
                                                <i class="ri-phone-line me-2"></i>
                                                <a href="tel:{{ $channel['phone'] }}">{{ $channel['phone'] }}</a>
                                            </p>
                                        @endif
                                        
                                        @if(!empty($channel['address']))
                                            <p>
                                                <i class="ri-map-pin-line me-2"></i>
                                                {{ $channel['address'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="ri-information-line"></i>
                                </div>
                                <div class="contact-info-content">
                                    <p>{{ $isRtl ? 'لا توجد معلومات اتصال متاحة حالياً' : 'No contact information available at the moment' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');
        
        if (!form) return;

        // إزالة رسائل الخطأ عند الكتابة
        const inputs = form.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorDiv = document.getElementById(this.id + '-error');
                if (errorDiv) {
                    errorDiv.textContent = '';
                    errorDiv.style.display = 'none';
                }
            });
        });

        // Validation function
        function validateForm() {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('[id$="-error"]').forEach(el => {
                el.textContent = '';
                el.style.display = 'none';
            });

            // Validate name
            const name = document.getElementById('name').value.trim();
            if (!name) {
                showError('name', '{{ $isRtl ? "الاسم مطلوب" : "Name is required" }}');
                isValid = false;
            } else if (name.length > 255) {
                showError('name', '{{ $isRtl ? "الاسم يجب أن يكون أقل من 255 حرف" : "Name must be less than 255 characters" }}');
                isValid = false;
            }

            // Validate email
            const email = document.getElementById('email').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                showError('email', '{{ $isRtl ? "البريد الإلكتروني مطلوب" : "Email is required" }}');
                isValid = false;
            } else if (!emailRegex.test(email)) {
                showError('email', '{{ $isRtl ? "البريد الإلكتروني غير صحيح" : "Invalid email format" }}');
                isValid = false;
            } else if (email.length > 255) {
                showError('email', '{{ $isRtl ? "البريد الإلكتروني يجب أن يكون أقل من 255 حرف" : "Email must be less than 255 characters" }}');
                isValid = false;
            }

            // Validate phone (optional)
            const phone = document.getElementById('phone').value.trim();
            if (phone && phone.length > 20) {
                showError('phone', '{{ $isRtl ? "الهاتف يجب أن يكون أقل من 20 حرف" : "Phone must be less than 20 characters" }}');
                isValid = false;
            }

            // Validate subject
            const subject = document.getElementById('subject').value.trim();
            if (!subject) {
                showError('subject', '{{ $isRtl ? "الموضوع مطلوب" : "Subject is required" }}');
                isValid = false;
            } else if (subject.length > 255) {
                showError('subject', '{{ $isRtl ? "الموضوع يجب أن يكون أقل من 255 حرف" : "Subject must be less than 255 characters" }}');
                isValid = false;
            }

            // Validate message
            const message = document.getElementById('message').value.trim();
            if (!message) {
                showError('message', '{{ $isRtl ? "الرسالة مطلوبة" : "Message is required" }}');
                isValid = false;
            } else if (message.length < 10) {
                showError('message', '{{ $isRtl ? "الرسالة يجب أن تكون على الأقل 10 أحرف" : "Message must be at least 10 characters" }}');
                isValid = false;
            } else if (message.length > 2000) {
                showError('message', '{{ $isRtl ? "الرسالة يجب أن تكون أقل من 2000 حرف" : "Message must be less than 2000 characters" }}');
                isValid = false;
            }

            return isValid;
        }

        function showError(fieldId, message) {
            const field = document.getElementById(fieldId);
            const errorDiv = document.getElementById(fieldId + '-error');
            
            if (field) {
                field.classList.add('is-invalid');
            }
            
            if (errorDiv) {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }
        }

        // Form submit handler
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!validateForm()) {
                // Scroll to first error
                const firstError = form.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }

            // Disable submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalHTML = submitBtn.innerHTML;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ $isRtl ? "جاري الإرسال..." : "Sending..." }}';
                
                // Submit form
                form.submit();
                
                // Re-enable after 5 seconds (in case of error)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHTML;
                }, 5000);
            } else {
                form.submit();
            }
        });
    });
</script>
@endpush
