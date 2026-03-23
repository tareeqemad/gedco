@php
    $breadcrumbTitle     = 'Edit Footer Link';
    $breadcrumbParent    = 'Footer Links';
    $breadcrumbParentUrl = route('admin.footer-links.index');
@endphp
@extends('layouts.admin')
@section('title','Edit Footer Link')

@section('content')
    <div class="container-fluid p-0">
        <x-admin.card>
            <x-admin.card-header-form
                icon="bi-link-45deg"
                title="Edit Footer Link"
                :back-route="route('admin.footer-links.index')" />

            <div class="card-body p-4">
                <form action="{{ route('admin.footer-links.update',$link) }}" method="post">
                    @include('admin.site.footer_links._form',['link'=>$link])
                </form>
            </div>
        </x-admin.card>
    </div>
@endsection
