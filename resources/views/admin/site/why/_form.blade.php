<form action="{{ $route }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    {{-- ========== Basic Information Section ========== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
            <h5 class="mb-1 fw-bold text-white" style="font-size: 1.15rem; color: #ffffff !important;">
                <i class="bi bi-info-circle me-2"></i>
                المعلومات الأساسية
            </h5>
            <small class="text-white opacity-75" style="color: rgba(255, 255, 255, 0.85) !important;">الحقول العربية والإنجليزية جنباً إلى جنب</small>
        </div>
        <div class="card-body p-4">
            <div class="row g-4">
                {{-- Badge --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-translate text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="badge" class="form-control @error('badge') is-invalid @enderror"
                           value="{{ old('badge', $model->badge ?? '') }}" required placeholder="مثال: لماذا تختارنا">
                    @error('badge') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-globe text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="badge_en" class="form-control @error('badge_en') is-invalid @enderror"
                           value="{{ old('badge_en', $model->badge_en ?? '') }}" placeholder="e.g: Why Choose Us">
                    @error('badge_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Tagline --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-translate text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="tagline" class="form-control @error('tagline') is-invalid @enderror"
                           value="{{ old('tagline', $model->tagline ?? '') }}" required placeholder="مثال: شريكك الموثوق في الخدمة الكهربائية">
                    @error('tagline') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-globe text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="tagline_en" class="form-control @error('tagline_en') is-invalid @enderror"
                           value="{{ old('tagline_en', $model->tagline_en ?? '') }}" placeholder="e.g: Your Trusted Partner in Electrical Services">
                    @error('tagline_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-translate text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.arabic') }}
                    </label>
                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="الوصف بالعربية">{{ old('description', $model->description ?? '') }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-globe text-primary me-1"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.english') }}
                    </label>
                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="4" placeholder="English Description">{{ old('description_en', $model->description_en ?? '') }}</textarea>
                    @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ========== Features Section ========== --}}
    <hr class="my-4">

    @php
        $items = old('feature_title') ? collect(old('feature_title'))->map(function($t, $i){
            return [
                'title' => $t,
                'text'  => old('feature_text')[$i] ?? '',
                'title_en' => old('feature_title_en')[$i] ?? '',
                'text_en'  => old('feature_text_en')[$i] ?? '',
                'icon'  => old('feature_icon')[$i] ?? 'bi bi-lightning-charge-fill',
            ];
        })->toArray() : ($items ?? []);

        if (empty($items)) $items = [[]];
    @endphp

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header text-white" style="background: linear-gradient(135deg, #0066cc 0%, #0052a3 100%);">
            <h5 class="mb-1 fw-bold text-white" style="font-size: 1.15rem; color: #ffffff !important;">
                <i class="bi bi-list-check me-2"></i>
                {{ __('admin.why_choose_us.form_items') }}
            </h5>
            <small class="text-white opacity-75" style="color: rgba(255, 255, 255, 0.85) !important;">كل عنصر يحتوي على حقول للعربية والإنجليزية</small>
        </div>
        <div class="card-body p-4">
            @foreach($items as $i => $f)
                @php $iconClass = $f['icon'] ?? 'bi bi-lightning-charge-fill'; @endphp

                <div class="feature-card mb-4 @if($i > 0) border-top pt-4 mt-4 @endif">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="{{ $iconClass }} text-primary" style="font-size:24px;"></i>
                        <span class="fw-bold text-primary" style="font-size: 1rem;">{{ __('admin.why_choose_us.form_item_number') }} {{ $i+1 }}</span>
                    </div>

                    <input type="hidden" name="feature_icon[]" value="{{ $iconClass }}">

                    <div class="row g-3">
                        {{-- Arabic Column --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                    <i class="bi bi-translate text-primary fs-5"></i>
                                    <span class="fw-bold text-primary" style="font-size: 0.95rem;">{{ __('admin.labels.arabic') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title[]" class="form-control" value="{{ $f['title'] ?? '' }}" placeholder="العنوان بالعربية">
                                </div>
                                <div>
                                    <label class="form-label fw-semibold small">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text[]" class="form-control" rows="3" placeholder="النص بالعربية">{{ $f['text'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- English Column --}}
                        <div class="col-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                    <i class="bi bi-globe text-primary fs-5"></i>
                                    <span class="fw-bold text-primary" style="font-size: 0.95rem;">{{ __('admin.labels.english') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold small">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title_en[]" class="form-control" value="{{ $f['title_en'] ?? '' }}" placeholder="English Title">
                                </div>
                                <div>
                                    <label class="form-label fw-semibold small">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text_en[]" class="form-control" rows="3" placeholder="English Text">{{ $f['text_en'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary px-4 fw-bold">{{ __('admin.why_choose_us.save') }}</button>
        <a href="{{ route('admin.why.index') }}" class="btn btn-light px-4">{{ __('admin.why_choose_us.cancel') }}</a>
    </div>
</form>
