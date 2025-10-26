@extends('layouts.admin')
@section('title','Edit Footer Link')
@section('content')
    <div class="card">
        <div class="card-header">Edit</div>
        <div class="card-body">
            <form action="{{ route('admin.footer-links.update',$link) }}" method="post">
                @include('admin.site.footer_links._form',['link'=>$link])
            </form>
        </div>
    </div>
@endsection
