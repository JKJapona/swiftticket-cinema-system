@extends('layouts.app')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@section('content')

<div class="container checkout-container">
    <form action="{{ route('bookings.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Hidden Fields --}}
        <input type="hidden" name="showtime_id" value="{{ $showtime->id }}">
        <input type="hidden" name="selected_seats" value="{{ implode(',', $selectedSeats) }}">
        <input type="hidden" name="total_price" value="{{ $totalAmount }}">

        <div class="row g-3">
            {{-- Left Column: User & Payment Details --}}
            <div class="col-lg-7">
                
                <div class="section-card">
                    <h2 class="heading-h4">Contact Information</h2>
                    <div class="row g-2">
                        <div class="col-md-12 mb-2">
                            <label class="label-custom">Full Name</label>
                            <input type="text" class="input-custom" value="{{ Auth::user()->full_name }}" readonly>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label class="label-custom">Email Address</label>
                            <input type="email" class="input-custom" value="{{ Auth::user()->email }}">
                            <span class="text-muted" style="font-size: 11px;">Your ticket will be sent to this email</span>
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <h2 class="heading-h4">Payment Method</h2>
                    
                    <label class="payment-option d-flex align-items-center gap-3 active" onclick="togglePayment('onsite', event)">
                        <input type="radio" name="payment_method" value="Pay at Cinema" checked>
                        <i class="bi bi-wallet2 text-primary fs-5"></i>
                        <div class="flex-grow-1 d-flex align-items-center gap-2">
                            <span class="fw-bold small">Pay at Cinema</span>
                            <span class="badge bg-success" style="font-size: 9px;">RECOMMENDED</span>
                        </div>
                    </label>

                    <label class="payment-option d-flex align-items-center gap-3" onclick="togglePayment('gcash', event)">
                        <input type="radio" name="payment_method" value="GCash">
                        <i class="bi bi-phone fs-5"></i>
                        <span class="fw-bold small">GCash</span>
                    </label>

                    <div id="panel-onsite" class="payment-panels mt-3">
                        <div class="info-box d-flex gap-2">
                            <i class="bi bi-geo-alt-fill fs-6 mt-1"></i>
                            <div>
                                <strong>Ayala Malls Abreeza Cinema</strong><br>
                                JP Laurel Avenue, Bajada, Davao City<br>
                                <span class="small opacity-75">Counter Hours: 10:00 AM - 12:30 AM</span>
                            </div>
                        </div>
                        {{-- Pay at Cinema Reminder --}}
                        <div class="reminder-box shadow-sm mt-2" style="border-left: 4px solid #f59e0b; background-color: #fffbeb;">
                            <strong style="color: #92400e; font-size: 12px;">
                                <i class="bi bi-exclamation-circle-fill me-1"></i> Important Reminders:
                            </strong>
                            <ul class="mb-0 mt-1 ps-3 text-amber-800" style="font-size: 11px; line-height: 1.4;">
                                <li>Present your <strong>Booking Reference</strong> at the cinema counter</li>
                                <li>Please arrive at least <strong>30 minutes</strong> before showtime</li>
                                <li>Counter accepts Cash and GCash payments</li>
                            </ul>
                        </div>
                    </div>

<div id="panel-gcash" class="payment-panels d-none mt-3 animate__animated animate__fadeIn">
    <div class="payment-detail-panel shadow-sm border rounded-4 bg-white overflow-hidden">
        
        {{-- Simulation/Payment Instruction Header (The "Info Box" equivalent) --}}
        {{-- Refined GCash Instruction Header --}}
