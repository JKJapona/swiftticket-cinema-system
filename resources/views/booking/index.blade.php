@extends('layouts.app')

@section('content')


<div class="container py-5">
    
    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-ticket-perforated text-primary fs-2"></i>
            <h1 class="h3 fw-bold mb-0">My Bookings</h1>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm rounded-1 px-3">
            <i class="bi bi-plus-lg me-1"></i> New Booking
        </a>
    </div>

    @if($bookings->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-5 border rounded-3 bg-light">
            <i class="bi bi-camera-reels text-muted display-1"></i>
            <p class="mt-3 text-secondary">No history found. Ready for a movie?</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Browse Now</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($bookings as $booking)
                <div class="col-12 col-xl-6">
                    
                    {{-- 1. LIST VIEW CARD --}}
                    <div class="card ticket-card-border rounded-3 overflow-hidden shadow-none">
                        <div class="row g-0">
                            <div class="col-4 d-none d-sm-block">
                                <img src="{{ $booking->showtime->movie->poster_url }}" 
                                    class="img-fluid h-100 object-fit-cover" 
                                    alt="Poster">
                            </div>
                            <div class="col-12 col-sm-8">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge {{ $booking->status == 'confirmed' ? 'bg-success' : 'bg-warning text-dark' }} text-uppercase px-3">
                                            {{ $booking->status }}
                                        </span>
                                        <small class="text-muted font-monospace fw-bold">#{{ $booking->reference_number }}</small>
                                    </div>

                                    <h2 class="h5 fw-bold mb-1">{{ $booking->showtime->movie->title }}</h2>
                                    <p class="text-secondary small mb-3">
                                        {{ $booking->showtime->hall->name }} • {{ $booking->showtime->hall->screen_type }}
                                    </p>

                                    <div class="row g-2 mb-3 bg-light p-2 rounded-3 text-center">
                                        <div class="col-6">
                                            <small class="d-block text-muted t-stub-label">Schedule</small>
                                            <span class="fw-bold small">
                                                {{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('M d, Y') }}<br>
                                                {{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('h:i A') }}
                                            </span>
                                        </div>
                                        <div class="col-6 border-start ps-3">
                                            <small class="d-block text-muted t-stub-label">Seats</small>
                                            <span class="fw-bold small text-primary">
                                                {{ $booking->seats->pluck('seat_code')->implode(', ') }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                        <span class="fw-bold text-dark fs-5">₱{{ number_format($booking->total_price, 2) }}</span>
                                        <button type="button" class="btn btn-primary btn-sm rounded-1 px-4 shadow-sm fw-bold" 
                                                data-bs-toggle="modal" data-bs-target="#ticketModal{{ $booking->id }}">
                                            View Ticket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. SIMPLIFIED TICKET MODAL --}}
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
                                        <div class="bg-white p-2 rounded-3 shadow-sm mb-3">
                                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ $booking->reference_number }}" 
                                                alt="QR Code" class="img-fluid" style="width: 140px;">
                                        </div>
                                        
                                        <div class="badge {{ $booking->status == 'confirmed' ? 'bg-success' : 'bg-warning' }} mb-3 px-3 py-2">
                                            {{ strtoupper($booking->status) }}
                                        </div>

                                        <button class="btn btn-dark w-100 rounded-3 fw-bold mb-2" onclick="window.print()">
                                            <i class="bi bi-printer me-2"></i> Print
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm w-100 border-0" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    /* Clean typography and structure */
                    #ticketModal{{ $booking->id }} .fw-black { font-weight: 900; }
                    #ticketModal{{ $booking->id }} .modal-content { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
                    
                    @media print {
                        body * { visibility: hidden; }
                        #ticketModal{{ $booking->id }} .modal-content, 
                        #ticketModal{{ $booking->id }} .modal-content * { visibility: visible; }
                        #ticketModal{{ $booking->id }} .modal-content { position: absolute; left: 0; top: 0; width: 100%; }
                        .btn, [data-bs-dismiss="modal"] { display: none !important; }
                    }
                </style>
            @endforeach
        </div>
    @endif
</div>
@endsection