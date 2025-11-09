@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

    $rel = function (?string $url) {
        if (!$url) return '#';
        $parts = parse_url($url);
        $path  = $parts['path']  ?? '/';
        $query = isset($parts['query']) ? ('?' . $parts['query']) : '';
        return $query ? ($path . $query) : $path;
    };
@endphp

<table class="table table-hover align-middle mb-0 responsive-table table-sticky">
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
            $insertDate = $ad->INSERT_DATE ? Carbon::parse($ad->INSERT_DATE)->timezone('Asia/Hebron') : null;
            $updateDate = $ad->UPDATE_DATE ? Carbon::parse($ad->UPDATE_DATE)->timezone('Asia/Hebron') : null;
            $newsDate   = $ad->DATE_NEWS   ? Carbon::parse($ad->DATE_NEWS)->timezone('Asia/Hebron')   : null;

            $isToday   = $newsDate?->isToday();
            $isYday    = $newsDate?->isYesterday();
            $isOld     = $newsDate?->lt(now('Asia/Hebron')->subMonths(6));
            $dateClass = $isToday ? 'text-primary' : ($isYday ? 'text-info' : ($isOld ? 'text-muted' : 'text-body'));

            $showUrl    = $rel(route('admin.advertisements.show', $ad));
            $editUrl    = $rel(route('admin.advertisements.edit', $ad));
            $pdfUrl     = $ad->PDF ? $rel(Storage::url($ad->PDF)) : null;
            $destroyUrl = $rel(route('admin.advertisements.destroy', $ad));
        @endphp
        <tr id="ad-row-{{ $ad->ID_ADVER }}">
            <td class="text-center fw-medium" data-label="#">{{ $ad->ID_ADVER }}</td>

            <td data-label="العنوان">
                <div class="line-clamp-2" style="max-width:320px" title="{{ $ad->TITLE }}">
                    <span class="fw-medium">{{ Str::limit($ad->TITLE, 110) }}</span>
                </div>
            </td>

            <td class="text-center" data-label="تاريخ الخبر">
                @if($newsDate)
                    <div class="fw-medium {{ $dateClass }}">{{ $newsDate->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ $newsDate->format('H:i') }}</small>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="أضيف بواسطة">
                <div class="fw-medium text-primary">{{ $ad->INSERT_USER }}</div>
                @if($insertDate)
                    <small class="text-muted">{{ $insertDate->format('d/m H:i') }}</small>
                @endif
            </td>

            <td class="text-center" data-label="آخر تحديث">
                @if($ad->UPDATE_USER)
                    <div class="fw-medium text-success">{{ $ad->UPDATE_USER }}</div>
                    @if($updateDate)
                        <small class="text-muted">{{ $updateDate->format('d/m H:i') }}</small>
                    @endif
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="ملف">
                @if($pdfUrl)
                    <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger" title="عرض الملف">
                        <i class="ri-file-pdf-line"></i>
                    </a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="الإجراءات">
                <div class="d-inline-block d-md-none">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            إجراءات
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ $showUrl }}"><i class="ri-eye-line me-1"></i> عرض</a></li>
                            <li><a class="dropdown-item" href="{{ $editUrl }}"><i class="ri-edit-line me-1"></i> تعديل</a></li>
                            <li>
                                <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')" class="px-3 py-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0">
                                        <i class="ri-delete-bin-line me-1"></i> حذف
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="btn-group btn-group-sm d-none d-md-inline-flex">
                    <a href="{{ $showUrl }}" class="btn btn-primary" title="عرض" aria-label="عرض"><i class="ri-eye-line"></i></a>
                    <a href="{{ $editUrl }}" class="btn btn-warning" title="تعديل" aria-label="تعديل"><i class="ri-edit-line"></i></a>
                    <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="حذف" aria-label="حذف">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-5 text-muted">لا توجد إعلانات</td>
        </tr>
    @endforelse
    </tbody>
</table>
