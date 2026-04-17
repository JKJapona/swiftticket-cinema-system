<div class="modal fade" id="adminSeatOverride{{ $booking->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-shield-lock-fill fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Administrative Seat Override</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Ref: {{ $booking->reference_number }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('admin.bookings.approve-change', $booking->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <div class="row g-4">
                        {{-- Left Column: Info & Selection --}}
                        <div class="col-lg-4">
                            <div class="d-flex flex-column h-100 gap-3">
                                {{-- Current Seats --}}
                                <div class="p-3 bg-slate-50 rounded-3 border-start border-4 shadow-sm" style="border-color: #64748B !important;">
                                    <label class="label text-slate-500 text-uppercase fw-700 mb-1 d-block" style="font-size: 10px; letter-spacing: 0.5px;">Currently Booked</label>
                                    <div class="fw-800 text-slate-900 fs-6">{{ $booking->bookedSeats->pluck('seat_code')->implode(', ') }}</div>
                                </div>

                                {{-- Requested Change --}}
                                <div class="p-3 rounded-3 border-start border-4 shadow-sm" style="background-color: #fffbeb; border-color: #FFC107 !important;">
                                    <label class="label text-warning text-uppercase fw-700 mb-1 d-block" style="font-size: 10px; letter-spacing: 0.5px; color: #b45309 !important;">Requested Change</label>
                                    <div class="fw-800 text-warning fs-6" style="color: #b45309 !important;">{{ $booking->requested_seats ?? 'None' }}</div>
                                </div>

                                {{-- Legend --}}
                                <div class="p-3 bg-white border rounded-3 shadow-sm">
                                    <h6 class="caption text-slate-400 mb-3" style="font-size: 10px; letter-spacing: 1px;">Legend</h6>
                                    <div class="row g-2">
                                        <div class="col-6 d-flex align-items-center gap-2 fw-700 text-slate-600" style="font-size: 11px;">
                                            <div style="width: 14px; height: 14px; background: #E2E8F0; border: 1px solid #CBD5E1; border-radius: 3px;"></div> Available
                                        </div>
                                        <div class="col-6 d-flex align-items-center gap-2 fw-700 text-slate-600" style="font-size: 11px;">
                                            <div style="width: 14px; height: 14px; background: #1E293B; border-radius: 3px;"></div> Current
                                        </div>
                                        <div class="col-6 d-flex align-items-center gap-2 fw-700 text-slate-600" style="font-size: 11px;">
                                            <div style="width: 14px; height: 14px; background: #77879e; border-radius: 3px;"></div> Occupied
                                        </div>
                                        <div class="col-6 d-flex align-items-center gap-2 fw-700 text-slate-600" style="font-size: 11px;">
                                            <div style="width: 14px; height: 14px; background: #FFC107; border: 1px solid #ff6600; border-radius: 3px;"></div> Selected
                                        </div>
                                    </div>
                                </div>

                                {{-- Selection Status --}}
                                <div class="mt-auto p-3 rounded-3 bg-slate-900 text-white shadow-lg">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-800 text-uppercase" style="font-size: 10px; color: #94a3b8; letter-spacing: 0.5px;">New Selection</span>
                                        <span id="admin-counter-{{ $booking->id }}" class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-800" style="font-size: 10px;">0 / {{ $booking->bookedSeats->count() }}</span>
                                    </div>
                                    <input type="hidden" name="manual_seats" id="admin-input-{{ $booking->id }}">
                                    <div id="admin-display-{{ $booking->id }}" class="bg-white rounded-2 p-2 text-center fw-800 text-dark d-flex align-items-center justify-content-center" style="min-height: 45px; font-size: 16px; border: 2px solid #FFC107;">
                                        <span class="text-slate-400 fw-600 small italic">Select {{ $booking->bookedSeats->count() }} seats...</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column: Interactive Grid --}}
                        <div class="col-lg-8">
                            <div class="bg-slate-50 rounded-3 p-4 text-center overflow-auto shadow-inner border d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; background-color: #f8fafc;">
                                <div class="seat-grid-wrapper d-inline-block">
                                    {{-- Cinema Screen --}}
                                    <div class="mb-5">
                                        <div class="mx-auto shadow-sm" style="height: 6px; width: 80%; background: #cbd5e1; border-radius: 10px;"></div>
                                        <small class="caption text-slate-400 d-block mt-2" style="font-size: 10px; letter-spacing: 2px;">Cinema Screen Area</small>
                                    </div>

                                    @php 
                                        $allShowtimeSeats = $booking->showtime->bookedSeats; 
                                        $currentSeats = $booking->bookedSeats->pluck('seat_code')->toArray();
                                        $takenByOthers = $allShowtimeSeats->where('booking_id', '!=', $booking->id)->pluck('seat_code')->toArray();
                                    @endphp

                                    @for ($r = 0; $r < $booking->showtime->hall->number_of_rows; $r++)
                                        @php $rowLetter = chr(65 + $r); @endphp
                                        <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                            <small class="text-slate-400 fw-bold" style="width: 25px; font-size: 12px;">{{ $rowLetter }}</small>
                                            
                                            @for ($s = 1; $s <= $booking->showtime->hall->seats_per_row; $s++)
                                                @php 
                                                    $seatCode = $rowLetter . $s; 
                                                    $isTaken = in_array($seatCode, $takenByOthers);
                                                    $isCurrent = in_array($seatCode, $currentSeats);
                                                    $isRequested = in_array($seatCode, explode(',', $booking->requested_seats ?? ''));

                                                    // Determine initial class string
                                                    $classes = 'admin-seat-item';
                                                    if ($isTaken) $classes .= ' taken';
                                                    elseif ($isRequested) $classes .= ' selected'; // Start as selected if it's the request
                                                    elseif ($isCurrent) $classes .= ' current';
                                                    else $classes .= ' available';
                                                @endphp

                                                <div class="{{ $classes }}" 
                                                    data-code="{{ $seatCode }}"
                                                    data-booking-id="{{ $booking->id }}"
                                                    style="cursor: {{ $isTaken ? 'not-allowed' : 'pointer' }};">
                                                </div>
                                                @if ($s == ceil($booking->showtime->hall->seats_per_row / 2)) <div style="width: 20px;"></div> @endif
                                            @endfor
                                            
                                            <small class="text-slate-400 fw-bold" style="width: 25px; font-size: 12px;">{{ $rowLetter }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-700 btn-sm" data-bs-dismiss="modal">
                        Close
                    </button>
                    
                    <button type="submit" id="admin-submit-{{ $booking->id }}" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm" disabled>
                        Confirm Seat Override
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const adminSelections = {};

    document.querySelectorAll('.admin-seat-item').forEach(seat => {
        const bId = seat.dataset.bookingId;
        const code = seat.dataset.code;
        
        if (seat.classList.contains('selected') || seat.style.backgroundColor === 'rgb(255, 193, 7)' || seat.style.backgroundColor === '#FFC107') {
            if (!adminSelections[bId]) adminSelections[bId] = [];
            adminSelections[bId].push(code);
            seat.classList.add('selected');
            updateUI(bId);
        }
    });

    document.querySelectorAll('.admin-seat-item').forEach(seat => {
    
        if (seat.classList.contains('taken') || seat.classList.contains('current')) {
            return; 
        }
        
        seat.addEventListener('click', function() {
            const bId = this.dataset.bookingId;
            const code = this.dataset.code;
            const counterElem = document.getElementById(`admin-counter-${bId}`);
            const required = parseInt(counterElem.innerText.split(' / ')[1]);
            
            if (!adminSelections[bId]) adminSelections[bId] = [];

            if (adminSelections[bId].includes(code)) {

                adminSelections[bId] = adminSelections[bId].filter(c => c !== code);
                this.classList.remove('selected');
            } else {
                if (adminSelections[bId].length >= required) {
                    // FIFO logic: Remove the oldest (first) selection
                    const oldestCode = adminSelections[bId].shift();
                    const oldestSeat = document.querySelector(`.admin-seat-item[data-booking-id="${bId}"][data-code="${oldestCode}"]`);
                    if (oldestSeat) oldestSeat.classList.remove('selected');
                }
                
                adminSelections[bId].push(code);
                this.classList.add('selected');
            }

            updateUI(bId);
        });
    });

    function updateUI(bId) {
        const counterContainer = document.getElementById(`admin-counter-${bId}`);
        if(!counterContainer) return;

        const required = parseInt(counterContainer.innerText.split(' / ')[1]);
        const input = document.getElementById(`admin-input-${bId}`);
        const display = document.getElementById(`admin-display-${bId}`);
        const submitBtn = document.getElementById(`admin-submit-${bId}`);

        const selections = adminSelections[bId] || [];

        input.value = selections.join(',');
        display.innerText = selections.length > 0 ? selections.join(', ') : 'Select seats above...';
        counterContainer.innerText = `${selections.length} / ${required}`;
        submitBtn.disabled = (selections.length !== required);
    }
});
</script>