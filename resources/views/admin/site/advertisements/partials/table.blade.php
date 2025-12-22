@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    use Illuminate\Support\Facades\Storage;
@endphp

<!-- Desktop Table View -->
<div class="table-responsive d-none d-md-block">
    <table class="table table-hover align-middle mb-0 responsive-table">
        <thead class="table-light">
        <tr>
            <th class="text-center text-muted small fw-semibold" style="width:60px">{{ __('admin.advertisements.table_id') }}</th>
            <th class="text-muted small fw-semibold">{{ __('admin.advertisements.table_title') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:120px">{{ __('admin.advertisements.table_date_news') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:130px">{{ __('admin.advertisements.table_added_by') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:130px">{{ __('admin.advertisements.table_last_update') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:80px">{{ __('admin.advertisements.table_file') }}</th>
            <th class="text-center text-muted small fw-semibold" style="width:160px">{{ __('admin.advertisements.table_actions') }}</th>
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

                $showUrl    = route('admin.advertisements.show', $ad);
                $editUrl    = route('admin.advertisements.edit', $ad);
                $pdfUrl     = $ad->PDF ? Storage::url($ad->PDF) : null;
                $destroyUrl = route('admin.advertisements.destroy', $ad);
            @endphp
            <tr id="ad-row-{{ $ad->ID_ADVER }}">
                <td class="text-center fw-medium small">{{ $ad->ID_ADVER }}</td>

                <td>
                    <div class="text-truncate" style="max-width:320px" title="{{ $ad->TITLE }}">
                        <span class="fw-medium">{{ Str::limit($ad->TITLE, 110) }}</span>
                    </div>
                </td>

                <td class="text-center">
                    @if($newsDate)
                        <div class="fw-medium small {{ $dateClass }}">{{ $newsDate->format('d/m/Y') }}</div>
                        <small class="text-muted">{{ $newsDate->format('H:i') }}</small>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                <td class="text-center">
                    <div class="fw-medium small text-primary">{{ $ad->INSERT_USER }}</div>
                    @if($insertDate)
                        <small class="text-muted">{{ $insertDate->format('d/m H:i') }}</small>
                    @endif
                </td>

                <td class="text-center">
                    @if($ad->UPDATE_USER)
                        <div class="fw-medium small text-success">{{ $ad->UPDATE_USER }}</div>
                        @if($updateDate)
                            <small class="text-muted">{{ $updateDate->format('d/m H:i') }}</small>
                        @endif
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                <td class="text-center">
                    @if($pdfUrl)
                        <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" 
                           class="btn btn-outline-danger btn-sm rounded-3 shadow-sm" 
                           title="{{ __('admin.advertisements.view_file') }}">
                            <i class="bi bi-file-pdf"></i>
                        </a>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>

                <td class="text-center">
                    <div class="d-flex gap-2 justify-content-center">
                        <a href="{{ $showUrl }}" 
                           class="btn btn-outline-primary btn-sm rounded-3 shadow-sm"
                           title="{{ __('admin.advertisements.view') }}">
                            <i class="bi bi-eye me-1"></i><span class="d-none d-lg-inline">{{ __('admin.advertisements.view') }}</span>
                        </a>
                        <a href="{{ $editUrl }}" 
                           class="btn btn-outline-warning btn-sm rounded-3 shadow-sm"
                           title="{{ __('admin.advertisements.edit') }}">
                            <i class="bi bi-pencil me-1"></i><span class="d-none d-lg-inline">{{ __('admin.advertisements.edit') }}</span>
                        </a>
                        <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 shadow-sm"
                                    title="{{ __('admin.advertisements.delete') }}">
                                <i class="bi bi-trash me-1"></i><span class="d-none d-lg-inline">{{ __('admin.advertisements.delete') }}</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center py-5">
                    <div class="text-muted">
                        <i class="bi bi-megaphone" style="font-size: 4rem; opacity: 0.5;"></i>
                        <p class="mb-2 mt-3">{{ __('admin.advertisements.no_advertisements') }}</p>
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Mobile Card View -->
<div class="d-md-none p-3">
    @forelse($ads as $ad)
        @php
            $insertDate = $ad->INSERT_DATE ? Carbon::parse($ad->INSERT_DATE)->timezone('Asia/Hebron') : null;
            $updateDate = $ad->UPDATE_DATE ? Carbon::parse($ad->UPDATE_DATE)->timezone('Asia/Hebron') : null;
            $newsDate   = $ad->DATE_NEWS   ? Carbon::parse($ad->DATE_NEWS)->timezone('Asia/Hebron')   : null;
            $isToday   = $newsDate?->isToday();
            $isYday    = $newsDate?->isYesterday();
            $isOld     = $newsDate?->lt(now('Asia/Hebron')->subMonths(6));
            $dateClass = $isToday ? 'text-primary' : ($isYday ? 'text-info' : ($isOld ? 'text-muted' : 'text-body'));
            $showUrl    = route('admin.advertisements.show', $ad);
            $editUrl    = route('admin.advertisements.edit', $ad);
            $pdfUrl     = $ad->PDF ? Storage::url($ad->PDF) : null;
            $destroyUrl = route('admin.advertisements.destroy', $ad);
        @endphp
        <div class="card mb-3 shadow-sm border-0 rounded-3 advertisement-mobile-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-primary rounded-pill">#{{ $ad->ID_ADVER }}</span>
                        @if($pdfUrl)
                            <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" 
                               class="badge bg-danger rounded-pill">
                                <i class="bi bi-file-pdf me-1"></i>PDF
                            </a>
                        @endif
                    </div>
                </div>
                
                <h6 class="mb-2 fw-semibold">{{ Str::limit($ad->TITLE, 80) }}</h6>
                
                <div class="d-flex flex-wrap gap-2 mb-2">
                    @if($newsDate)
                        <span class="badge bg-secondary small {{ $dateClass }}">
                            <i class="bi bi-calendar me-1"></i>{{ $newsDate->format('d/m/Y') }}
                        </span>
                    @endif
                    @if($ad->INSERT_USER)
                        <span class="badge bg-info small">
                            <i class="bi bi-person me-1"></i>{{ $ad->INSERT_USER }}
                        </span>
                    @endif
                    @if($ad->UPDATE_USER)
                        <span class="badge bg-success small">
                            <i class="bi bi-arrow-clockwise me-1"></i>{{ $ad->UPDATE_USER }}
                        </span>
                    @endif
                </div>
                
                <div class="d-flex gap-2">
                    <a href="{{ $showUrl }}" 
                       class="btn btn-outline-primary btn-sm rounded-3 shadow-sm flex-fill">
                        <i class="bi bi-eye me-1"></i>{{ __('admin.advertisements.view') }}
                    </a>
                    <a href="{{ $editUrl }}" 
                       class="btn btn-outline-warning btn-sm rounded-3 shadow-sm flex-fill">
                        <i class="bi bi-pencil me-1"></i>{{ __('admin.advertisements.edit') }}
                    </a>
                    <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')" class="flex-fill">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-3 shadow-sm w-100">
                            <i class="bi bi-trash me-1"></i>{{ __('admin.advertisements.delete') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="text-muted">
                <i class="bi bi-megaphone" style="font-size: 4rem; opacity: 0.5;"></i>
                <p class="mb-2 mt-3">{{ __('admin.advertisements.no_advertisements') }}</p>
            </div>
        </div>
    @endforelse
</div>
