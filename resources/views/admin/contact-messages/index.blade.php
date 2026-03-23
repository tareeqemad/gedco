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
                    <div class="d-flex align-items-center gap-4 flex-wrap text-white-50" style="font-size: 0.9rem;">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-envelope-fill"></i>
                            <span>إجمالي الرسائل:</span>
                            <strong class="text-white">{{ $messages->total() }}</strong>
                        </div>
                        @if($unreadCount > 0)
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-envelope-exclamation"></i>
                                <span>غير مقروءة:</span>
                                <strong class="text-warning">{{ $unreadCount }}</strong>
                            </div>
                        @endif
                    </div>
                </x-slot:badge>
            </x-admin.card-header-index>

            <!-- Filter Section -->
            <div class="card-body p-3">
                <form method="GET" action="{{ route('admin.contact-messages.index') }}" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-search me-1"></i>البحث
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="ابحث بالاسم، البريد، الموضوع أو الرسالة..."
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-funnel me-1"></i>الحالة
                        </label>
                        <select name="is_read" class="form-select">
                            <option value="">الكل</option>
                            <option value="0" {{ request('is_read') === '0' ? 'selected' : '' }}>غير مقروءة</option>
                            <option value="1" {{ request('is_read') === '1' ? 'selected' : '' }}>مقروءة</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small text-muted mb-1 fw-semibold">
                            <i class="bi bi-sort me-1"></i>الترتيب
                        </label>
                        <select name="sort" class="form-select">
                            <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>التاريخ</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>الاسم</option>
                            <option value="subject" {{ request('sort') === 'subject' ? 'selected' : '' }}>الموضوع</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i>
                        </button>
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
