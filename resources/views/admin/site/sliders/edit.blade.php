@extends('layouts.admin')
@section('title','Edit Slider')
@section('content')
    <div class="card"><div class="card-header">Edit</div><div class="card-body">
            <form action="{{ route('admin.sliders.update',$slider) }}" method="post" enctype="multipart/form-data">
                @include('admin.site.sliders._form',['slider'=>$slider])
            </form>
        </div></div>
@endsection
