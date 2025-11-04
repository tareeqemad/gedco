@extends('layouts.admin')

@section('title', 'تفاصيل العطاء #' . $tender->id)

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">تفاصيل العطاء <span class="text-muted">#{{ $tender->id }}</span></h4>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.tenders.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-right-short"></i> رجوع
                </a>
                @can('tenders.edit')
                    <a href="{{ route('admin.tenders.edit', $tender->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> تعديل
                    </a>
                @endcan
            </div>
        </div>

        {{-- بطاقة بيانات عامة --}}
        <div class="card mb-4">
            <div class="card-header fw-semibold">البيانات العامة</div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">ID</div>
                        <div class="text-break">{{ $tender->id }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">MNEWS_ID</div>
                        <div class="text-break">{{ $tender->mnews_id ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">COLUMN_NAME_1</div>
                        <div class="text-break">{{ $tender->column_name_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">THE_DATE_1</div>
                        <div class="text-break">{{ $tender->the_date_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">EVENT_1</div>
                        <div class="text-break">{{ $tender->event_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">THE_USER_1</div>
                        <div class="text-break">{{ $tender->the_user_1 ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">COULM_SERIAL</div>
                        <div class="text-break">{{ $tender->coulm_serial ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">Created At</div>
                        <div class="text-break">{{ optional($tender->created_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>

                    <div class="list-group-item d-flex justify-content-between flex-wrap">
                        <div class="fw-semibold text-muted">Updated At</div>
                        <div class="text-break">{{ optional($tender->updated_at)->format('Y-m-d H:i:s') ?? '—' }}</div>
                    </div>

                </div>
            </div>
        </div>

        {{-- بطاقة المحتوى OLD/NEW مع فواصل واضحة --}}
        <div class="card">
            <div class="card-header fw-semibold d-flex align-items-center gap-2">
                المحتوى التفصيلي
                <span class="badge bg-secondary">OLD_VALUE_1 & NEW_VALUE_1</span>
            </div>
            <div class="card-body">

                <ul class="nav nav-tabs" id="valueTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="old-html-tab" data-bs-toggle="tab" data-bs-target="#old-html" type="button" role="tab">
                            OLD (HTML)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="new-html-tab" data-bs-toggle="tab" data-bs-target="#new-html" type="button" role="tab">
                            NEW (HTML)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="old-text-tab" data-bs-toggle="tab" data-bs-target="#old-text" type="button" role="tab">
                            OLD (Text)
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="new-text-tab" data-bs-toggle="tab" data-bs-target="#new-text" type="button" role="tab">
                            NEW (Text)
                        </button>
                    </li>
                </ul>

                <div class="tab-content border-start border-end border-bottom p-3" id="valueTabsContent" style="min-height: 260px">
                    {{-- OLD HTML --}}
                    <div class="tab-pane fade show active" id="old-html" role="tabpanel" aria-labelledby="old-html-tab">
                        @if($tender->old_value_1)
                            <div class="border rounded p-3" style="max-height: 480px; overflow:auto; direction: rtl;">
                                {!! $tender->old_value_1 !!}
                            </div>
                        @else
                            <div class="text-muted">لا يوجد محتوى.</div>
                        @endif
                    </div>

                    {{-- NEW HTML --}}
                    <div class="tab-pane fade" id="new-html" role="tabpanel" aria-labelledby="new-html-tab">
                        @if($tender->new_value_1)
                            <div class="border rounded p-3" style="max-height: 480px; overflow:auto; direction: rtl;">
                                {!! $tender->new_value_1 !!}
                            </div>
                        @else
                            <div class="text-muted">لا يوجد محتوى.</div>
                        @endif
                    </div>

                    {{-- OLD Text --}}
                    <div class="tab-pane fade" id="old-text" role="tabpanel" aria-labelledby="old-text-tab">
                        <pre class="border rounded p-3" style="white-space: pre-wrap; word-break: break-word; max-height: 480px; overflow:auto;">{{ strip_tags((string)$tender->old_value_1) ?: '—' }}</pre>
                    </div>

                    {{-- NEW Text --}}
                    <div class="tab-pane fade" id="new-text" role="tabpanel" aria-labelledby="new-text-tab">
                        <pre class="border rounded p-3" style="white-space: pre-wrap; word-break: break-word; max-height: 480px; overflow:auto;">{{ strip_tags((string)$tender->new_value_1) ?: '—' }}</pre>
                    </div>
                </div>

                {{-- فاصل بصري بين الكلوّمات --}}
                <hr class="my-4">

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">OLD_VALUE_1 (ملخّص)</div>
                            <div class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags((string)$tender->old_value_1), 300) ?: '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded p-3 h-100">
                            <div class="fw-semibold mb-2">NEW_VALUE_1 (ملخّص)</div>
                            <div class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags((string)$tender->new_value_1), 300) ?: '—' }}</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
