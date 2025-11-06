@if($items->count())
    <div class="row g-3">
        @foreach($items as $row)
            <div class="col-12 col-md-6 col-xl-4" data-news-id="{{ $row->id }}">
                <div class="card card-news h-100">
                    <a class="thumb" href="{{ route('admin.news.show', $row) }}">
                        @php
                            $coverUrl = $row->cover_url ?? null;
                            if (!$coverUrl && method_exists($row, 'coverUrl')) {
                                $coverUrl = $row->coverUrl();
                            }
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
                                    {{ $row->status === 'draft' ? 'مسودة' : 'منشور' }}
                                </span>
                                @if($row->featured)
                                    <span class="badge bg-warning-subtle text-warning border">مميّز</span>
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
                            {{ $row->excerpt(120) }}
                        </p>

                        <!-- مين أضاف + مين عدّل + التواريخ -->
                        <div class="d-flex justify-content-between align-items-center mb-3 text-muted small">
                            <div>
                                <strong>أضاف:</strong> {{ $row->creator?->name ?? 'غير معروف' }}<br>
                                <strong>عدّل:</strong> {{ $row->updater?->name ?? 'غير معروف' }}
                            </div>
                            <div class="text-end">
                                <div>{{ $row->created_at->format('Y-m-d H:i') }}</div>
                                <div>{{ $row->updated_at->format('Y-m-d H:i') }}</div>
                            </div>
                        </div>

                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.news.show', $row) }}" class="btn btn-sm btn-light">عرض</a>
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit', $row) }}" class="btn btn-sm btn-warning">تعديل</a>
                            @endcan
                            @can('news.delete')
                                <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-delete-url="{{ route('admin.news.destroy', $row) }}"
                                        onclick="confirmDelete(this, {{ $row->id }})">
                                    حذف
                                </button>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center text-muted py-5">لا توجد أخبار.</div>
@endif
