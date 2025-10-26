@extends('layouts.admin')
@section('title','صلاحية جديدة')

@section('content')
    <div class="card">
        <div class="card-header"><h5>إنشاء صلاحية</h5></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.permissions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">اسم الصلاحية (مثال: users.view)</label>
                    <input type="text" name="name" class="form-control" dir="ltr" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Guard</label>
                    <select name="guard_name" class="form-select">
                        <option value="web" selected>web</option>
                        <option value="api">api</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>
@endsection
