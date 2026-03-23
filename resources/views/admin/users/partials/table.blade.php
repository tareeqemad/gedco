@if($users->count() > 0)
    <!-- Desktop Table View -->
    <div class="table-responsive d-none d-md-block">
        <table class="table align-middle mb-0 users-table">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="width: 60px">#</th>
                    <th>{{ __('admin.users.user') }}</th>
                    <th>{{ __('admin.users.email') }}</th>
                    <th>{{ __('admin.users.roles') }}</th>
                    <th>{{ __('admin.users.created_at') }}</th>
                    <th class="text-center" style="width: 180px">{{ __('admin.users.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $user)
                    <tr>
                        <td class="text-center text-muted small">
                            {{ $users->firstItem() + $index }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                         class="users-avatar" width="40" height="40">
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:{{ $user->email }}" class="text-decoration-none">
{{ $user->email }}
                            </a>
                        </td>
                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge rounded-pill users-badge
                                    @if($role->name === 'super-admin') bg-danger
                                    @elseif($role->name === 'admin') bg-primary
                                    @elseif($role->name === 'editor') bg-warning text-dark
                                    @elseif($role->name === 'viewer') bg-secondary
                                    @else bg-info text-dark
                                    @endif
                                    me-1">
                                    {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                </span>
                            @empty
                                <span class="badge bg-light text-dark border users-badge">{{ __('admin.users.no_role_badge') }}</span>
                            @endforelse
                        </td>
                        <td>
                            <div class="small">
                                <div class="text-dark">{{ $user->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-1 users-actions">
                                @can('users.edit')
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-outline-primary rounded-3"
                                       title="{{ __('admin.users.edit') }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                @endcan

                                @can('users.delete')
                                    @if(auth()->id() !== $user->id)
                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger rounded-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                title="{{ __('admin.users.delete') }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                @endcan

                                @if(auth()->user()->hasRole('super-admin'))
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary rounded-3"
                                            onclick="showTemporaryPassword({{ $user->id }})"
                                            title="عرض كلمة المرور"
                                            id="showPasswordBtn-{{ $user->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-secondary rounded-3"
                                            data-bs-toggle="modal"
                                            data-bs-target="#changePasswordModal-{{ $user->id }}"
                                            title="تغيير كلمة المرور">
                                        <i class="bi bi-key"></i>
                                    </button>
                                    @if(auth()->id() !== $user->id && !$user->hasRole('super-admin'))
                                        <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('admin.users.impersonate_confirm', ['name' => $user->name]) }}');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning rounded-3" title="{{ __('admin.users.impersonate') }}">
                                                <i class="bi bi-person-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="d-md-none">
        <div class="row g-3">
            @foreach($users as $index => $user)
                <div class="col-12">
                    <div class="card border-0 shadow-sm users-mobile-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div class="d-flex align-items-center flex-grow-1">
                                    <div class="avatar avatar-sm me-3">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" 
                                             class="users-avatar" width="50" height="50">
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold text-dark mb-1">{{ $user->name }}</div>
                                        <small class="text-muted d-block">ID: {{ $user->id }}</small>
                                        <small class="text-muted d-block">#{{ $users->firstItem() + $index }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-1 users-actions">
                                    @can('users.edit')
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-sm btn-outline-primary"
                                           title="{{ __('admin.users.edit') }}">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    @endcan
                                    @can('users.delete')
                                        @if(auth()->id() !== $user->id)
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal-{{ $user->id }}"
                                                    title="{{ __('admin.users.delete') }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-envelope me-1"></i>{{ __('admin.users.email') }}
                                </small>
                                <a href="mailto:{{ $user->email }}" class="text-decoration-none text-dark">
                                    {{ $user->email }}
                                </a>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-shield-check me-1"></i>{{ __('admin.users.roles') }}
                                </small>
                                <div>
                                    @forelse($user->roles as $role)
                                        <span class="badge rounded-pill users-badge
                                            @if($role->name === 'super-admin') bg-danger
                                            @elseif($role->name === 'admin') bg-primary
                                            @elseif($role->name === 'editor') bg-warning text-dark
                                            @elseif($role->name === 'viewer') bg-secondary
                                            @else bg-info text-dark
                                            @endif
                                            me-1 mb-1">
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </span>
                                    @empty
                                        <span class="badge bg-light text-dark border users-badge">{{ __('admin.users.no_role_badge') }}</span>
                                    @endforelse
                                </div>
                            </div>
                            
                            <div class="mb-2">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-calendar3 me-1"></i>{{ __('admin.users.created_at') }}
                                </small>
                                <div class="small">
                                    <div class="text-dark">{{ $user->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted">{{ $user->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            
                            @if(auth()->user()->hasRole('super-admin'))
                                <div class="d-flex gap-2 mt-3 pt-3 border-top flex-wrap">
                                    @if(auth()->id() !== $user->id && !$user->hasRole('super-admin'))
                                        <form action="{{ route('admin.users.impersonate', $user) }}" method="POST" class="d-inline flex-fill" onsubmit="return confirm('{{ __('admin.users.impersonate_confirm', ['name' => $user->name]) }}');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-warning w-100"
                                                    title="{{ __('admin.users.impersonate') }}">
                                                <i class="bi bi-person-check me-1"></i>{{ __('admin.users.impersonate') }}
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button"
                                            class="btn btn-sm btn-outline-success flex-fill"
                                            onclick="showTemporaryPassword({{ $user->id }})"
                                            title="عرض كلمة المرور المؤقتة"
                                            id="showPasswordBtn-mobile-{{ $user->id }}">
                                        <i class="bi bi-eye me-1"></i>كلمة المرور
                                    </button>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-info flex-fill"
                                            data-bs-toggle="modal"
                                            data-bs-target="#changePasswordModal-{{ $user->id }}"
                                            title="تغيير كلمة المرور">
                                        <i class="bi bi-key me-1"></i>تغيير
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="users-empty-state">
        <i class="bi bi-people"></i>
        <p class="mb-0">{{ __('admin.users.no_users') }}</p>
    </div>
@endif

