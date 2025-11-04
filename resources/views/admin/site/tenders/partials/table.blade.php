@php
    use Illuminate\Support\Str;
    $mode = $viewMode ?? 'compact';
@endphp

@if($mode === 'compact')
    <div class="table-responsive">
        <table class="table table-striped align-middle mb-0">
            <thead class="table-light">
            <tr>
                <th style="width:70px">ID</th>
                <th style="width:100px">MNEWS_ID</th>
                <th style="width:160px">COLUMN_NAME_1</th>
                <th style="width:260px">OLD_VALUE_1</th>
                <th style="width:260px">NEW_VALUE_1</th>
                <th style="width:160px">THE_DATE_1</th>
                <th style="width:120px">THE_USER_1</th>
                <th style="width:110px">EVENT_1</th>
                <th class="text-end" style="width:190px">إجراءات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($tenders as $t)
                @php
                    // اختصار آمن: إزالة الوسوم ثم limit
                    $oldShort = Str::limit(strip_tags((string)$t->old_value_1), 120);
                    $newShort = Str::limit(strip_tags((string)$t->new_value_1), 120);
                @endphp
                <tr>
                    <td>{{ $t->id }}</td>
                    <td>{{ $t->mnews_id }}</td>
                    <td title="{{ $t->column_name_1 }}">{{ Str::limit($t->column_name_1, 40) }}</td>
                    <td title="{{ strip_tags((string)$t->old_value_1) }}">{{ $oldShort }}</td>
                    <td title="{{ strip_tags((string)$t->new_value_1) }}">{{ $newShort }}</td>
                    <td>{{ $t->the_date_1 }}</td>
                    <td>{{ $t->the_user_1 }}</td>
                    <td title="{{ $t->event_1 }}">{{ Str::limit($t->event_1, 30) }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.tenders.show',$t->id) }}" class="btn btn-sm btn-outline-primary">عرض</a>
                        @can('tenders.edit')
                            <a href="{{ route('admin.tenders.edit',$t->id) }}" class="btn btn-sm btn-outline-warning">تعديل</a>
                        @endcan
                        @can('tenders.delete')
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="delTender({{ $t->id }})">حذف</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">لا توجد بيانات</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
@else
    {{-- وضع العرض الكامل كما أعطيتُك سابقًا يعرض HTML داخل div مع scroll --}}
@endif
