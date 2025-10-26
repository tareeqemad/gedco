@extends('layouts.admin')
@section('title','إضافة مستخدم')

@section('content')
    <div class="card">
        <div class="card-header"><h5>إضافة مستخدم جديد</h5></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">الاسم</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">البريد الإلكتروني</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">الدور</label>
                    <select name="role_id" class="form-select">
                        <option value="">— بدون دور —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id')==$role->id ? 'selected':'' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">صلاحيات إضافية</label>
                    <div class="row g-2">
                        @foreach($permissions as $perm)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="{{ $perm->id }}" id="perm{{ $perm->id }}"
                                        {{ in_array($perm->id, old('permissions', [])) ? 'checked':'' }}>
                                    <label class="form-check-label" for="perm{{ $perm->id }}">{{ $perm->name }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>
@endsection
