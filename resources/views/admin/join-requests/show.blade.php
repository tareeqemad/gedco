@php
    $breadcrumbTitle     = __('admin.join_requests.view_entry');
    $breadcrumbParent    = __('admin.join_requests.title');
    $breadcrumbParentUrl = route('admin.join-requests.index');

    $sourceMeta = [
        'friend_employee' => ['icon' => 'bi-people-fill',     'color' => '#0EA5E9'],
        'social_media'    => ['icon' => 'bi-hash',            'color' => '#8B5CF6'],
        'website'         => ['icon' => 'bi-globe',           'color' => '#10B981'],
        'advertisement'   => ['icon' => 'bi-megaphone-fill',  'color' => '#F59E0B'],
        'other'           => ['icon' => 'bi-three-dots',      'color' => '#64748B'],
    ];
    $meta = $sourceMeta[$joinRequest->source] ?? $sourceMeta['other'];
@endphp
@extends('layouts.admin')
@section('title', __('admin.join_requests.view_entry') . ' #' . $joinRequest->id)

@section('content')
<div class="container-fluid p-0">
    <div class="row g-4">
        {{-- Main --}}
        <div class="col-12 col-lg-8">
            <x-admin.card>
                <x-admin.card-header-form
                    icon="bi-person-plus-fill"
                    :title="__('admin.join_requests.entry_details')"
                    :back-route="route('admin.join-requests.index')"
                    :back-label="__('admin.join_requests.title')" />

                <div class="card-body p-4">
                    <div class="mb-4 pb-3 border-bottom">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <span style="width:54px;height:54px;border-radius:14px;
                                         background: {{ $meta['color'] }}15; color: {{ $meta['color'] }};
                                         display:inline-flex;align-items:center;justify-content:center;font-size:1.4rem;">
                                <i class="bi {{ $meta['icon'] }}"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-1" style="color:#24364A;">
                                    {{ __('admin.join_requests.sources.' . $joinRequest->source) }}
                                </h5>
                                <small class="text-muted">#{{ $joinRequest->id }}</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            @if($joinRequest->is_read)
                                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                    <i class="bi bi-check-circle me-1"></i>{{ __('admin.ui.read') }}
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-1" style="background: rgba(217,119,6,0.1); color: #D97706;">
                                    <i class="bi bi-circle-fill me-1" style="font-size: 0.4rem;"></i>{{ __('admin.ui.unread') }}
                                </span>
                            @endif
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>
                                {{ $joinRequest->created_at->translatedFormat('d M Y - h:i A') }}
                            </small>
                        </div>
                    </div>

                    <h6 class="section-title mb-3">
                        <i class="bi bi-person-vcard"></i>
                        {{ __('admin.join_requests.applicant_info') }}
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.applicant_name') }}</small>
                            <div class="fw-semibold" style="color:#24364A;">
                                <i class="bi bi-person me-1 text-muted"></i>{{ $joinRequest->applicant_name ?? '—' }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.applicant_phone') }}</small>
                            @if($joinRequest->applicant_phone)
                                <a href="tel:{{ e($joinRequest->applicant_phone) }}" class="fw-semibold text-decoration-none" dir="ltr" style="color:#1ABC9C;">
                                    <i class="bi bi-telephone me-1"></i>{{ $joinRequest->applicant_phone }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.applicant_email') }}</small>
                            @if($joinRequest->applicant_email)
                                <a href="mailto:{{ e($joinRequest->applicant_email) }}" class="fw-semibold text-decoration-none" dir="ltr" style="color:#1ABC9C;">
                                    <i class="bi bi-envelope me-1"></i>{{ $joinRequest->applicant_email }}
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.company_name') }}</small>
                            <div class="fw-semibold" style="color:#24364A;">
                                <i class="bi bi-building me-1 text-muted"></i>{{ $joinRequest->company_name ?? '—' }}
                            </div>
                        </div>
                    </div>

                    @if($joinRequest->referrer_name)
                        <div class="p-3 rounded-3 border mb-4" style="background:#FFF7ED;border-color:#FED7AA !important;">
                            <small class="d-block mb-1" style="color:#9A3412;font-weight:700;">
                                <i class="bi bi-people-fill me-1"></i>{{ __('admin.join_requests.referrer_name') }}
                            </small>
                            <div class="fw-semibold" style="color:#7C2D12;">
                                {{ $joinRequest->referrer_name }}
                            </div>
                        </div>
                    @endif

                    <h6 class="section-title mb-3">
                        <i class="bi bi-info-square"></i>
                        {{ __('admin.join_requests.summary') }}
                    </h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.locale') }}</small>
                            <span class="badge rounded-pill px-3 py-1"
                                  style="background:#F1F5F9;color:#475569;">
                                {{ $joinRequest->locale === 'ar' ? __('admin.join_requests.arabic') : __('admin.join_requests.english') }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">{{ __('admin.join_requests.ip_address') }}</small>
                            <code style="color:#0f172a;">{{ $joinRequest->ip_address ?? '—' }}</code>
                        </div>
                    </div>
                </div>
            </x-admin.card>
        </div>

        {{-- Sidebar --}}
        <div class="col-12 col-lg-4">
            <x-admin.card class="mb-3">
                <div class="card-body p-4">
                    <h6 class="section-title mb-3">
                        <i class="bi bi-info-circle"></i>
                        {{ __('admin.join_requests.summary') }}
                    </h6>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">{{ __('admin.join_requests.source') }}</small>
                        <div class="fw-semibold" style="color:#24364A;">
                            {{ __('admin.join_requests.sources.' . $joinRequest->source) }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">{{ __('admin.join_requests.submitted_at') }}</small>
                        <div class="fw-semibold" style="color:#24364A;">
                            {{ $joinRequest->created_at->translatedFormat('d M Y - h:i A') }}
                        </div>
                    </div>
                    @if($joinRequest->read_at)
                        <div>
                            <small class="text-muted d-block mb-1">{{ __('admin.contact_messages.read_at') }}</small>
                            <div class="fw-semibold" style="color:#24364A;">
                                {{ $joinRequest->read_at->translatedFormat('d M Y - h:i A') }}
                            </div>
                        </div>
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card>
                <div class="card-body p-4">
                    <h6 class="section-title mb-3">
                        <i class="bi bi-lightning"></i>
                        {{ __('admin.contact_messages.quick_actions') }}
                    </h6>
                    <div class="d-grid gap-2">
                        @if($joinRequest->applicant_phone)
                            <a href="tel:{{ e($joinRequest->applicant_phone) }}" class="btn btn-save btn-sm">
                                <i class="bi bi-telephone me-1"></i>{{ __('admin.contact_messages.call') }}
                            </a>
                        @endif
                        @if($joinRequest->applicant_email)
                            <a href="mailto:{{ e($joinRequest->applicant_email) }}" class="btn btn-cancel btn-sm">
                                <i class="bi bi-envelope me-1"></i>{{ __('admin.contact_messages.reply') }}
                            </a>
                        @endif
                        @can('join-requests.edit')
                            @if($joinRequest->is_read)
                                <form action="{{ route('admin.join-requests.mark-unread', $joinRequest) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-cancel btn-sm w-100">
                                        <i class="bi bi-envelope me-1"></i>{{ __('admin.contact_messages.mark_unread') }}
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.join-requests.mark-read', $joinRequest) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-cancel btn-sm w-100">
                                        <i class="bi bi-check-circle me-1"></i>{{ __('admin.contact_messages.mark_read') }}
                                    </button>
                                </form>
                            @endif
                        @endcan
                        @can('join-requests.delete')
                            <button type="button" id="btnDelete" class="btn btn-delete-confirm btn-sm">
                                <i class="bi bi-trash me-1"></i>{{ __('admin.actions.delete') }}
                            </button>
                        @endcan
                    </div>
                </div>
            </x-admin.card>
        </div>
    </div>
</div>

@can('join-requests.delete')
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <form action="{{ route('admin.join-requests.destroy', $joinRequest) }}" method="POST">
            @csrf @method('DELETE')
            <div class="modal-content border-0 rounded-4">
                <div class="modal-body text-center py-4">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 2.5rem;"></i>
                    <p class="mt-3 mb-1 fw-semibold">{{ __('admin.join_requests.delete_confirm') }}</p>
                    <small class="text-muted">#{{ $joinRequest->id }}</small>
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
@endcan
@endsection

@push('scripts')
<script>
    document.getElementById('btnDelete')?.addEventListener('click', function () {
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    });
</script>
@endpush
