@extends('layouts.admin')
@section('content')
    <h3>تعديل: {{ $job->title }}</h3>
    <form action="{{ route('admin.jobs.update',$job) }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf @method('PUT')
        @include('admin.jobs.form')
    </form>
@endsection

