@php
    $breadcrumbTitle     = __('admin.settings.homepage_video_settings');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
    
    $videoUrl = $videoId ? 'https://www.youtube.com/watch?v='.$videoId : '';
    $thumb = $videoId ? "https://img.youtube.com/vi/$videoId/maxresdefault.jpg" : null;
@endphp
@extends('layouts.admin')
@section('title', __('admin.settings.homepage_video_settings'))

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <x-admin.card class="mb-4">
            <x-admin.card-header-form
                icon="bi-camera-video"
                :title="__('admin.settings.homepage_video_settings')" />
        </x-admin.card>

        <form action="{{ route('admin.homeVideo.update') }}" method="POST">
            @csrf @method('PUT')

            <div class="row g-4">
                <!-- Left Column: Form -->
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-body p-4">
                            <!-- Enable/Disable Toggle -->
                            <div class="mb-4 pb-3 border-bottom">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="enabled" name="enabled" value="1" {{ $enabled ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold text-dark" for="enabled">
                                        {{ __('admin.settings.enable_video_display') }}
                                    </label>
                                </div>
                            </div>

                            <!-- Video URL -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    {{ __('admin.labels.video_url') }}
                                </label>
                                <input type="text" name="video_url" id="video_url"
                                       class="form-control rounded-3  @error('video_url') is-invalid @enderror"

                                       placeholder="https://youtu.be/abcdEFGhijk"
                                       value="{{ old('video_url', $videoUrl) }}">
                                @error('video_url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">
                                    {{ __('admin.settings.video_url_help') }}
                                </small>
                            </div>

                            <!-- Caption -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark mb-2">
                                    {{ __('admin.settings.video_caption') }}
                                </label>
                                <input type="text" name="caption" id="caption"
                                       class="form-control rounded-3  @error('caption') is-invalid @enderror"

                                       value="{{ old('caption', $caption) }}"
                                       placeholder="مثال: شاهد فيديو تعريفي عن خدماتنا"
                                       maxlength="255">
                                @error('caption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-cancel">
                                    إلغاء
                                </a>
                                <button type="submit" class="btn btn-save d-flex align-items-center gap-2">
                                    <i class="bi bi-check-circle"></i>
                                    {{ __('admin.settings.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Preview -->
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 bg-white">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold text-dark mb-3">معاينة الفيديو</h6>
                            <div id="video-preview" class="border rounded-4 overflow-hidden position-relative bg-light" style="aspect-ratio:16/9; min-height: 300px;">
                                @if($videoId)
                                    <img src="{{ $thumb }}" 
                                         onerror="this.src='https://img.youtube.com/vi/{{ $videoId }}/hqdefault.jpg'"
                                         alt="Video Preview" 
                                         class="w-100 h-100" 
                                         style="object-fit:cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle" 
                                         style="width: 80px; height: 80px; border-radius: 50%; background: rgba(220, 53, 69, 0.9); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; cursor: pointer;" 
                                         onclick="window.open('https://www.youtube.com/watch?v={{ $videoId }}', '_blank')">
                                        <i class="bi bi-play-fill" style="margin-left: 4px;"></i>
                                    </div>
                                    @if($caption)
                                        <div class="position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: rgba(0,0,0,0.6);">
                                            <span class="small">{{ $caption }}</span>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4">
                                        <i class="bi bi-camera-video-off fs-1 mb-2 opacity-50"></i>
                                        <p class="mb-0 text-center small">أدخل رابط YouTube لرؤية المعاينة</p>
                                    </div>
                                @endif
                            </div>

                            @if($videoId)
                                <div class="mt-3 d-flex align-items-center justify-content-between">
                                    <small class="text-muted">معرف الفيديو: <code class="text-primary">{{ $videoId }}</code></small>
                                    <a href="https://www.youtube.com/watch?v={{ $videoId }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-3">
                                        <i class="bi bi-youtube me-1"></i>فتح في YouTube
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const url = document.getElementById('video_url');
            const caption = document.getElementById('caption');
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
                const captionText = caption.value || '';
                
                if (!id) {
                    preview.innerHTML = `
                        <div class="w-100 h-100 d-flex flex-column align-items-center justify-content-center text-muted p-4">
                            <i class="bi bi-camera-video-off fs-1 mb-2 opacity-50"></i>
                            <p class="mb-0 text-center small">أدخل رابط YouTube لرؤية المعاينة</p>
                        </div>
                    `;
                    return;
                }
                
                const thumb = `https://img.youtube.com/vi/${id}/maxresdefault.jpg`;
                preview.innerHTML = `
                    <img src="${thumb}" 
                         onerror="this.src='https://img.youtube.com/vi/${id}/hqdefault.jpg'"
                         alt="Video Preview" 
                         class="w-100 h-100" 
                         style="object-fit:cover;">
                    <div class="position-absolute top-50 start-50 translate-middle" 
                         style="width: 80px; height: 80px; border-radius: 50%; background: rgba(220, 53, 69, 0.9); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 36px; cursor: pointer;" 
                         onclick="window.open('https://www.youtube.com/watch?v=${id}', '_blank')">
                        <i class="bi bi-play-fill" style="margin-left: 4px;"></i>
                    </div>
                    ${captionText ? `
                        <div class="position-absolute bottom-0 start-0 w-100 p-3 text-white" style="background: rgba(0,0,0,0.6);">
                            <span class="small">${captionText}</span>
                        </div>
                    ` : ''}
                `;
            };

            url?.addEventListener('input', renderPreview);
            caption?.addEventListener('input', renderPreview);
        });
    </script>
    @endpush
@endsection
