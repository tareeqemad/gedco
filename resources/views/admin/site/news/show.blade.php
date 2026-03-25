@extends('layouts.admin')
@section('title', __('admin.news.show_title') . ' #' . $item->id)

@section('content')
    @php
        $breadcrumbTitle = __('admin.news.show_title') . ' #' . $item->id;
        $breadcrumbParent = __('admin.menu.news');
        $breadcrumbParentUrl = route('admin.news.index');
        $published = $item->is_published ?? (($item->status ?? 'published') === 'published');
        $publishedAt = optional($item->published_at)->format('Y-m-d') ?? '—';
        $coverUrl = $item->cover_url;
        $pdfUrl = $item->pdf_url;
    @endphp

    <div class="container-fluid p-0">
        <div class="row g-3 g-lg-4">
            {{-- ============ Main Content ============ --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    {{-- Cover Image --}}
                    @if($coverUrl)
                        <div class="news-show-cover position-relative">
                            <img src="{{ $coverUrl }}" alt="{{ $item->title }}" class="w-100" style="height: 280px; object-fit: cover;">
                            <div class="news-show-cover-overlay"></div>
                        </div>
                    @endif

                    <div class="card-body p-3 p-md-4">
                        {{-- Title --}}
                        <h3 class="fw-bold mb-3" style="color: #24364A; font-size: 1.35rem; line-height: 1.6;">
                            {{ $item->title }}
                        </h3>

                        {{-- Meta badges --}}
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                            <span class="badge rounded-pill px-3 py-2 {{ $published ? 'bg-success' : 'bg-secondary' }}">
                                <i class="bi bi-{{ $published ? 'check-circle' : 'clock' }} me-1"></i>
                                {{ $published ? __('admin.labels.published') : __('admin.labels.draft') }}
                            </span>
                            @if($item->featured)
                                <span class="badge rounded-pill bg-warning text-dark px-3 py-2">
                                    <i class="bi bi-star-fill me-1"></i> {{ __('admin.news.featured_badge') }}
                                </span>
                            @endif
                            <span class="news-show-meta-item">
                                <i class="bi bi-calendar3"></i>
                                {{ $publishedAt !== '—' ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d F Y') : '—' }}
                            </span>
                            <span class="news-show-meta-item">
                                <i class="bi bi-eye"></i>
                                {{ $item->views ?? 0 }} {{ __('admin.news.show_views') }}
                            </span>
                        </div>

                        {{-- Separator --}}
                        <hr style="border-color: #E6ECF2;">

                        {{-- Content Tabs --}}
                        <ul class="nav nav-tabs news-show-tabs mb-0" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-preview" type="button" role="tab">
                                    <i class="bi bi-eye me-1"></i> {{ __('admin.news.show_tab_preview') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-html" type="button" role="tab">
                                    <i class="bi bi-code-slash me-1"></i> {{ __('admin.news.show_tab_html') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-text" type="button" role="tab">
                                    <i class="bi bi-file-text me-1"></i> {{ __('admin.news.show_tab_text') }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="tab-content">
                        {{-- Preview Tab --}}
                        <div class="tab-pane fade show active" id="tab-preview" role="tabpanel">
                            <div class="px-3 px-md-4 pb-4">
                                <div class="article-html news-show-article" style="direction: rtl; text-align: right;">
                                    {!! $item->body !!}
                                </div>
                            </div>
                        </div>

                        {{-- HTML Tab --}}
                        <div class="tab-pane fade" id="tab-html" role="tabpanel">
                            <div class="px-3 px-md-4 pb-4">
                                <div class="news-show-code-block">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-light text-muted border">HTML</span>
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="copyHtml()">
                                            <i class="bi bi-clipboard me-1"></i> {{ __('admin.labels.copy') }}
                                        </button>
                                    </div>
                                    <pre class="news-show-pre" id="htmlContent"><code>{{ $item->body }}</code></pre>
                                </div>
                            </div>
                        </div>

                        {{-- Text Tab --}}
                        <div class="tab-pane fade" id="tab-text" role="tabpanel">
                            <div class="px-3 px-md-4 pb-4">
                                <div class="news-show-code-block">
                                    <pre class="news-show-pre">{{ strip_tags($item->body ?? '') }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ Sidebar ============ --}}
            <div class="col-12 col-lg-4">
                {{-- Actions Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="card-body p-3">
                        <div class="d-flex gap-2">
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-save flex-fill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-pencil-square"></i> {{ __('admin.news.edit_news') }}
                                </a>
                            @endcan
                            @can('news.delete')
                                <button type="button" id="btnDelete" class="btn btn-outline-danger rounded-3 flex-fill d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-trash"></i> {{ __('admin.news.delete') }}
                                </button>
                            @endcan
                            <a href="{{ route('admin.news.index') }}" class="btn btn-cancel flex-fill d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-arrow-right"></i> {{ __('admin.news.back') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Metadata Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="news-show-sidebar-header">
                        <i class="bi bi-info-circle"></i>
                        <span>{{ __('admin.news.show_meta_data') }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="news-show-meta-list">
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">ID</span>
                                <span class="badge rounded-pill" style="background: #1E5F8A; font-size: 0.75rem;">{{ $item->id }}</span>
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">{{ __('admin.news.show_status') }}</span>
                                <span class="badge rounded-pill {{ $published ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $published ? __('admin.labels.published') : __('admin.labels.draft') }}
                                </span>
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">{{ __('admin.news.show_featured') }}</span>
                                @if($item->featured)
                                    <span class="badge rounded-pill bg-warning text-dark"><i class="bi bi-star-fill me-1"></i> {{ __('admin.labels.yes') }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">{{ __('admin.news.show_publish_date') }}</span>
                                <span class="fw-medium" style="color: #24364A;">
                                    {{ $publishedAt !== '—' ? \Carbon\Carbon::parse($publishedAt)->locale('ar')->translatedFormat('d M Y') : '—' }}
                                </span>
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">{{ __('admin.news.show_created_at') }}</span>
                                <span class="fw-medium small" style="color: #24364A;">{{ optional($item->created_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">{{ __('admin.news.show_updated_at') }}</span>
                                <span class="fw-medium small" style="color: #24364A;">{{ optional($item->updated_at)->format('Y-m-d H:i') ?? '—' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- People Card --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3">
                    <div class="news-show-sidebar-header">
                        <i class="bi bi-people"></i>
                        <span>{{ __('admin.news.show_created_by') }} / {{ __('admin.news.show_updated_by') }}</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="news-show-meta-list">
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">
                                    <i class="bi bi-person-plus text-success me-1"></i>{{ __('admin.news.show_created_by') }}
                                </span>
                                <span class="fw-medium" style="color: #24364A;">{{ $item->creator?->name ?? __('admin.news.show_unknown') }}</span>
                            </div>
                            <div class="news-show-meta-row">
                                <span class="news-show-meta-label">
                                    <i class="bi bi-person-gear text-primary me-1"></i>{{ __('admin.news.show_updated_by') }}
                                </span>
                                <span class="fw-medium" style="color: #24364A;">{{ $item->updater?->name ?? __('admin.news.show_unknown') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PDF Attachment Card --}}
                @if($pdfUrl)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="news-show-sidebar-header" style="background: #FFF5F5; border-color: #FECACA;">
                            <i class="bi bi-file-pdf text-danger"></i>
                            <span>{{ __('admin.news.show_attachment') }}</span>
                        </div>
                        <div class="card-body p-3">
                            <a href="{{ $pdfUrl }}" class="btn btn-outline-danger w-100 rounded-3 d-flex align-items-center justify-content-center gap-2" target="_blank">
                                <i class="bi bi-file-earmark-pdf fs-5"></i> {{ __('admin.news.show_open_pdf') }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/news.css') }}">
    <style>
        /* ========== News Show Page ========== */
        .news-show-cover-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 80px;
            background: linear-gradient(transparent, rgba(255,255,255,0.9));
        }

        .news-show-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.82rem;
            color: #6B7C93;
            background: #F4F7FA;
            padding: 0.3rem 0.7rem;
            border-radius: 2rem;
        }

        .news-show-meta-item i {
            font-size: 0.85rem;
            color: #8A99AB;
        }

        /* Tabs */
        .news-show-tabs {
            border-bottom: 2px solid #E6ECF2 !important;
        }

        .news-show-tabs .nav-link {
            border: none !important;
            color: #6B7C93 !important;
            font-weight: 500;
            font-size: 0.88rem;
            padding: 0.6rem 1rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
            transition: all 0.2s ease;
            margin-bottom: -2px;
        }

        .news-show-tabs .nav-link:hover {
            color: #1E5F8A !important;
            background: rgba(30, 95, 138, 0.05) !important;
        }

        .news-show-tabs .nav-link.active {
            color: #1E5F8A !important;
            font-weight: 600;
            background: transparent !important;
            border-bottom: 2px solid #1E5F8A !important;
        }

        /* Article Content */
        .news-show-article {
            line-height: 1.9;
            font-size: 0.98rem;
            color: #333;
        }

        .news-show-article img {
            max-width: 100% !important;
            height: auto !important;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        .news-show-article h1, .news-show-article h2, .news-show-article h3 {
            color: #24364A;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .news-show-article p {
            margin-bottom: 1rem;
        }

        /* Code Block */
        .news-show-code-block {
            background: #F8FBFD;
            border: 1px solid #E6ECF2;
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .news-show-pre {
            background: #FDFDFE;
            border: 1px solid #E6ECF2;
            border-radius: 0.5rem;
            padding: 1rem;
            margin: 0;
            white-space: pre-wrap;
            word-break: break-word;
            font-size: 0.85rem;
            max-height: 500px;
            overflow-y: auto;
            direction: ltr;
            text-align: left;
        }

        /* Sidebar */
        .news-show-sidebar-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            background: #F4F7FA;
            border-bottom: 1px solid #E6ECF2;
            font-weight: 600;
            font-size: 0.88rem;
            color: #24364A;
            border-radius: 1rem 1rem 0 0;
        }

        .news-show-sidebar-header i {
            font-size: 1rem;
            color: #1E5F8A;
        }

        .news-show-meta-list {
            /* no extra style needed */
        }

        .news-show-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.7rem 1rem;
            border-bottom: 1px solid #F1F5F9;
            transition: background 0.15s ease;
        }

        .news-show-meta-row:last-child {
            border-bottom: none;
        }

        .news-show-meta-row:hover {
            background: #FAFBFC;
        }

        .news-show-meta-label {
            font-size: 0.82rem;
            color: #6B7C93;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .news-show-cover img {
                height: 200px !important;
            }
        }

        @media (max-width: 575.98px) {
            .news-show-cover img {
                height: 160px !important;
            }

            .news-show-tabs .nav-link {
                font-size: 0.8rem;
                padding: 0.5rem 0.65rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Copy HTML function
        function copyHtml() {
            const content = document.getElementById('htmlContent')?.innerText;
            if (!content) return;
            navigator.clipboard.writeText(content).then(() => {
                if (window.adminNotifications) {
                    window.adminNotifications.success('{{ __('admin.labels.copied') }}');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            const btnDelete = document.getElementById('btnDelete');
            if (!btnDelete) return;

            btnDelete.addEventListener('click', function () {
                Swal.fire({
                    title: '{{ __('admin.news.show_delete_confirm_title') }}',
                    text: '{{ __('admin.news.show_delete_confirm_text') }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '{{ __('admin.news.show_delete_confirm_yes') }}',
                    cancelButtonText: '{{ __('admin.common.cancel') }}',
                    reverseButtons: true,
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger px-4',
                        cancelButton: 'btn btn-secondary px-4 me-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch('{{ route('admin.news.destroy', $item) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: '{{ __('admin.news.show_delete_success') }}',
                                    text: data.message,
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = '{{ route('admin.news.index') }}';
                                });
                            } else {
                                Swal.fire('{{ __('admin.news.show_delete_error') }}', data.message || '{{ __('admin.news.show_delete_failed') }}', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('{{ __('admin.news.show_delete_error') }}', '{{ __('admin.news.show_delete_connection_error') }}', 'error');
                        });
                    }
                });
            });
        });
    </script>
@endpush
