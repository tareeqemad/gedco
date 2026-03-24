@php
    $breadcrumbTitle = __('admin.breadcrumbs.home');
    $breadcrumbParent = __('admin.menu.dashboard');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')

@section('title', __('admin.menu.dashboard'))

@section('content')

    {{-- ===== Hero: compact welcome + smart alerts ===== --}}
    <div class="dash-hero mb-4">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="mb-1 fw-bold" style="color: #FFFFFF;">{{ __('admin.dashboard.welcome', ['name' => auth()->user()->name]) }}</h4>
                <p class="mb-0" style="color: rgba(255,255,255,0.6); font-size: 0.82rem;">{{ now()->translatedFormat('l، d F Y') }} &middot; {{ now()->format('h:i A') }}</p>
            </div>
            @if($pendingItems->count() > 0)
                <div class="d-flex flex-column gap-1">
                    @foreach($pendingItems as $item)
                        <a href="{{ $item['route'] }}" class="dash-hero-alert">
                            <i class="bi {{ $item['icon'] }}" style="color: {{ $item['color'] }};"></i>
                            <span>{{ $item['text'] }}</span>
                            <i class="bi bi-chevron-left" style="font-size: 0.65rem; opacity: 0.5;"></i>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- ===== Row 1: Core KPIs (4 cards) ===== --}}
    <div class="row mb-3">
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(15, 103, 198, 0.08); color: #0F67C6;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['users']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.users') }}</div>
                    @if($stats['users']['today'] > 0)
                        <span class="stat-chip" style="font-size: 0.6rem; color: #16A34A;">+{{ $stats['users']['today'] }} {{ __('admin.dashboard_ui.today') }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(22, 163, 74, 0.08); color: #16A34A;">
                    <i class="bi bi-newspaper"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['news']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.news') }}</div>
                    <div class="d-flex gap-1 mt-1">
                        <span class="stat-chip" style="font-size: 0.6rem; color: #16A34A;">{{ $stats['news']['published'] }} {{ __('admin.labels.published') }}</span>
                        @if($stats['news']['draft'] > 0)
                            <span class="stat-chip" style="font-size: 0.6rem; color: #D97706;">{{ $stats['news']['draft'] }} {{ __('admin.labels.draft') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(2, 132, 199, 0.08); color: #0284C7;">
                    <i class="bi bi-images"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['sliders']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.sliders') }}</div>
                    <span class="stat-chip" style="font-size: 0.6rem;">{{ $stats['sliders']['active'] }} {{ __('admin.labels.active') }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(217, 119, 6, 0.08); color: #D97706;">
                    <i class="bi bi-megaphone-fill"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['advertisements']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.advertisements') }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Row 2: Secondary KPIs (4 cards) ===== --}}
    <div class="row mb-4">
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(220, 38, 38, 0.08); color: #DC2626;">
                    <i class="bi bi-file-earmark-text-fill"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['tenders']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.tenders') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(124, 58, 237, 0.08); color: #7C3AED;">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['impact_stats']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.impact_stats') }}</div>
                    <span class="stat-chip" style="font-size: 0.6rem;">{{ $stats['impact_stats']['active'] }} {{ __('admin.labels.active') }}</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(13, 148, 136, 0.08); color: #0D9488;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['staff_profiles']['total']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard.staff_profiles') }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3 mb-3">
            <div class="dash-kpi">
                <div class="dash-kpi-icon" style="background: rgba(234, 88, 12, 0.08); color: #EA580C;">
                    <i class="bi bi-activity"></i>
                </div>
                <div>
                    <div class="dash-kpi-value">{{ number_format($stats['activities']['today']) }}</div>
                    <div class="dash-kpi-label">{{ __('admin.dashboard_ui.today_activity') }}</div>
                    <span class="stat-chip" style="font-size: 0.6rem;">{{ $stats['activities']['active_users_today'] }} {{ __('admin.dashboard.active_users') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Row 3: Chart + Quick Actions ===== --}}
    <div class="row mb-4">
        @can('activity-logs.view')
        @if($activitiesChart->count() > 0)
        <div class="col-12 col-lg-8 mb-3">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #24364A; font-size: 0.88rem;">
                        <i class="bi bi-bar-chart-fill" style="color: #5B7088;"></i>
                        {{ __('admin.dashboard.activities_chart') }}
                    </h6>
                    <canvas id="activitiesChart" height="70"></canvas>
                </div>
            </div>
        </div>
        @endif
        @endcan

        <div class="col-12 col-lg-{{ (auth()->user()->can('activity-logs.view') && $activitiesChart->count() > 0) ? '4' : '12' }} mb-3">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-3">
                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color: #24364A; font-size: 0.88rem;">
                        <i class="bi bi-lightning-charge-fill" style="color: #5B7088;"></i>
                        {{ __('admin.dashboard_ui.quick_links') }}
                    </h6>
                    <div class="row g-2">
                        @can('news.create')
                            <div class="col-6"><a href="{{ route('admin.news.create') }}" class="dash-quick-tile"><i class="bi bi-newspaper"></i><span>{{ __('admin.dashboard_ui.new_news') }}</span></a></div>
                        @endcan
                        @can('sliders.create')
                            <div class="col-6"><a href="{{ route('admin.sliders.create') }}" class="dash-quick-tile"><i class="bi bi-images"></i><span>{{ __('admin.dashboard_ui.new_slide') }}</span></a></div>
                        @endcan
                        @can('users.create')
                            <div class="col-6"><a href="{{ route('admin.users.create') }}" class="dash-quick-tile"><i class="bi bi-person-plus"></i><span>{{ __('admin.dashboard_ui.new_user') }}</span></a></div>
                        @endcan
                        @can('advertisements.create')
                            <div class="col-6"><a href="{{ route('admin.advertisements.create') }}" class="dash-quick-tile"><i class="bi bi-megaphone"></i><span>{{ __('admin.dashboard_ui.new_ad') }}</span></a></div>
                        @endcan
                        <div class="col-6"><a href="{{ route('admin.site-settings.edit', 1) }}" class="dash-quick-tile"><i class="bi bi-gear"></i><span>{{ __('admin.dashboard_ui.settings') }}</span></a></div>
                        @can('contact-messages.view')
                            <div class="col-6"><a href="{{ route('admin.contact-messages.index') }}" class="dash-quick-tile"><i class="bi bi-envelope"></i><span>{{ __('admin.dashboard_ui.messages') }}</span></a></div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Row 4: Recent News + Recent Activities ===== --}}
    <div class="row">
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-2">
                        <h6 class="fw-bold d-flex align-items-center gap-2 mb-0" style="color: #24364A; font-size: 0.88rem;">
                            <i class="bi bi-newspaper" style="color: #5B7088;"></i>
                            {{ __('admin.dashboard.recent_news') }}
                        </h6>
                        @can('news.view')
                            <a href="{{ route('admin.news.index') }}" class="dash-view-all">{{ __('admin.dashboard.view_all') }} →</a>
                        @endcan
                    </div>
                    @forelse($recentNews as $news)
                        <div class="dash-list-item">
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size: 0.83rem; color: #24364A;">{{ \Illuminate\Support\Str::limit($news->title, 55) }}</div>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <span class="stat-chip" style="font-size: 0.6rem; {{ $news->status === 'published' ? 'color: #16A34A;' : 'color: #D97706;' }}">
                                        {{ $news->status === 'published' ? __('admin.dashboard.published') : __('admin.dashboard.draft') }}
                                    </span>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $news->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            @can('news.edit')
                                <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            @endcan
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-folder2-open fs-3 d-block mb-2" style="color: #CDD9E3;"></i>
                            <small>{{ __('admin.dashboard.no_recent_news') }}</small>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        @can('activity-logs.view')
        <div class="col-12 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-0">
                    <div class="d-flex justify-content-between align-items-center p-3 pb-2">
                        <h6 class="fw-bold d-flex align-items-center gap-2 mb-0" style="color: #24364A; font-size: 0.88rem;">
                            <i class="bi bi-activity" style="color: #5B7088;"></i>
                            {{ __('admin.dashboard.recent_activities') }}
                        </h6>
                        <a href="{{ route('admin.activity-logs.index') }}" class="dash-view-all">{{ __('admin.dashboard.view_all') }} →</a>
                    </div>
                    <div style="max-height: 380px; overflow-y: auto;">
                        @forelse($recentActivities as $activity)
                            <div class="dash-list-item">
                                <div class="activity-avatar flex-shrink-0">
                                    {{ mb_strtoupper(mb_substr($activity->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                        <span class="fw-semibold" style="font-size: 0.8rem; color: #24364A;">{{ $activity->user->name ?? __('admin.dashboard_ui.deleted_user') }}</span>
                                        @php
                                            $actionIcon = match($activity->action) {
                                                'login' => 'bi-box-arrow-in-right',
                                                'logout' => 'bi-box-arrow-right',
                                                'create' => 'bi-plus-circle',
                                                'update' => 'bi-pencil',
                                                'delete' => 'bi-trash',
                                                'view' => 'bi-eye',
                                                default => 'bi-circle',
                                            };
                                        @endphp
                                        <span class="stat-chip activity-badge-{{ $activity->action }}" style="font-size: 0.6rem;">
                                            <i class="bi {{ $actionIcon }}"></i> {{ $activity->action }}
                                        </span>
                                    </div>
                                    <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">{{ $activity->description }}</p>
                                    <small class="text-muted" style="font-size: 0.68rem;">{{ $activity->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 text-muted">
                                <i class="bi bi-folder2-open fs-3 d-block mb-2" style="color: #CDD9E3;"></i>
                                <small>{{ __('admin.dashboard.no_recent_activities') }}</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endcan
    </div>
@endsection

@push('scripts')
@can('activity-logs.view')
@if($activitiesChart->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activitiesChart');
        if (!ctx) return;

        const chartData = @json($activitiesChart);
        const labels = Object.keys(chartData).map(date => {
            const d = new Date(date);
            return d.toLocaleDateString('ar-EG', { weekday: 'short', day: 'numeric' });
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    data: Object.values(chartData),
                    backgroundColor: 'rgba(15, 103, 198, 0.12)',
                    borderColor: '#0F67C6',
                    borderWidth: 1.5,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#24364A', padding: 10, cornerRadius: 8 }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { ticks: { font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    });
</script>
@endif
@endcan
@endpush
