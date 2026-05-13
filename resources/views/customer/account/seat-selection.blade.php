<div class="modal fade" id="customerChangeSeat{{ $booking->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog {{ in_array($booking->status, ['change_requested', 'confirmed']) ? 'modal-md' : 'modal-lg' }} modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Unified Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-person-badge-fill fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 16px !important;">
                           @if($booking->status === 'change_requested')
                                Request Status
                            @elseif($booking->status === 'confirmed')
                                Booking Details
                            @else
                                Change Your Seats
                            @endif
                        </h2>
                        <p class="text-slate-500 mb-0 fw-600" style="font-size: 11px;">Booking: #{{ $booking->reference_number }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            @if($booking->status === 'change_requested')
                <div class="modal-body p-0 bg-white">
                    {{-- Slim Amber Header --}}
                    <div class="py-2 text-center border-bottom" style="background-color: #fffbeb; border-color: #fde68a !important;">
                        <span class="fw-black text-uppercase" style="font-size: 10px; letter-spacing: 1px; color: #b45309;">
                            <i class="bi bi-clock-history me-1"></i> Change Request Under Review
                        </span>
                    </div>

                    <div class="p-3">
                        {{-- Horizontal Comparison Row --}}
                        <div class="row g-0 align-items-center rounded-3 border overflow-hidden">
                            <div class="col-5 bg-light p-3 text-center border-end">
                                <p class="text-uppercase text-muted fw-bold mb-2" style="font-size: 9px; letter-spacing: 0.5px;">Current</p>
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    @forelse($booking->bookedSeats as $seat)
                                        <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-10 rounded-2 px-2 py-1" style="font-size: 10px;">
                                            {{ $seat->seat_code }}
                                        </span>
                                    @empty
                                        <span class="small text-muted italic">None</span>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Transition Arrow --}}
                            <div class="col-2 bg-white d-flex align-items-center justify-content-center py-2" style="margin-left: -1px; margin-right: -1px; z-index: 2;">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 28px; height: 28px;">
                                    <i class="bi bi-arrow-right-short fs-5"></i>
                                </div>
                            </div>

                            {{-- Requested Side --}}
                            <div class="col-5 p-3 text-center border-start" style="background-color: #f0f7ff; border-color: #dbeafe !important;">
                                <p class="text-uppercase text-primary fw-bold mb-2" style="font-size: 9px; letter-spacing: 0.5px;">Requested</p>
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    @php $requestedSeats = $booking->requested_seats ? explode(',', $booking->requested_seats) : []; @endphp
                                    @forelse($requestedSeats as $reqSeat)
                                        <span class="badge bg-primary rounded-2 px-2 py-1 shadow-sm" style="font-size: 10px;">
                                            {{ trim($reqSeat) }}
                                        </span>
                                    @empty
                                        <span class="small text-muted italic">None</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- Info Box --}}
                        <div class="mt-3 p-3 rounded-3 bg-light border border-opacity-50 text-center">
                            <h6 class="fw-bold mb-1 text-slate-800 small">Request in queue</h6>
                            <p class="text-muted mb-0 lh-sm" style="font-size: 12px;">
                                Admins review requests within 24 hours. You'll receive an email update once processed.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm" data-bs-dismiss="modal">
                        Got it
                    </button>
                </div>
            
            @elseif($booking->status === 'confirmed')
                <div class="modal-body p-0 bg-white">
                    <div class="py-2 text-center border-bottom" style="background-color: #ecfdf5; border-color: #a7f3d0 !important;">
                        <span class="fw-black text-uppercase" style="font-size: 10px; letter-spacing: 1px; color: #059669;">
                            <i class="bi bi-check-circle-fill me-1"></i> Booking Confirmed & Verified
                        </span>
                    </div>

                    <div class="p-4">
                        <div class="text-center mb-4">
                            <div class="display-6 fw-bold text-slate-900 mb-1">
                                {{ $booking->bookedSeats->pluck('seat_code')->implode(', ') }}
                            </div>
                            <p class="text-uppercase text-muted fw-bold" style="font-size: 10px; letter-spacing: 1px;">Final Seat Assignment</p>
                        </div>

                        {{-- Confirmation Details Box --}}
                        <div class="rounded-3 border overflow-hidden">
                            <div class="row g-0">
                                <div class="col-6 bg-light p-3 border-end">
                                    <label class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 9px;">Payment Method</label>
                                    <span class="fw-bold text-slate-800">{{ $booking->payment_method }}</span>
                                </div>
                                <div class="col-6 bg-light p-3">
                                    <label class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 9px;">Transaction Status</label>
                                    <span class="badge bg-success rounded-pill px-2 py-1" style="font-size: 10px;">Fully Verified</span>
                                </div>
                            </div>
                        </div>

                        {{-- Success Info Box --}}
                        <div class="mt-4 p-3 rounded-3 text-center" style="background-color: #f8fafc; border: 1px dashed #cbd5e1;">
                            <h6 class="fw-bold mb-1 text-slate-800 small">Ready for Showtime!</h6>
                            <p class="text-muted mb-0 lh-sm" style="font-size: 12px;">
                                Your seats are locked in. Please present your digital ticket or reference number <strong>#{{ $booking->reference_number }}</strong> at the cinema counter.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm" data-bs-dismiss="modal">
                        Got it
                    </button>
                </div>

            @else
                <form action="{{ route('bookings.request-change', $booking->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-white">
                        <div class="row g-4">
                            {{-- Left Column: Info & Selection --}}
                            <div class="col-lg-4">
                                <div class="d-flex flex-column h-100 gap-3">

                                    <div class="p-3 bg-slate-50 rounded-3 border-0 border-start border-4 shadow-sm" style="border-left-color: #64748B !important;">
                                        <label class="label text-slate-500 text-uppercase fw-700 mb-1 d-block" style="font-size: 10px; letter-spacing: 0.5px;">Current Selection</label>
                                        <div class="fw-800 text-slate-900 fs-6">{{ $booking->bookedSeats->pluck('seat_code')->implode(', ') }}</div>
                                    </div>

                                    <div class="p-3 rounded-3 border-0 border-start border-4 shadow-sm" style="background-color: #f0f9ff; border-left-color: #0369a1 !important;">
                                        <label class="label text-info text-uppercase fw-700 mb-1 d-block" style="color: #0369a1 !important; font-size: 10px; letter-spacing: 0.5px;">Selection Rule</label>
                                        <div class="fw-800 text-info" style="color: #0369a1 !important; font-size: 12px;">Select exactly {{ $booking->bookedSeats->count() }} new seats.</div>
                                    </div>

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

                                    <div class="mt-auto p-3 rounded-3 border bg-light shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-800 text-uppercase text-slate-500" style="font-size: 10px; letter-spacing: 0.5px;">New Selection</span>
                                            <span id="cust-counter-{{ $booking->id }}" class="badge bg-warning text-dark px-2 py-1 rounded-pill fw-800" style="font-size: 10px;">0 / {{ $booking->bookedSeats->count() }}</span>
                                        </div>
                                        <input type="hidden" name="new_seats" id="cust-input-{{ $booking->id }}">
                                        
                                        <div id="cust-display-{{ $booking->id }}" class="bg-white rounded-2 p-2 text-center fw-800 text-dark d-flex align-items-center justify-content-center border-2" 
                                            style="min-height: 48px; font-size: 16px; border-color: #FFC107 !important; border-style: dashed;">
                                            <span class="text-slate-400 fw-600 small italic">Pick new seats...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Interactive Grid --}}
                            <div class="col-lg-8">
                                <div class="bg-slate-50 rounded-3 p-4 text-center overflow-auto shadow-inner border d-flex flex-column align-items-center justify-content-center" style="min-height: 400px; background-color: #f8fafc;">
                                    <div class="seat-grid-wrapper d-inline-block">
                                        <div class="mb-5">
                                            <div class="mx-auto shadow-sm" style="height: 6px; width: 80%; background: #cbd5e1; border-radius: 10px;"></div>
                                            <small class="caption text-slate-400 d-block mt-2" style="font-size: 10px; letter-spacing: 2px;">Cinema Screen Area</small>
                                        </div>

                                        @php 
                                            $allShowtimeSeats = $booking->showtime->bookedSeats; 
                                            $currentSeatsArr = $booking->bookedSeats->pluck('seat_code')->toArray();
                                            $takenByOthers = $allShowtimeSeats->where('booking_id', '!=', $booking->id)->pluck('seat_code')->toArray();
                                        @endphp

                                        @for ($r = 0; $r < $booking->showtime->hall->number_of_rows; $r++)
                                            @php $rowLetter = chr(65 + $r); @endphp
                                            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                                                <small class="text-slate-400 fw-bold" style="width: 25px;">{{ $rowLetter }}</small>
                                                @for ($s = 1; $s <= $booking->showtime->hall->seats_per_row; $s++)
                                                    @php 
                                                        $seatCode = $rowLetter . $s; 
                                                        $isTaken = in_array($seatCode, $takenByOthers);
                                                        $isCurrent = in_array($seatCode, $currentSeatsArr);
                                                    @endphp
                                                    <div class="cust-seat-item {{ $isTaken ? 'taken' : ($isCurrent ? 'current' : 'available') }}" 
                                                         data-code="{{ $seatCode }}"
                                                         data-booking-id="{{ $booking->id }}"
                                                         style="cursor: {{ $isTaken ? 'not-allowed' : 'pointer' }};">
                                                    </div>
                                                    @if ($s == ceil($booking->showtime->hall->seats_per_row / 2)) <div style="width: 20px;"></div> @endif
                                                @endfor
                                                <small class="text-slate-400 fw-bold" style="width: 25px;">{{ $rowLetter }}</small>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-700 btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="cust-submit-{{ $booking->id }}" class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm" disabled>Confirm Selection</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        const customerSelections = {};

        document.querySelectorAll('.cust-seat-item').forEach(seat => {
            if (seat.classList.contains('taken') || seat.classList.contains('current')) return;

            seat.addEventListener('click', function() {
                const bId = this.dataset.bookingId;
                const code = this.dataset.code;
                const counterElem = document.getElementById(`cust-counter-${bId}`);
                if(!counterElem) return;

                const required = parseInt(counterElem.innerText.split(' / ')[1]);
                if (!customerSelections[bId]) customerSelections[bId] = [];

                if (customerSelections[bId].includes(code)) {
                    customerSelections[bId] = customerSelections[bId].filter(c => c !== code);
                    this.classList.remove('selected');
                } else {
                    if (customerSelections[bId].length >= required) {
                        const oldestCode = customerSelections[bId].shift();
                        const oldestSeat = document.querySelector(`.cust-seat-item[data-booking-id="${bId}"][data-code="${oldestCode}"]`);
                        if (oldestSeat) oldestSeat.classList.remove('selected');
                    }
                    customerSelections[bId].push(code);
                    this.classList.add('selected');
                }

                const input = document.getElementById(`cust-input-${bId}`);
                const display = document.getElementById(`cust-display-${bId}`);
                const submitBtn = document.getElementById(`cust-submit-${bId}`);

                input.value = customerSelections[bId].join(',');
                display.innerText = customerSelections[bId].length > 0 ? customerSelections[bId].join(', ') : 'Pick new seats...';
                counterElem.innerText = `${customerSelections[bId].length} / ${required}`;
                submitBtn.disabled = (customerSelections[bId].length !== required);
            });
        });
    });
</script>