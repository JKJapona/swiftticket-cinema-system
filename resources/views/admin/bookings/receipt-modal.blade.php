<div class="modal fade" id="receiptModal{{ $booking->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi bi-receipt-cutoff fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">Verify Payment</h2>
                        <p class="text-slate-500 caption mb-0 fw-600">Ref: {{ $booking->reference_number }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4 bg-white">
                {{-- Booking Summary Card --}}
                <div class="mb-4 p-3 rounded-3 bg-slate-50 border shadow-sm">
                    <div class="row text-center align-items-center">
                        <div class="col-6 border-end border-slate-200">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Amount Due</label>
                            <div class="h5 fw-800 text-slate-900 mb-0">₱{{ number_format($booking->total_price, 2) }}</div>
                        </div>
                        <div class="col-6">
                            <label class="label text-slate-500 text-uppercase fw-700 mb-1" style="font-size: 10px; letter-spacing: 0.5px;">Method</label>
                            <div class="h5 fw-800 text-slate-900 mb-0">{{ $booking->payment_method }}</div>
                        </div>
                    </div>
                </div>

                {{-- Receipt Image Section --}}
                <label class="label text-slate-500 text-uppercase fw-700 mb-2 d-block" style="font-size: 11px;">Uploaded Proof of Payment</label>
                <div class="media-dropzone text-center p-2 border-2 border-dashed rounded-3 bg-light overflow-hidden shadow-inner d-flex align-items-center justify-content-center" style="min-height: 320px; background-color: #f8fafc;">
                    @if($booking->payment_receipt)
                        <a href="{{ asset('storage/' . $booking->payment_receipt) }}" target="_blank" class="d-block w-100 h-100">
                            <img src="{{ asset('storage/' . $booking->payment_receipt) }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="max-height: 450px; width: 100%; object-fit: contain; transition: transform 0.2s;" 
                                 onmouseover="this.style.transform='scale(1.01)'"
                                 onmouseout="this.style.transform='scale(1)'"
                                 alt="Payment Receipt">
                        </a>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x fs-1 text-slate-300"></i>
                            <p class="caption text-slate-400 mt-2 mb-0 fw-600" style="font-size: 10px !important;">No receipt uploaded for this booking.</p>
                        </div>
                    @endif
                </div>
                
                {{-- Info Footer --}}
                <div class="mt-3 p-3 rounded-3 bg-slate-50 border d-flex align-items-center justify-content-center shadow-sm">
                    <p class="text-center text-slate-500 mb-0 fw-600" style="font-size: 11px;">
                        <i class="bi bi-info-circle-fill me-1 text-swift-blue"></i> 
                        Verify the reference number in the GCash App matches this image.
                    </p>
                </div>
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-slate-50 border-0 p-2 px-4 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-700 btn-sm" data-bs-dismiss="modal">
                    Discard
                </button>
                
                @if($booking->status === 'pending' || $booking->status === 'change_requested')
                    <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="m-0">
                        @csrf
                        @method('PATCH')
                        <button type="submit" 
                                class="btn {{ $booking->status === 'change_requested' ? 'btn-warning' : 'btn-primary bg-swift-blue' }} border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                            
                            {{ $booking->status === 'change_requested' ? 'Review & Approve' : 'Confirm & Approve' }}
                        </button>
                    </form>

                @elseif($booking->status === 'cancelled')
                    <button class="btn btn-danger border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm disabled opacity-75" disabled>
                        Cancelled
                    </button>

                @else {{-- This handles 'confirmed' --}}
                    <button class="btn btn-success border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm disabled opacity-75" disabled>
                        Already {{ ucfirst($booking->status) }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>