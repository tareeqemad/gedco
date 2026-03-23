@php
    $breadcrumbTitle = __('admin.contact_messages.title');
    $breadcrumbParent = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.contact_messages.title'))

@section('content')
    <div class="container-fluid">
        <!-- Main Card -->
        <x-admin.card>
            <!-- Header Section -->
            <x-admin.card-header-index
                icon="bi-envelope-fill"
                :title="__('admin.contact_messages.title')">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header">{{ $messages->total() }} {{ __('admin.ui.message') }}</span>
                    @if($unreadCount > 0)
                        <span class="stat-chip stat-chip-header" style="background: rgba(217,119,6,0.2); border-color: rgba(217,119,6,0.3);">
                            {{ $unreadCount }} {{ __('admin.ui.unread') }}
                        </span>
                    @endif
                </x-slot:badge>
            </x-admin.card-header-index>

            <!-- Filter Section -->
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.contact-messages.index') }}">
                    <div class="d-flex gap-2 align-items-center flex-wrap">
                        <div style="flex: 1 1 40%;">
                            <input type="text" name="search" class="form-control rounded-3"
                                   placeholder="{{ __('admin.contact_messages.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>
                        <div style="flex: 0 1 20%;">
                            <select name="is_read" class="form-select rounded-3">
                                <option value="">{{ __('admin.ui.all_statuses') }}</option>
                                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>{{ __('admin.ui.unread') }}</option>
                                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>{{ __('admin.ui.read') }}</option>
                            </select>
                        </div>
                        <div style="flex: 0 1 18%;">
                            <select name="sort" class="form-select rounded-3">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>{{ __('admin.ui.newest') }}</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>{{ __('admin.labels.name') }}</option>
                                <option value="subject" {{ request('sort') === 'subject' ? 'selected' : '' }}>{{ __('admin.ui.subject') }}</option>
                            </select>
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-outline-secondary rounded-3">
                                <i class="bi bi-search me-1"></i> {{ __('admin.ui.query') }}
                            </button>
                            <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-danger rounded-3">
                                <i class="bi bi-x-circle me-1"></i> {{ __('admin.ui.clear') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table Container -->
            <div class="p-0">
                <div id="messagesTableContainer">
                    @include('admin.contact-messages.partials.table', ['messages' => $messages])
                </div>
            </div>

            <!-- Pagination Container -->
            @if($messages->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    <div id="messagesPaginationContainer">
                        @include('admin.contact-messages.partials.pagination', ['messages' => $messages])
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>
@endsection
