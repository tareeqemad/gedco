@extends('layouts.admin')
@section('title','Create Slider')
@section('content')
    <div class="card"><div class="card-header">Create</div><div class="card-body">
            <form action="{{ route('admin.sliders.store') }}" method="post" enctype="multipart/form-data">
                @include('admin.site.sliders._form')
            </form>
        </div></div>
@endsection
