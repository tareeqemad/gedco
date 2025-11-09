@extends('layouts.admin')
@section('title','Site Settings')

@section('content')
    <style>
        /* ====== تنسيق الهاتف (يعرض LTR) ====== */
        .tel-input {
            direction: ltr;
            text-align: left;
            unicode-bidi: plaintext;
            font-variant-numeric: tabular-nums;
        }
        .channel-card {
            border: 1px solid #e5e7eb;
            border-radius: .5rem;
            padding: 1rem;
        }
        .badge-pos {
            font-size: .75rem;
            background: #e0e7ff;
            color: #3730a3;
            border-radius: .5rem;
            padding: .25rem .5rem;
        }
        .help-muted {
            color: #6b7280;
            font-size: .85rem;
        }
    </style>

    <div class="card">
        <div class="card-header">إعدادات الموقع</div>
        <div class="card-body">
            <form method="post" action="{{ route('admin.site-settings.update',$setting->id) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">عنوان الفوتر</label>
                    <input name="footer_title_ar" class="form-control"
                           value="{{ old('footer_title_ar',$setting->footer_title_ar) }}">
                    @error('footer_title_ar')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo White Path</label>
                        <input name="logo_white_path" class="form-control"
                               value="{{ old('logo_white_path',$setting->logo_white_path) }}">
                        @error('logo_white_path')<div class="text-danger">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-1">مثال: assets/site/images/logo-white.webp</small>
                    </div>
                </div>

                <hr>
                <h5 class="mb-3">تواصل معنا (حتى قناتين)</h5>

                @php
                    $channels = old('channels');
                    if (is_null($channels)) {
                        $channels = ($setting->relationLoaded('contactChannels') ? $setting->contactChannels : $setting->contactChannels()->orderBy('position')->get())
                            ->map(fn($c) => [
                                'id'         => $c->id,
                                'position'   => $c->position,
                                'label'      => $c->label,
                                'email'      => $c->email,
                                // استخدم phone_formatted للعرض إن توفر:
                                'phone'      => method_exists($c, 'getAttribute') && $c->phone_formatted ? $c->phone_formatted : $c->phone,
                                'address_ar' => $c->address_ar,
                            ])->toArray();
                    }
                    for ($k = count($channels); $k < 2; $k++) {
                        $channels[] = ['id'=>null,'position'=>$k+1,'label'=>'','email'=>'','phone'=>'','address_ar'=>''];
                    }
                @endphp

                <div class="row">
                    @for($i=0; $i<2; $i++)
                        <div class="col-md-12 mb-3">
                            <div class="channel-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">قناة #{{ $i+1 }}</h6>
                                    <span class="badge-pos">Position: {{ $channels[$i]['position'] ?? ($i+1) }}</span>
                                </div>

                                <input type="hidden" name="channels[{{ $i }}][id]" value="{{ $channels[$i]['id'] ?? '' }}">
                                <input type="hidden" name="channels[{{ $i }}][position]" value="{{ $channels[$i]['position'] ?? ($i+1) }}">

                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">التسمية (اختياري)</label>
                                        <input name="channels[{{ $i }}][label]" class="form-control"
                                               value="{{ old("channels.$i.label", $channels[$i]['label'] ?? '') }}">
                                        @error("channels.$i.label")<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">البريد الإلكتروني</label>
                                        <input name="channels[{{ $i }}][email]" class="form-control"
                                               value="{{ old("channels.$i.email", $channels[$i]['email'] ?? '') }}">
                                        @error("channels.$i.email")<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">الهاتف</label>
                                        <input name="channels[{{ $i }}][phone]" class="form-control tel-input"
                                               inputmode="tel" placeholder="+970 59X XXX XXX"
                                               value="{{ old("channels.$i.phone", $channels[$i]['phone'] ?? '') }}">
                                        @error("channels.$i.phone")<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">العنوان</label>
                                        <input name="channels[{{ $i }}][address_ar]" class="form-control"
                                               value="{{ old("channels.$i.address_ar", $channels[$i]['address_ar'] ?? '') }}">
                                        @error("channels.$i.address_ar")<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <small class="help-muted">اترك الحقول فارغة لإهمال هذه القناة.</small>
                            </div>
                        </div>
                    @endfor
                </div>

                <button class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>

    @push('scripts')
    {{-- JS لتنسيق الهاتف أثناء الكتابة (اختياري لكن مفيد) --}}
    <script>
        document.querySelectorAll('input.tel-input').forEach(function (el) {
            el.addEventListener('input', function () {
                const before = el.value;
                let v = el.value.replace(/[^\d+]/g, '');   // اسمح بالأرقام و +
                if (v[0] !== '+') v = v.replace(/\+/g, ''); // + فقط في البداية

                // لو +970 طبّق نمط فلسطين
                if (v.startsWith('+970')) {
                    const digits = v.replace(/\D/g, '');
                    let out = '+970';
                    const rest = digits.slice(4);
                    if (rest.length > 0) out += ' ' + rest.slice(0, 2); // 59
                    if (rest.length > 2) out += rest[2];                // X
                    if (rest.length > 3) out = out.replace(/(\+970 \d{3})(\d{1,3})/, '$1 $2');
                    if (rest.length > 6) out = out.replace(/(\+970 \d{3} \d{3})(\d{1,3})/, '$1 $2');
                    el.value = out;
                } else {
                    // عام: +CCC XXX XXX XXX
                    const d = v.replace(/\D/g, '');
                    if (v.startsWith('+') && d.length > 0) {
                        let out = '+' + d.slice(0, 3);
                        let rest = d.slice(3);
                        while (rest.length > 0) {
                            out += ' ' + rest.slice(0, 3);
                            rest = rest.slice(3);
                        }
                        el.value = out.trim();
                    } else {
                        // بدون +: أرقام فقط (اختياري تقسيم)
                        el.value = d;
                    }
                }

                // حافظ على اتجاه الكورسور طبيعي (بسبب RTL قد يكون مزعج؛ نتركه بسيط)
                if (el.value !== before) {
                    el.selectionStart = el.selectionEnd = el.value.length;
                }
            });
        });
    </script>
    @endpush
@endsection