<div class="py-2 border-bottom" style="background: linear-gradient(to right, #f8fafc, #f1f5f9);">
    <div class="d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            {{-- Icon with soft shadow and brand color --}}
            <div class="bg-primary shadow-sm d-flex align-items-center justify-content-center rounded-3" 
                 style="width: 44px; height: 44px; background: #007dfe !important;">
                <i class="bi bi-qr-code-scan text-white fs-5"></i>
            </div>
            <div>
                <h6 class="mb-0 fw-bold text-dark" style="font-size: 14px; letter-spacing: -0.3px;">Digital Payment</h6>
                <p class="text-muted mb-0" style="font-size: 11px;">Scan QR to settle your total amount</p>
            </div>
        </div>

        {{-- Action Button --}}
        <a href="{{ route('payment.gcash') }}" 
           target="_blank" 
           class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 d-flex align-items-center gap-2"
           style="font-size: 11px; font-weight: 700; transition: all 0.2s ease;">
            <span class="text-primary">PAY VIA QR</span>
            <i class="bi bi-box-arrow-up-right text-muted" style="font-size: 9px;"></i>
        </a>
    </div>
</div>
            {{-- Receipt Upload Area --}}
            <div class="mb-3">
                <label class="form-label fw-600 text-slate-700" style="font-size: 12px;">Proof of Payment</label>
                
                <div class="upload-container position-relative border border-dashed rounded-3 p-3 text-center bg-light-subtle" id="drop-zone">
                    <input type="file" name="payment_receipt" id="receipt-upload" 
                           class="position-absolute top-0 start-0 w-100 h-100 opacity-0" 
                           style="cursor: pointer;" accept="image/*">
                    
                    <div id="upload-placeholder">
                        <i class="bi bi-cloud-arrow-up fs-3 text-primary opacity-75"></i>
                        <p class="mb-0 mt-1 fw-medium text-slate-600" style="font-size: 11px;">Click to upload receipt screenshot</p>
                        <p class="text-muted mb-0" style="font-size: 9px;">JPG or PNG (Max 5MB)</p>
                    </div>

                    <div id="file-preview-container" class="d-none">
                        <div class="d-flex align-items-center justify-content-center gap-2 border rounded-2 p-2 bg-white">
                            <i class="bi bi-image text-success"></i>
                            <span id="file-name-display" class="text-truncate fw-bold text-slate-700" style="max-width: 150px; font-size: 11px;"></span>
                            <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                        </div>
                    </div>
                </div>

                <div id="receipt-error" class="text-danger mt-2 d-none animate__animated animate__headShake" style="font-size: 10px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> Please upload your GCash receipt to proceed.
                </div>
            </div>
            
            {{-- Status Disclosure (The "Reminder Box" equivalent) --}}
            <div class="reminder-box shadow-sm mt-2" style="border-left: 4px solid #f59e0b; background-color: #fffbeb;">
                <strong style="color: #92400e; font-size: 12px;"><i class="bi bi-info-circle"></i> Verification Notice:</strong>
                <ul class="mb-0 mt-1 ps-3 text-amber-800" style="font-size: 11px; line-height: 1.4;">
                    <li>Booking remains <strong>Pending</strong> until manually verified</li>
                    <li>Ensure the Ref No. and Amount are clearly visible</li>
                    <li>Unverified receipts may result in booking cancellation</li>
                </ul>
            </div>
        </div>
    </div>
