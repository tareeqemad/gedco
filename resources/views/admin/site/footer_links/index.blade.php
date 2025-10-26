@extends('layouts.admin')
@section('title','Footer Links')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Footer Links</h4>
        <a href="{{ route('admin.footer-links.create') }}" class="btn btn-primary">+ جديد</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="row g-2 mb-3">
                <div class="col-md-3">
                    <select name="group" class="form-select" onchange="this.form.submit()">
                        <option value="">كل المجموعات</option>
                        <option value="services" @selected(request('group')=='services')>services</option>
                        <option value="company"  @selected(request('group')=='company')>company</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <input name="q" class="form-control" placeholder="ابحث بالعنوان/المسار/الرابط..." value="{{ request('q') }}">
                </div>
                <div class="col-md-3 text-end">
                    <button class="btn btn-outline-secondary">بحث</button>
                </div>
            </form>

            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>#</th><th>Group</th><th>Label</th><th>Route</th><th>URL</th><th>Order</th><th>Active</th><th class="text-end">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($links as $l)
                    <tr>
                        <td>{{ $l->id }}</td>
                        <td><span class="badge bg-dark">{{ $l->group }}</span></td>
                        <td>{{ $l->label_ar }}</td>
                        <td><code>{{ $l->route_name }}</code></td>
                        <td><small>{{ $l->url }}</small></td>
                        <td>{{ $l->sort_order }}</td>
                        <td>{!! $l->is_active ? '✅' : '❌' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.footer-links.edit',$l) }}" class="btn btn-sm btn-secondary">تعديل</a>
                            <form action="{{ route('admin.footer-links.destroy',$l) }}" method="post" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted">لا توجد روابط</td></tr>
                @endforelse
                </tbody>
            </table>

            {{ $links->withQueryString()->links() }}
        </div>
    </div>
@endsection
