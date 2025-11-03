@extends('layouts.admin')
@section('title','فيديو الصفحة الرئيسية')

@section('content')
    <div class="container-fluid p-0">
        <div class="card border-0 shadow-sm rounded-3 bg-white">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <h6 class="mb-0 fw-semibold">إعداد فيديو الصفحة الرئيسية</h6>
            </div>

            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <form action="{{ route('admin.homeVideo.update') }}" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-3 form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1" {{ $enabled ? 'checked' : '' }}>
                        <label class="form-check-label" for="enabled">تفعيل عرض الفيديو على الصفحة الرئيسية</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">رابط الفيديو (YouTube) أو الـID</label>
                        @php
                            $videoUrl = $videoId ? 'https://www.youtube.com/watch?v='.$videoId : '';
                        @endphp
                        <input type="text" name="video_url" id="video_url"
                               class="form-control @error('video_url') is-invalid @enderror"
                               placeholder="https://youtu.be/abcdEFGhijk أو abcdEFGhijk"
                               value="{{ old('video_url', $videoUrl) }}">
                        @error('video_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        <div class="form-text">ندعم youtu.be / watch?v= / embed / shorts أو ID مباشرة (11 حرف).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">عنوان توضيحي (Caption)</label>
                        <input type="text" name="caption" class="form-control @error('caption') is-invalid @enderror"
                               value="{{ old('caption', $caption) }}" maxlength="255">
                        @error('caption') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    {{-- معاينة --}}
                    <div class="mb-4">
                        <label class="form-label">معاينة</label>
                        @php
                            $thumb = $videoId ? "https://img.youtube.com/vi/$videoId/maxresdefault.jpg" : null;
                        @endphp
                        <div id="video-preview" class="border rounded-3 overflow-hidden" style="aspect-ratio:16/9; position:relative; background:#f5f7fb;">
                            @if($videoId)
                                <img src="{{ $thumb }}" onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'"
                                     alt="Preview" style="width:100%; height:100%; object-fit:cover;">
                                <div style="position:absolute; inset:0; background:linear-gradient(to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5));"></div>
                                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:90px; height:90px; border-radius:50%; background:rgba(220,53,69,.9); display:flex; align-items:center; justify-content:center; color:#fff; font-size:38px; box-shadow:0 8px 25px rgba(220,53,69,.4);">
                                    <i class="fas fa-play" style="margin-left:6px;"></i>
                                </div>
                                <div style="position:absolute; bottom:1rem; left:1rem; color:#fff; font-weight:600; text-shadow:0 2px 4px rgba(0,0,0,.5);">
                                    {{ $caption }}
                                </div>
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">لا يوجد فيديو محدد</div>
                            @endif
                        </div>
                    </div>

                    <button class="btn btn-primary">
                        <i class="ri-save-2-line"></i> حفظ
                    </button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const url = document.getElementById('video_url');
                const preview = document.getElementById('video-preview');

                const extractId = (val) => {
                    if (!val) return null;
                    if (/^[A-Za-z0-9_-]{11}$/.test(val)) return val;
                    let m;
                    if (m = val.match(/youtu\.be\/([A-Za-z0-9_-]{11})/i)) return m[1];
                    if (m = val.match(/[?&]v=([A-Za-z0-9_-]{11})/i)) return m[1];
                    if (m = val.match(/embed\/([A-Za-z0-9_-]{11})/i)) return m[1];
                    if (m = val.match(/shorts\/([A-Za-z0-9_-]{11})/i)) return m[1];
                    return null;
                };

                const renderPreview = () => {
                    const id = extractId(url.value);
                    if (!id) {
                        preview.innerHTML = '<div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted">لا يوجد فيديو محدد</div>';
                        return;
                    }
                    const thumb = `https://img.youtube.com/vi/${id}/maxresdefault.jpg`;
                    preview.innerHTML = `
      <img src="${thumb}" onerror="this.src='https://img.youtube.com/vi/${id}/hqdefault.jpg'"
           alt="Preview" style="width:100%; height:100%; object-fit:cover;">
      <div style="position:absolute; inset:0; background:linear-gradient(to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5));"></div>
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); width:90px; height:90px; border-radius:50%; background:rgba(220,53,69,.9); display:flex; align-items:center; justify-content:center; color:#fff; font-size:38px; box-shadow:0 8px 25px rgba(220,53,69,.4);">
        <i class="fas fa-play" style="margin-left:6px;"></i>
      </div>
    `;
                };

                url?.addEventListener('input', renderPreview);
            });
        </script>
    @endpush
@endsection
