<div class="modal fade" id="createHallModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-door-open fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Add New Hall</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Configuring Cinema Infrastructure</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.cinema-halls.store') }}" method="POST" id="createHallForm">
                @csrf
                <div class="modal-body p-3 bg-white">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 py-2 mb-3 rounded-3">
                            <ul class="mb-0 small fw-600">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row g-3">
                        {{-- Hall Identity --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Hall Name / Number</label>
                            <input type="text" name="name" class="form-control form-control-sm border-2" placeholder="e.g. Cinema 01" required>
                        </div>

                        {{-- Technical Specs --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Screen Type</label>
                            <select name="screen_type" class="form-select form-select-sm border-2">
                                <option value="Standard">Standard</option>
                                <option value="IMAX">IMAX</option>
                                <option value="Premium">Premium</option>
                                <option value="4DX">4DX</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Audio System</label>
                            <input type="text" name="audio_system" class="form-control form-control-sm border-2" placeholder="e.g. Dolby Atmos" value="Dolby Atmos">
                        </div>

                        {{-- Seating Configuration --}}
                        <div class="col-12 mt-2">
                            <div class="p-3 rounded-3 bg-slate-50 border shadow-sm">
                                <h4 class="caption text-slate-900 mb-3 d-flex align-items-center">
                                    <i class="bi bi-grid-3x3-gap me-2 text-swift-blue"></i> Seating Layout
                                </h4>
                                <div class="row gx-3">
                                    <div class="col-md-6">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-1">Number of Rows</label>
                                        <input type="number" name="number_of_rows" id="hall_rows" class="form-control form-control-sm" min="1" max="26" required oninput="calculateTotal()">
                                        <p class="caption text-slate-500 mt-1 mb-0" style="font-size: 9px !important;">Max 26 (A-Z)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-1">Seats Per Row</label>
                                        <input type="number" name="seats_per_row" id="hall_seats" class="form-control form-control-sm" min="1" max="40" required oninput="calculateTotal()">
                                        <p class="caption text-slate-500 mt-1 mb-0" style="font-size: 9px !important;">Max 40 seats per row</p>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2 border-top d-flex justify-content-between align-items-center">
                                    <span class="caption text-slate-500 mb-0" style="font-size: 10px !important;">Calculated Capacity:</span>
                                    <span id="total_capacity_display" class="badge bg-swift-blue px-3 py-2 rounded-pill fw-700" style="font-size: 11px;">0 Seats</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Initial Status</label>
                            <select name="status" class="form-select form-select-sm border-2">
                                <option value="Active">Active</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm me-3" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        Create Hall Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function calculateTotal() {
        const rowsInput = document.getElementById('hall_rows');
        const seatsInput = document.getElementById('hall_seats');
        const display = document.getElementById('total_capacity_display');

        let rows = parseInt(rowsInput.value) || 0;
        let seats = parseInt(seatsInput.value) || 0;

        const total = rows * seats;
        display.innerText = total + ' Seats';
    }
</script>