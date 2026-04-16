 <div class="modal fade" id="ticketModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 rounded-3 overflow-hidden shadow">
                            
                            <div class="modal-body p-0">
                                <div class="row g-0">
                                    {{-- LEFT SIDE: MOVIE & DETAILS --}}
                                    <div class="col-md-8 p-4 p-lg-5 bg-white">
                                        <div class="d-flex align-items-center gap-2 mb-4">
                                            <img src="{{ asset('images/swiftticket_abreeza.svg') }}" height="24">
                                            <span class="text-muted ms-auto small font-monospace">{{ $booking->reference_number }}</span>
                                        </div>

                                        <div class="mb-4">
                                            <h2 class="fw-black text-dark mb-1">{{ strtoupper($booking->showtime->movie->title) }}</h2>
                                            <p class="text-muted mb-0">
                                                <i class="bi bi-geo-alt-fill text-danger"></i> 
                                                {{ $booking->showtime->hall->name }} • {{ $booking->showtime->hall->screen_type }}
                                            </p>
                                        </div>

                                        <div class="row border-top border-bottom py-3 my-4 text-center text-md-start">
                                            <div class="col-4">
                                                <small class="text-uppercase text-muted d-block fw-bold" style="font-size: 0.7rem;">Date</small>
                                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="col-4 border-start">
                                                <small class="text-uppercase text-muted d-block fw-bold" style="font-size: 0.7rem;">Time</small>
                                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('h:i A') }}</span>
                                            </div>
                                            <div class="col-4 border-start">
                                                <small class="text-uppercase text-muted d-block fw-bold" style="font-size: 0.7rem;">Seats</small>
                                                <span class="fw-bold text-primary">
                                                    {{ $booking->seats->pluck('seat_code')->implode(', ') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Gate Entry</small>
                                                <span class="fw-medium small">Cinema Level, Abreeza Mall</span>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted d-block">Total Paid</small>
                                                <span class="fw-bold fs-5">₱{{ number_format($booking->total_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- RIGHT SIDE: SCANNER STUB --}}
                                    <div class="col-md-4 bg-light border-start p-4 d-flex flex-column align-items-center justify-content-center text-center">
                                        
                                        {{-- QR Box with Skeleton --}}
                                        <div class="qr-box mb-3 position-relative skeleton-loader mx-auto shadow-sm d-flex align-items-center justify-content-center" 
                                            style="width: 170px; height: 170px; border-radius: 12px; overflow: hidden;">
                                            
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ $booking->reference_number }}" 
                                                alt="QR Code"
                                                width="150" 
                                                height="150"
                                                style="opacity: 0; transition: opacity 0.5s ease; display: block;"
                                                onload="this.parentElement.classList.remove('skeleton-loader'); this.style.opacity='1';"
                                                onerror="handleQrError(this)">

                                                {{-- Professional Error State (Hidden by default) --}}
                                            <div id="qr-error-state" class="d-none flex-column align-items-center justify-content-center text-center p-2" 
                                                style="position: absolute; inset: 0; z-index: 4; background-color: #fff1f2;">
                                                <i class="bi bi-cloud-slash text-danger mb-2" style="font-size: 2rem;"></i>
                                                <p class="fw-bold text-dark mb-1" style="font-size: 0.85rem;">Failed to load</p>
                                                <button onclick="location.reload()" class="btn btn-sm btn-danger py-0 px-3 rounded-pill" style="font-size: 0.75rem;">
                                                    Retry
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <div class="badge mb-3 px-3 py-2 text-uppercase
                                            {{ $booking->status == 'confirmed' ? 'bg-success' : '' }} 
                                            {{ $booking->status == 'cancelled' ? 'bg-danger' : '' }}" 
                                            style="
                                                @if($booking->status == 'pending') 
                                                    background-color: #f59e0b; color: white;
                                                @elseif($booking->status == 'change_requested') 
                                                    background-color: #6366f1; color: white;
                                                @endif">
                                            {{ strtoupper(str_replace('_', ' ', $booking->status)) }}
                                        </div>

                                        <button class="btn btn-dark w-100 rounded-3 fw-bold mb-2 d-flex align-items-center justify-content-center" onclick="window.print()">
                                            <i class="bi bi-printer me-2"></i> Print
                                        </button>

                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 border-0 d-flex align-items-center justify-content-center"  data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
    function handleQrError(img) {
        const errorState = img.parentElement.querySelector('#qr-error-state');
        if (errorState) {
            img.style.display = 'none';
            errorState.classList.remove('d-none');
            errorState.classList.add('d-flex');
        }
}
</script>