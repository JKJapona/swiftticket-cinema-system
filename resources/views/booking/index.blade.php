@extends('layouts.app')

@section('content')


<div class="container py-5">
    
    {{-- Header Section --}}
    <div class="d-flex align-items-center justify-content-between mb-5">
        <div class="d-flex align-items-center gap-3">
            <i class="bi bi-ticket-perforated text-primary fs-2"></i>
            <h1 class="h3 fw-bold mb-0">My Bookings</h1>
        </div>
        <a href="{{ route('home') }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">
            <i class="bi bi-plus-lg me-1"></i> New Booking
        </a>
    </div>

    @if($bookings->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-5 border rounded-4 bg-light">
            <i class="bi bi-camera-reels text-muted display-1"></i>
            <p class="mt-3 text-secondary">No history found. Ready for a movie?</p>
            <a href="{{ route('home') }}" class="btn btn-primary px-4">Browse Now</a>
        </div>
    @else
        <div class="row g-4">
            @foreach($bookings as $booking)
                <div class="col-12 col-xl-6">
                    
                    {{-- 1. LIST VIEW CARD --}}
                    <div class="card ticket-card-border rounded-4 overflow-hidden shadow-none">
                        <div class="row g-0">
                            <div class="col-4 d-none d-sm-block">
                                <img src="{{ asset($booking->showtime->movie->poster_path) }}" 
                                    class="img-fluid h-100 object-fit-cover" alt="Poster">
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
                                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm fw-bold" 
                                                data-bs-toggle="modal" data-bs-target="#ticketModal{{ $booking->id }}">
                                            View Ticket
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. LANDSCAPE TICKET MODAL --}}
                <div class="modal fade" id="ticketModal{{ $booking->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content bg-transparent border-0">
                            
                            <div class="ticket-landscape">
                                
                                {{-- LEFT SIDE: MAIN TICKET --}}
                                <div class="p-5 flex-grow-1 d-flex flex-column justify-content-between">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 28px;">
                                            <span class="font-monospace fw-bold text-muted">REF: {{ $booking->reference_number }}</span>
                                        </div>

                                        <h1 class="t-movie-title mb-2">{{ strtoupper($booking->showtime->movie->title) }}</h1>
                                        <p class="text-secondary fw-medium mb-4">
                                            <i class="bi bi-geo-alt-fill me-1 text-primary"></i> {{ $booking->showtime->hall->name }} • {{ $booking->showtime->hall->screen_type }}
                                        </p>

                                        <div class="row g-0 border-top border-bottom py-3 mb-4">
                                            <div class="col-3">
                                                <span class="t-stub-label d-block">Date</span>
                                                <span class="fw-bold fs-5 text-dark">{{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="col-3 border-start ps-3">
                                                <span class="t-stub-label d-block">Time</span>
                                                <span class="fw-bold fs-5 text-dark">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('h:i A') }}</span>
                                            </div>
                                            <div class="col-3 border-start ps-3">
                                                <span class="t-stub-label d-block">Total Paid</span>
                                                <span class="price-display">₱{{ number_format($booking->total_price, 2) }}</span>
                                            </div>
                                            <div class="col-3 border-start ps-3">
                                                <span class="t-stub-label d-block">Status</span>
                                                <span class="fw-bold fs-5 {{ $booking->status == 'confirmed' ? 'text-success' : 'text-warning' }}">
                                                    {{ strtoupper($booking->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Seat Visualization --}}
                                    <div class="d-flex align-items-center gap-4 bg-light p-3 rounded-4">
                                        <div class="t-stub-label" style="writing-mode: vertical-lr; transform: rotate(180deg);">Your Seats</div>
                                        <div class="d-flex gap-2">
                                            @foreach($booking->seats as $seat)
                                                <div class="seat-mini-box">{{ $seat->seat_code }}</div>
                                            @endforeach
                                        </div>
                                        <div class="ms-auto text-end border-start ps-4">
                                            <span class="t-stub-label d-block mb-1">Gate Entry</span>
                                            <span class="fw-bold">Level 4, Abreeza Mall</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- MIDDLE: PERFORATION --}}
                                <div class="ticket-divider-horizontal"></div>

                                {{-- RIGHT SIDE: QR STUB --}}
                                <div class="p-5 d-flex flex-column align-items-center justify-content-center text-center bg-white" style="width: 320px; min-width: 320px;">
                                    <div class="qr-box mb-3 shadow-sm">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ $booking->reference_number }}" 
                                             alt="QR Code" class="img-fluid">
                                    </div>
                                    
                                    <h5 class="fw-bold mb-1">ENTRY PASS</h5>
                                    <p class="t-caption text-muted mb-4 small">Scan at entrance gate</p>

                                    <div class="w-100">
                                        @if($booking->status == 'pending')
                                            <div class="alert alert-warning py-2 small mb-3 border-0 fw-medium">
                                                <i class="bi bi-wallet2 me-1"></i> Pay at Counter
                                            </div>
                                        @else
                                            <div class="alert alert-success py-2 small mb-3 border-0 fw-medium">
                                                <i class="bi bi-patch-check-fill me-1"></i> Valid Ticket
                                            </div>
                                        @endif
                                        
                                        <button class="btn btn-outline-dark btn-sm w-100 rounded-pill py-2 fw-bold" onclick="window.print()">
                                            <i class="bi bi-printer me-2"></i> PRINT TICKET
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Dismiss Button --}}
                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-link text-white text-decoration-none opacity-75" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-2"></i> Dismiss
                                </button>
                            </div>

                        </div>
                    </div>
                </div> {{-- End Modal --}}
            @endforeach
        </div>
    @endif
</div>
@endsection