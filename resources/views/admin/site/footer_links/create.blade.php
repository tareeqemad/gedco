@extends('layouts.admin')
@section('title','Create Footer Link')
@section('content')
    <div class="card">
        <div class="card-header">Create</div>
        <div class="card-body">
            <form action="{{ route('admin.footer-links.store') }}" method="post">
                @include('admin.site.footer_links._form')
            </form>
        </div>
    </div>
@endsection
