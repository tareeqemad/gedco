@php
    $sourceMeta = [
        'friend_employee' => ['icon' => 'bi-people-fill',     'color' => '#0EA5E9'],
        'social_media'    => ['icon' => 'bi-hash',            'color' => '#8B5CF6'],
        'website'         => ['icon' => 'bi-globe',           'color' => '#10B981'],
        'advertisement'   => ['icon' => 'bi-megaphone-fill',  'color' => '#F59E0B'],
        'other'           => ['icon' => 'bi-three-dots',      'color' => '#64748B'],
    ];
@endphp

@once
@push('styles')
<style>
    .jr-contact-row {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.55rem;
        border-radius: 0.5rem;
        background: #F8FAFC;
        border: 1px solid #EEF2F7;
        transition: background 0.15s ease, border-color 0.15s ease, transform 0.15s ease;
        max-width: 100%;
        font-size: 0.78rem;
        color: #1F2937 !important;
        line-height: 1.2;
    }
    .jr-contact-row:hover {
        background: #FFF7ED;
        border-color: #FED7AA;
        transform: translateY(-1px);
    }
    .jr-contact-ico {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 0.7rem;
    }
    .jr-contact-ico-phone {
        background: rgba(16, 185, 129, 0.12);
        color: #059669;
    }
    .jr-contact-ico-mail {
        background: rgba(59, 130, 246, 0.12);
        color: #2563EB;
    }
    .jr-contact-val {
        font-weight: 600;
        color: #24364A;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        flex: 1 1 auto;
        min-width: 0;
    }
    .jr-contact-row:hover .jr-contact-val { color: #9A3412; }
</style>
@endpush
@endonce

@if($requests->count() > 0)
    <div class="table-responsive d-none d-md-block">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 50px">#</th>
                    <th>{{ __('admin.join_requests.applicant') }}</th>
                    <th>{{ __('admin.join_requests.contact') }}</th>
                    <th>{{ __('admin.join_requests.source') }}</th>
                    <th>{{ __('admin.join_requests.date') }}</th>
                    <th class="text-center" style="width: 100px">{{ __('admin.labels.status') }}</th>
                    <th class="text-center" style="width: 130px">{{ __('admin.contact_messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $index => $jr)
                    @php $meta = $sourceMeta[$jr->source] ?? $sourceMeta['other']; @endphp
                    <tr class="{{ !$jr->is_read ? 'msg-unread' : '' }}">
                        <td class="text-center text-muted small">
                            {{ $requests->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="fw-semibold" style="font-size: 0.85rem; color: #24364A;">
                                {{ $jr->applicant_name ?? '—' }}
                            </div>
                            @if($jr->company_name)
                                <small class="text-muted d-block" style="font-size: 0.7rem;">
                                    <i class="bi bi-building me-1"></i>{{ $jr->company_name }}
                                </small>
                            @endif
                            @if($jr->referrer_name)
                                <small class="text-muted d-block" style="font-size: 0.68rem;">
                                    <i class="bi bi-people me-1"></i>{{ $jr->referrer_name }}
                                </small>
                            @endif
                        </td>
                        <td style="min-width: 220px;" class="text-center">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                @if($jr->applicant_phone)
                                    <a href="tel:{{ e($jr->applicant_phone) }}"
                                       class="jr-contact-row text-decoration-none"
                                       title="{{ __('admin.contact_messages.call') }}">
                                        <span class="jr-contact-ico jr-contact-ico-phone">
                                            <i class="bi bi-telephone-fill"></i>
                                        </span>
                                        <span class="jr-contact-val" dir="ltr">{{ $jr->applicant_phone }}</span>
                                    </a>
                                @endif
                                @if($jr->applicant_email)
                                    <a href="mailto:{{ e($jr->applicant_email) }}"
                                       class="jr-contact-row text-decoration-none"
                                       title="{{ __('admin.contact_messages.reply') }}">
                                        <span class="jr-contact-ico jr-contact-ico-mail">
                                            <i class="bi bi-envelope-fill"></i>
                                        </span>
                                        <span class="jr-contact-val text-truncate" dir="ltr"
                                              style="max-width: 170px;">{{ $jr->applicant_email }}</span>
                                    </a>
                                @endif
                                @if(!$jr->applicant_phone && !$jr->applicant_email)
                                    <span class="text-muted small">—</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span style="width:30px;height:30px;border-radius:50%;
                                             background: {{ $meta['color'] }}15; color: {{ $meta['color'] }};
                                             display:inline-flex;align-items:center;justify-content:center;">
                                    <i class="bi {{ $meta['icon'] }}"></i>
                                </span>
                                <span class="fw-semibold" style="font-size: 0.78rem; color: #24364A;">
                                    {{ __('admin.join_requests.sources.' . $jr->source) }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.8rem; color: #24364A;">
                                {{ $jr->created_at->translatedFormat('d M Y') }}
                            </div>
                            <small class="text-muted" style="font-size: 0.7rem;">
                                {{ $jr->created_at->translatedFormat('h:i A') }}
                            </small>
                        </td>
                        <td class="text-center">
                            @if($jr->is_read)
                                <span class="stat-chip" style="font-size: 0.65rem; color: #16A34A;">
                                    <i class="bi bi-check-circle"></i> {{ __('admin.ui.read') }}
                                </span>
                            @else
                                <span class="stat-chip" style="font-size: 0.65rem; color: #D97706; background: rgba(217,119,6,0.08); border-color: rgba(217,119,6,0.15);">
                                    <i class="bi bi-circle-fill" style="font-size: 0.4rem;"></i> {{ __('admin.ui.new') }}
                                </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="{{ route('admin.join-requests.show', $jr) }}"
                                   class="btn btn-sm btn-outline-primary rounded-3" title="{{ __('admin.actions.view') }}">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('join-requests.edit')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary rounded-3" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @if($jr->is_read)
                                            <li>
                                                <form action="{{ route('admin.join-requests.mark-unread', $jr) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-envelope me-2 text-muted"></i>{{ __('admin.contact_messages.mark_unread') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @else
                                            <li>
                                                <form action="{{ route('admin.join-requests.mark-read', $jr) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check-circle me-2 text-muted"></i>{{ __('admin.contact_messages.mark_read') }}
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        @can('join-requests.delete')
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#jrDeleteModal-{{ $jr->id }}">
                                                    <i class="bi bi-trash me-2"></i>{{ __('admin.actions.delete') }}
                                                </button>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Mobile cards --}}
    <div class="d-md-none p-3">
        @foreach($requests as $jr)
            @php $meta = $sourceMeta[$jr->source] ?? $sourceMeta['other']; @endphp
            <div class="dash-list-item rounded-3 mb-2 {{ !$jr->is_read ? 'msg-unread' : '' }}"
                 style="border:1px solid #E6ECF2;">
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <span class="fw-bold" style="font-size:0.85rem;color:#24364A;">
                            {{ $jr->applicant_name ?? '—' }}
                        </span>
                        @if(!$jr->is_read)
                            <span class="stat-chip" style="font-size: 0.55rem; color: #D97706; background: rgba(217,119,6,0.08);">
                                {{ __('admin.ui.new') }}
                            </span>
                        @endif
                    </div>
                    @if($jr->applicant_phone)
                        <div style="font-size:0.78rem;color:#475569;" dir="ltr">
                            <i class="bi bi-telephone me-1"></i>{{ $jr->applicant_phone }}
                        </div>
                    @endif
                    <div style="font-size:0.78rem;color:{{ $meta['color'] }};">
                        <i class="bi {{ $meta['icon'] }} me-1"></i>{{ __('admin.join_requests.sources.' . $jr->source) }}
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">
                        {{ $jr->created_at->diffForHumans() }}
                    </small>
                </div>
                <a href="{{ route('admin.join-requests.show', $jr) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i>
                </a>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <i class="bi bi-person-plus" style="font-size: 2.5rem; color: #CDD9E3;"></i>
        <p class="text-muted mt-3" style="font-size: 0.85rem;">{{ __('admin.join_requests.no_entries') }}</p>
    </div>
@endif

{{-- Delete modals --}}
@can('join-requests.delete')
@foreach($requests as $jr)
    <div class="modal fade" id="jrDeleteModal-{{ $jr->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <form action="{{ route('admin.join-requests.destroy', $jr) }}" method="POST">
                @csrf @method('DELETE')
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-body text-center py-4">
                        <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                        <p class="mt-3 mb-1 fw-semibold">{{ __('admin.join_requests.delete_confirm') }}</p>
                        <small class="text-muted">#{{ $jr->id }} — {{ $jr->applicant_name ?? __('admin.join_requests.sources.' . $jr->source) }}</small>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-2 pt-0 pb-3">
                        <button type="button" class="btn btn-cancel btn-sm" data-bs-dismiss="modal">{{ __('admin.actions.cancel') }}</button>
                        <button type="submit" class="btn btn-delete-confirm btn-sm">
                            <i class="bi bi-trash me-1"></i>{{ __('admin.actions.delete') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach
@endcan
