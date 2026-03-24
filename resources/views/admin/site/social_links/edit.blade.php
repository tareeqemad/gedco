@php
    $breadcrumbTitle     = __('admin.social_links.edit_title');
    $breadcrumbParent    = __('admin.social_links.title');
    $breadcrumbParentUrl = route('admin.social-links.index');
@endphp
@extends('layouts.admin')
@section('title', __('admin.social_links.edit_title'))

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-share"
                :title="__('admin.social_links.edit_title')"
                :back-route="route('admin.social-links.index')" />

            <div class="card-body p-4">
                <form action="{{ route('admin.social-links.update',$link) }}" method="post">
                    @include('admin.site.social_links._form',['link'=>$link])
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection
