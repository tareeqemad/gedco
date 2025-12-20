@if($items->count())
    <div class="row g-3">
        @foreach($items as $row)
            <div class="col-12 col-md-6 col-xl-4" data-news-id="{{ $row->id }}">
                <div class="card card-news h-100">
                    <a class="thumb" href="{{ route('admin.news.show', $row) }}">
                        @php
                            $coverUrl = $row->cover_url ?? (method_exists($row, 'coverUrl') ? $row->coverUrl() : null);
                        @endphp
                        @if($coverUrl)
                            <img src="{{ $coverUrl }}" alt="cover">
                        @else
                            <img src="{{ asset('assets/admin/images/apps/google.png') }}" alt="placeholder">
                        @endif
                    </a>

                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex gap-2 align-items-center">
                        <span class="badge badge-dot {{ $row->status === 'draft' ? 'badge-draft' : '' }}">
                            {{ $row->status === 'draft' ? __('admin.news.status_draft') : __('admin.news.status_published') }}
                        </span>
                                @if($row->featured)
                                    <span class="badge bg-warning-subtle text-warning border">{{ __('admin.news.featured_badge') }}</span>
                                @endif
                                @if($row->pdf_url)
                                    <span class="badge bg-danger-subtle text-danger border">PDF</span>
                                @endif
                            </div>
                            <small class="text-muted">
                                {{ optional($row->published_at)->format('Y-m-d') ?? '—' }}
                            </small>
                        </div>

                        <h6 class="mb-1">
                            <a class="text-decoration-none" href="{{ route('admin.news.show', $row) }}">
                                {{ \Illuminate\Support\Str::limit($row->title, 90) }}
                            </a>
                        </h6>

                        <p class="text-muted small mb-3">
                            {{ method_exists($row,'excerpt') ? $row->excerpt(120) : \Illuminate\Support\Str::limit(strip_tags($row->content), 120) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mb-3 text-muted small">
                            <div>
                                <strong>{{ __('admin.news.show_created_by') }}:</strong> {{ $row->creator?->name ?? __('admin.news.show_unknown') }}<br>
                                <strong>{{ __('admin.news.show_updated_by') }}:</strong> {{ $row->updater?->name ?? __('admin.news.show_unknown') }}
                            </div>
                            <div class="text-end">
                                <div>{{ optional($row->created_at)->format('Y-m-d H:i') }}</div>
                                <div>{{ optional($row->updated_at)->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>

                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.news.show', $row) }}" class="btn btn-sm btn-light">{{ __('admin.actions.view') }}</a>
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit', $row) }}" class="btn btn-sm btn-warning">{{ __('admin.actions.edit') }}</a>
                            @endcan
                            @can('news.delete')
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-delete-url="{{ route('admin.news.destroy', $row) }}"
                                        onclick="confirmDelete(this, {{ $row->id }})">
                                    {{ __('admin.actions.delete') }}
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
                <i class="bi bi-newspaper"></i>
            </div>
            <h5 class="text-dark mb-2 fw-semibold">{{ __('admin.messages.no_data') }}</h5>
            <p class="text-muted mb-4">{{ __('admin.news.no_news_message') }}</p>
            @can('news.create')
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('admin.news.add_new_news') }}
                </a>
            @endcan
        </div>
    </div>
@endif
