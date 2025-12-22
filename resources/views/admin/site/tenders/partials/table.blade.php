@php
    use Illuminate\Support\Str;
    $mode = $viewMode ?? 'compact';
@endphp

@if($mode === 'compact')
    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th class="text-muted small fw-semibold" style="width:70px">ID</th>
                <th class="text-muted small fw-semibold" style="width:100px">MNEWS_ID</th>
                <th class="text-muted small fw-semibold" style="width:160px">COLUMN_NAME_1</th>
                <th class="text-muted small fw-semibold" style="width:260px">OLD_VALUE_1</th>
                <th class="text-muted small fw-semibold" style="width:260px">NEW_VALUE_1</th>
                <th class="text-muted small fw-semibold" style="width:160px">THE_DATE_1</th>
                <th class="text-muted small fw-semibold" style="width:120px">THE_USER_1</th>
                <th class="text-muted small fw-semibold" style="width:110px">EVENT_1</th>
                <th class="text-muted small fw-semibold text-end" style="width:190px">{{ __('admin.tenders.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tenders as $t)
                @php
                    // Safe truncation: strip tags then limit
                    $oldShort = Str::limit(strip_tags((string)$t->old_value_1), 120);
                    $newShort = Str::limit(strip_tags((string)$t->new_value_1), 120);
                @endphp
                <tr>
                    <td class="small">{{ $t->id }}</td>
                    <td class="small">{{ $t->mnews_id ?? '—' }}</td>
                    <td class="small" title="{{ $t->column_name_1 }}">{{ Str::limit($t->column_name_1, 40) }}</td>
                    <td class="small" title="{{ strip_tags((string)$t->old_value_1) }}">
                        <div class="text-truncate" style="max-width: 260px;">{{ $oldShort }}</div>
                    </td>
                    <td class="small" title="{{ strip_tags((string)$t->new_value_1) }}">
                        <div class="text-truncate" style="max-width: 260px;">{{ $newShort }}</div>
                    </td>
                    <td class="small">{{ $t->the_date_1 ?? '—' }}</td>
                    <td class="small">{{ $t->the_user_1 ?? '—' }}</td>
                    <td class="small" title="{{ $t->event_1 }}">{{ Str::limit($t->event_1, 30) }}</td>
                    <td class="text-end">
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.tenders.show',$t->id) }}" 
                               class="btn btn-outline-primary btn-sm rounded-3 shadow-sm"
                               title="{{ __('admin.tenders.view') }}">
                                <i class="bi bi-eye me-1"></i><span class="d-none d-lg-inline">{{ __('admin.tenders.view') }}</span>
                            </a>
                            @can('tenders.edit')
                                <a href="{{ route('admin.tenders.edit',$t->id) }}" 
                                   class="btn btn-outline-warning btn-sm rounded-3 shadow-sm"
                                   title="{{ __('admin.tenders.edit') }}">
                                    <i class="bi bi-pencil me-1"></i><span class="d-none d-lg-inline">{{ __('admin.tenders.edit') }}</span>
                                </a>
                            @endcan
                            @can('tenders.delete')
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm rounded-3 shadow-sm"
                                        onclick="delTender({{ $t->id }})"
                                        title="{{ __('admin.tenders.delete') }}">
                                    <i class="bi bi-trash me-1"></i><span class="d-none d-lg-inline">{{ __('admin.tenders.delete') }}</span>
                                </button>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <div class="text-muted">
                            <i class="bi bi-file-earmark-text" style="font-size: 4rem; opacity: 0.5;"></i>
                            <p class="mb-2 mt-3">{{ __('admin.tenders.no_data') }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="d-md-none p-3">
        @forelse($tenders as $t)
            @php
                $oldShort = Str::limit(strip_tags((string)$t->old_value_1), 100);
                $newShort = Str::limit(strip_tags((string)$t->new_value_1), 100);
            @endphp
            <div class="card mb-3 shadow-sm border-0 rounded-3 tender-mobile-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary rounded-pill">#{{ $t->id }}</span>
                            @if($t->mnews_id)
                                <span class="badge bg-info rounded-pill small">MNEWS: {{ $t->mnews_id }}</span>
                            @endif
                        </div>
                    </div>
                    
                    @if($t->column_name_1)
                        <h6 class="mb-2 fw-semibold">{{ $t->column_name_1 }}</h6>
                    @endif
                    
                    @if($oldShort)
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">OLD_VALUE_1:</small>
                            <div class="bg-light rounded p-2 small">{{ $oldShort }}</div>
                        </div>
                    @endif
                    
                    @if($newShort)
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1">NEW_VALUE_1:</small>
                            <div class="bg-light rounded p-2 small">{{ $newShort }}</div>
                        </div>
                    @endif
                    
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @if($t->the_date_1)
                            <span class="badge bg-secondary small">
                                <i class="bi bi-calendar me-1"></i>{{ $t->the_date_1 }}
                            </span>
                        @endif
                        @if($t->the_user_1)
                            <span class="badge bg-secondary small">
                                <i class="bi bi-person me-1"></i>{{ $t->the_user_1 }}
                            </span>
                        @endif
                        @if($t->event_1)
                            <span class="badge bg-secondary small">
                                <i class="bi bi-info-circle me-1"></i>{{ Str::limit($t->event_1, 20) }}
                            </span>
                        @endif
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.tenders.show',$t->id) }}" 
                           class="btn btn-outline-primary btn-sm rounded-3 shadow-sm flex-fill">
                            <i class="bi bi-eye me-1"></i>{{ __('admin.tenders.view') }}
                        </a>
                        @can('tenders.edit')
                            <a href="{{ route('admin.tenders.edit',$t->id) }}" 
                               class="btn btn-outline-warning btn-sm rounded-3 shadow-sm flex-fill">
                                <i class="bi bi-pencil me-1"></i>{{ __('admin.tenders.edit') }}
                            </a>
                        @endcan
                        @can('tenders.delete')
                            <button type="button" 
                                    class="btn btn-outline-danger btn-sm rounded-3 shadow-sm flex-fill"
                                    onclick="delTender({{ $t->id }})">
                                <i class="bi bi-trash me-1"></i>{{ __('admin.tenders.delete') }}
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-file-earmark-text" style="font-size: 4rem; opacity: 0.5;"></i>
                    <p class="mb-2 mt-3">{{ __('admin.tenders.no_data') }}</p>
                </div>
            </div>
        @endforelse
    </div>
@else
    {{-- وضع العرض الكامل كما أعطيتُك سابقًا يعرض HTML داخل div مع scroll --}}
@endif
