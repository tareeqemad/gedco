@if($items->count())
    <div class="row g-4">
        @foreach($items as $row)
            <div class="col-12 col-md-6 col-xl-4" data-news-id="{{ $row->id }}">
                <div class="card news-card h-100 border-0 shadow-sm rounded-4 overflow-hidden transition-all">
                    @php
                        $coverUrl = $row->cover_url ?? (method_exists($row, 'coverUrl') ? $row->coverUrl() : null);
                    @endphp
                    
                    <!-- Cover Image -->
                    <div class="news-card-image position-relative overflow-hidden" style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <a href="{{ route('admin.news.show', $row) }}" class="text-decoration-none">
                            @if($coverUrl)
                                <img src="{{ $coverUrl }}" alt="{{ $row->title }}" 
                                     class="w-100 h-100 object-fit-cover transition-transform">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image text-white opacity-50" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="position-absolute top-0 start-0 p-2">
                                <span class="badge {{ $row->status === 'draft' ? 'bg-secondary' : 'bg-success' }} shadow-sm">
                                    {{ $row->status === 'draft' ? __('admin.news.status_draft') : __('admin.news.status_published') }}
                                </span>
                            </div>
                            @if($row->featured)
                                <div class="position-absolute top-0 end-0 p-2">
                                    <span class="badge bg-warning text-dark shadow-sm">
                                        <i class="bi bi-star-fill me-1"></i> {{ __('admin.news.featured_badge') }}
                                    </span>
                                </div>
                            @endif
                        </a>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-3 p-md-4">
                        <!-- Meta Info -->
                        <div class="d-flex justify-content-between align-items-center mb-2 mb-md-3">
                            <div class="d-flex gap-1 gap-sm-2 flex-wrap">
                                <small class="text-muted d-flex align-items-center gap-1 small">
                                    <i class="bi bi-calendar3"></i>
                                    <span class="d-none d-sm-inline">{{ optional($row->published_at)->format('Y-m-d') ?? '—' }}</span>
                                    <span class="d-sm-none">{{ optional($row->published_at)->format('m/d') ?? '—' }}</span>
                                </small>
                                @if($row->pdf_url)
                                    <span class="badge bg-danger-subtle text-danger border-0 small">
                                        <i class="bi bi-file-pdf me-1"></i> <span class="d-none d-sm-inline">PDF</span>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Title -->
                        <h6 class="mb-2 fw-bold fs-6">
                            <a class="text-decoration-none text-dark stretched-link" 
                               href="{{ route('admin.news.show', $row) }}">
                                {{ \Illuminate\Support\Str::limit($row->title, 80) }}
                            </a>
                        </h6>

                        <!-- Excerpt -->
                        <p class="text-muted small mb-2 mb-md-3 lh-base" style="min-height: 3rem;">
                            {{ method_exists($row,'excerpt') ? $row->excerpt(100) : \Illuminate\Support\Str::limit(strip_tags($row->body ?? ''), 100) }}
                        </p>

                        <!-- Author Info -->
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <small class="d-block text-muted fw-medium">
                                        {{ $row->creator?->name ?? __('admin.news.show_unknown') }}
                                    </small>
                                    <small class="text-muted">
                                        {{ optional($row->created_at)->format('Y-m-d') }}
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex gap-1 gap-sm-2 flex-wrap">
                            <a href="{{ route('admin.news.show', $row) }}" 
                               class="btn btn-sm btn-outline-primary flex-fill flex-sm-grow-0">
                                <i class="bi bi-eye me-1"></i> <span class="d-none d-sm-inline">{{ __('admin.actions.view') }}</span>
                                <span class="d-sm-none">{{ __('admin.actions.view') }}</span>
                            </a>
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit', $row) }}" 
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                    <span class="d-none d-md-inline ms-1">تعديل</span>
                                </a>
                            @endcan
                            @can('news.delete')
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger"
                                        data-delete-url="{{ route('admin.news.destroy', $row) }}"
                                        onclick="confirmDelete(this, {{ $row->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5 my-5">
        <div class="empty-state-news">
            <div class="empty-state-icon mb-4">
                <div class="bg-light rounded-circle p-4 d-inline-block">
                    <i class="bi bi-newspaper text-muted" style="font-size: 4rem;"></i>
                </div>
            </div>
            <h5 class="text-dark mb-2 fw-semibold">{{ __('admin.messages.no_data') }}</h5>
            <p class="text-muted mb-4">{{ __('admin.news.no_news_message') }}</p>
            @can('news.create')
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('admin.news.add_new_news') }}
                </a>
            @endcan
        </div>
    </div>
@endif
