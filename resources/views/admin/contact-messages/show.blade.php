@php
    $breadcrumbTitle = 'عرض الرسالة';
    $breadcrumbParent = 'رسائل الاتصال';
    $breadcrumbParentUrl = route('admin.contact-messages.index');
@endphp
@extends('layouts.admin')
@section('title', 'عرض الرسالة #' . $contactMessage->id)

@section('content')
    <div class="container-fluid p-0">
        <div class="row g-4">
            {{-- المحتوى الرئيسي --}}
            <div class="col-12 col-lg-8">
                <x-admin.card>
                    <x-admin.card-header-form
                        icon="bi-envelope-open"
                        title="تفاصيل الرسالة"
                        :back-route="route('admin.contact-messages.index')"
                        back-label="رسائل الاتصال" />

                    <div class="card-body p-4">
                        {{-- الموضوع والحالة --}}
                        <div class="mb-4 pb-3 border-bottom">
                            <h5 class="fw-bold mb-2" style="color: #24364A;">{{ $contactMessage->subject }}</h5>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                @if($contactMessage->is_read)
                                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                        <i class="bi bi-check-circle me-1"></i>مقروءة
                                    </span>
                                @else
                                    <span class="badge rounded-pill px-3 py-1" style="background: rgba(217,119,6,0.1); color: #D97706;">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem;"></i>غير مقروءة
                                    </span>
                                @endif
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>{{ $contactMessage->created_at->translatedFormat('d M Y - h:i A') }}
                                </small>
                                @if($contactMessage->read_at)
                                    <small class="text-muted">
                                        <i class="bi bi-check-circle me-1"></i>قرئت: {{ $contactMessage->read_at->translatedFormat('d M Y - h:i A') }}
                                    </small>
                                @endif
                            </div>
                        </div>

                        {{-- نص الرسالة --}}
                        <div class="p-3 rounded-3 border" style="min-height: 180px; white-space: pre-wrap; line-height: 1.8; background: #FAFBFC;">
                            {{ $contactMessage->message }}
                        </div>
                    </div>
                </x-admin.card>
            </div>

            {{-- الشريط الجانبي --}}
            <div class="col-12 col-lg-4">
                {{-- معلومات المرسل --}}
                <x-admin.card class="mb-3">
                    <div class="card-body p-4">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-person"></i>
                            معلومات المرسل
                        </h6>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">الاسم</small>
                            <div class="fw-semibold" style="color: #24364A;">{{ $contactMessage->name }}</div>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">البريد الإلكتروني</small>
                            <a href="mailto:{{ $contactMessage->email }}" class="text-decoration-none" style="color: #1ABC9C;">
                                {{ $contactMessage->email }}
                            </a>
                        </div>
                        @if($contactMessage->phone)
                            <div>
                                <small class="text-muted d-block mb-1">الهاتف</small>
                                <a href="tel:{{ $contactMessage->phone }}" class="text-decoration-none" style="color: #1ABC9C;">
                                    {{ $contactMessage->phone }}
                                </a>
                            </div>
                        @endif
                    </div>
                </x-admin.card>

                {{-- إجراءات سريعة --}}
                <x-admin.card>
                    <div class="card-body p-4">
                        <h6 class="section-title mb-3">
                            <i class="bi bi-lightning"></i>
                            إجراءات سريعة
                        </h6>
                        <div class="d-grid gap-2">
                            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ $contactMessage->subject }}"
                               class="btn btn-save btn-sm">
                                <i class="bi bi-reply me-1"></i>الرد على الرسالة
                            </a>
                            @if($contactMessage->phone)
                                <a href="tel:{{ $contactMessage->phone }}" class="btn btn-cancel btn-sm">
                                    <i class="bi bi-telephone me-1"></i>اتصال
                                </a>
                            @endif
                            @if($contactMessage->is_read)
                                <form action="{{ route('admin.contact-messages.mark-unread', $contactMessage) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-cancel btn-sm w-100">
                                        <i class="bi bi-envelope me-1"></i>تحديد كغير مقروءة
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.contact-messages.mark-read', $contactMessage) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-cancel btn-sm w-100">
                                        <i class="bi bi-check-circle me-1"></i>تحديد كمقروءة
                                    </button>
                                </form>
                            @endif
                            <button type="button" id="btnDelete" class="btn btn-delete-confirm btn-sm">
                                <i class="bi bi-trash me-1"></i>حذف الرسالة
                            </button>
                        </div>
                    </div>
                </x-admin.card>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form action="{{ route('admin.contact-messages.destroy', $contactMessage) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                        <p class="mt-3 mb-1 fw-semibold">حذف هذه الرسالة؟</p>
                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($contactMessage->subject, 40) }}</small>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-3">
                        <button type="button" class="btn btn-cancel btn-sm" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-delete-confirm btn-sm">
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
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
</script>
@endpush
