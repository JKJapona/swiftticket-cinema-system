<div class="modal fade" id="viewModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-start">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-800">Booking Details</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1 text-secondary caption">Reference Number</p>
                <h4 class="fw-800 text-primary mb-3">#{{ $booking->reference_number }}</h4>
                
                <div class="row g-3">
                    <div class="col-6">
                        <p class="mb-0 text-secondary caption">Customer</p>
                        <p class="fw-700">{{ $booking->user->full_name }}</p>
                    </div>
                    <div class="col-6">
                        <p class="mb-0 text-secondary caption">Payment</p>
                        <p class="fw-700 text-capitalize">{{ $booking->payment_method }}</p>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="mb-1 text-secondary caption">Booked Seats</p>
                    <div class="d-flex flex-wrap gap-1">
                        @if($booking->status === 'cancelled')
                            <span class="text-danger fw-700" style="font-size: 11px;">
                                <i class="bi bi-info-circle me-1"></i> SEATS HAVE BEEN RELEASED
                            </span>
                        @else
                            @foreach($booking->bookedSeats as $seat)
                                <span class="badge bg-light text-dark border fw-600 px-2 py-1" style="font-size: 11px;">
                                    {{ $seat->seat_code }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <hr class="text-slate-100">
                <p class="mb-1 text-secondary caption">Movie Schedule</p>
                <p class="fw-700 mb-0">{{ $booking->showtime->movie->title }}</p>
                <p class="text-secondary small">{{ $booking->showtime->show_date->format('F d, Y') }} | {{ date('h:i A', strtotime($booking->showtime->show_time)) }}</p>
            </div>
        </div>
    </div>
</div>