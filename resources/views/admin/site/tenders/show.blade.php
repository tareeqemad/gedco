@php
    $breadcrumbTitle     = __('admin.tenders.show_title') . ' #' . $tender->id;
    $breadcrumbParent    = __('admin.tenders.title');
    $breadcrumbParentUrl = route('admin.tenders.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.tenders.show_title') . ' #' . $tender->id)

@section('content')
    <div class="container-fluid p-0">
        <!-- Header Section -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap w-100" style="gap: 1rem;">
                    <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                        <div>
                            <h5 class="mb-0 fw-bold text-white d-flex align-items-center gap-2 flex-wrap" style="font-size: 1.25rem; line-height: 1.3;">
                                <i class="bi bi-eye"></i>
                                <span>{{ __('admin.tenders.show_title') }} <span class="text-white-50">#{{ $tender->id }}</span></span>
                            </h5>
                        </div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.tenders.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i class="bi bi-arrow-left me-1"></i>{{ __('admin.tenders.back') }}
                        </a>
                        @can('tenders.edit')
                            <a href="{{ route('admin.tenders.edit', $tender->id) }}" class="btn btn-light btn-sm shadow-sm">
                                <i class="bi bi-pencil me-1"></i>{{ __('admin.tenders.edit') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- General Data Card --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4">
                <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle"></i>{{ __('admin.tenders.show_general_data') ?? 'البيانات العامة' }}
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-hash text-primary"></i>ID
                        </div>
                        <div class="text-break fw-semibold">{{ $tender->id }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-123 text-primary"></i>MNEWS_ID
                        </div>
                        <div class="text-break">{{ $tender->mnews_id ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-file-text text-primary"></i>COLUMN_NAME_1
                        </div>
                        <div class="text-break">{{ $tender->column_name_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-calendar text-primary"></i>THE_DATE_1
                        </div>
                        <div class="text-break">{{ $tender->the_date_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-primary"></i>EVENT_1
                        </div>
                        <div class="text-break">{{ $tender->event_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-person text-primary"></i>THE_USER_1
                        </div>
                        <div class="text-break">{{ $tender->the_user_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-list-ol text-primary"></i>COULM_SERIAL
                        </div>
                        <div class="text-break">{{ $tender->coulm_serial ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-primary"></i>Created At
                        </div>
                        <div class="text-break">{{ optional($tender->created_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap align-items-center py-3 px-4">
                        <div class="fw-semibold text-muted d-flex align-items-center gap-2">
                            <i class="bi bi-clock-history text-primary"></i>Updated At
                        </div>
                        <div class="text-break">{{ optional($tender->updated_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Detailed Content Card OLD/NEW --}}
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-header bg-gradient-primary text-white border-0 py-3 px-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 fw-bold text-white d-flex align-items-center gap-2">
                        <i class="bi bi-file-text"></i>{{ __('admin.tenders.show_detailed_content') ?? 'المحتوى التفصيلي' }}
                    </h6>
                    <span class="badge bg-white text-primary rounded-pill">OLD_VALUE_1 & NEW_VALUE_1</span>
                </div>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-tabs border-0 mb-3" id="valueTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-3 me-2" id="old-html-tab" data-bs-toggle="tab" data-bs-target="#old-html" type="button" role="tab">
                            <i class="bi bi-file-text me-1"></i>{{ __('admin.tenders.show_old_value_html') ?? 'OLD_VALUE_1 (HTML)' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 me-2" id="new-html-tab" data-bs-toggle="tab" data-bs-target="#new-html" type="button" role="tab">
                            <i class="bi bi-file-text me-1"></i>{{ __('admin.tenders.show_new_value_html') ?? 'NEW_VALUE_1 (HTML)' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3 me-2" id="old-text-tab" data-bs-toggle="tab" data-bs-target="#old-text" type="button" role="tab">
                            <i class="bi bi-file-text me-1"></i>{{ __('admin.tenders.show_old_value_text') ?? 'OLD_VALUE_1 (Text)' }}
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-3" id="new-text-tab" data-bs-toggle="tab" data-bs-target="#new-text" type="button" role="tab">
                            <i class="bi bi-file-text me-1"></i>{{ __('admin.tenders.show_new_value_text') ?? 'NEW_VALUE_1 (Text)' }}
                        </button>
                    </li>
                </ul>

                <div class="tab-content border rounded-3 p-3" id="valueTabsContent" style="min-height: 260px">
                    {{-- OLD HTML --}}
                    <div class="tab-pane fade show active" id="old-html" role="tabpanel" aria-labelledby="old-html-tab">
                        @if($tender->old_value_1)
                            <div class="border rounded-3 p-3 bg-light" style="max-height: 480px; overflow:auto; direction: rtl;">
                                {!! $tender->old_value_1 !!}
                            </div>
                        @else
                            <div class="text-muted text-center py-5">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2 opacity-50"></i>
                                {{ __('admin.tenders.show_no_content') ?? 'لا يوجد محتوى' }}
                            </div>
                        @endif
                    </div>

                    {{-- NEW HTML --}}
                    <div class="tab-pane fade" id="new-html" role="tabpanel" aria-labelledby="new-html-tab">
                        @if($tender->new_value_1)
                            <div class="border rounded-3 p-3 bg-light" style="max-height: 480px; overflow:auto; direction: rtl;">
                                {!! $tender->new_value_1 !!}
                            </div>
                        @else
                            <div class="text-muted text-center py-5">
                                <i class="bi bi-file-earmark-text fs-1 d-block mb-2 opacity-50"></i>
                                {{ __('admin.tenders.show_no_content') ?? 'لا يوجد محتوى' }}
                            </div>
                        @endif
                    </div>

                    {{-- OLD Text --}}
                    <div class="tab-pane fade" id="old-text" role="tabpanel" aria-labelledby="old-text-tab">
                        <pre class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap; word-break: break-word; max-height: 480px; overflow:auto;">{{ strip_tags((string)$tender->old_value_1) ?: '—' }}</pre>
                    </div>

                    {{-- NEW Text --}}
                    <div class="tab-pane fade" id="new-text" role="tabpanel" aria-labelledby="new-text-tab">
                        <pre class="border rounded-3 p-3 bg-light" style="white-space: pre-wrap; word-break: break-word; max-height: 480px; overflow:auto;">{{ strip_tags((string)$tender->new_value_1) ?: '—' }}</pre>
                    </div>
                </div>

                {{-- فاصل بصري بين الكلوّمات --}}
                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="fw-semibold mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-file-text text-secondary"></i>{{ __('admin.tenders.show_old_summary') ?? 'ملخص OLD_VALUE_1' }}
                            </div>
                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags((string)$tender->old_value_1), 300) ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100 bg-light">
                            <div class="fw-semibold mb-2 d-flex align-items-center gap-2">
                                <i class="bi bi-file-text text-success"></i>{{ __('admin.tenders.show_new_summary') ?? 'ملخص NEW_VALUE_1' }}
                            </div>
                            <div class="text-muted small">{{ \Illuminate\Support\Str::limit(strip_tags((string)$tender->new_value_1), 300) ?: '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
