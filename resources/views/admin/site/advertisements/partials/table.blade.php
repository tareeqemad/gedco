@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;

@endphp

<table class="table table-hover align-middle mb-0 responsive-table table-sticky">
    <thead class="table-light">
    <tr>
        <th class="text-center" style="width:60px">{{ __('admin.advertisements.table_id') }}</th>
        <th>{{ __('admin.advertisements.table_title') }}</th>
        <th class="text-center" style="width:120px">{{ __('admin.advertisements.table_date_news') }}</th>
        <th class="text-center" style="width:130px">{{ __('admin.advertisements.table_added_by') }}</th>
        <th class="text-center" style="width:130px">{{ __('admin.advertisements.table_last_update') }}</th>
        <th class="text-center" style="width:80px">{{ __('admin.advertisements.table_file') }}</th>
        <th class="text-center" style="width:160px">{{ __('admin.advertisements.table_actions') }}</th>
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

            $showUrl    = get_relative_path(route('admin.advertisements.show', $ad), '#');
            $editUrl    = get_relative_path(route('admin.advertisements.edit', $ad), '#');
            $pdfUrl     = $ad->PDF ? get_relative_path(Storage::url($ad->PDF)) : null;
            $destroyUrl = get_relative_path(route('admin.advertisements.destroy', $ad), '#');
        @endphp
        <tr id="ad-row-{{ $ad->ID_ADVER }}">
            <td class="text-center fw-medium" data-label="{{ __('admin.advertisements.table_id') }}">{{ $ad->ID_ADVER }}</td>

            <td data-label="{{ __('admin.advertisements.table_title') }}">
                <div class="line-clamp-2" style="max-width:320px" title="{{ $ad->TITLE }}">
                    <span class="fw-medium">{{ Str::limit($ad->TITLE, 110) }}</span>
                </div>
            </td>

            <td class="text-center" data-label="{{ __('admin.advertisements.table_date_news') }}">
                @if($newsDate)
                    <div class="fw-medium {{ $dateClass }}">{{ $newsDate->format('d/m/Y') }}</div>
                    <small class="text-muted">{{ $newsDate->format('H:i') }}</small>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="{{ __('admin.advertisements.table_added_by') }}">
                <div class="fw-medium text-primary">{{ $ad->INSERT_USER }}</div>
                @if($insertDate)
                    <small class="text-muted">{{ $insertDate->format('d/m H:i') }}</small>
                @endif
            </td>

            <td class="text-center" data-label="{{ __('admin.advertisements.table_last_update') }}">
                @if($ad->UPDATE_USER)
                    <div class="fw-medium text-success">{{ $ad->UPDATE_USER }}</div>
                    @if($updateDate)
                        <small class="text-muted">{{ $updateDate->format('d/m H:i') }}</small>
                    @endif
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="{{ __('admin.advertisements.table_file') }}">
                @if($pdfUrl)
                    <a href="{{ $pdfUrl }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-danger" title="{{ __('admin.advertisements.view_file') }}">
                        <i class="bi bi-file-pdf"></i>
                    </a>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>

            <td class="text-center" data-label="{{ __('admin.advertisements.table_actions') }}">
                <div class="d-inline-block d-md-none">
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ __('admin.advertisements.actions') }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ $showUrl }}"><i class="bi bi-eye me-1"></i> {{ __('admin.advertisements.view') }}</a></li>
                            <li><a class="dropdown-item" href="{{ $editUrl }}"><i class="bi bi-pencil me-1"></i> {{ __('admin.advertisements.edit') }}</a></li>
                            <li>
                                <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')" class="px-3 py-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0">
                                        <i class="bi bi-trash me-1"></i> {{ __('admin.advertisements.delete') }}
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="btn-group btn-group-sm d-none d-md-inline-flex">
                    <a href="{{ $showUrl }}" class="btn btn-primary" title="{{ __('admin.advertisements.view') }}" aria-label="{{ __('admin.advertisements.view') }}"><i class="bi bi-eye"></i></a>
                    <a href="{{ $editUrl }}" class="btn btn-warning" title="{{ __('admin.advertisements.edit') }}" aria-label="{{ __('admin.advertisements.edit') }}"><i class="bi bi-pencil"></i></a>
                    <form action="{{ $destroyUrl }}" method="POST" onsubmit="return confirmDelete(this,'{{ $ad->ID_ADVER }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="{{ __('admin.advertisements.delete') }}" aria-label="{{ __('admin.advertisements.delete') }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="text-center py-5 text-muted">{{ __('admin.advertisements.no_advertisements') }}</td>
        </tr>
    @endforelse
    </tbody>
</table>
