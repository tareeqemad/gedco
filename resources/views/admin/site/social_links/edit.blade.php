@extends('layouts.admin')
@section('title','Edit Social Link')
@section('content')
    <div class="card">
        <div class="card-header">Edit</div>
        <div class="card-body">
            <form action="{{ route('admin.social-links.update',$link) }}" method="post">
                @include('admin.site.social_links._form',['link'=>$link])
            </form>
        </div>
    </div>
@endsection
