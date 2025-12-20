@extends('layouts.admin')
@section('title', __('admin.slider.title'))

@section('content')
    @php
        $breadcrumbTitle     = __('admin.menu.slider');
        $breadcrumbParent    = __('admin.breadcrumbs.home');
        $breadcrumbParentUrl = route('admin.dashboard');

    @endphp

    <div class="py-4">

        <!-- Card -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 py-3">
                <h5 class="card-title mb-0 text-dark fw-semibold d-flex align-items-center gap-2">
                    <i class="bi bi-images text-primary"></i>
                    {{ __('admin.slider.slider_items') }}
                    <span class="badge bg-primary rounded-pill small">{{ $sliders->total() }}</span>
                    <span class="badge bg-info rounded-pill small">
                        {{ $currentLanguage === 'ar' ? __('admin.labels.arabic') : __('admin.labels.english') }}
                    </span>
                </h5>
                <a href="{{ route('admin.sliders.create') }}"
                   class="btn btn-primary btn-sm d-flex align-items-center gap-1 shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    {{ __('admin.slider.add_new_slide') }}
                </a>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th class="text-muted small fw-semibold" width="6%">#</th>
                            <th class="text-muted small fw-semibold" width="30%">{{ __('admin.slider.table_title') }}</th>
                            <th class="text-muted small fw-semibold" width="28%">{{ __('admin.slider.table_bg_image') }}</th>
                            <th class="text-muted small fw-semibold text-center" width="10%">{{ __('admin.slider.table_order') }}</th>
                            <th class="text-muted small fw-semibold text-center" width="10%">{{ __('admin.slider.table_status') }}</th>
                            <th class="text-muted small fw-semibold text-end" width="16%">{{ __('admin.slider.table_actions') }}</th>
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
                                    <img src="{{ build_image_url($s->bg_image) }}"
                                         alt="{{ __('admin.slider.table_bg_image') }}"
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
                                            {{ $s->is_active ? __('admin.slider.form_active') : __('admin.slider.form_inactive') }}
                                        </span>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.sliders.edit', $s) }}"
                                           class="btn btn-outline-primary"
                                           title="{{ __('admin.actions.edit') }}">
                                            {{ __('admin.actions.edit') }}
                                        </a>

                                       <button type="button"
                                                class="btn btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal-{{ $s->id }}"
                                                title="{{ __('admin.actions.delete') }}">
                                            {{ __('admin.actions.delete') }}
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-images display-5 d-block mb-3 opacity-50"></i>
                                        <p class="mb-1">{{ __('admin.slider.no_slides') }}</p>
                                        <a href="{{ route('admin.sliders.create') }}" class="small text-primary">{{ __('admin.slider.add_first_slide') }}</a>
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
                                    {{ __('admin.slider.delete_modal_title') }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('admin.slider.close') }}"></button>
                            </div>
                            <div class="modal-body pt-2 pb-3 text-center">
                                <i class="bi bi-exclamation-triangle-fill text-danger display-5 mb-3"></i>
                                <p class="mb-2 text-muted small">{{ __('admin.slider.delete_confirm') }}</p>
                                <p class="fw-semibold text-dark mb-0">
                                    "{{ Str::limit($s->title, 40) }}"
                                </p>
                                <small class="text-danger d-block mt-2">{{ __('admin.slider.delete_irreversible') }}</small>
                            </div>
                            <div class="modal-footer border-0 pt-2 justify-content-center gap-2">
                                <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">
                                    {{ __('admin.actions.cancel') }}
                                </button>
                                <button type="submit" class="btn btn-danger btn-sm px-4">
                                    {{ __('admin.slider.delete_final') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
        <!-- ====================== نهاية المودال ====================== -->

    </div>

@endsection
