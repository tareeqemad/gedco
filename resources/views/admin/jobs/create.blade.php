@extends('layouts.admin')
@section('content')
    <h3>إضافة وظيفة</h3>
    <form action="{{ route('admin.jobs.store') }}" method="POST" enctype="multipart/form-data" class="mt-3">
        @csrf
        @include('admin.jobs.form')
    </form>
@endsection

