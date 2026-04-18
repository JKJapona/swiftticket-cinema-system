<div class="toast-container-custom position-fixed top-0 end-0 p-3" style="z-index: 10050;">
        @if(session('success'))
            <div class="alert alert-dismissible fade show shadow-sm border-0 mb-2 p-3" role="alert" 
                 style="background: white; border-left: 4px solid #10b981 !important; min-width: 300px; border-radius: 8px;">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1 me-4">
                        <p class="mb-0 text-slate-900 fw-800" style="font-size: 14px;">Success</p>
                        <p class="mb-0 text-slate-500 fw-600" style="font-size: 12px; line-height: 1.4;">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.6rem;"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-dismissible fade show shadow-sm border-0 mb-2 p-3" role="alert" 
                 style="background: white; border-left: 4px solid #ef4444 !important; min-width: 300px; border-radius: 8px;">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-exclamation-circle-fill" style="color: #ef4444; font-size: 1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1 me-4">
                        <p class="mb-0 text-slate-900 fw-800" style="font-size: 14px;">System Error</p>
                        <p class="mb-0 text-slate-500 fw-600" style="font-size: 12px; line-height: 1.4;">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.6rem;"></button>
                </div>
            </div>
        @endif
    </div>