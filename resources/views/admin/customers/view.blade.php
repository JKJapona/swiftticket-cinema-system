<div class="modal fade" id="customerViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">

            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-person-badge fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Customer Details</h2>
                        <p class="text-slate-500 caption mb-0 fw-600" id="m-id">Account Information</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                {{-- Data Content --}}
                <div id="modal-data-content">
                    <div class="text-center mb-4">
                        <div id="m-avatar" class="avatar-letter mx-auto mb-2 shadow-sm d-flex align-items-center justify-content-center fw-800 text-white" style="width: 60px; height: 60px; font-size: 24px; border-radius: 15px; background: var(--swift-blue);"></div>
                        <h4 id="m-name" class="fw-800 text-slate-900 mb-0"></h4>
                        <p id="m-email" class="text-slate-500 small fw-600"></p>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-slate-50 border">
                                <label class="label text-slate-400 d-block mb-1">Phone Number</label>
                                <span id="m-phone" class="fw-700 text-slate-900"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 rounded-3 bg-slate-50 border">
                                <label class="label text-slate-400 d-block mb-1">Account Status</label>
                                <span id="m-status" class="badge rounded-pill fw-800" style="font-size: 10px;"></span>
                            </div>
                        </div>
                    </div>

                    <div class="dashed-divider"></div>

                    <h6 class="caption text-slate-900 mb-3"><i class="bi bi-clock-history me-1"></i> Recent Bookings</h6>
                    <div id="m-bookings" class="list-group list-group-flush gap-2"></div>
                </div>

                {{-- Skeleton Loader --}}
                <div id="modal-loader" class="d-none">
                    <div class="text-center mb-4">
                        <div class="skeleton mx-auto mb-2" style="width: 60px; height: 60px; border-radius: 15px;"></div>
                        <div class="skeleton mx-auto mb-1" style="width: 150px; height: 20px;"></div>
                        <div class="skeleton mx-auto" style="width: 100px; height: 14px;"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6"><div class="skeleton" style="width: 100%; height: 45px; border-radius: 8px;"></div></div>
                        <div class="col-6"><div class="skeleton" style="width: 100%; height: 45px; border-radius: 8px;"></div></div>
                    </div>
                    <div class="dashed-divider"></div>
                    <div class="skeleton mb-3" style="width: 100px; height: 12px;"></div>
                    <div class="skeleton mb-2" style="width: 100%; height: 45px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 100%; height: 45px; border-radius: 8px;"></div>
                </div>
            </div>

            <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-700 btn-sm" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>