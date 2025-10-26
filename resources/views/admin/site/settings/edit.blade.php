@extends('layouts.admin')
@section('title','Site Settings')

@section('content')
    <div class="card">
        <div class="card-header">إعدادات الموقع</div>
        <div class="card-body">
            <form method="post" action="{{ route('admin.site-settings.update',$setting->id) }}">
                @csrf @method('PUT')

                <div class="mb-3">
                    <label class="form-label">عنوان الفوتر</label>
                    <input name="footer_title_ar" class="form-control" value="{{ old('footer_title_ar',$setting->footer_title_ar) }}">
                    @error('footer_title_ar')<div class="text-danger">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Logo White Path</label>
                        <input name="logo_white_path" class="form-control" value="{{ old('logo_white_path',$setting->logo_white_path) }}">
                        @error('logo_white_path')<div class="text-danger">{{ $message }}</div>@enderror
                        <small class="text-muted d-block mt-1">مثال: assets/site/images/logo-white.webp</small>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">تواصل معنا</h5>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">البريد الإلكتروني</label>
                        <input name="contact_email" class="form-control" value="{{ old('contact_email',$setting->contact_email) }}">
                        @error('contact_email')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">الهاتف</label>
                        <input name="contact_phone" class="form-control" value="{{ old('contact_phone',$setting->contact_phone) }}">
                        @error('contact_phone')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">العنوان</label>
                        <input name="contact_address" class="form-control" value="{{ old('contact_address',$setting->contact_address) }}">
                        @error('contact_address')<div class="text-danger">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button class="btn btn-primary">حفظ</button>
            </form>
        </div>
    </div>
@endsection
