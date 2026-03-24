@forelse($permissionsWithRoles as $item)
    @php $perm = $item['permission']; @endphp
    <tr class="permission-row">
        <td class="text-center text-muted">{{ $loop->iteration + ($permissions->currentPage()-1)*$permissions->perPage() }}</td>
        <td>
            <span class="fw-semibold" style="color: #24364A;">{{ $perm->name }}</span>
        </td>
        <td class="text-center">
            <span class="stat-chip stat-chip-primary" style="font-size: 0.7rem;">{{ $perm->guard_name }}</span>
        </td>
        <td>
            @if($item['roles_count'] > 0)
                <div class="d-flex align-items-center gap-1 flex-wrap">
                    <span class="text-muted small me-1">{{ $item['roles_count'] }} {{ $item['roles_count'] == 1 ? __('admin.permissions.role_count_singular') : __('admin.permissions.role_count_plural') }}</span>
                    @foreach(array_slice($item['roles'], 0, 2) as $role)
                        <span class="badge bg-light text-dark rounded-pill px-2 py-1" style="font-size: 0.68rem;">{{ $role }}</span>
                    @endforeach
                    @if($item['roles_count'] > 2)
                        <span class="badge bg-secondary rounded-pill px-2 py-1" style="font-size: 0.68rem;">+{{ $item['roles_count'] - 2 }}</span>
                    @endif
                </div>
            @else
                <small class="text-muted">{{ __('admin.permissions.no_roles') }}</small>
            @endif
        </td>
        <td class="text-end">
            <div class="d-flex gap-1 justify-content-end">
                <a href="{{ route('admin.permissions.edit', $perm->id) }}"
                   class="btn btn-sm btn-outline-warning rounded-3"
                   title="{{ __('admin.actions.edit') }}">
                    <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.permissions.destroy', $perm->id) }}"
                      method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="btn btn-sm btn-outline-danger rounded-3"
                            title="{{ __('admin.actions.delete') }}"
                            data-confirm-delete>
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center py-5">
            <i class="bi bi-shield-x display-4 text-muted opacity-50 d-block mb-3"></i>
            <h5 class="text-muted mb-2">{{ __('admin.permissions.no_permissions') }}</h5>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-save rounded-3 mt-2">
                <i class="bi bi-plus-circle me-1"></i>
                {{ __('admin.permissions.add_new') }}
            </a>
        </td>
    </tr>
@endforelse
