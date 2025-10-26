@extends('layouts.admin')
@section('title','Social Links')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Social Links</h4>
        <a href="{{ route('admin.social-links.create') }}" class="btn btn-primary">+ جديد</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>#</th><th>Platform</th><th>Icon</th><th>URL</th><th>Order</th><th>Active</th><th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($links as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td><span class="badge bg-dark">{{ $l->platform }}</span></td>
                        <td><code>{{ $l->icon_class }}</code></td>
                        <td><small>{{ $l->url }}</small></td>
                        <td>{{ $l->sort_order }}</td>
                        <td>{!! $l->is_active ? '✅' : '❌' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.social-links.edit',$l) }}" class="btn btn-sm btn-secondary">تعديل</a>
                            <form action="{{ route('admin.social-links.destroy',$l) }}" method="post" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted">لا توجد روابط</td></tr>
                @endforelse
                </tbody>
            </table>

            {{ $links->links() }}
        </div>
    </div>
@endsection
