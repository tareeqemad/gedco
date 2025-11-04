{{-- resources/views/admin/site/tenders/edit.blade.php --}}
@extends('layouts.admin')
@section('title','تعديل عطاء #'.$tender->id)

@section('content')
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">تعديل عطاء #{{ $tender->id }}</h4>
            <a href="{{ route('admin.tenders.index') }}" class="btn btn-outline-secondary">رجوع للقائمة</a>
        </div>

        <div class="card">
            <form action="{{ route('admin.tenders.update',$tender->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card-body row g-3">

                    <div class="col-md-3">
                        <label class="form-label">MNEWS_ID</label>
                        <input type="number" name="mnews_id" class="form-control" value="{{ old('mnews_id',$tender->mnews_id) }}">
                        @error('mnews_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">COLUMN_NAME_1</label>
                        <input type="text" name="column_name_1" class="form-control" value="{{ old('column_name_1',$tender->column_name_1) }}">
                        @error('column_name_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">THE_DATE_1 (نصي)</label>
                        <input type="text" name="the_date_1" class="form-control" value="{{ old('the_date_1',$tender->the_date_1) }}">
                        @error('the_date_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">COULM_SERIAL</label>
                        <input type="number" name="coulm_serial" class="form-control" value="{{ old('coulm_serial',$tender->coulm_serial) }}">
                        @error('coulm_serial')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">EVENT_1</label>
                        <input type="text" name="event_1" class="form-control" value="{{ old('event_1',$tender->event_1) }}">
                        @error('event_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">THE_USER_1</label>
                        <input type="text" name="the_user_1" class="form-control" value="{{ old('the_user_1',$tender->the_user_1) }}">
                        @error('the_user_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">OLD_VALUE_1 (HTML/Narrative)</label>
                        <textarea name="old_value_1" class="form-control" rows="6">{{ old('old_value_1',$tender->old_value_1) }}</textarea>
                        @error('old_value_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label">NEW_VALUE_1 (HTML/Narrative)</label>
                        <textarea name="new_value_1" class="form-control" rows="6">{{ old('new_value_1',$tender->new_value_1) }}</textarea>
                        @error('new_value_1')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>

                </div>
                <div class="card-footer d-flex justify-content-end gap-2">
                    <button class="btn btn-primary" type="submit">تحديث</button>
                    <a class="btn btn-light" href="{{ route('admin.tenders.index') }}">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection
