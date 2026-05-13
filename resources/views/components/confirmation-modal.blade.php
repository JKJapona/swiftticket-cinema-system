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

    <script>
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