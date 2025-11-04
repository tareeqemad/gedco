{{-- resources/views/admin/profile/edit.blade.php --}}
@extends('layouts.admin')

@section('title', 'ملفي الشخصي')

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- رسائل النجاح -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('success_password'))
                <div class="alert alert-info alert-dismissible fade show">
                    <i class="fas fa-key me-2"></i> {{ session('success_password') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">

                <!-- الصورة الثابتة (بدون أي تفاعل) -->
                <div class="col-xl-4">
                    <div class="card border-0 shadow">
                        <div class="card-body text-center pt-0">
                            <div class="mt-n5">
                                <img src="{{ $user->avatar_url }}"
                                     class="avatar-xl rounded-circle img-thumbnail border-4 border-white shadow"
                                     alt="الصورة الشخصية"
                                     style="pointer-events: none;"> <!-- ما يقدرش يضغط -->
                            </div>
                            <h4 class="mt-3">{{ $user->name }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            <span class="badge bg-success fs-6">مشرف</span>
                        </div>
                    </div>
                </div>

                <!-- الفورمات (بدون أي input للصورة) -->
                <div class="col-xl-8">
                    <div class="card border-0 shadow">
                        <div class="card-body">

                            <form action="{{ route('admin.profile.update') }}" method="POST">
                                @csrf @method('PUT')

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">الاسم</label>
                                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                               class="form-control form-control-lg" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">البريد الإلكتروني</label>
                                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                               class="form-control form-control-lg" required>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save me-2"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </form>

                            <hr class="my-5">

                            <form action="{{ route('admin.profile.password') }}" method="POST">
                                @csrf @method('PUT')
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <input type="password" name="current_password" placeholder="كلمة المرور الحالية"
                                               class="form-control form-control-lg" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="password" name="password" placeholder="الجديدة"
                                               class="form-control form-control-lg" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="password" name="password_confirmation" placeholder="تأكيد"
                                               class="form-control form-control-lg" required>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-danger btn-lg px-5">
                                        <i class="fas fa-key me-2"></i> تغيير كلمة المرور
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
