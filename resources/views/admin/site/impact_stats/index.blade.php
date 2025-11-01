@extends('layouts.admin')

@section('title', 'إحصائيات الخسائر')

@section('content')
    <div class="container-fluid py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-3">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-muted text-decoration-none">الرئيسية</a></li>
                <li class="breadcrumb-item active text-primary fw-semibold" aria-current="page">إحصائيات الخسائر</li>
            </ol>
        </nav>

        <!-- العنوان -->
        <h1 class="h3 mb-4 text-gray-800 d-flex align-items-center gap-2">
            <i class="fas fa-chart-line text-primary"></i>
            إحصائيات الخسائر
        </h1>

        <!-- رسالة نجاح -->
        @if(session('ok'))
            <div class="alert alert-success alert-dismissible fade show rounded-pill border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('ok') }}
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- الكروت -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="stats-cards">
            @foreach($items as $it)
                <div class="col" data-id="{{ $it->id }}" data-order="{{ $it->sort_order }}">
                    <div class="card h-100 border-0 shadow-sm rounded-3 hover-lift position-relative drag-handle">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-light text-dark rounded-pill px-3 py-1 fw-semibold sort-badge">#{{ $it->sort_order }}</span>
                                <span class="status-badge badge rounded-pill px-3 py-2 {{ $it->is_active ? 'bg-teal text-white' : 'bg-secondary' }}" data-id="{{ $it->id }}">
                                <i class="fas fa-circle small me-1"></i>
                                {{ $it->is_active ? 'مفعل' : 'معطل' }}
                            </span>
                            </div>
                            <h5 class="card-title mb-2 fw-bold text-dark">{{ $it->title_ar }}</h5>
                            <p class="display-6 fw-bold text-danger mb-3">
                                ${{ number_format($it->amount_usd, 1) }}
                            </p>
                            <div class="d-flex gap-2">
                                <button class="toggle-btn btn btn-sm w-100 d-flex align-items-center justify-content-center gap-1 {{ $it->is_active ? 'btn-teal' : 'btn-outline-secondary' }}"
                                        data-id="{{ $it->id }}" data-active="{{ $it->is_active ? '1' : '0' }}">
                                    <i class="fas fa-power-off"></i>
                                    {{ $it->is_active ? 'إيقاف' : 'تفعيل' }}
                                </button>

                                <!-- تعديل -->
                                <button class="btn btn-sm btn-warning flex-fill edit-btn d-flex align-items-center justify-content-center gap-1"
                                        data-id="{{ $it->id }}"
                                        data-title="{{ $it->title_ar }}"
                                        data-amount="{{ $it->amount_usd }}"
                                        data-active="{{ $it->is_active ? '1' : '0' }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="fas fa-edit"></i> تعديل
                                </button>

                                <!-- حذف -->
                                <button class="btn btn-sm btn-danger delete-btn d-flex align-items-center justify-content-center"
                                        data-id="{{ $it->id }}"
                                        data-title="{{ $it->title_ar }}"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="drag-handle-icon position-absolute top-0 end-0 p-3 text-muted opacity-50">
                            <i class="fas fa-grip-lines fa-lg"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Modal الإضافة (واحد فقط) -->
        <div class="modal fade" id="createModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-lg">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i> إضافة إحصائية جديدة</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="createForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">العنوان</label>
                                <input type="text" name="title_ar" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">القيمة (USD)</label>
                                <input type="number" step="0.1" name="amount_usd" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="newActive" checked>
                                <label class="form-check-label" for="newActive">مفعل</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-success save-btn">
                                <span class="spinner d-none"><i class="fas fa-spinner fa-spin"></i></span>
                                إضافة
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal التعديل (واحد فقط) -->
        <div class="modal fade" id="editModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3 shadow-lg">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i> تعديل الإحصائية</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editForm" class="edit-form">
                        @csrf @method('PATCH')
                        <input type="hidden" name="id" id="editId">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">العنوان</label>
                                <input type="text" name="title_ar" id="editTitle" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">القيمة (USD)</label>
                                <input type="number" step="0.1" name="amount_usd" id="editAmount" class="form-control" required>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editActive">
                                <label class="form-check-label" for="editActive">مفعل</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                            <button type="submit" class="btn btn-primary save-btn">
                                <span class="spinner d-none"><i class="fas fa-spinner fa-spin"></i></span>
                                حفظ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal الحذف (واحد فقط) -->
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-3">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="fas fa-trash me-2"></i> تأكيد الحذف</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>هل أنت متأكد من حذف: <strong id="deleteTitle"></strong>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <form id="deleteForm" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger delete-confirm">
                                <span class="spinner d-none"><i class="fas fa-spinner fa-spin"></i></span>
                                حذف
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- زر إضافة عائم -->
        <button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4 shadow-lg d-flex align-items-center justify-content-center"
                style="width:60px;height:60px;z-index:1050;background:#1c3d5a;border:none;"
                data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus fa-lg"></i>
        </button>
    </div>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @push('styles')
        <style>
            :root{--teal:#20c997;--primary:#1c3d5a}
            .breadcrumb-item a{color:#6c757d!important}
            .breadcrumb-item a:hover{color:var(--primary)!important;text-decoration:underline!important}
            .breadcrumb-item.active{color:var(--primary)!important;font-weight:600}
            .card{border-radius:1.2rem!important;transition:all .3s}
            .hover-lift:hover{transform:translateY(-6px);box-shadow:0 1.5rem 3rem rgba(0,0,0,.15)!important}
            .drag-handle,.drag-handle-icon{cursor:move!important;user-select:none}
            .drag-handle-icon:hover{opacity:.8}
            .btn-teal{background:var(--teal);border-color:var(--teal);color:#fff}
            .btn-teal:hover{background:#1baa80;border-color:#1baa80}
            .bg-teal{background:var(--teal)!important}
            .sort-badge{min-width:40px;text-align:center}
            .save-btn,.delete-confirm{position:relative}
            .spinner{position:absolute;right:10px;top:50%;transform:translateY(-50%)}
        </style>
    @endpush

    @push('scripts')
        @vite(['resources/js/impact-stats.js'])
    @endpush
@endsection
