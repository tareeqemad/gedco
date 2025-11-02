@extends('layouts.admin')
@section('title', 'عرض الإعلان')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h5>تفاصيل الإعلان #{{ $ad->ID_ADVER }}</h5>
            <a href="{{ route('admin.advertisements.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tr><th>العنوان</th><td>{{ $ad->TITLE }}</td></tr>
                <tr><th>النص</th><td>{!! nl2br(e($ad->BODY ?? '—')) !!}</td></tr>
                <tr><th>تاريخ الإعلان</th><td>{{ $ad->DATE_NEWS?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</td></tr>
                <tr><th>أضيف بواسطة</th><td>{{ $ad->INSERT_USER }} <br><small>{{ $ad->INSERT_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') }}</small></td></tr>
                <tr><th>آخر تحديث</th><td>{{ $ad->UPDATE_USER ?? '—' }} <br><small>{{ $ad->UPDATE_DATE?->timezone('Asia/Hebron')->format('d/m/Y H:i') ?? '—' }}</small></td></tr>
                <tr>
                    <th>ملف PDF</th>
                    <td>
                        @if($ad->PDF)
                            <a href="{{ Storage::url($ad->PDF) }}" target="_blank" class="btn btn-primary btn-sm">عرض الملف</a>
                        @else
                            <span class="text-muted">لا يوجد ملف</span>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('admin.advertisements.edit', $ad) }}" class="btn btn-warning">تعديل</a>
        </div>
    </div>
@endsection
