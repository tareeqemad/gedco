@php use Illuminate\Support\Str; @endphp

<!-- Desktop Table View -->
<div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
        <tr>
            <th class="text-center text-muted small fw-semibold" style="width:60px">#</th>
            <th class="text-muted small fw-semibold">{{ __('admin.tenders.table_title') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:120px">{{ __('admin.tenders.table_date') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:130px">{{ __('admin.tenders.table_user') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:100px">{{ __('admin.tenders.table_event') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:160px">{{ __('admin.tenders.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($tenders as $t)
            @php
                $contentPreview = Str::limit(strip_tags((string)$t->new_value_1), 80);
            @endphp
            <tr>
                <td class="text-center fw-medium small">{{ $t->mnews_id ?? $t->id }}</td>
                <td>
                    <div class="fw-medium" style="max-width: 350px;">{{ Str::limit($t->column_name_1, 60) ?: '—' }}</div>
                    @if($contentPreview)
                        <small class="text-muted d-block text-truncate" style="max-width: 350px;">{{ $contentPreview }}</small>
                    @endif
                </td>
                <td class="text-center">
                    @if($t->the_date_1)
                        <div class="fw-medium small">{{ $t->the_date_1 }}</div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($t->the_user_1)
                        <div class="fw-medium small text-primary">{{ $t->the_user_1 }}</div>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($t->event_1)
                        @php
                            $eventClass = match($t->event_1) {
                                'create' => 'bg-success',
                                'update' => 'bg-info',
                                'delete' => 'bg-danger',
                                default  => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $eventClass }} rounded-pill" style="font-size: 0.7rem;">{{ $t->event_1 }}</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ route('admin.tenders.show', $t->id) }}"
                           class="btn btn-outline-primary btn-sm rounded-3"
                           title="{{ __('admin.tenders.view') }}">
                            <i class="bi bi-eye"></i>
                        </a>
                        @can('tenders.edit')
                            <a href="{{ route('admin.tenders.edit', $t->id) }}"
                               class="btn btn-outline-warning btn-sm rounded-3"
                               title="{{ __('admin.tenders.edit') }}">
                                <i class="bi bi-pencil"></i>
                            </a>
                        @endcan
                        @can('tenders.delete')
                            <button type="button"
                                    class="btn btn-outline-danger btn-sm rounded-3"
                                    onclick="delTender({{ $t->id }})"
                                    title="{{ __('admin.tenders.delete') }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <i class="bi bi-file-earmark-text display-4 text-muted opacity-50 d-block mb-3"></i>
                    <h5 class="text-muted mb-2">{{ __('admin.tenders.no_data') }}</h5>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Card View -->
<div class="d-md-none p-3">
    @forelse($tenders as $t)
        @php $contentPreview = Str::limit(strip_tags((string)$t->new_value_1), 100); @endphp
        <div class="card mb-3 shadow-sm border-0 rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <span class="badge bg-primary rounded-pill">#{{ $t->mnews_id ?? $t->id }}</span>
                    @if($t->event_1)
                        @php
                            $eventClass = match($t->event_1) {
                                'create' => 'bg-success',
                                'update' => 'bg-info',
                                'delete' => 'bg-danger',
                                default  => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $eventClass }} rounded-pill" style="font-size: 0.7rem;">{{ $t->event_1 }}</span>
                    @endif
                </div>

                <h6 class="mb-2 fw-semibold">{{ $t->column_name_1 ?: __('admin.tenders.no_title') }}</h6>

                @if($contentPreview)
                    <p class="text-muted small mb-2">{{ $contentPreview }}</p>
                @endif

                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if($t->the_date_1)
                        <span class="badge bg-light text-dark border small">
                            <i class="bi bi-calendar me-1"></i>{{ $t->the_date_1 }}
                        </span>
                    @endif
                    @if($t->the_user_1)
                        <span class="badge bg-light text-dark border small">
                            <i class="bi bi-person me-1"></i>{{ $t->the_user_1 }}
                        </span>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.tenders.show', $t->id) }}"
                       class="btn btn-outline-primary btn-sm rounded-3 flex-fill">
                        <i class="bi bi-eye me-1"></i>{{ __('admin.tenders.view') }}
                    </a>
                    @can('tenders.edit')
                        <a href="{{ route('admin.tenders.edit', $t->id) }}"
                           class="btn btn-outline-warning btn-sm rounded-3 flex-fill">
                            <i class="bi bi-pencil me-1"></i>{{ __('admin.tenders.edit') }}
                        </a>
                    @endcan
                    @can('tenders.delete')
                        <button type="button"
                                class="btn btn-outline-danger btn-sm rounded-3 flex-fill"
                                onclick="delTender({{ $t->id }})">
                            <i class="bi bi-trash me-1"></i>{{ __('admin.tenders.delete') }}
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-text display-4 text-muted opacity-50 d-block mb-3"></i>
            <h5 class="text-muted mb-2">{{ __('admin.tenders.no_data') }}</h5>
        </div>
    @endforelse
</div>
