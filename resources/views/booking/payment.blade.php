@extends('layouts.app')

@section('content')

<div class="container checkout-container">
    <form action="{{ route('bookings.store') }}" method="POST">
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
                        <div class="col-md-12">
                            <label class="label-custom">Phone Number</label>
                            <input type="text" name="phone_number" class="input-custom" placeholder="09XX XXX XXXX">
                        </div>
                    </div>
                </div>

                <div class="section-card">
                    <h2 class="heading-h4">Payment Method</h2>
                    
                    <label class="payment-option d-flex align-items-center gap-3 active" onclick="togglePayment('onsite')">
                        <input type="radio" name="payment_method" value="Pay at Cinema" checked>
                        <i class="bi bi-wallet2 text-primary fs-5"></i>
                        <div class="flex-grow-1 d-flex align-items-center gap-2">
                            <span class="fw-bold small">Pay at Cinema</span>
                            <span class="badge bg-success" style="font-size: 9px;">RECOMMENDED</span>
                        </div>
                    </label>

                    <label class="payment-option d-flex align-items-center gap-3" onclick="togglePayment('card')">
                        <input type="radio" name="payment_method" value="Credit/Debit Card">
                        <i class="bi bi-credit-card fs-5"></i>
                        <span class="fw-bold small">Credit / Debit Card</span>
                    </label>

                    <label class="payment-option d-flex align-items-center gap-3" onclick="togglePayment('gcash')">
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
                                <span class="small opacity-75">Counter Hours: 10:00 AM - 10:00 PM</span>
                            </div>
                        </div>
                        <div class="reminder-box shadow-sm">
                            <strong class="text-warning-emphasis"><i class="bi bi-exclamation-circle"></i> Important Reminders:</strong>
                            <ul class="mb-0 mt-1 ps-3">
                                <li>Present your booking reference at the cinema counter</li>
                                <li>Arrive at least 30 minutes before showtime</li>
                                <li>Payment can be made via cash or card at the counter</li>
                            </ul>
                        </div>
                    </div>

                    <div id="panel-card" class="payment-panels d-none">
                        <div class="payment-detail-panel shadow-sm mt-3">
                            <div class="mb-2">
                                <label class="label-custom">Card Number</label>
                                <input type="text" id="card_number" name="card_number" class="input-custom" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="row g-2">
                                <div class="col-8">
                                    <label class="label-custom">Expiry Date</label>
                                    <input type="text" id="card_expiry" name="card_expiry" class="input-custom" placeholder="MM/YY">
                                </div>
                                <div class="col-4">
                                    <label class="label-custom">CVV</label>
                                    <input type="text" id="card_cvv" name="card_cvv" class="input-custom" placeholder="123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="panel-gcash" class="payment-panels d-none mt-3">
                        <div class="payment-detail-panel text-center shadow-sm">
                            <span class="text-muted small">You will be redirected to GCash to complete your payment.</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Booking Summary --}}
            <div class="col-lg-5">
                <div class="section-card">
                    <h2 class="heading-h4">Booking Summary</h2>
                    
                    <div class="d-flex gap-3 align-items-center mb-2">
                        <img src="{{ $showtime->movie->poster_url }}" class="movie-thumb" alt="{{ $showtime->movie->title }}">
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

                <button type="submit" class="btn-complete mb-2" id="submit-btn">
                    Reserve Seats - ₱{{ number_format($totalAmount, 0) }} Onsite
                </button>
            </div>
        </div>
    </form>
</div>

<script>
function togglePayment(type) {
    document.querySelectorAll('.payment-option').forEach(el => el.classList.remove('active'));
    document.querySelectorAll('.payment-panels').forEach(el => el.classList.add('d-none'));
    
    const selectedOption = event.currentTarget;
    selectedOption.classList.add('active');
    selectedOption.querySelector('input').checked = true;
    document.getElementById('panel-' + type).classList.remove('d-none');
    
    const cardFields = ['card_number', 'card_expiry', 'card_cvv'];
    
    cardFields.forEach(id => {
        const input = document.getElementById(id);
        if (type === 'card') {
            input.setAttribute('required', 'required');
        } else {
            input.removeAttribute('required');
            input.value = '';
        }
    });

    const btn = document.getElementById('submit-btn');
    const label = document.getElementById('total-label');
    const formattedAmount = "₱{{ number_format($totalAmount, 0) }}";
    
    if(type === 'onsite') {
        btn.innerText = "Reserve Seats - " + formattedAmount + " Onsite";
        label.innerText = "Total (Pay Onsite)";
    } else {
        btn.innerText = "Complete Payment - " + formattedAmount;
        label.innerText = "Total Amount";
    }
}
</script>
@endsection