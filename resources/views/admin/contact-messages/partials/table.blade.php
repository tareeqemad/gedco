@if($messages->count() > 0)
    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px">#</th>
                    <th>المرسل</th>
                    <th>الموضوع</th>
                    <th>التاريخ</th>
                    <th class="text-center" style="width: 100px">الحالة</th>
                    <th class="text-center" style="width: 130px">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($messages as $index => $message)
                    <tr class="{{ !$message->is_read ? 'msg-unread' : '' }}">
                        <td class="text-center text-muted small">
                            {{ $messages->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-semibold" style="font-size: 0.85rem; color: #24364A;">{{ $message->name }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $message->email }}</small>
                        </td>
                        <td>
                            <div class="{{ !$message->is_read ? 'fw-bold' : 'fw-semibold' }}" style="font-size: 0.85rem; color: #24364A;">
                                {{ \Illuminate\Support\Str::limit($message->subject, 45) }}
                            </div>
                            <small class="text-muted d-block" style="font-size: 0.72rem; line-height: 1.3;">
                                {{ \Illuminate\Support\Str::limit($message->message, 60) }}
                            </small>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem; color: #24364A;">
                                {{ $message->created_at->translatedFormat('d M Y') }}
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                {{ $message->created_at->translatedFormat('h:i A') }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($message->is_read)
                                <span class="stat-chip" style="font-size: 0.65rem; color: #16A34A;">
                                    <i class="bi bi-check-circle"></i> مقروءة
                                </span>
                            @else
                                <span class="stat-chip" style="font-size: 0.65rem; color: #D97706; background: rgba(217,119,6,0.08); border-color: rgba(217,119,6,0.15);">
                                    <i class="bi bi-circle-fill" style="font-size: 0.4rem;"></i> جديدة
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.contact-messages.show', $message) }}"
                                   class="btn btn-sm btn-outline-primary rounded-3" title="عرض">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary rounded-3" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @if($message->is_read)
                                            <li>
                                                <form action="{{ route('admin.contact-messages.mark-unread', $message) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-envelope me-2 text-muted"></i>تحديد كغير مقروءة
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('admin.contact-messages.mark-read', $message) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check-circle me-2 text-muted"></i>تحديد كمقروءة
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <button type="button" class="dropdown-item text-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $message->id }}">
                                                <i class="bi bi-trash me-2"></i>حذف
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="d-md-none p-3">
        @foreach($messages as $index => $message)
            <div class="dash-list-item rounded-3 mb-2 {{ !$message->is_read ? 'msg-unread' : '' }}" style="border: 1px solid #E6ECF2;">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold" style="font-size: 0.85rem; color: #24364A;">{{ $message->name }}</span>
                        @if(!$message->is_read)
                            <span class="stat-chip" style="font-size: 0.55rem; color: #D97706; background: rgba(217,119,6,0.08);">جديدة</span>
                        @endif
                    </div>
                    <div class="{{ !$message->is_read ? 'fw-semibold' : '' }}" style="font-size: 0.82rem;">{{ $message->subject }}</div>
                    <small class="text-muted" style="font-size: 0.7rem;">{{ $message->created_at->diffForHumans() }}</small>
                </div>
                <a href="{{ route('admin.contact-messages.show', $message) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-envelope-open" style="font-size: 2.5rem; color: #CDD9E3;"></i>
        <p class="text-muted mt-3" style="font-size: 0.85rem;">لا توجد رسائل</p>
    </div>
@endif

<!-- Delete Modals -->
@foreach($messages as $message)
    <div class="modal fade" id="deleteModal-{{ $message->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form action="{{ route('admin.contact-messages.destroy', $message) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                        <p class="mt-3 mb-1 fw-semibold">حذف هذه الرسالة؟</p>
                        <small class="text-muted">{{ \Illuminate\Support\Str::limit($message->subject, 40) }}</small>
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
@endforeach
