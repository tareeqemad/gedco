@php
    $breadcrumbTitle = 'رسائل الاتصال';
    $breadcrumbParent = 'الرئيسية';
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', 'رسائل الاتصال')

@section('content')
    <div class="container-fluid">
        <!-- Main Card -->
        <x-admin.card>
            <!-- Header Section -->
            <x-admin.card-header-index
                icon="bi-envelope-fill"
                title="رسائل الاتصال">
                <x-slot:badge>
                    <span class="stat-chip stat-chip-header">{{ $messages->total() }} رسالة</span>
                    @if($unreadCount > 0)
                        <span class="stat-chip stat-chip-header" style="background: rgba(217,119,6,0.2); border-color: rgba(217,119,6,0.3);">
                            {{ $unreadCount }} غير مقروءة
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
                                   placeholder="ابحث بالاسم أو البريد أو الموضوع..."
                                   value="{{ request('search') }}">
                        </div>
                        <div style="flex: 0 1 20%;">
                            <select name="is_read" class="form-select rounded-3">
                                <option value="">كل الحالات</option>
                                <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>غير مقروءة</option>
                                <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>مقروءة</option>
                            </select>
                        </div>
                        <div style="flex: 0 1 18%;">
                            <select name="sort" class="form-select rounded-3">
                                <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>الأحدث</option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم</option>
                                <option value="subject" {{ request('sort') === 'subject' ? 'selected' : '' }}>الموضوع</option>
                            </select>
                        </div>
                        <div style="flex: 0 0 auto;">
                            <button type="submit" class="btn btn-primary rounded-3">
                                <i class="bi bi-funnel me-1"></i> فلتر
                            </button>
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