</div>
                </div>

            {{-- Right Column: Booking Summary --}}
            <div class="col-lg-5">
                <div class="section-card">
                    <h2 class="heading-h4">Booking Summary</h2>
                    
                    <div class="d-flex gap-3 align-items-center mb-2">
                        <div class="skeleton-loader rounded-2 overflow-hidden shadow-sm" 
                            style="width: 50px; height: 70px; flex-shrink: 0; background-color: #f1f5f9;">
                            <img src="{{ $showtime->movie->poster_url ?: asset('images/placeholder-poster.svg') }}" 
                                class="w-100 h-100" 
                                alt="{{ $showtime->movie->title }}" 
                                style="object-fit: cover; opacity: 0; transition: opacity 0.3s ease;"
                                onload="this.parentElement.classList.remove('skeleton-loader'); this.style.opacity='1';"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-poster.svg') }}'; this.style.opacity='1'; this.parentElement.classList.remove('skeleton-loader');">
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0" style="font-size: 16px;">{{ $showtime->movie->title }}</h3>
                            <p class="text-muted mb-1" style="font-size: 12px;">{{ $showtime->movie->genre }} • {{ \Carbon\Carbon::parse($showtime->movie->release_date)->format('Y') }}</p>
                            <span class="badge bg-light text-dark border" style="font-size: 10px;">{{ floor($showtime->movie->runtime_minutes / 60) }}h {{ $showtime->movie->runtime_minutes % 60 }}m</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Location</span>
                            <span class="fw-medium">Abreeza Cinema</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Date</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($showtime->show_date)->format('Y-m-d') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Time</span>
                            <span class="fw-medium">{{ \Carbon\Carbon::parse($showtime->show_time)->format('H:i') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Cinema</span>
                            <span class="fw-medium">{{ $showtime->hall->name }}</span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <label class="text-muted d-block mb-1">Selected Seats</label>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($selectedSeats as $seat)
                                <span class="seat-badge shadow-sm">{{ $seat }}</span>
                            @endforeach
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Tickets ({{ count($selectedSeats) }}x)</span>
                            <span class="fw-bold">₱{{ number_format($totalAmount, 0) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Booking Fee</span>
                            <span class="text-success fw-bold">FREE</span>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="fw-bold" id="total-label">Total (Pay Onsite)</span>
                        <span class="price-total">₱{{ number_format($totalAmount, 0) }}</span>
                    </div>
                </div>

                <button type="submit" class="btn-complete mb-2" id="submit-btn" data-skip-global-loader="true">
                    Reserve Seats - ₱{{ number_format($totalAmount, 0) }} Onsite
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Processing Overlay --}}
<div id="payment-overlay" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center" 
     style="background: rgba(255, 255, 255, 0.95); z-index: 9999; backdrop-filter: blur(4px);">
    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem; border-width: 0.25em;"></div>
    <h5 class="fw-bold text-slate-900 mb-1">Finalizing Your Booking</h5>
    <p class="text-muted small">Securing your seats at Abreeza Cinema...</p>
</div>

<script>
    function togglePayment(type, event) {
        document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.payment-panels').forEach(el => el.classList.add('d-none'));
        
        const selectedOption = event.currentTarget;
        selectedOption.classList.add('active');
        selectedOption.querySelector('input').checked = true;
        
        document.getElementById('panel-' + type).classList.remove('d-none');
        
        const btn = document.getElementById('submit-btn');
        const label = document.getElementById('total-label');
        const formattedAmount = "₱{{ number_format($totalAmount, 0) }}";
        
        // Reset validation state when switching
        document.getElementById('receipt-error').classList.add('d-none');
        
        if(type === 'onsite') {
            btn.innerText = "Reserve Seats - " + formattedAmount + " Onsite";
            label.innerText = "Total (Pay Onsite)";
        } else {
            btn.innerText = "Complete Payment - " + formattedAmount;
            label.innerText = "Total Amount";
        }
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const btn = document.getElementById('submit-btn');
        const overlay = document.getElementById('payment-overlay');
        const receiptInput = document.getElementById('receipt-upload');
        const receiptError = document.getElementById('receipt-error');

        // GCASH VALIDATION LOGIC
        if (paymentMethod === 'GCash') {
            if (receiptInput.files.length === 0) {
                e.preventDefault(); // Stop form
                receiptError.classList.remove('d-none');
                receiptInput.classList.add('is-invalid');
                return false;
            }
        }

        // If validation passes, show overlay and process
        overlay.classList.remove('d-none');
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btn.innerText = "Processing...";
    });

    document.getElementById('receipt-upload').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    const placeholder = document.getElementById('upload-placeholder');
    const preview = document.getElementById('file-preview-container');
    const nameDisplay = document.getElementById('file-name-display');
    const error = document.getElementById('receipt-error');

    if (fileName) {
        placeholder.classList.add('d-none');
        preview.classList.remove('d-none');
        nameDisplay.innerText = fileName;
        error.classList.add('d-none');
        this.closest('.upload-container').style.borderColor = '#10b981';
    } else {
        placeholder.classList.remove('d-none');
        preview.classList.add('d-none');
        this.closest('.upload-container').style.borderColor = '#cbd5e1';
    }
});
</script>


@endsection