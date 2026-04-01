<div class="modal fade" id="editHallModal{{ $hall->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Edit Hall</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Updating: {{ $hall->name }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.cinema-halls.update', $hall->id) }}" method="POST" id="editHallForm{{ $hall->id }}">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-3 bg-white">
                    <div class="row g-3">
                        {{-- Hall Identity --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Hall Name / Number</label>
                            <input type="text" name="name" class="form-control form-control-sm border-2" value="{{ $hall->name }}" required>
                        </div>

                        {{-- Technical Specs --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Screen Type</label>
                            <select name="screen_type" class="form-select form-select-sm border-2">
                                <option value="Standard" {{ $hall->screen_type == 'Standard' ? 'selected' : '' }}>Standard</option>
                                <option value="IMAX" {{ $hall->screen_type == 'IMAX' ? 'selected' : '' }}>IMAX</option>
                                <option value="Premium" {{ $hall->screen_type == 'Premium' ? 'selected' : '' }}>Premium</option>
                                <option value="4DX" {{ $hall->screen_type == '4DX' ? 'selected' : '' }}>4DX</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Audio System</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-slate-100 border-end-0 text-slate-400">
                                    <i class="bi bi-speaker-fill" style="font-size: 10px;"></i>
                                </span>
                                <input type="text" name="audio_system" class="form-control border-start-0 ps-0" value="{{ $hall->audio_system }}">
                            </div>
                        </div>

                        {{-- Infrastructure --}}
                        <div class="col-12 mt-2">
                            <div class="bg-slate-50 rounded-3 p-3 border border-dashed shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="caption text-slate-900 mb-0 d-flex align-items-center">
                                        <i class="bi bi-grid-3x3-gap me-2 text-warning"></i> Seating Infrastructure
                                    </h4>
                                    <span class="badge bg-slate-200 text-slate-600 fw-700" style="font-size: 9px;">LOCKED</span>
                                </div>
                                
                                <div class="row gx-3">
                                    <div class="col-6">
                                        <label class="label text-slate-400 text-uppercase fw-700 mb-1">Rows (Fixed)</label>
                                        <input type="text" class="form-control form-control-sm bg-light border-0 text-slate-500 fw-600" value="{{ $hall->number_of_rows }}" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="label text-slate-400 text-uppercase fw-700 mb-1">Seats/Row (Fixed)</label>
                                        <input type="text" class="form-control form-control-sm bg-light border-0 text-slate-500 fw-600" value="{{ $hall->seats_per_row }}" readonly>
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-top border-slate-200 d-flex justify-content-between align-items-center">
                                    <span class="caption text-slate-400" style="font-size: 9px !important;">Total Capacity</span>
                                    <span class="fw-800 text-slate-600" style="font-size: 11px;">{{ $hall->number_of_rows * $hall->seats_per_row }} Seats</span>
                                </div>
                            </div>
                        </div>

                        {{-- Status Selection --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Operation Status</label>
                            <select name="status" class="form-select form-select-sm border-2">
                                <option value="Active" {{ $hall->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Maintenance" {{ $hall->status == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Inactive" {{ $hall->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-slate-50 border-0 p-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm text-dark">
                        Update Hall Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>