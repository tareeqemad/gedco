@extends('layouts.admin')
@section('title','سلايدر الموقع')

@section('content')
    @php
        // عشان الـ breadcrumb partial في الـ layout يشتغل
        $breadcrumbTitle    = 'السلايدر';
        $breadcrumbParent   = 'إعدادات الموقع';
        $breadcrumbParentUrl= route('admin.site-settings.edit', 1);
    @endphp

    <div class="py-4">

        <div class="card shadow-sm border-0">
            {{-- عنوان الكارد + زر جديد --}}
            <div class="card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title mb-0">سلايدرز الموقع</h5>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">+ جديد</a>
            </div>

            <div class="card-body">
                <table class="table table-sm align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>العنوان</th>
                        <th>صورة الخلفية</th>
                        <th>ترتيب</th>
                        <th>نشط</th>
                        <th class="text-end">إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sliders as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($s->title, 40) }}</td>
                            <td>
                                @if($s->bg_image)
                                    @php
                                        $img = $s->bg_image;
                                        if (\Illuminate\Support\Str::startsWith($img, ['http://','https://'])) {
                                            $src = $img; // رابط خارجي
                                        } elseif (\Illuminate\Support\Str::startsWith($img, ['assets/','public/'])) {
                                            $src = asset($img); // من public مباشرة
                                        } elseif (\Illuminate\Support\Str::startsWith($img, 'storage/')) {
                                            $src = asset($img); // storage/ مسبقًا
                                        } else {
                                            $src = asset('storage/'.$img); // مرفوعة داخل storage/app/public
                                        }
                                    @endphp
                                    <img src="{{ $src }}" alt="bg" width="90" class="rounded shadow-sm">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $s->sort_order }}</td>
                            <td>{!! $s->is_active ? '✅' : '❌' !!}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.sliders.edit', $s) }}" class="btn btn-sm btn-secondary">تعديل</a>
                                <form action="{{ route('admin.sliders.destroy', $s) }}" method="post" class="d-inline">
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

    </div>
@endsection
