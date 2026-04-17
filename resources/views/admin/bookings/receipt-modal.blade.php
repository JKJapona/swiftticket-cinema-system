<div class="modal fade" id="receiptModal{{ $booking->id }}" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            
            {{-- Header --}}
            <div class="modal-header bg-slate-50 border-0 px-4 py-2">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-swift-blue text-white rounded-2 p-2 d-flex shadow-sm">
                        <i class="bi {{ $booking->payment_method === 'Pay at Cinema' ? 'bi-cash-stack' : 'bi-receipt-cutoff' }} fs-5"></i>
                    </div>
                    <div>
                        <h2 class="h2 text-slate-900 mb-0" style="font-size: 18px !important;">
                            {{ $booking->payment_method === 'Pay at Cinema' ? 'Verify Walk-in' : 'Verify Payment' }}
                        </h2>
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

                {{-- Proof of Payment Section --}}
                @if($booking->payment_method === 'Pay at Cinema')
                    <div class="text-center p-4 border-2 border-dashed rounded-3 bg-light mb-3">
                        <div class="bg-white rounded-circle d-inline-flex p-3 shadow-sm mb-3">
                            <i class="bi bi-person-check fs-1 text-swift-blue"></i>
                        </div>
                        <h6 class="fw-800 text-slate-900">On-site Payment Expected</h6>
                        <p class="text-slate-500 mb-0" style="font-size: 13px;">This customer has chosen to pay at the counter. Please confirm if the customer is present and has settled the amount.</p>
                    </div>
                @else
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
                @endif

                {{-- Action/Reason Section --}}
                @if($booking->status === 'pending' || $booking->status === 'change_requested')
                    <div class="mt-4 pt-2">
                        <label class="label text-slate-500 text-uppercase fw-700 mb-2 d-block" style="font-size: 11px;">Action Decision</label>
                        <div class="d-flex gap-2 mb-3">
                            <div class="flex-grow-1">
                                <input type="radio" class="btn-check" name="decision{{ $booking->id }}" id="approve{{ $booking->id }}" autocomplete="off" checked 
                                       onclick="toggleCancellationReason('{{ $booking->id }}', 'approve')">
                                <label class="btn btn-outline-success w-100 fw-700 py-2 rounded-2 border-2 d-flex align-items-center justify-content-center gap-2" for="approve{{ $booking->id }}">
                                    <i class="bi bi-check-lg"></i> Approve
                                </label>
                            </div>
                            <div class="flex-grow-1">
                                <input type="radio" class="btn-check" name="decision{{ $booking->id }}" id="deny{{ $booking->id }}" autocomplete="off"
                                       onclick="toggleCancellationReason('{{ $booking->id }}', 'deny')">
                                <label class="btn btn-outline-danger w-100 fw-700 py-2 rounded-2 border-2 d-flex align-items-center justify-content-center gap-2" for="deny{{ $booking->id }}">
                                    <i class="bi bi-x-lg"></i> Deny
                                </label>
                            </div>
                        </div>

                        <div id="cancellationSection{{ $booking->id }}" style="display: none;" class="mt-3">
                            <label class="label text-danger text-uppercase fw-700 mb-2 d-block" style="font-size: 11px;">Reason for Denial</label>
                            <form id="denyForm{{ $booking->id }}" action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <textarea name="cancellation_reason" class="form-control border-2 shadow-none rounded-3 p-3 mb-0" 
                                          style="font-size: 13px; resize: none; min-height: 100px; background-color: #fff9f9;" 
                                          placeholder="Explain why the booking was denied..."></textarea>
                                <button type="submit" class="d-none"></button>
                            </form>
                        </div>

                        <div class="mt-3 p-3 rounded-3 bg-slate-50 border d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle-fill text-swift-blue mt-1"></i>
                            <p class="text-start text-slate-500 mb-0 fw-600" style="font-size: 11px; line-height: 1.4;">
                            @if($booking->payment_method === 'Pay at Cinema')
                                Please ensure the customer is physically present at the ticketing counter and has successfully settled the full amount before proceeding with this approval.
                            @else
                                Carefully cross-check the reference number and transaction details on the uploaded receipt against your payment gateway records to ensure the funds have been received.
                            @endif
                            </p>
                        </div>

                        <form id="approveForm{{ $booking->id }}" action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="d-none">
                            @csrf @method('PATCH')
                            <button type="submit" class="d-none"></button>
                        </form>
                    </div>
                @elseif($booking->status === 'cancelled')
                    <div class="mt-4 pt-2">
                        <label class="label text-danger text-uppercase fw-700 mb-2 d-block" style="font-size: 11px;">Reason for Denial (Read-only)</label>
                        <textarea class="form-control border-2 shadow-none rounded-3 p-3 mb-0" 
                                  style="font-size: 13px; resize: none; min-height: 100px; background-color: #f8fafc; color: #64748b;" 
                                  readonly>{{ $booking->cancellation_reason ?: 'No specific reason was provided.' }}</textarea>
                        
                        <div class="mt-3 p-3 rounded-3 bg-light border d-flex align-items-start gap-2">
                            <i class="bi bi-slash-circle text-danger mt-1"></i>
                            <p class="text-start text-slate-500 mb-0 fw-600" style="font-size: 11px; line-height: 1.4;">
                                This booking has been cancelled and is no longer eligible for verification. The reason above was recorded during the denial process.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer bg-slate-50 border-0 p-3 px-4 d-flex justify-content-end align-items-center gap-2">
                <button type="button" class="btn btn-link text-slate-500 text-decoration-none fw-700 btn-sm" data-bs-dismiss="modal">
                    Close
                </button>
                
                @if($booking->status === 'pending' || $booking->status === 'change_requested')
                    <button type="submit" 
                            id="submitBtn{{ $booking->id }}"
                            form="approveForm{{ $booking->id }}"
                            class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 rounded-2 shadow-sm btn-sm">
                        {{ $booking->status === 'change_requested' ? 'Review & Approve' : 'Confirm & Approve' }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
if (typeof toggleCancellationReason !== 'function') {
    function toggleCancellationReason(id, mode) {
        const section = document.getElementById('cancellationSection' + id);
        const btn = document.getElementById('submitBtn' + id);
        const status = "{{ $booking->status }}";
        
        if (mode === 'deny') {
            section.style.display = 'block';
            btn.innerText = 'Confirm Cancellation';
            btn.classList.remove('bg-swift-blue', 'btn-primary');
            btn.classList.add('btn-danger');
            
            btn.setAttribute('form', 'denyForm' + id);
        } else {
            section.style.display = 'none';
            btn.innerText = (status === 'change_requested') ? "Review & Approve" : "Confirm & Approve";
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-primary', 'bg-swift-blue');
            
            btn.setAttribute('form', 'approveForm' + id);
        }
    }
}
</script>