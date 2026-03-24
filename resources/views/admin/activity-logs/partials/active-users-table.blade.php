@forelse($activeUsers as $item)
    @php
        $user = $item['user'];
        $lastActivity = \Carbon\Carbon::parse($item['last_activity']);
        $roleName = $user->roles->first()?->name ?? '—';
    @endphp
    <tr class="active-user-row">
        <td>
            <div class="d-flex align-items-center gap-3">
                <div class="position-relative">
                    <div class="active-user-avatar">
                        {{ mb_strtoupper(mb_substr($user->display_name, 0, 1)) }}
                    </div>
                    <span class="active-pulse"></span>
                </div>
                <div class="min-width-0">
                    <a href="{{ route('admin.activity-logs.user', $user->id) }}" class="fw-semibold text-dark text-decoration-none d-block text-truncate" style="font-size: 0.85rem; max-width: 180px;">
                        {{ $user->display_name }}
                    </a>
                    <small class="text-muted d-block text-truncate" style="font-size: 0.72rem; max-width: 180px;">{{ $user->email }}</small>
                </div>
            </div>
        </td>
        <td>
            <span class="stat-chip" style="font-size: 0.7rem;">{{ $roleName }}</span>
        </td>
        <td>
            <div>
                <span class="text-dark" style="font-size: 0.82rem;">{{ $lastActivity->diffForHumans() }}</span>
            </div>
        </td>
        <td class="text-center">
            <span class="fw-bold" style="font-size: 0.95rem; color: #24364A;">{{ $item['today_count'] }}</span>
        </td>
        <td class="text-center">
            @if($item['first_login_today'])
                <small class="text-muted" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($item['first_login_today'])->format('h:i A') }}</small>
            @else
                <small class="text-muted">—</small>
            @endif
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <div>
                <i class="bi bi-broadcast display-4 text-muted opacity-50 d-block mb-3"></i>
                <h5 class="text-muted mb-2">{{ __('admin.activity_logs_ui.no_online_users') }}</h5>
                <p class="text-muted small mb-0">{{ __('admin.activity_logs_ui.no_online_desc') }}</p>
            </div>
        </td>
    </tr>
@endforelse
