@php
    $offset = ($profiles->currentPage() - 1) * $profiles->perPage();
@endphp
@foreach($profiles as $profile)
    <tr class="align-middle profile-row"
        data-status="{{ $profile->status }}"
        data-readiness="{{ $profile->readiness }}">
        <td class="ps-4 text-muted small">
            {{ $loop->iteration + $offset }}
        </td>
        <td>
            <div class="fw-semibold">{{ $profile->full_name }}</div>
            <small class="text-muted">#{{ $profile->employee_number ?: '—' }}</small>
        </td>
        <td class="text-primary fw-semibold">{{ $profile->national_id }}</td>
        <td class="d-none d-md-table-cell">
            <span class="badge bg-light text-dark small px-3 rounded-pill">
                {{ $locations[$profile->location] ?? '—' }}
            </span>
        </td>
        <td>
            @php $s = $statusMap[$profile->status] ?? null @endphp
            @if($s)<span class="badge {{ $s['class'] }} small rounded-pill px-3">{{ $s['label'] }}</span>@endif
        </td>
        <td class="d-none d-lg-table-cell">
            @php $r = $readinessMap[$profile->readiness] ?? null @endphp
            @if($r)<span class="badge {{ $r['class'] }} small rounded-pill px-3">{{ $r['label'] }}</span>@endif
        </td>
        <td class="small text-muted d-none d-xl-table-cell">
            {{ $profile->created_at->format('d/m/Y') }}
        </td>
        <td class="text-center">
            <a href="{{ route('admin.staff-profiles.show', $profile) }}"
               class="btn btn-primary btn-sm rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-1" style="min-width: 80px;">
                <i class="bi bi-eye-fill"></i><span class="d-none d-md-inline">{{ __('admin.staff_profiles.table_view') }}</span>
            </a>
        </td>
    </tr>
@endforeach
