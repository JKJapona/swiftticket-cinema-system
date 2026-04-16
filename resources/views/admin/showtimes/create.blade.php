<div class="modal fade" id="createShowtimeModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3">
            <div class="modal-header bg-slate-50 border-0 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-calendar-plus fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Create Showtime</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Schedule a new screening</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.showtimes.store') }}" method="POST" id="createShowtimeForm">
                @csrf
                <div class="modal-body p-4 bg-white">
                    
                    {{-- Top Level Error Block --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 py-2 mb-4 rounded-3 shadow-sm">
                            <ul class="mb-0 small fw-600 list-unstyled">
                                @foreach ($errors->all() as $error)
                                    <li><i class="bi bi-exclamation-circle-fill me-2"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">
                        {{-- Movie Selection --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Select Movie</label>
                            <select name="movie_id" class="form-select form-select-sm border-2 @error('movie_id') is-invalid @enderror" required>
                                <option value="" selected disabled>Choose a movie...</option>
                                @foreach($allMovies as $m)
                                    <option value="{{ $m->id }}" {{ (session('is_create_error') ? old('movie_id') : (isset($movie) ? $movie->id : '')) == $m->id ? 'selected' : '' }}>
                                        {{ $m->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date & Time --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Date</label>
                            <input type="date" name="show_date" class="form-control form-control-sm border-2 @error('show_date') is-invalid @enderror" 
                                min="{{ date('Y-m-d') }}" value="{{ session('is_create_error') ? old('show_date') : '' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Time</label>
                            <input type="time" name="show_time" class="form-control form-control-sm border-2 @error('show_time') is-invalid @enderror" 
                                value="{{ session('is_create_error') ? old('show_time') : '' }}" required>
                        </div>

                        {{-- Hall Selection --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Cinema Hall</label>
                            <select name="hall_id" id="hallSelect" class="form-select form-select-sm border-2 @error('hall_id') is-invalid @enderror" required onchange="updateCapacity()">
                                <option value="" selected disabled>Select Hall...</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall->id }}" data-capacity="{{ $hall->total_seats }}" {{ (session('is_create_error') ? old('hall_id') : '') == $hall->id ? 'selected' : '' }}>
                                        {{ $hall->name }} ({{ $hall->screen_type }} - {{ $hall->total_seats }} seats)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price & Capacity --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Ticket Price (₱)</label>
                            <input type="number" name="price" class="form-control form-control-sm border-2 @error('price') is-invalid @enderror" 
                                value="{{ session('is_create_error') ? old('price') : '350' }}" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Total Capacity</label>
                                <input type="number" name="total_capacity" id="hallCapacity" class="form-control form-control-sm bg-light border-2" 
                                    value="{{ session('is_create_error') ? old('total_capacity') : '' }}" readonly required>
                            <small class="text-muted" style="font-size: 10px;">Auto-filled from Hall data</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-3">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm" data-bs-dismiss="modal">Discard</button>
                    <button type="submit" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        Create Slot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateCapacity() {
        const select = document.getElementById('hallSelect');
        const capacityInput = document.getElementById('hallCapacity');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            capacityInput.value = selectedOption.getAttribute('data-capacity');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('hallSelect').value) {
            updateCapacity();
        }
    });
</script>

<script>
    @if (session('is_create_error'))
        document.addEventListener('DOMContentLoaded', function () {
            var createModal = new bootstrap.Modal(document.getElementById('createShowtimeModal'));
            createModal.show();
        });
    @endif
</script>