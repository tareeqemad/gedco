@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>الوظائف</h3>
        @can('jobs.create')
            <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">إضافة</a>
        @endcan
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif

    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>#</th>
            <th>الصورة</th>
            <th>العنوان</th>
            <th>مفعل؟</th>
            <th>الترتيب</th>
            <th width="180">تحكم</th>
        </tr>
        </thead>
        <tbody>
        @forelse($jobs as $job)
            <tr>
                <td>{{ $job->id }}</td>
                <td>
                    @if($job->image)
                        <img src="{{ asset('storage/'.$job->image) }}" alt="" style="height:50px">
                    @endif
                </td>
                <td>{{ $job->title }}</td>
                <td>{!! $job->is_active ? '<span class="badge bg-success">نعم</span>' : '<span class="badge bg-secondary">لا</span>' !!}</td>
                <td>{{ $job->sort }}</td>
                <td>
                    @can('jobs.edit')
                        <a href="{{ route('admin.jobs.edit',$job) }}" class="btn btn-sm btn-warning">تعديل</a>
                    @endcan
                    @can('jobs.delete')
                        <form action="{{ route('admin.jobs.destroy',$job) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('حذف نهائي؟')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">حذف</button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">لا توجد بيانات</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $jobs->links() }}
@endsection
