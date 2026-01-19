@extends('layouts.admin')
@section('title', 'عرض الرسالة #' . $contactMessage->id)

@section('content')
    @php
        $breadcrumbTitle = 'عرض الرسالة #' . $contactMessage->id;
        $breadcrumbParent = 'رسائل الاتصال';
        $breadcrumbParentUrl = route('admin.contact-messages.index');
    @endphp

    <div class="container-fluid p-0">
        <!-- Header -->
        <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
            <div class="card-header bg-gradient-primary text-white border-0 py-2 px-3 d-flex align-items-center justify-content-between w-100" style="gap: 1rem;">
                <div class="d-flex align-items-center gap-2" style="flex: 0 0 auto;">
                    <div>
                        <h5 class="mb-0 fw-bold text-white" style="font-size: 1.25rem; line-height: 1.3;">
                            رسالة #{{ $contactMessage->id }}
                        </h5>
                        <small class="text-white opacity-75 d-none d-md-block" style="font-size: 0.8rem; line-height: 1.2;">
                            {{ \Illuminate\Support\Str::limit($contactMessage->subject, 50) }}
                        </small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-1 gap-md-2 flex-wrap" style="flex: 0 0 auto; margin-inline-start: auto;">
                    <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-light btn-sm shadow-sm">
                        <i class="bi bi-arrow-left me-1"></i> <span class="d-none d-sm-inline">رجوع</span>
                    </a>
                    @if($contactMessage->is_read)
                        <form action="{{ route('admin.contact-messages.mark-unread', $contactMessage) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-warning btn-sm shadow-sm">
                                <i class="bi bi-envelope me-1"></i> <span class="d-none d-md-inline">تحديد كغير مقروءة</span>
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.contact-messages.mark-read', $contactMessage) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm shadow-sm">
                                <i class="bi bi-check me-1"></i> <span class="d-none d-md-inline">تحديد كمقروءة</span>
                            </button>
                        </form>
                    @endif
                    <button type="button" id="btnDelete" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-trash me-1"></i> <span class="d-none d-sm-inline">حذف</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 g-md-4">
            <!-- Main content -->
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-body p-3 p-md-4">
                        <!-- Subject -->
                        <div class="mb-3 mb-md-4">
                            <h3 class="fw-bold mb-2 mb-md-3 text-primary fs-4 fs-md-3">{{ $contactMessage->subject }}</h3>
                            <div class="d-flex align-items-center gap-3 flex-wrap">
                                <span class="badge {{ $contactMessage->is_read ? 'bg-success' : 'bg-warning text-dark' }}">
                                    <i class="bi bi-{{ $contactMessage->is_read ? 'check-circle' : 'envelope-exclamation' }} me-1"></i>
                                    {{ $contactMessage->is_read ? 'مقروءة' : 'غير مقروءة' }}
                                </span>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $contactMessage->created_at->format('Y-m-d H:i') }}
                                </small>
                                @if($contactMessage->read_at)
                                    <small class="text-muted">
                                        <i class="bi bi-check-circle me-1"></i>
                                        قرئت في: {{ $contactMessage->read_at->format('Y-m-d H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="mb-3 mb-md-4">
                            <h6 class="fw-semibold mb-2 text-muted">الرسالة:</h6>
                            <div class="p-3 bg-light rounded border" style="min-height: 200px; white-space: pre-wrap; line-height: 1.8;">
                                {{ $contactMessage->message }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-12 col-lg-4">
                <!-- Sender Info -->
                <div class="card border-0 shadow-sm rounded-4 bg-white mb-3">
                    <div class="card-header bg-light border-0 py-2 px-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-person-fill me-2"></i>معلومات المرسل
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">الاسم:</label>
                            <div class="fw-semibold">{{ $contactMessage->name }}</div>
                        </div>
                        <div class="mb-3">
                            <label class="text-muted small d-block mb-1">البريد الإلكتروني:</label>
                            <a href="mailto:{{ $contactMessage->email }}" class="text-decoration-none">
                                <i class="bi bi-envelope me-1"></i>{{ $contactMessage->email }}
                            </a>
                        </div>
                        @if($contactMessage->phone)
                            <div class="mb-3">
                                <label class="text-muted small d-block mb-1">الهاتف:</label>
                                <a href="tel:{{ $contactMessage->phone }}" class="text-decoration-none">
                                    <i class="bi bi-telephone me-1"></i>{{ $contactMessage->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="card border-0 shadow-sm rounded-4 bg-white">
                    <div class="card-header bg-light border-0 py-2 px-3">
                        <h6 class="mb-0 fw-bold">
                            <i class="bi bi-lightning-fill me-2"></i>إجراءات سريعة
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-reply me-1"></i>الرد على الرسالة
                            </a>
                            @if($contactMessage->phone)
                                <a href="tel:{{ $contactMessage->phone }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-telephone me-1"></i>اتصال
                                </a>
                            @endif
                            @if($contactMessage->is_read)
                                <form action="{{ route('admin.contact-messages.mark-unread', $contactMessage) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm w-100">
                                        <i class="bi bi-envelope me-1"></i>تحديد كغير مقروءة
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.contact-messages.mark-read', $contactMessage) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm w-100">
                                        <i class="bi bi-check me-1"></i>تحديد كمقروءة
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title text-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>تأكيد الحذف
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 mb-2">هل أنت متأكد من حذف هذه الرسالة؟</p>
                        <p class="fw-bold text-dark">{{ $contactMessage->subject }}</p>
                        <small class="text-danger">هذا الإجراء لا يمكن التراجع عنه</small>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i>حذف
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('btnDelete')?.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    });
</script>
@endpush
