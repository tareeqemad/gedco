<form action="{{ $route }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    {{-- ========== Basic Information Section ========== --}}
    <x-admin.card class="mb-4">
        <x-admin.card-header-form
            icon="bi-info-circle"
            :title="__('admin.news.basic_info')" />
        <div class="card-body p-4 p-md-5">
            <div class="row g-4">
                {{-- Badge --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="badge" class="form-control rounded-3 border-0 bg-light @error('badge') is-invalid @enderror"
                           value="{{ old('badge', $model->badge ?? '') }}" required placeholder="مثال: لماذا تختارنا"
                           >
                    @error('badge') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_badge') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="badge_en" class="form-control rounded-3 border-0 bg-light @error('badge_en') is-invalid @enderror"
                           value="{{ old('badge_en', $model->badge_en ?? '') }}" placeholder="e.g: Why Choose Us"
                           >
                    @error('badge_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Tagline --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.arabic') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="tagline" class="form-control rounded-3 border-0 bg-light @error('tagline') is-invalid @enderror"
                           value="{{ old('tagline', $model->tagline ?? '') }}" required placeholder="مثال: شريكك الموثوق في الخدمة الكهربائية"
                           >
                    @error('tagline') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_tagline') }} - {{ __('admin.labels.english') }}
                    </label>
                    <input type="text" name="tagline_en" class="form-control rounded-3 border-0 bg-light @error('tagline_en') is-invalid @enderror"
                           value="{{ old('tagline_en', $model->tagline_en ?? '') }}" placeholder="e.g: Your Trusted Partner in Electrical Services"
                           >
                    @error('tagline_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>

                {{-- Description --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-translate " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.arabic') }}
                    </label>
                    <textarea name="description" class="form-control rounded-3 @error('description') is-invalid @enderror" rows="8" placeholder="الوصف بالعربية" style="font-size: 0.92rem; line-height: 2; min-height: 160px; border: 1.5px solid #CDD9E3;">{{ old('description', $model->description ?? '') }}</textarea>
                    @error('description') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-dark d-flex align-items-center gap-2 mb-2">
                        <i class="bi bi-globe " style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_description') }} - {{ __('admin.labels.english') }}
                    </label>
                    <textarea name="description_en" class="form-control rounded-3 @error('description_en') is-invalid @enderror" rows="8" placeholder="English Description" style="font-size: 0.92rem; line-height: 2; min-height: 160px; border: 1.5px solid #CDD9E3;">{{ old('description_en', $model->description_en ?? '') }}</textarea>
                    @error('description_en') <div class="invalid-feedback d-block mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </x-admin.card>

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

    <x-admin.card class="mb-4">
        <x-admin.card-header-form
            icon="bi-list-check"
            :title="__('admin.why_choose_us.form_items')" />
        <div class="card-body p-4 p-md-5">
            @foreach($items as $i => $f)
                @php $iconClass = $f['icon'] ?? 'bi bi-lightning-charge-fill'; @endphp

                <div class="feature-card mb-4 @if($i > 0) border-top pt-4 mt-4 @endif">
                    <h6 class="fw-bold d-flex align-items-center gap-2 section-title">
                        <i class="{{ $iconClass }}" style="color: #5B7088;"></i>
                        {{ __('admin.why_choose_us.form_item_number') }} {{ $i+1 }}
                    </h6>

                    <input type="hidden" name="feature_icon[]" value="{{ $iconClass }}">

                    <div class="row g-3">
                        {{-- Arabic Column --}}
                        <div class="col-md-6">
                            <div class="rounded-3 p-3" style="background: #F8FBFD; border: 1px solid #E6ECF2;">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid #E6ECF2;">
                                    <i class="bi bi-translate" style="color: #5B7088;"></i>
                                    <span class="fw-bold" style="color: #24364A; font-size: 0.85rem;">{{ __('admin.labels.arabic') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title[]" class="form-control rounded-3 border-0 bg-white" value="{{ $f['title'] ?? '' }}" placeholder="{{ __('admin.labels.title') }} - {{ __('admin.labels.arabic') }}" >
                                </div>
                                <div>
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text[]" class="form-control rounded-3" rows="6" placeholder="النص بالعربية" style="font-size: 0.92rem; line-height: 1.9; min-height: 120px; border: 1.5px solid #CDD9E3;">{{ $f['text'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- English Column --}}
                        <div class="col-md-6">
                            <div class="rounded-3 p-3" style="background: #F8FBFD; border: 1px solid #E6ECF2;">
                                <div class="d-flex align-items-center gap-2 mb-3 pb-2" style="border-bottom: 1px solid #E6ECF2;">
                                    <i class="bi bi-globe" style="color: #5B7088;"></i>
                                    <span class="fw-bold" style="color: #24364A; font-size: 0.85rem;">{{ __('admin.labels.english') }}</span>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_title') }}</label>
                                    <input type="text" name="feature_title_en[]" class="form-control rounded-3 border-0 bg-white" value="{{ $f['title_en'] ?? '' }}" placeholder="English Title" >
                                </div>
                                <div>
                                    <label class="form-label fw-semibold text-dark mb-2">{{ __('admin.why_choose_us.form_item_text') }}</label>
                                    <textarea name="feature_text_en[]" class="form-control rounded-3" rows="6" placeholder="English Text" style="font-size: 0.92rem; line-height: 1.9; min-height: 120px; border: 1.5px solid #CDD9E3;">{{ $f['text_en'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-admin.card>

    {{-- Form Actions --}}
    <div class="d-flex gap-3 justify-content-end pt-3 border-top mt-4">
        <a href="{{ route('admin.why.index') }}" class="btn btn-cancel">
            <i class="bi bi-x-circle me-2"></i>{{ __('admin.common.cancel') }}
        </a>
        <button type="submit" class="btn btn-save">
            <i class="bi bi-check-lg me-2"></i>{{ __('admin.common.form_save') }}
        </button>
    </div>
</form>
