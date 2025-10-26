@extends('layouts.admin')
@section('title','تعديل صلاحية')

@section('content')
    <div class="card">
        <div class="card-header"><h5>تعديل الصلاحية</h5></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.permissions.update',$permission->id) }}" method="POST">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">اسم الصلاحية</label>
                    <input type="text" name="name" class="form-control" dir="ltr" value="{{ old('name',$permission->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Guard</label>
                    <select name="guard_name" class="form-select">
                        <option value="web" {{ $permission->guard_name==='web'?'selected':'' }}>web</option>
                        <option value="api" {{ $permission->guard_name==='api'?'selected':'' }}>api</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">تحديث</button>
            </form>
        </div>
    </div>
@endsection
