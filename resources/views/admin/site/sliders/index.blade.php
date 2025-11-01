@extends('layouts.admin')
@section('title', 'سلايدر الموقع')

@section('content')
    @php
        $breadcrumbTitle     = 'السلايدر';
        $breadcrumbParent    = 'لوحةالتحكم';
        $breadcrumbParentUrl = route('admin.dashboard');

        use Illuminate\Support\Str;

        $getImageUrl = function ($path) {
            if (!$path) return asset('assets/admin/images/placeholder.png');
            if (Str::startsWith($path, ['http://', 'https://'])) return $path;
            if (Str::startsWith($path, ['assets/', 'public/', 'storage/'])) return asset($path);
            return asset('storage/' . $path);
        };
    @endphp

    <div class="py-4">

        <!-- Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 py-3">
                <h5 class="card-title mb-0 text-dark fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-images text-primary"></i>
                    شرائح السلايدر
                    <span class="badge bg-primary rounded-pill small">{{ $sliders->total() }}</span>
                </h5>
                <a href="{{ route('admin.sliders.create') }}"
                   class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    إضافة شريحة جديدة
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="text-muted small fw-semibold" width="6%">#</th>
                            <th class="text-muted small fw-semibold" width="30%">العنوان</th>
                            <th class="text-muted small fw-semibold" width="28%">صورة الخلفية</th>
                            <th class="text-muted small fw-semibold text-center" width="10%">ترتيب</th>
                            <th class="text-muted small fw-semibold text-center" width="10%">الحالة</th>
                            <th class="text-muted small fw-semibold text-end" width="16%">إجراءات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($sliders as $index => $s)
                            <tr class="{{ $s->is_active ? '' : 'opacity-75' }}">
                                <td class="small">
                                    {{ $loop->iteration + ($sliders->currentPage() - 1) * $sliders->perPage() }}
                                </td>

                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="status-dot {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}"></span>
                                        <div class="text-truncate" style="max-width: 220px;" title="{{ $s->title }}">
                                            <strong>{{ Str::limit($s->title, 45) }}</strong>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <img src="{{ $getImageUrl($s->bg_image) }}"
                                         alt="صورة الخلفية"
                                         width="100"
                                         height="56"
                                         class="rounded shadow-sm object-fit-cover"
                                         loading="lazy"
                                         onerror="this.src='{{ asset('assets/admin/images/placeholder.png') }}'"
                                         style="border: 1px solid #eee;">
                                </td>

                                <td class="text-center">
                                        <span class="badge bg-light text-dark small px-2 py-1">
                                            {{ $s->sort_order }}
                                        </span>
                                </td>

                                <td class="text-center">
                                        <span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }} small">
                                            {{ $s->is_active ? 'مفعل' : 'معطل' }}
                                        </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.sliders.edit', $s) }}"
                                           class="btn btn-outline-primary"
                                           title="تعديل">
                                            تعديل
                                        </a>
                                      {{--
                                       <button type="button"
                                                class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $s->id }}"
                                                title="حذف">
                                            حذف
                                        </button>
                                      --}}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-images display-5 d-block mb-3 opacity-50"></i>
                                        <p class="mb-1">لا توجد شرائح بعد</p>
                                        <a href="{{ route('admin.sliders.create') }}" class="small text-primary">+ أضف أول شريحة</a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                @if($sliders->hasPages())
                    <div class="card-footer bg-transparent border-top-0 py-3">
                        {{ $sliders->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- ====================== المودال خارج الجدول ====================== -->
        @foreach($sliders as $s)
            <div class="modal fade" id="deleteModal-{{ $s->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <div class="modal-content shadow-lg border-0">
                            <div class="modal-header border-0 pb-2">
                                <h5 class="modal-title text-danger fw-bold">
                                    حذف الشريحة
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                            </div>
                            <div class="modal-body pt-2 pb-3 text-center">
                                <i class="bi bi-exclamation-triangle-fill text-danger display-5 mb-3"></i>
                                <p class="mb-2 text-muted small">هل أنت متأكد من حذف هذه الشريحة؟</p>
                                <p class="fw-semibold text-dark mb-0">
                                    "{{ Str::limit($s->title, 40) }}"
                                </p>
                                <small class="text-danger d-block mt-2">هذا الإجراء لا يمكن التراجع عنه</small>
                            </div>
                            <div class="modal-footer border-0 pt-2 justify-content-center gap-2">
                                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">
                                    إلغاء
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm px-4">
                                    حذف نهائيًا
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        <!-- ====================== نهاية المودال ====================== -->

    </div>

    @push('styles')
        <style>
            .status-dot {
                width: 10px;
                height: 10px;
                border-radius: 50%;
                display: inline-block;
                flex-shrink: 0;
            }
            .table-hover tbody tr:hover {
                background-color: rgba(0, 0, 0, 0.025) !important;
            }
            .object-fit-cover { object-fit: cover; }
            .badge { font-weight: 500; }

            /* تحسين المودال */
            .modal-content {
                border-radius: 1rem;
            }
            .modal-dialog-centered {
                display: flex;
                align-items: center;
                min-height: calc(100% - 1rem);
            }
        </style>
    @endpush
@endsection
