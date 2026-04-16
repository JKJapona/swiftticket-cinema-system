<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | SwiftTicket</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body class="bg-light">
    <div id="top-progress-bar"></div>

    <div class="d-flex">
        @include('layouts.admin-sidebar')

        <main id="admin-main-wrapper" class="admin-main-content flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

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

    <div class="modal fade" id="confirmActionModal" tabindex="-1" aria-hidden="true" style="z-index: 10002;">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
                <div class="modal-header bg-slate-50 border-0 px-4 py-2 justify-content-center">
                    <p class="text-slate-500 caption mb-0 fw-700">System Confirmation</p>
                </div>

                <div class="modal-body p-4 text-center bg-white">
                    <div id="confirm-icon-bg" class="mx-auto mb-3 d-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 64px; height: 64px; border-radius: 18px;">
                        <i id="confirm-icon" class="bi fs-2"></i>
                    </div>

                    <h5 id="confirm-title" class="fw-800 text-slate-900 mb-2" style="font-size: 18px !important;"></h5>
                    <p id="confirm-message" class="text-slate-500 fw-600 mb-4" style="font-size: 13px; line-height: 1.5;"></p>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light text-slate-500 fw-700 w-100 rounded-2 border-0 py-2 btn-sm d-flex align-items-center justify-content-center" 
                                style="background-color: var(--slate-100);" 
                                data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" id="confirm-submit-btn" 
                                class="btn btn-primary bg-swift-blue border-0 fw-700 w-100 rounded-2 py-2 shadow-sm btn-sm d-flex align-items-center justify-content-center">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.getElementById('top-progress-bar');

        function startLoadingBar() {
            progressBar.style.display = 'block';
            progressBar.style.width = '0%';
            void progressBar.offsetWidth;
            progressBar.style.transition = 'width 2s cubic-bezier(0.1, 0.05, 0, 1)';
            progressBar.style.width = '90%';
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.href && !link.href.includes('#') && 
                !link.getAttribute('target') && !link.hasAttribute('data-bs-toggle') &&
                !link.classList.contains('logout-btn')) {
                startLoadingBar();
            }
        });

        document.addEventListener('submit', function (e) {
            const form = e.target;
            const submitBtn = form.querySelector('[type="submit"]') || document.activeElement;

            if (submitBtn && submitBtn.type === 'submit') {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerText.trim();

                if (submitBtn.classList.contains('btn-small')) {
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${originalText}`;
                } else {
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;
                }
            }
            
            if (progressBar) {
                // Apply animation logic to the progress bar on submit
                progressBar.style.display = 'block';
                progressBar.style.width = '0%';
                void progressBar.offsetWidth;
                progressBar.style.transition = 'width 2s cubic-bezier(0.1, 0.05, 0, 1)';
                progressBar.style.width = '100%';
            }
        });
    });

    window.swiftConfirm = function(title, message, type, callback) {
        const modalElem = document.getElementById('confirmActionModal');
        const modal = new bootstrap.Modal(modalElem);
        const btn = document.getElementById('confirm-submit-btn');
        const iconBg = document.getElementById('confirm-icon-bg');
        const icon = document.getElementById('confirm-icon');

        document.getElementById('confirm-title').innerText = title;
        document.getElementById('confirm-message').innerText = message;

        btn.style.backgroundColor = ''; 
        btn.style.color = '';
        btn.className = 'btn fw-700 w-100 rounded-2 py-2 shadow-sm btn-sm d-flex align-items-center justify-content-center';

        if (type === 'success') {
            btn.classList.add('border-0', 'text-white');
            btn.style.backgroundColor = '#10b981';
            iconBg.style.backgroundColor = '#ecfdf5';
            icon.className = 'bi bi-check-circle-fill fs-3 text-success';
        } else if (type === 'danger') {
            btn.classList.add('btn-danger', 'border-0');
            iconBg.style.backgroundColor = '#fff1f2'; 
            icon.className = 'bi bi-trash3-fill fs-3 text-danger';
        } else if (type === 'warning') {
            btn.classList.add('btn-warning', 'text-white', 'border-0');
            iconBg.style.backgroundColor = '#fffbeb'; 
            icon.className = 'bi bi-exclamation-triangle-fill fs-3 text-warning';
        } else {
            btn.classList.add('btn-primary', 'border-0'); 
            iconBg.style.backgroundColor = '#f0f9ff'; 
            icon.className = 'bi bi-info-circle-fill fs-3 text-primary';
        }

        btn.innerText = 'Confirm';
        btn.onclick = (e) => {
            e.preventDefault();
            const originalText = btn.innerText.trim();
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${originalText}`;
            
            // Trigger progress bar animation for the modal confirmation as well
            const progressBar = document.getElementById('top-progress-bar');
            if (progressBar) {
                progressBar.style.display = 'block';
                progressBar.style.width = '0%';
                void progressBar.offsetWidth;
                progressBar.style.transition = 'width 2s cubic-bezier(0.1, 0.05, 0, 1)';
                progressBar.style.width = '100%';
            }

            callback();
        };

        modal.show();
    };
</script>
</body>
</html>