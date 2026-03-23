@php
    $breadcrumbTitle = __('admin.breadcrumbs.home');
    $breadcrumbParent = __('admin.menu.dashboard');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')

@section('title', __('admin.menu.dashboard'))
@section('page-title', __('admin.menu.dashboard'))


@section('content')
    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div>
                        <h2 class="mb-2 fw-bold">{{ __('admin.dashboard.welcome', ['name' => auth()->user()->name]) }} 👋</h2>
                        <p class="mb-0 opacity-90">{{ __('admin.dashboard.welcome_subtitle') }} - {{ now()->translatedFormat('l، d F Y') }}</p>
                    </div>
                    <div class="text-end">
                        <div class="fs-4 fw-bold">{{ now()->format('H:i') }}</div>
                        <small class="opacity-75">{{ now()->format('A') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Users -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.users') }}</div>
                            <div class="stat-value">{{ number_format($stats['users']['total']) }}</div>
                            @if($stats['users']['today'] > 0)
                                <div class="stat-change text-success">
                                    <i class="bi bi-arrow-up"></i> +{{ $stats['users']['today'] }} {{ __('admin.dashboard.new_today') }}
                                </div>
                            @endif
                        </div>
                        <div class="stat-icon stat-icon-primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- News -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.news') }}</div>
                            <div class="stat-value">{{ number_format($stats['news']['total']) }}</div>
                            <div class="stat-change">
                                <span class="badge bg-success badge-custom">{{ $stats['news']['published'] }} {{ __('admin.dashboard.published') }}</span>
                                @if($stats['news']['draft'] > 0)
                                    <span class="badge bg-warning badge-custom ms-1">{{ $stats['news']['draft'] }} {{ __('admin.dashboard.draft') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="stat-icon stat-icon-success">
                            <i class="bi bi-newspaper"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sliders -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card info">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.sliders') }}</div>
                            <div class="stat-value">{{ number_format($stats['sliders']['total']) }}</div>
                            <div class="stat-change">
                                <span class="text-info">
                                    <i class="bi bi-check-circle"></i> {{ $stats['sliders']['active'] }} {{ __('admin.dashboard.active') }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon stat-icon-info">
                            <i class="bi bi-images"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advertisements -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.advertisements') }}</div>
                            <div class="stat-value">{{ number_format($stats['advertisements']['total']) }}</div>
                            @if($stats['advertisements']['today'] > 0)
                                <div class="stat-change text-warning">
                                    <i class="bi bi-arrow-up"></i> +{{ $stats['advertisements']['today'] }} {{ __('admin.dashboard.new_today') }}
                                </div>
                            @endif
                        </div>
                        <div class="stat-icon stat-icon-warning">
                            <i class="bi bi-megaphone-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Statistics -->
    <div class="row mb-4">
        <!-- Tenders -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.tenders') }}</div>
                            <div class="stat-value">{{ number_format($stats['tenders']['total']) }}</div>
                            @if($stats['tenders']['today'] > 0)
                                <div class="stat-change text-danger">
                                    <i class="bi bi-arrow-up"></i> +{{ $stats['tenders']['today'] }} {{ __('admin.dashboard.new_today') }}
                                </div>
                            @endif
                        </div>
                        <div class="stat-icon stat-icon-danger">
                            <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Stats -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card purple">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.impact_stats') }}</div>
                            <div class="stat-value">{{ number_format($stats['impact_stats']['total']) }}</div>
                            <div class="stat-change">
                                <span class="text-purple-dashboard">
                                    <i class="bi bi-check-circle"></i> {{ $stats['impact_stats']['active'] }} {{ __('admin.dashboard.active') }}
                                </span>
                            </div>
                        </div>
                        <div class="stat-icon stat-icon-purple">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Staff Profiles -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card teal">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.staff_profiles') }}</div>
                            <div class="stat-value">{{ number_format($stats['staff_profiles']['total']) }}</div>
                            <div class="stat-change">
                                <small class="text-teal-dashboard">{{ $stats['staff_profiles']['working'] }} {{ __('admin.dashboard.working') }}</small>
                            </div>
                        </div>
                        <div class="stat-icon stat-icon-teal">
                            <i class="bi bi-person-badge-fill"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activities -->
        <div class="col-12 col-sm-6 col-lg-3 mb-3">
            <div class="card stat-card orange">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="stat-label">{{ __('admin.dashboard.activities') }}</div>
                            <div class="stat-value">{{ number_format($stats['activities']['today']) }}</div>
                            <div class="stat-change">
                                <small class="text-orange-dashboard">{{ $stats['activities']['active_users_today'] }} {{ __('admin.dashboard.active_users') }}</small>
                            </div>
                        </div>
                        <div class="stat-icon stat-icon-orange">
                            <i class="bi bi-activity"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Items -->
    <div class="row">
        <!-- Chart -->
        @if(auth()->user()->hasRole('super-admin') && $activitiesChart->count() > 0)
        <div class="col-12 col-lg-8 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
                        {{ __('admin.dashboard.activities_chart') }}
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="activitiesChart" height="80"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Links -->
        <div class="col-12 col-lg-{{ auth()->user()->hasRole('super-admin') && $activitiesChart->count() > 0 ? '4' : '12' }} mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-link-45deg me-2 text-primary"></i>
                        {{ __('admin.dashboard.quick_links') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @can('news.create')
                            <a href="{{ route('admin.news.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('admin.dashboard.add_new_news') }}
                            </a>
                        @endcan
                        @can('sliders.create')
                            <a href="{{ route('admin.sliders.create') }}" class="btn btn-outline-info">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('admin.dashboard.add_new_slider') }}
                            </a>
                        @endcan
                        @can('advertisements.create')
                            <a href="{{ route('admin.advertisements.create') }}" class="btn btn-outline-warning">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('admin.dashboard.add_new_advertisement') }}
                            </a>
                        @endcan
                        @can('tenders.create')
                            <a href="{{ route('admin.tenders.create') }}" class="btn btn-outline-danger">
                                <i class="bi bi-plus-circle me-2"></i> {{ __('admin.dashboard.add_new_tender') }}
                            </a>
                        @endcan
                        @can('activity-logs.view')
                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-activity me-2"></i> {{ __('admin.dashboard.activity_logs') }}
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent News & Activities -->
    <div class="row">
        <!-- Recent News -->
        <div class="col-12 col-lg-6 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-newspaper me-2 text-success"></i>
                        {{ __('admin.dashboard.recent_news') }}
                    </h5>
                    @can('news.view')
                        <a href="{{ route('admin.news.index') }}" class="btn btn-sm btn-outline-success">
                            {{ __('admin.dashboard.view_all') }}
                        </a>
                    @endcan
                </div>
                <div class="card-body p-0">
                    @forelse($recentNews as $news)
                        <div class="recent-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-semibold">{{ \Illuminate\Support\Str::limit($news->title, 50) }}</h6>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="badge bg-{{ $news->status === 'published' ? 'success' : 'warning' }} badge-custom">
                                            {{ $news->status === 'published' ? __('admin.dashboard.published') : __('admin.dashboard.draft') }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-clock"></i> {{ $news->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                @can('news.edit')
                                    <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            {{ __('admin.dashboard.no_recent_news') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activities (Permission Based) -->
        @can('activity-logs.view')
        <div class="col-12 col-lg-6 mb-4">
            <div class="card chart-card border-0 shadow-sm activity-card-wrapper">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center activity-card-header">
                    <h5 class="mb-0 fw-bold d-flex align-items-center">
                        <i class="bi bi-activity me-2 fs-5"></i>
                        <span>{{ __('admin.dashboard.recent_activities') }}</span>
                    </h5>
                    <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-sm btn-light btn-hover-scale">
                        <i class="bi bi-arrow-left me-1"></i>
                        {{ __('admin.dashboard.view_all') }}
                    </a>
                </div>
                <div class="card-body p-0 activity-card-body">
                    @forelse($recentActivities as $activity)
                        <div class="activity-item">
                            <div class="activity-item-content">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Avatar -->
                                    <div class="activity-avatar flex-shrink-0">
                                        {{ strtoupper(substr($activity->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-grow-1 activity-content-wrapper">
                                        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                                            <span class="fw-semibold text-dark activity-user-name">{{ $activity->user->name ?? 'مستخدم محذوف' }}</span>
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
                                                $badgeClass = 'activity-badge-' . $activity->action;
                                            @endphp
                                            <span class="badge d-flex align-items-center gap-1 activity-badge {{ $badgeClass }}">
                                                <i class="bi {{ $actionIcon }}"></i>
                                                <span>{{ $activity->action }}</span>
                                            </span>
                                        </div>
                                        
                                        <p class="mb-2 text-muted activity-description">
                                            {{ $activity->description }}
                                        </p>
                                        
                                        <div class="d-flex align-items-center gap-3 flex-wrap activity-meta">
                                            <span class="text-muted d-flex align-items-center gap-1">
                                                <i class="bi bi-clock activity-meta-icon"></i>
                                                <span>{{ $activity->created_at->diffForHumans() }}</span>
                                            </span>
                                            @if($activity->route_name)
                                                <span class="text-muted d-flex align-items-center gap-1">
                                                    <i class="bi bi-link-45deg activity-meta-icon"></i>
                                                    <span class="activity-code">{{ Str::limit($activity->route_name, 30) }}</span>
                                                </span>
                                            @endif
                                            @if($activity->ip_address)
                                                <span class="text-muted d-flex align-items-center gap-1">
                                                    <i class="bi bi-geo-alt activity-meta-icon"></i>
                                                    <span class="activity-code">{{ $activity->ip_address }}</span>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-inbox empty-state-icon"></i>
                            </div>
                            <p class="text-muted mb-0">{{ __('admin.dashboard.no_recent_activities') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endcan
    </div>
@endsection

@push('scripts')
@if(auth()->user()->hasRole('super-admin') && $activitiesChart->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('activitiesChart');
        if (!ctx) return;

        const chartData = @json($activitiesChart);
        const labels = Object.keys(chartData).map(date => {
            const d = new Date(date);
            return d.toLocaleDateString('ar-EG', { weekday: 'short', day: 'numeric', month: 'short' });
        });
        const data = Object.values(chartData);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                            label: '{{ __('admin.dashboard.activities') }}',
                    data: data,
                    borderColor: '#35516F',
                    backgroundColor: 'rgba(53, 81, 111, 0.1)',
                    tension: 0.4,
                    fill: true,
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#35516F',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 13 },
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endpush