{{-- Flash Messages Component --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 alert-success d-none" role="alert" data-toast-message="{{ session('success') }}">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>{{ __('admin.notifications.success') }}:</strong> {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 alert-danger d-none" role="alert" data-toast-message="{{ session('error') }}">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>{{ __('admin.notifications.error') }}:</strong> {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-3 alert-warning d-none" role="alert" data-toast-message="{{ session('warning') }}">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>{{ __('admin.notifications.warning') }}:</strong> {{ session('warning') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm mb-3 alert-info d-none" role="alert" data-toast-message="{{ session('info') }}">
        <div class="d-flex align-items-center">
            <i class="bi bi-info-circle-fill me-2 fs-5"></i>
            <div class="flex-grow-1">
                <strong>{{ __('admin.notifications.info') }}:</strong> {{ session('info') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3 alert-danger d-none" role="alert" data-toast-message="{{ $errors->first() }}">
        <div class="d-flex align-items-start">
            <i class="bi bi-x-circle-fill me-2 fs-5 mt-1"></i>
            <div class="flex-grow-1">
                <strong>{{ __('admin.notifications.validation_error') }}:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif

{{-- Toast Container --}}
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;" id="toast-container">
    <!-- Toasts will be inserted here via JavaScript -->
</div>
