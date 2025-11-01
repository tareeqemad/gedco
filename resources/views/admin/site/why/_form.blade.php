<form action="{{ $route }}" method="POST">
    @csrf
    @if($method !== 'POST') @method($method) @endif

    <div class="mb-3">
        <label class="form-label">نص الشارة</label>
        <input type="text" name="badge" class="form-control border-orange-soft"
               value="{{ old('badge', $model->badge ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">العنوان الرئيسي</label>
        <input type="text" name="tagline" class="form-control border-orange-soft"
               value="{{ old('tagline', $model->tagline ?? '') }}" required>
    </div>

    <div class="mb-3">
        <label class="form-label">الوصف</label>
        <textarea name="description" class="form-control border-orange-soft" rows="3">{{ old('description', $model->description ?? '') }}</textarea>
    </div>

    <hr class="my-4">

    @php
        $items = old('feature_title') ? collect(old('feature_title'))->map(function($t, $i){
            return [
                'title' => $t,
                'text'  => old('feature_text')[$i] ?? '',
                'icon'  => old('feature_icon')[$i] ?? 'bi bi-lightning-charge-fill',
            ];
        })->toArray() : ($items ?? []);

        if (empty($items)) $items = [[]];
    @endphp

    <h6 class="fw-bold text-orange mb-3">العناصر</h6>

    @foreach($items as $i => $f)
        @php $iconClass = $f['icon'] ?? 'bi bi-lightning-charge-fill'; @endphp

        <div class="feature-card">

            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="{{ $iconClass }} text-orange" style="font-size:22px;"></i>
                <small class="fw-semibold text-orange">العنصر رقم {{ $i+1 }}</small>
            </div>

            <input type="hidden" name="feature_icon[]" value="{{ $iconClass }}">

            <div class="mb-3">
                <label class="form-label">العنوان</label>
                <input type="text" name="feature_title[]" class="form-control border-orange-soft" value="{{ $f['title'] ?? '' }}">
            </div>

            <div>
                <label class="form-label">النص</label>
                <textarea name="feature_text[]" class="form-control border-orange-soft" rows="2">{{ $f['text'] ?? '' }}</textarea>
            </div>
        </div>
    @endforeach

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-orange px-4 fw-bold">حفظ</button>
        <a href="{{ route('admin.why.index') }}" class="btn btn-light px-4">إلغاء</a>
    </div>
</form>
