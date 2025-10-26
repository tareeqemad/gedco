@extends('layouts.admin')
@section('title','الصلاحيات')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5>الصلاحيات</h5>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">
                <i class="bi bi-plus"></i> صلاحية جديدة
            </a>
        </div>

        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered align-middle">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاسم</th>
                    <th>الحارس (guard)</th>
                    <th>الإجراءات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($permissions as $perm)
                    <tr>
                        <td>{{ $perm->id }}</td>
                        <td dir="ltr">{{ $perm->name }}</td>
                        <td>{{ $perm->guard_name }}</td>
                        <td>
                            <a href="{{ route('admin.permissions.edit',$perm->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.permissions.destroy',$perm->id) }}" method="POST" style="display:inline-block">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('تأكيد الحذف؟')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">لا توجد صلاحيات بعد.</td></tr>
                @endforelse
                </tbody>
            </table>

            {{ $permissions->links() }}
        </div>
    </div>
@endsection
