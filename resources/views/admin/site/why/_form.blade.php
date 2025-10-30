<form action="{{ $route }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="mb-3">
        <label class="form-label">نص الشارة (Badge)</label>
        <input type="text" name="badge" class="form-control"
               value="{{ old('badge', $model->badge ?? 'لماذا تختارنا') }}" required>
        @error('badge') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">العنوان الرئيسي (Tagline)</label>
        <input type="text" name="tagline" class="form-control"
               value="{{ old('tagline', $model->tagline ?? '') }}" required>
        @error('tagline') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">الوصف</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $model->description ?? '') }}</textarea>
        @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <hr class="my-4">

    @php
        // بناء العناصر من old() أولاً ثم من الموديل
        $items = old('feature_title') ? collect(old('feature_title'))->map(function($t, $i){
            return [
                'title' => $t,
                'text'  => old('feature_text')[$i] ?? '',
                'icon'  => old('feature_icon')[$i] ?? 'bi bi-lightning-charge-fill',
            ];
        })->toArray() : ($items ?? []);

        if (empty($items)) {
            $items = [[
                'title' => '',
                'text'  => '',
                'icon'  => 'bi bi-lightning-charge-fill',
            ]];
        }
    @endphp

    <div id="features-repeater">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="mb-0">العناصر (Features)</h6>
         </div>

        <div id="features-list">
            @foreach($items as $i => $f)
                @php $iconClass = $f['icon'] ?? 'bi bi-lightning-charge-fill'; @endphp
                <div class="border rounded-3 p-3 mb-3 feature-item">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label">العنوان</label>
                            <input type="text" name="feature_title[]" class="form-control"
                                   value="{{ $f['title'] ?? '' }}">
                        </div>

                        <div class="col-md-5">
                            <label class="form-label d-flex align-items-center gap-2">
                                الأيقونة (Bootstrap Icons)
                                <i class="{{ $iconClass }}"></i>
                            </label>
                            <input type="text"
                                   name="feature_icon[]"
                                   class="form-control text-center bg-light"
                                   value="{{ $iconClass }}"
                                   readonly
                                   style="pointer-events: none;">
                        </div>



                        <div class="col-12">
                            <label class="form-label">النص</label>
                            <textarea name="feature_text[]" class="form-control" rows="2">{{ $f['text'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">حفظ</button>
        <a href="{{ route('admin.why.index') }}" class="btn btn-light">إلغاء</a>
    </div>
</form>

<script>
    function addFeature(){
        const tpl = `
    <div class="border rounded-3 p-3 mb-3 feature-item">
        <div class="row g-3">
            <div class="col-md-5">
                <label class="form-label">العنوان</label>
                <input type="text" name="feature_title[]" class="form-control" value="">
            </div>

            <div class="col-md-5">
                <label class="form-label d-flex align-items-center gap-2">
                    الأيقونة (Bootstrap Icons)
                    <i class="bi bi-lightning-charge-fill"></i>
                </label>
                <input type="text"
                       name="feature_icon[]"
                       class="form-control text-center bg-light"
                       value="bi bi-lightning-charge-fill"
                       readonly
                       style="pointer-events: none;">
            </div>



            <div class="col-12">
                <label class="form-label">النص</label>
                <textarea name="feature_text[]" class="form-control" rows="2"></textarea>
            </div>
        </div>
    </div>`;
        document.getElementById('features-list').insertAdjacentHTML('beforeend', tpl);
    }

    function removeFeature(btn){
        const item = btn.closest('.feature-item');
        item.remove();
    }
</script>
