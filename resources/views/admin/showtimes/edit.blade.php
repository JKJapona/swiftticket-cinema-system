<div class="modal fade" id="editShowtimeModal{{ $showtime->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            <div class="modal-header bg-slate-50 border-0 px-4 py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Edit Showtime</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Modify screening details</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.showtimes.update', $showtime->id) }}" method="POST" id="editShowtimeForm{{ $showtime->id }}">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-4 bg-white">
                    
                    {{-- Error Block --}}
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
                                <option value="" disabled>Choose a movie...</option>
                                @foreach($allMovies as $m)
                                    <option value="{{ $m->id }}" 
                                        {{ (session('error_showtime_id') == $showtime->id ? old('movie_id', $showtime->movie_id) : $showtime->movie_id) == $m->id ? 'selected' : '' }}>
                                        {{ $m->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date & Time --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Date</label>
                            <input type="date" name="show_date" class="form-control form-control-sm border-2" 
                                min="{{ date('Y-m-d') }}" 
                                value="{{ session('error_showtime_id') == $showtime->id 
                                    ? old('show_date', \Carbon\Carbon::parse($showtime->show_date)->format('Y-m-d')) 
                                    : \Carbon\Carbon::parse($showtime->show_date)->format('Y-m-d') }}" 
                                required>
                        </div>
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Time</label>
                            <input type="time" name="show_time" class="form-control form-control-sm border-2" 
                                value="{{ session('error_showtime_id') == $showtime->id ? old('show_time', \Carbon\Carbon::parse($showtime->show_time)->format('H:i')) : \Carbon\Carbon::parse($showtime->show_time)->format('H:i') }}" required>
                        </div>

                        {{-- Hall Selection --}}
                        <div class="col-12">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Cinema Hall</label>
                            <select name="hall_id" id="hallSelect{{ $showtime->id }}" class="form-select form-select-sm border-2 @error('hall_id') is-invalid @enderror" 
                                    required onchange="updateCapacity{{ $showtime->id }}()">
                                <option value="" disabled>Select Hall...</option>
                                @foreach($halls as $hall)
                                    <option value="{{ $hall->id }}" data-capacity="{{ $hall->total_seats }}" 
                                        {{ (session('error_showtime_id') == $showtime->id ? old('hall_id', $showtime->hall_id) : $showtime->hall_id) == $hall->id ? 'selected' : '' }}>
                                        {{ $hall->name }} ({{ $hall->screen_type }} - {{ $hall->total_seats }} seats)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price & Capacity --}}
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Ticket Price (₱)</label>
                            <input type="number" name="price" class="form-control form-control-sm border-2 @error('price') is-invalid @enderror" 
                                   value="{{ old('price', $showtime->price) }}" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1">Total Capacity</label>
                            <input type="number" name="total_capacity" id="hallCapacity{{ $showtime->id }}" class="form-control form-control-sm bg-light border-2" 
                                   value="{{ old('total_capacity', $showtime->total_capacity) }}" readonly required>
                            <small class="caption text-slate-500" style="font-size: 9px !important;">Auto-filled from Hall data</small>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-slate-50 border-0 p-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-600 btn-sm me-3" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        Update Showtime
                    </button>
                </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateCapacity{{ $showtime->id }}() {
        const select = document.getElementById('hallSelect{{ $showtime->id }}');
        const capacityInput = document.getElementById('hallCapacity{{ $showtime->id }}');
        const selectedOption = select.options[select.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            capacityInput.value = selectedOption.getAttribute('data-capacity');
        }
    }
</script>