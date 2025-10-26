@extends('layouts.admin')
@section('title','Sliders')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Sliders</h4>
        <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">+ جديد</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-sm align-middle">
                <thead>
                <tr>
                    <th>#</th><th>العنوان</th><th>صورة الخلفية</th><th>ترتيب</th><th>نشط</th><th class="text-end">إجراءات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($sliders as $s)
                    <tr>
                        <td>{{ $s->id }}</td>
                        <td>{{ Str::limit($s->title,40) }}</td>
                        <td>
                            @if($s->bg_image)
                                <img src="{{ asset('storage/'.$s->bg_image) }}" alt="" width="90">
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $s->sort_order }}</td>
                        <td>{!! $s->is_active ? '✅' : '❌' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.sliders.edit',$s) }}" class="btn btn-sm btn-secondary">تعديل</a>
                            <form action="{{ route('admin.sliders.destroy',$s) }}" method="post" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('حذف؟')">حذف</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">لا توجد شرائح</td></tr>
                @endforelse
                </tbody>
            </table>

            {{ $sliders->links() }}
        </div>
    </div>
@endsection
