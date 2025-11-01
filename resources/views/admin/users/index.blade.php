@extends('layouts.admin')
@section('title', 'إدارة المستخدمين')

@section('content')
    <!-- Breadcrumbs -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">إدارة المستخدمين</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="feather icon-home"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active">المستخدمون</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-people-fill me-2"></i>
                        قائمة المستخدمين
                        <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                    </h5>
                    @can('users.create')
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-circle me-1"></i>
                            مستخدم جديد
                        </a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    @if (session('success'))
                        <div class="alert alert-success border-0 m-3">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- فلتر البحث -->
                    <div class="p-3 bg-light border-bottom">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <div class="input-group input-group-sm">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                    <input type="text" name="search" class="form-control" placeholder="ابحث بالاسم أو البريد..."
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select form-select-sm">
                                    <option value="">جميع الأدوار</option>
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                    <i class="bi bi-funnel"></i> فلتر
                                </button>
                            </div>
                            <div class="col-md-3 text-end">
                                @if(request()->hasAny(['search', 'role']))
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-arrow-counterclockwise"></i> إلغاء
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- الجدول -->
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>المستخدم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الأدوار</th>
                                <th>تاريخ الإنشاء</th>
                                <th class="text-center">الإجراءات</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($users as $index => $user)
                                <tr>
                                    <td class="text-center text-muted small">
                                        {{ $users->firstItem() + $index }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-3">
                                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle" width="40">
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name }}</div>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                            {{ $user->email }}
                                        </a>
                                    </td>
                                    <td>
                                        @forelse($user->roles as $role)
                                            <span class="badge rounded-pill
                                                @if($role->name === 'super-admin') bg-danger
                                                @elseif($role->name === 'admin') bg-primary
                                                @elseif($role->name === 'editor') bg-warning text-dark
                                                @elseif($role->name === 'viewer') bg-secondary
                                                @else bg-info text-dark
                                                @endif
                                                me-1">
                                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                            </span>
                                        @empty
                                            <span class="badge bg-light text-dark border">بدون دور</span>
                                        @endforelse
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d/m/Y') }}
                                            <br>
                                            <span class="text-muted">{{ $user->created_at->diffForHumans() }}</span>
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            @can('users.edit')
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                   class="btn btn-sm btn-outline-warning"
                                                   title="تعديل">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @endcan

                                            @can('users.delete')
                                                @if(auth()->id() !== $user->id)
                                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')"
                                                                title="حذف">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled title="لا يمكنك حذف نفسك">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-people fs-1"></i>
                                        <p class="mt-3">لا يوجد مستخدمون</p>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-white">
                        {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
