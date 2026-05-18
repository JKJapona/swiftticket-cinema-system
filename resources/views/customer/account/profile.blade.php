@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row g-4">
        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="card border border-primary border-opacity-25 shadow-sm rounded-3 sticky-top sidebar-card">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h6 class="text-uppercase x-small fw-bold text-primary mb-0" style="letter-spacing: 2px;">Account Menu</h6>
                    </div>
                    <div class="list-group list-group-flush rounded-0" id="profile-tabs">
                        <a href="{{ route('profile') }}" 
                           class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center gap-3 {{ request()->routeIs('profile') ? 'active-sidebar' : '' }}">
                            <i class="bi bi-grid-1x2-fill fs-5"></i>
                            <span class="fw-bold">Overview</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" 
                           class="list-group-item list-group-item-action py-3 px-4 border-0 d-flex align-items-center gap-3 {{ request()->routeIs('profile.edit') ? 'active-sidebar' : '' }}">
                            <i class="bi bi-person-gear fs-5"></i>
                            <span class="fw-bold">Profile Settings</span>
                        </a>

                        <div class="p-3 mt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-light-danger w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            {{-- HEADER CARD --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 profile-header-gradient overflow-hidden">
                <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-center text-center text-md-start gap-4 position-relative">
                    <div class="profile-avatar-wrapper mx-auto mx-md-0">
                        <div class="avatar-circle">
                            <span class="fs-1 fw-black">{{ substr(Auth::user()->full_name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-grow-1 mt-3 mt-md-0">
                        <h2 class="fw-black text-white mb-1">{{ Auth::user()->full_name }}</h2>
                        <p class="text-white text-opacity-75 mb-0 fw-medium">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            {{-- SECTION TITLE --}}
            <div class="d-flex align-items-end justify-content-between mb-4">
                <div>
                    <h3 class="fw-black text-slate-900 mb-1">Your Cinema Journey</h3>
                    <p class="text-muted mb-0 small">Manage your upcoming movie experiences</p>
                </div>
                <div class="text-end">
                    <span class="h4 fw-black text-primary mb-0 d-block">{{ $bookings->count() }}</span>
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 10px;">Tickets</span>
                </div>
            </div>

            @forelse($bookings as $booking)
                <div class="card border border-primary border-opacity-25 shadow-sm rounded-2 mb-4 booking-ticket-card overflow-hidden" style="height: 310px;">
                    <div class="row g-0">
                        {{-- Poster Column --}}
                        <div class="col-md-3">
                            <div class="position-relative h-100 skeleton-loader ratio-2-3 overflow-hidden">
                                <img src="{{ $booking->showtime->movie->poster_url ?: asset('images/placeholder-poster.svg') }}" 
                                     class="ticket-poster" 
                                     loading="lazy" 
                                     alt="Movie Poster" 
                                     onload="this.parentElement.classList.remove('skeleton-loader');"
                                     onerror="this.src='{{ asset('images/placeholder-poster.svg') }}';"
                                     width="300" height="450">
                                <div class="poster-overlay"></div>
                            </div>
                        </div>

                        {{-- Info Column --}}
                        <div class="col-md-9 p-4">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                {{-- Left side: Title and Genre --}}
                                <div class="flex-grow-1 min-w-0">
                                    <h4 class="fw-black text-slate-900 mb-1" style="word-break: break-word;">
                                        {{ $booking->showtime->movie->title }}
                                    </h4>
                                    <p class="text-primary fw-bold small mb-0">
                                        <i class="bi bi-camera-reels-fill me-2"></i>{{ $booking->showtime->movie->genre ?? 'TBA' }}
                                    </p>
                                </div>

                                {{-- Right side: Status Badge --}}
                                @php
                                    $statusMap = [
                                        'confirmed'        => ['icon' => 'bi-check-circle-fill'],
                                        'pending'          => ['icon' => 'bi-clock-fill'],
                                        'change_requested' => ['icon' => 'bi-arrow-repeat'],
                                        'cancelled'        => ['icon' => 'bi-x-circle-fill'],
                                    ];
                                    $icon = $statusMap[$booking->status]['icon'] ?? 'bi-x-circle-fill';
                                @endphp

                                <div class="flex-shrink-0" style="min-width: 140px; text-align: right;">
                                    <span class="status-badge {{ $booking->status }} shadow-sm">
                                        <i class="bi {{ $icon }} me-1"></i>
                                        {{ str_replace('_', ' ', $booking->status) }}
                                    </span>
                                </div>
                            </div>

                            <div class="booking-grid mb-4">
                                <div class="grid-item">
                                    <span class="label">Date</span>
                                    <span class="value">{{ \Carbon\Carbon::parse($booking->showtime->show_date)->format('D, M d') }}</span>
                                </div>
                                <div class="grid-item">
                                    <span class="label">Showtime</span>
                                    <span class="value">{{ \Carbon\Carbon::parse($booking->showtime->show_time)->format('h:i A') }}</span>
                                </div>
                                <div class="grid-item">
                                    <span class="label">Cinema</span>
                                    <span class="value">{{ $booking->showtime->hall->name }}</span>
                                </div>
                                <div class="grid-item">
                                    <span class="label">Seats</span>
                                    <span class="value text-primary">
                                        @foreach($booking->seats as $seat)
                                            {{ $seat->seat_code }}{{ !$loop->last ? ',' : '' }}
                                        @endforeach
                                    </span>
                                </div>
                            </div>

                            @if($booking->status === 'cancelled')
                                <div class="mb-3 p-3 rounded-3 border-0 border-start border-4 shadow-sm" 
                                    style="background-color: #fef2f2; border-left-color: #ef4444 !important;">
                                    <div class="d-flex gap-2">
                                        <i class="bi bi-exclamation-octagon-fill text-danger"></i>
                                        <div>
                                            <h6 class="fw-bold text-danger small mb-1">Cancellation Reason</h6>
                                            <p class="text-slate-600 mb-0 lh-sm" style="font-size: 12px;">
                                                {{ $booking->cancellation_reason ?? 'The admin has cancelled this booking. Please contact support for more details.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="p-2 bg-light rounded-3">
                                        <i class="bi bi-qr-code text-slate-900"></i>
                                    </div>
                                    <span class="text-muted small fw-bold">ID: #ST-{{ $booking->id + 1000 }}</span>
                                </div>
                                <div class="d-flex gap-2">
                                    @if($booking->status !== 'cancelled')
                                    <button class="btn btn-outline-secondary rounded-2 px-4 fw-bold btn-sm-custom" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#customerChangeSeat{{ $booking->id }}">
                                        Manage Seats
                                    </button>
                                    @endif
                                    <button class="btn btn-primary rounded-2 px-4 fw-bold shadow-sm btn-sm-custom" 
                                            data-bs-toggle="modal" data-bs-target="#ticketModal{{ $booking->id }}">
                                        View Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card border border-primary border-opacity-25 shadow-sm rounded-2 py-3 mt-4">
                    <div class="card-body text-center py-2">

                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-ticket-perforated text-muted" style="font-size: 2rem;"></i>
                        </div>
                        
                        <h5 class="fw-black text-slate-900 mb-2">No Bookings Found</h5>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                            It looks like you haven't reserved any seats yet. Ready to watch something amazing?
                        </p>
                        
                        <a href="{{ route('home') }}" class="btn btn-primary bg-swift-blue rounded-2 px-4 fw-bold shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>Browse Movies
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Modals --}}
@foreach($bookings as $booking)
    @include('customer.account.ticket-view')
    @include('customer.account.seat-selection')
@endforeach

<style>
    @media print {
    body * {
        visibility: hidden;
        height: 0;
        margin: 0;
        padding: 0;
    }

    .modal.show, 
    .modal.show .modal-dialog, 
    .modal.show .modal-content, 
    .modal.show .modal-body, 
    .modal.show .modal-body * {
        visibility: visible;
        height: auto;
    }

    .modal.show {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        display: block !important;
    }

    .modal-dialog {
        margin: 0;
        max-width: 100%;
        width: 100%;
    }

    .modal.show .btn, 
    .modal.show .btn-outline-secondary,
    .modal.show .btn-dark {
        display: none !important;
    }

    .modal.show img[src*="swiftticket_abreeza"] {
        height: 40px !important;
        width: auto !important;
        display: block !important;
        margin-bottom: 15px !important;
        visibility: visible !important;
    }

    .modal.show .d-flex.align-items-center.gap-2.mb-4 {
        display: flex !important;
        height: auto !important;
    }

    .bg-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
    }
    .badge {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection