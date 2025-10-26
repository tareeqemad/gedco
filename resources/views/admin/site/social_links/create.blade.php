@extends('layouts.admin')
@section('title','Create Social Link')
@section('content')
    <div class="card">
        <div class="card-header">Create</div>
        <div class="card-body">
            <form action="{{ route('admin.social-links.store') }}" method="post">
                @include('admin.site.social_links._form')
            </form>
        </div>
    </div>
@endsection
