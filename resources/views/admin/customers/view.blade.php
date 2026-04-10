<div class="modal fade" id="customerViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-slate-50 border-0 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-person-badge fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Customer Details</h2>
                        <p class="text-slate-500 caption mb-0 fw-600" id="m-id">Account Information</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                {{-- Data Content --}}
                <div id="modal-data-content">
                    <div class="text-center mb-4">
                        <div id="m-avatar" class="avatar-letter mx-auto mb-2" style="width: 60px; height: 60px; font-size: 24px; border-radius: 15px;"></div>
                        <h4 id="m-name" class="fw-800 text-slate-900 mb-0"></h4>
                        <p id="m-email" class="text-secondary small"></p>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="caption text-slate-400 d-block" style="font-size: 10px;">Phone</label>
                            <span id="m-phone" class="fw-700 text-slate-900"></span>
                        </div>
                        <div class="col-6">
                            <label class="caption text-slate-400 d-block" style="font-size: 10px;">Status</label>
                            <span id="m-status" class="badge rounded-pill"></span>
                        </div>
                    </div>
                    <hr class="my-4 opacity-50">
                    <h6 class="caption text-slate-900 mb-3">Recent Bookings</h6>
                    <div id="m-bookings" class="list-group list-group-flush"></div>
                </div>

                {{-- Skeleton Loader (Hidden by default) --}}
                <div id="modal-loader" class="d-none">
                    <div class="text-center mb-4">
                        <div class="skeleton mx-auto mb-2" style="width: 60px; height: 60px; border-radius: 15px;"></div>
                        <div class="skeleton mx-auto mb-1" style="width: 150px; height: 20px;"></div>
                        <div class="skeleton mx-auto" style="width: 100px; height: 14px;"></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="skeleton mb-1" style="width: 40px; height: 10px;"></div>
                            <div class="skeleton" style="width: 80px; height: 18px;"></div>
                        </div>
                        <div class="col-6">
                            <div class="skeleton mb-1" style="width: 40px; height: 10px;"></div>
                            <div class="skeleton" style="width: 60px; height: 18px;"></div>
                        </div>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="skeleton mb-3" style="width: 100px; height: 12px;"></div>
                    <div class="skeleton mb-2" style="width: 100%; height: 40px; border-radius: 8px;"></div>
                    <div class="skeleton" style="width: 100%; height: 40px; border-radius: 8px;"></div>
                </div>
            </div>

            <div class="modal-footer border-0 bg-slate-50">
                <button type="button" class="btn btn-secondary btn-sm w-100 fw-700 rounded-3" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .skeleton {
        background: #e2e8f0;
        background: linear-gradient(110deg, #ececec 8%, #f5f5f5 18%, #ececec 33%);
        background-size: 200% 100%;
        animation: 1.5s shine linear infinite;
        border-radius: 4px;
    }

    @keyframes shine {
        to { background-position-x: -200%; }
    }
</style>