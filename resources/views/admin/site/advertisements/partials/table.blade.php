<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
    <tr>
        <th class="text-center" style="width:60px">#</th>
        <th>العنوان</th>
        <th class="text-center" style="width:120px">تاريخ الخبر</th>
        <th class="text-center" style="width:130px">أضيف بواسطة</th>
        <th class="text-center" style="width:130px">آخر تحديث</th>
        <th class="text-center" style="width:80px">ملف</th>
        <th class="text-center" style="width:160px">الإجراءات</th>
    </tr>
    </thead>
    <tbody>
    @forelse($ads as $ad)
        @php
            $insertDate = $ad->INSERT_DATE ? \Carbon\Carbon::parse($ad->INSERT_DATE)->timezone('Asia/Hebron') : null;
            $updateDate = $ad->UPDATE_DATE ? \Carbon\Carbon::parse($ad->UPDATE_DATE)->timezone('Asia/Hebron') : null;
            $newsDate   = $ad->DATE_NEWS ? \Carbon\Carbon::parse($ad->DATE_NEWS)->timezone('Asia/Hebron') : null;
        @endphp
        <tr data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
            <td class="text-center fw-medium">{{ $ad->ID_ADVER }}</td>
            <td>
                <div class="text-truncate" style="max-width:320px" title="{{ $ad->TITLE }}">
                    <span class="fw-medium">{{ Str::limit($ad->TITLE, 50) }}</span>
                </div>
            </td>
            <td class="text-center">
                @if($newsDate)
                    <div class="fw-medium">{{ $newsDate->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ $newsDate->format('H:i') }}</small>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td class="text-center">
                <div class="fw-medium text-primary">{{ $ad->INSERT_USER }}</div>
                @if($insertDate)<small class="text-muted">{{ $insertDate->format('d/m H:i') }}</small>@endif
            </td>
            <td class="text-center">
                @if($ad->UPDATE_USER)
                    <div class="fw-medium text-success">{{ $ad->UPDATE_USER }}</div>
                    @if($updateDate)<small class="text-muted">{{ $updateDate->format('d/m H:i') }}</small>@endif
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td class="text-center">
                @if($ad->PDF)
                    <a href="{{ Storage::url($ad->PDF) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="ri-file-pdf-line"></i>
                    </a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td class="text-center">
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.advertisements.show', $ad) }}" class="btn btn-primary" title="عرض"><i class="ri-eye-line"></i></a>
                    <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-warning" title="تعديل"><i class="ri-edit-line"></i></a>
                    <form action="{{ route('admin.advertisements.destroy', $ad) }}" method="POST" style="display:inline" onsubmit="return confirmDelete(this)">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="حذف"><i class="ri-delete-bin-line"></i></button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="7" class="text-center py-5 text-muted">لا توجد إعلانات</td></tr>
    @endforelse
    </tbody>
</table>
