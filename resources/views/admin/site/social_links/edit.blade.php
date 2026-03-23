@php
    $breadcrumbTitle     = 'Edit Social Link';
    $breadcrumbParent    = 'Social Links';
    $breadcrumbParentUrl = route('admin.social-links.index');
@endphp
@extends('layouts.admin')
@section('title','Edit Social Link')

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-share"
                title="Edit Social Link"
                :back-route="route('admin.social-links.index')" />

            <div class="card-body p-4">
                <form action="{{ route('admin.social-links.update',$link) }}" method="post">
                    @include('admin.site.social_links._form',['link'=>$link])
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection
