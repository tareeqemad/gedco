@php
    $breadcrumbTitle     = 'Social Links';
    $breadcrumbParent    = __('admin.breadcrumbs.home');
    $breadcrumbParentUrl = route('admin.dashboard');
@endphp
@extends('layouts.admin')
@section('title','Social Links')

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-index
                icon="bi-share"
                title="Social Links"
                :create-route="route('admin.social-links.create')"
                create-label="+ جديد" />

            <div class="card-body p-3">
                <div class="table-responsive">
                    <table class="table table-hover table-sm align-middle mb-0">
                        <thead class="bg-light">
                        <tr>
                            <th>#</th><th>Platform</th><th>Icon</th><th>URL</th><th>Order</th><th>Active</th><th class="text-end">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($links as $l)
                            <tr>
                                <td>{{ $l->id }}</td>
                                <td><span class="badge bg-dark">{{ $l->platform }}</span></td>
                                <td><code>{{ $l->icon_class }}</code></td>
                                <td><small>{{ $l->url }}</small></td>
                                <td>{{ $l->sort_order }}</td>
                                <td>{!! $l->is_active ? '✅' : '❌' !!}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.social-links.edit',$l) }}" class="btn btn-sm btn-outline-warning rounded-3">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.social-links.destroy',$l) }}" method="post" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-3" onclick="return confirm('حذف؟')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">لا توجد روابط</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $links->links() }}
            </div>
        </x-admin.card>
    </div>
@endsection
