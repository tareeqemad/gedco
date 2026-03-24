@forelse($logs as $log)
    @php
        $actionClass = match($log->action) {
            'login' => 'activity-badge-login',
            'logout' => 'activity-badge-logout',
            'create' => 'activity-badge-create',
            'update' => 'activity-badge-update',
            'delete' => 'activity-badge-delete',
            'view' => 'activity-badge-view',
            default => '',
        };
        $actionIcon = match($log->action) {
            'login' => 'bi-box-arrow-in-right',
            'logout' => 'bi-box-arrow-right',
            'create' => 'bi-plus-circle',
            'update' => 'bi-pencil',
            'delete' => 'bi-trash',
            'view' => 'bi-eye',
            default => 'bi-circle',
        };
    @endphp
    <tr>
        {{-- User --}}
        <td>
            <div class="d-flex align-items-center gap-2">
                <div class="active-user-avatar" style="width: 32px; height: 32px; font-size: 0.7rem; border-radius: 8px;">
                    {{ mb_strtoupper(mb_substr($log->user->display_name ?? 'U', 0, 1)) }}
                </div>
                <div class="min-width-0">
                    <span class="fw-semibold d-block text-truncate" style="font-size: 0.82rem; color: #24364A; max-width: 150px;">{{ $log->user->display_name ?? __('admin.dashboard_ui.deleted_user') }}</span>
                </div>
            </div>
        </td>
        {{-- Action --}}
        <td>
            <span class="stat-chip {{ $actionClass }}" style="font-size: 0.68rem;">
                <i class="bi {{ $actionIcon }}"></i> {{ $log->action }}
            </span>
        </td>
        {{-- Description --}}
        <td>
            <span class="text-muted text-truncate d-inline-block" style="font-size: 0.78rem; max-width: 220px;" title="{{ $log->description }}">
                {{ Str::limit($log->description, 50) }}
            </span>
        </td>
        {{-- Route --}}
        <td>
            <code class="text-muted" style="font-size: 0.7rem;">{{ $log->route_name }}</code>
        </td>
        {{-- IP --}}
        <td>
            <small class="text-muted" style="font-size: 0.72rem;">{{ $log->ip_address }}</small>
        </td>
        {{-- Date --}}
        <td>
            <small class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->diffForHumans() }}</small>
        </td>
        {{-- Actions --}}
        <td class="text-end">
            @if($log->user)
                <a href="{{ route('admin.activity-logs.user', $log->user_id) }}"
                   class="btn btn-sm btn-outline-primary rounded-3"
                   title="{{ __('admin.activity_logs.view_activities') }}">
                    <i class="bi bi-eye"></i>
                </a>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-5">
            <i class="bi bi-clock-history display-4 text-muted opacity-50 d-block mb-3"></i>
            <h5 class="text-muted mb-2">{{ __('admin.activity_logs.no_activities') }}</h5>
        </td>
    </tr>
@endforelse
