{{-- resources/views/admin/roles/index.blade.php --}}
@extends('layouts.admin')
@section('title', 'الأدوار')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">الأدوار</h5>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">إضافة دور جديد</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>اسم الدور</th>
                        <th>عدد المستخدمين</th>
                        <th>الإجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>{{ $loop->iteration + ($roles->currentPage() - 1) * $roles->perPage() }}</td>
                            <td>
                                <span class="badge bg-{{ $role->name === 'super-admin' ? 'danger' : 'primary' }} fs-6">
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $role->users_count }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                    تعديل
                                </a>

                                @if($role->name !== 'super-admin' && $role->users_count == 0)
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الدور؟')">
                                            حذف
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>محمي</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">لا توجد أدوار بعد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $roles->links() }}
        </div>
    </div>
@endsection
