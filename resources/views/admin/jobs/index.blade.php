@php
    $breadcrumbTitle     = __('admin.jobs.title');
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title', __('admin.jobs.title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-briefcase"
                :title="__('admin.jobs.title')"
                :create-route="route('admin.jobs.create')"
                :create-label="__('admin.jobs.add')"
                create-permission="jobs.create" />

            <!-- Desktop Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">#</th>
                        <th>{{ __('admin.labels.image') }}</th>
                        <th>{{ __('admin.labels.title') }}</th>
                        <th class="text-center">{{ __('admin.labels.enabled') }}?</th>
                        <th class="text-center">{{ __('admin.labels.order') }}</th>
                        <th class="text-center" style="width: 180px;">{{ __('admin.jobs.controls') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($jobs as $job)
                        <tr>
                            <td class="text-center text-muted">{{ $job->id }}</td>
                            <td>
                                @if($job->image)
                                    <img src="{{ asset('storage/'.$job->image) }}" alt="" style="height:50px">
                                @endif
                            </td>
                            <td>{{ $job->title }}</td>
                            <td class="text-center">{!! $job->is_active ? '<span class="badge bg-success">' . __('admin.jobs.yes') . '</span>' : '<span class="badge bg-secondary">' . __('admin.jobs.no') . '</span>' !!}</td>
                            <td class="text-center">{{ $job->sort }}</td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    @can('jobs.edit')
                                        <a href="{{ route('admin.jobs.edit',$job) }}" class="btn btn-sm btn-outline-warning rounded-3" title="{{ __('admin.actions.edit') }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                    @endcan
                                    @can('jobs.delete')
                                        <form action="{{ route('admin.jobs.destroy',$job) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3" title="{{ __('admin.actions.delete') }}" data-confirm-delete>
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-briefcase display-4 text-muted opacity-50 d-block mb-3"></i>
                                <h5 class="text-muted mb-2">{{ __('admin.jobs.no_data') }}</h5>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($jobs->hasPages())
                <div class="px-3 py-2" style="border-top: 1px solid #E6ECF2;">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 small">
                        <div class="text-muted">
                            {{ __('admin.ui.showing_range', ['first' => $jobs->firstItem(), 'last' => $jobs->lastItem(), 'total' => $jobs->total()]) }}
                        </div>
                        {{ $jobs->links() }}
                    </div>
                </div>
            @endif
        </x-admin.card>
    </div>
@endsection
