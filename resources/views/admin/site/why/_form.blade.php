<form action="{{ $route }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    {{-- ========== Basic Information Section ========== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4 rounded-top-4">
            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                <i class="bi bi-info-circle"></i>
                المعلومات الأساسية
            </h5>
        </div>
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                {{-- Badge --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate text-primary"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="badge" class="form-control rounded-3 border-0 bg-light @error('badge') is-invalid @enderror"
                           value="{{ old('badge', $model->badge ?? '') }}" required placeholder="مثال: لماذا تختارنا"
                           style="height: 45px; font-size: 1rem;">
                    @error('badge') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe text-primary"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="badge_en" class="form-control rounded-3 border-0 bg-light @error('badge_en') is-invalid @enderror"
                           value="{{ old('badge_en', $model->badge_en ?? '') }}" placeholder="e.g: Why Choose Us"
                           style="height: 45px; font-size: 1rem;">
                    @error('badge_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Tagline --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate text-primary"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="tagline" class="form-control rounded-3 border-0 bg-light @error('tagline') is-invalid @enderror"
                           value="{{ old('tagline', $model->tagline ?? '') }}" required placeholder="مثال: شريكك الموثوق في الخدمة الكهربائية"
                           style="height: 45px; font-size: 1rem;">
                    @error('tagline') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe text-primary"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="tagline_en" class="form-control rounded-3 border-0 bg-light @error('tagline_en') is-invalid @enderror"
                           value="{{ old('tagline_en', $model->tagline_en ?? '') }}" placeholder="e.g: Your Trusted Partner in Electrical Services"
                           style="height: 45px; font-size: 1rem;">
                    @error('tagline_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate text-primary"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.arabic') }}
                    </label>
                    <textarea name="description" class="form-control rounded-3 border-0 bg-light @error('description') is-invalid @enderror" rows="4" placeholder="الوصف بالعربية" style="font-size: 1rem;">{{ old('description', $model->description ?? '') }}</textarea>
                    @error('description') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe text-primary"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.english') }}
                    </label>
                    <textarea name="description_en" class="form-control rounded-3 border-0 bg-light @error('description_en') is-invalid @enderror" rows="4" placeholder="English Description" style="font-size: 1rem;">{{ old('description_en', $model->description_en ?? '') }}</textarea>
                    @error('description_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    {{-- ========== Features Section ========== --}}
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

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4 rounded-top-4">
            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2" style="font-size: 1.1rem;">
                <i class="bi bi-list-check"></i>
                {{ __('admin.why_choose_us.form_items') }}
            </h5>
        </div>
        <div class="card-body p-4 p-md-5">
            @foreach($items as $i => $f)
                @php $iconClass = $f['icon'] ?? 'bi bi-lightning-charge-fill'; @endphp

                <div class="feature-card mb-4 @if($i > 0) border-top pt-4 mt-4 @endif">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="{{ $iconClass }} text-primary" style="font-size:1.5rem;"></i>
                        <span class="fw-bold text-primary" style="font-size: 1rem;">{{ __('admin.why_choose_us.form_item_number') }} {{ $i+1 }}</span>
                    </div>

                    <input type="hidden" name="feature_icon[]" value="{{ $iconClass }}">

                    <div class="row g-3">
                        {{-- Arabic Column --}}
                        <div class="col-md-6">
                            <div class="border-0 rounded-3 p-4 bg-light shadow-sm">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                    <i class="bi bi-translate text-primary"></i>
                                    <span class="fw-bold text-primary">{{ __('admin.labels.arabic') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title[]" class="form-control rounded-3 border-0 bg-white" value="{{ $f['title'] ?? '' }}" placeholder="العنوان بالعربية" style="height: 45px; font-size: 1rem;">
                                </div>
                                <div>
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text[]" class="form-control rounded-3 border-0 bg-white" rows="3" placeholder="النص بالعربية" style="font-size: 1rem;">{{ $f['text'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- English Column --}}
                        <div class="col-md-6">
                            <div class="border-0 rounded-3 p-4 bg-light shadow-sm">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                                    <i class="bi bi-globe text-primary"></i>
                                    <span class="fw-bold text-primary">{{ __('admin.labels.english') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title_en[]" class="form-control rounded-3 border-0 bg-white" value="{{ $f['title_en'] ?? '' }}" placeholder="English Title" style="height: 45px; font-size: 1rem;">
                                </div>
                                <div>
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text_en[]" class="form-control rounded-3 border-0 bg-white" rows="3" placeholder="English Text" style="font-size: 1rem;">{{ $f['text_en'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Form Actions --}}
    <div class="d-flex gap-3 justify-content-end pt-3 border-top mt-4">
        <a href="{{ route('admin.why.index') }}" class="btn btn-outline-secondary px-5 py-2 rounded-3" style="min-width: 150px; font-weight: 600;">
            <i class="bi bi-x-circle me-2"></i>{{ __('admin.common.cancel') }}
        </a>
        <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 shadow-sm" style="min-width: 150px; font-weight: 600;">
            <i class="bi bi-check-lg me-2"></i>{{ __('admin.common.form_save') }}
        </button>
    </div>
</form>
