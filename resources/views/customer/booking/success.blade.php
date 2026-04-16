@extends('layouts.app')

@section('content')


<div class="success-container">
    <div class="container" style="max-width: 1000px;">
        
        <div class="ticket-main-card">
            <div class="row g-0">
                {{-- Left Side: Details --}}
                <div class="col-md-8 details-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <div class="info-label">Booking Reference</div>
                            <div class="ref-text">{{ $booking->reference_number }}</div>
                        </div>
                        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 24px; opacity: 0.8;">
                    </div>

                    <div class="d-flex gap-4 align-items-center mb-4 pb-4 border-bottom">
                        <div class="skeleton-loader rounded-3 overflow-hidden" style="width: 80px; height: 120px; flex-shrink: 0;">
                            <img src="{{ $showtime->movie->poster_url }}" 
                                class="w-100 h-100" 
                                alt="{{ $showtime->movie->title }}" 
                                style="object-fit: cover; opacity: 0; transition: opacity 0.5s ease; display: block;"
                                onload="this.parentElement.classList.remove('skeleton-loader'); this.style.opacity='1';"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-poster.svg') }}'; this.parentElement.classList.remove('skeleton-loader'); this.style.opacity='1';">
                        </div>

                        <div>
                            <h2 class="movie-title-success mb-2">{{ $showtime->movie->title }}</h2>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-secondary border px-2 py-1">{{ $showtime->movie->genre }}</span>
                                <span class="badge bg-light text-secondary border px-2 py-1">{{ $showtime->movie->runtime_minutes }} min</span>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6 col-md-3">
                            <div class="info-label">Date</div>
                            <div class="info-data">{{ \Carbon\Carbon::parse($showtime->show_date)->format('D, M d, Y') }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Time</div>
                            <div class="info-data">{{ \Carbon\Carbon::parse($showtime->show_time)->format('h:i A') }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Cinema</div>
                            <div class="info-data">{{ $showtime->hall->name }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="info-label">Total Paid</div>
                            <div class="price-display">₱{{ number_format($booking->total_price, 2) }}</div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="info-label mb-2">Your Seats</div>
                        <div class="d-flex gap-2 flex-wrap">
                            @foreach($selectedSeats as $seat)
                                <span class="seat-pill"><i class="bi bi-armchair-fill me-2"></i>{{ $seat }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Right Side: Stub --}}
                <div class="col-md-4 digital-ticket-vibe text-center">
                    <div class="info-label mb-3" style="letter-spacing: 2px;">Entry Pass</div>
                    
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
                    
                    <div class="px-3">
                        <div class="fw-bold text-dark mb-1">{{ $booking->reference_number }}</div>
                        <p class="small text-muted mb-0">Scan code at entrance</p>
                    </div>

                    <div class="mt-4 pt-3 border-top mx-3">
                        <p class="small text-muted mb-0">
                            <i class="bi bi-info-circle me-1"></i> Valid at Abreeza Cinema Davao.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($booking->payment_method == 'Pay at Cinema')
            <div class="alert mt-4 border-0 shadow-sm d-flex align-items-center gap-4 p-3" style="background: #ffffff; border-left: 6px solid #fbbf24 !important; border-radius: 12px;">
                <i class="bi bi-exclamation-triangle-fill fs-3 text-warning"></i>
                <div>
                    <h6 class="fw-bold text-dark mb-1">Pay at Counter</h6>
                    <p class="small text-secondary mb-0">Settle payment at Ayala Malls Abreeza Cinema to validate tickets.</p>
                </div>
            </div>
        @endif

        @if($booking->payment_method == 'GCash')
            <div class="alert mt-4 border-0 shadow-sm d-flex align-items-center gap-4 p-3" style="background: #ffffff; border-left: 6px solid #007dff !important; border-radius: 12px;">
                <i class="bi bi-receipt-cutoff fs-3" style="color: #007dff;"></i>
                <div>
                    <h6 class="fw-bold text-dark mb-1">Payment Under Review</h6>
                    <p class="small text-secondary mb-0">We are verifying your GCash receipt. Your ticket will be confirmed shortly!</p>
                </div>
            </div>
        @endif

        <div class="d-flex justify-content-center gap-3 mt-4">
            <button id="download-pdf" class="btn btn-outline-custom">
                <i class="bi bi-download me-2"></i>PDF
            </button>
            
            <button id="share-ticket" class="btn btn-outline-custom">
                <i class="bi bi-share me-2"></i>Share
            </button>
            
            <a href="{{ route('home') }}" class="btn btn-swift-home d-flex align-items-center">
                <i class="bi bi-house-door-fill me-2"></i> Back Home
            </a>
        </div>
    </div>
</div>

<script>
function handleQrError(image) {
        image.style.display = 'none';
        
        const parent = image.parentElement;
        parent.classList.remove('skeleton-loader');
        
        const errorState = document.getElementById('qr-error-state');
        errorState.classList.remove('d-none');
        errorState.classList.add('d-flex');
    }

    // Back button prevention
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        window.location.replace("{{ route('home') }}");
    };
</script>

<script>
    document.getElementById('download-pdf').addEventListener('click', function() {
        window.print();
    });

    document.getElementById('share-ticket').addEventListener('click', async function() {
        const shareData = {
            title: 'My SwiftTicket - {{ $showtime->movie->title }}',
            text: 'I just booked tickets for {{ $showtime->movie->title }} at Abreeza Cinema! Ref: {{ $booking->reference_number }}',
            url: window.location.href
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                await navigator.clipboard.writeText(`${shareData.text} ${shareData.url}`);
                alert('Ticket details copied to clipboard!');
            }
        } catch (err) {
            console.error('Error sharing:', err);
        }
    });
</script>

<style>
    @media print {
    .btn-outline-custom, 
    .btn-swift-home, 
    nav, 
    footer,
    .alert {
        display: none !important;
    }

    body {
        background: white !important;
    }
    
    .ticket-main-card {
        box-shadow: none !important;
        border: 1px solid #eee !important;
    }
}
</style>
@endsection