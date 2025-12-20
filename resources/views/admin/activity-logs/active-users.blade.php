@extends('layouts.admin')
@section('title', 'المستخدمون المتصلون')

@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">المستخدمون المتصلون حالياً</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.activity-logs.index') }}">سجل الأنشطة</a>
                        </li>
                        <li class="breadcrumb-item active">المستخدمون المتصلون</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- إحصائيات -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-success text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-person-check fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">المستخدمون النشطون</h6>
                            <h3 class="mb-0 fw-bold">{{ $activeUsers->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-info text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-clock-history fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">آخر 15 دقيقة</h6>
                            <h3 class="mb-0 fw-bold">{{ \Carbon\Carbon::now()->subMinutes(15)->diffForHumans() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-md bg-primary text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                            <i class="bi bi-activity fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 text-muted small">إجمالي أنشطة اليوم</h6>
                            <h3 class="mb-0 fw-bold">{{ number_format($activeUsers->sum(fn($item) => $item['today_count'] ?? 0)) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-people-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">المستخدمون المتصلون حالياً</h5>
                            <small class="text-muted">المستخدمون الذين لديهم نشاط في آخر 15 دقيقة ({{ $activeUsers->count() }} مستخدم)</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Search Bar -->
                    <div class="p-3 bg-light border-bottom">
                        <form method="GET" action="{{ route('admin.activity-logs.active-users') }}" class="row g-3 align-items-end">
                            <div class="col-md-8">
                                <label class="form-label small text-muted mb-1">البحث بالاسم أو البريد الإلكتروني</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="ابحث عن مستخدم..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-funnel me-2"></i>بحث
                                </button>
                            </div>
                        </form>
                        @if(request()->has('search'))
                            <div class="mt-2">
                                <a href="{{ route('admin.activity-logs.active-users') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-x-circle me-1"></i>إلغاء البحث
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th style="width: 60px;"></th>
                                    <th>المستخدم</th>
                                    <th>الأدوار</th>
                                    <th class="text-center" style="width: 150px;">آخر نشاط</th>
                                    <th class="text-center" style="width: 120px;">أنشطة اليوم</th>
                                    <th class="text-center" style="width: 150px;">أول دخول اليوم</th>
                                    <th class="text-center" style="width: 130px;">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeUsers as $index => $active)
                                    @php $user = $active['user']; @endphp
                                    <tr>
                                        <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="user-avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; font-weight: 600;">
                                                {{ mb_substr($user->name, 0, 1) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-semibold text-dark">{{ $user->name }}</span>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->roles->count() > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($user->roles->take(2) as $role)
                                                        <span class="badge bg-info">{{ $role->name }}</span>
                                                    @endforeach
                                                    @if($user->roles->count() > 2)
                                                        <span class="badge bg-secondary">+{{ $user->roles->count() - 2 }}</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <span class="fw-semibold">{{ \Carbon\Carbon::parse($active['last_activity'])->diffForHumans() }}</span>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($active['last_activity'])->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill px-3 py-1">
                                                {{ number_format($active['today_count']) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($active['first_login_today'])
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="fw-semibold">{{ \Carbon\Carbon::parse($active['first_login_today'])->format('H:i') }}</span>
                                                    <small class="text-muted">{{ \Carbon\Carbon::parse($active['first_login_today'])->format('d/m') }}</small>
                                                </div>
                                            @else
                                                <span class="text-muted small">—</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.activity-logs.user', $user->id) }}" 
                                               class="btn btn-sm btn-primary" 
                                               title="عرض جميع الأنشطة">
                                                <i class="bi bi-eye me-1"></i>
                                                عرض الأنشطة
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-5">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="bi bi-person-x display-1 text-muted mb-3"></i>
                                                <h5 class="text-muted">لا يوجد مستخدمون نشطون حالياً</h5>
                                                <p class="text-muted small">المستخدمون الذين لديهم نشاط في آخر 15 دقيقة سيظهرون هنا</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($activeUsers->count() > 0)
                        <!-- Pagination Info -->
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                عرض <strong>{{ $activeUsers->count() }}</strong> مستخدم نشط
                            </div>
                            <div class="text-muted small">
                                آخر تحديث: <strong>{{ now()->format('H:i:s') }}</strong>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .user-avatar-sm {
        font-size: 14px;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.875rem;
        color: #495057;
        white-space: nowrap;
    }
    
    .table td {
        vertical-align: middle;
    }
    
    .table tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush
