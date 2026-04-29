@php
    $breadcrumbTitle      = __('admin.join_requests.title');
    $breadcrumbParent     = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl  = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.join_requests.title'))

@section('content')
<div class="container-fluid">
    <x-admin.card>
        <x-admin.card-header-index
            icon="bi-person-plus-fill"
            :title="__('admin.join_requests.title')">
            <x-slot:badge>
                <span class="stat-chip stat-chip-header">
                    {{ $requests->total() }} {{ __('admin.join_requests.entry') }}
                </span>
                @if($unreadCount > 0)
                    <span class="stat-chip stat-chip-header" style="background: rgba(217,119,6,0.2); border-color: rgba(217,119,6,0.3);">
                        {{ $unreadCount }} {{ __('admin.ui.unread') }}
                    </span>
                @endif
            </x-slot:badge>
            <x-slot:actions>
                <a href="{{ route('admin.join-requests.export', request()->query()) }}"
                   class="btn btn-light btn-sm shadow-sm fw-semibold"
                   title="{{ __('admin.join_requests.export_excel') }}">
                    <i class="bi bi-file-earmark-excel-fill me-1" style="color:#1F7244;"></i>
                    <span class="d-none d-md-inline">{{ __('admin.join_requests.export_excel') }}</span>
                </a>
            </x-slot:actions>
        </x-admin.card-header-index>

        {{-- Source counts row --}}
        @if(!empty($sourceCounts))
            <div class="px-3 pt-3 pb-1 d-flex gap-2 flex-wrap">
                @foreach(\App\Models\JoinRequest::SOURCES as $src)
                    @php $count = $sourceCounts[$src] ?? 0; @endphp
                    <a href="{{ route('admin.join-requests.index', array_merge(request()->except('page','source'), ['source' => $src])) }}"
                       class="text-decoration-none">
                        <span class="badge rounded-pill px-3 py-2"
                              style="background: {{ request('source') === $src ? '#F85C00' : '#FFF7ED' }};
                                     color: {{ request('source') === $src ? '#fff' : '#9A3412' }};
                                     border: 1px solid #FED7AA; font-size: 0.72rem;">
                            <i class="bi bi-{{ [
                                'friend_employee' => 'people-fill',
                                'social_media' => 'hash',
                                'website' => 'globe',
                                'advertisement' => 'megaphone-fill',
                                'other' => 'three-dots',
                            ][$src] }} me-1"></i>
                            {{ __('admin.join_requests.sources.' . $src) }}
                            <span class="ms-1 fw-bold">{{ $count }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Filters --}}
        <div class="card-body p-3 admin-filters">
            <form method="GET" action="{{ route('admin.join-requests.index') }}">
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    <div class="filter-field-wide">
                        <input type="text" name="search" class="form-control rounded-3"
                               placeholder="{{ __('admin.join_requests.search_placeholder') }}"
                               value="{{ request('search') }}">
                    </div>
                    <div class="filter-field-medium">
                        <select name="source" class="form-select rounded-3">
                            <option value="">{{ __('admin.join_requests.all_sources') }}</option>
                            @foreach(\App\Models\JoinRequest::SOURCES as $src)
                                <option value="{{ $src }}" {{ request('source') === $src ? 'selected' : '' }}>
                                    {{ __('admin.join_requests.sources.' . $src) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-field-medium">
                        <select name="is_read" class="form-select rounded-3">
                            <option value="">{{ __('admin.ui.all_statuses') }}</option>
                            <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>{{ __('admin.ui.unread') }}</option>
                            <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>{{ __('admin.ui.read') }}</option>
                        </select>
                    </div>
                    <div class="filter-field-auto">
                        <button type="submit" class="btn btn-outline-secondary rounded-3">
                            <i class="bi bi-search me-1"></i> {{ __('admin.ui.query') }}
                        </button>
                        <a href="{{ route('admin.join-requests.index') }}" class="btn btn-outline-danger rounded-3">
                            <i class="bi bi-x-circle me-1"></i> {{ __('admin.ui.clear') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="p-0">
            @include('admin.join-requests.partials.table', ['requests' => $requests])
        </div>

        @if($requests->hasPages())
            <div class="card-footer bg-white border-top py-3">
                {{ $requests->links() }}
            </div>
        @endif
    </x-admin.card>
</div>
@endsection
