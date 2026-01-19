@if($messages->count() > 0)
    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 60px">#</th>
                    <th>المرسل</th>
                    <th>الموضوع</th>
                    <th>البريد الإلكتروني</th>
                    <th>التاريخ</th>
                    <th class="text-center" style="width: 120px">الحالة</th>
                    <th class="text-center" style="width: 150px">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $index => $message)
                    <tr class="{{ !$message->is_read ? 'table-warning' : '' }}">
                        <td class="text-center text-muted small">
                            {{ $messages->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $message->name }}</div>
                            @if($message->phone)
                                <small class="text-muted">
                                    <i class="bi bi-telephone me-1"></i>{{ $message->phone }}
                                </small>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($message->subject, 40) }}</div>
                            <small class="text-muted">{{ \Illuminate\Support\Str::limit($message->message, 50) }}</small>
                        </td>
                        <td>
                            <a href="mailto:{{ $message->email }}" class="text-decoration-none">
                                <i class="bi bi-envelope me-1 text-muted"></i>{{ $message->email }}
                            </a>
                        </td>
                        <td>
                            <div class="small">
                                {{ $message->created_at->format('Y-m-d') }}
                            </div>
                            <div class="text-muted" style="font-size: 0.75rem;">
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </td>
                        <td class="text-center">
                            @if($message->is_read)
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>مقروءة
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-envelope-exclamation me-1"></i>غير مقروءة
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.contact-messages.show', $message) }}" 
                                   class="btn btn-primary" 
                                   title="عرض">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($message->is_read)
                                    <form action="{{ route('admin.contact-messages.mark-unread', $message) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning" title="تحديد كغير مقروءة">
                                            <i class="bi bi-envelope"></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.contact-messages.mark-read', $message) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-success" title="تحديد كمقروءة">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <button type="button" 
                                        class="btn btn-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal-{{ $message->id }}"
                                        title="حذف">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="d-md-none">
        @foreach($messages as $index => $message)
            <div class="card mb-3 {{ !$message->is_read ? 'border-warning' : '' }}">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $message->name }}</h6>
                            <small class="text-muted">
                                <i class="bi bi-envelope me-1"></i>{{ $message->email }}
                            </small>
                        </div>
                        @if($message->is_read)
                            <span class="badge bg-success">مقروءة</span>
                        @else
                            <span class="badge bg-warning text-dark">غير مقروءة</span>
                        @endif
                    </div>
                    <h6 class="mb-2">{{ $message->subject }}</h6>
                    <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($message->message, 100) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>{{ $message->created_at->format('Y-m-d H:i') }}
                        </small>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal-{{ $message->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
        <p class="text-muted mt-3">لا توجد رسائل</p>
    </div>
@endif

<!-- Delete Modals -->
@foreach($messages as $message)
    <div class="modal fade" id="deleteModal-{{ $message->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST">
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
                        <p class="fw-bold text-dark">{{ $message->subject }}</p>
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
@endforeach
