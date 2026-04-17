@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Seat Bookings</h1>
            <p class="text-secondary mb-0">Track and manage all customer reservations and payments.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        {{-- Total Revenue --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Revenue</span>
                <h3 class="stat-value mb-0 text-end">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
            </div>
        </div>

        {{-- Change Requests --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-info border-4">
                <span class="caption d-block mb-1">Change Requests</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['change_requests_count'] ?? 0 }}</h3>
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-warning border-4">
                <span class="caption d-block mb-1">Pending Payments</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['pending_count'] }}</h3>
            </div>
        </div>

        {{-- Confirmed Bookings --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-success border-4">
                <span class="caption d-block mb-1">Confirmed Bookings</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['confirmed_count'] }}</h3>
            </div>
        </div>
    </div>

    {{-- BOOKING FILTERS --}}
    <div class="section-card p-3 mb-4 border-0 shadow-sm">
        <form action="{{ route('admin.bookings.index') }}" method="GET">
            <div class="row align-items-center g-3">
                {{-- Search Input --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <span class="caption text-slate-500 text-nowrap">Search:</span>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0 text-slate-400 py-2">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" 
                                class="form-control border-start-0 ps-0 shadow-none py-2 fw-600" 
                                placeholder="Ref # or Customer Name..."
                                value="{{ request('search') }}"
                                style="border-color: #e2e8f0;">
                        </div>
                    </div>
                </div>

                {{-- Status & Actions --}}
                <div class="col-md-6">
                    <div class="d-flex align-items-center justify-content-md-end gap-3">
                        <span class="caption text-slate-500 text-nowrap">Status:</span>
                        <select name="status" class="form-select form-select-sm shadow-none fw-600 w-auto" 
                                style="border-color: #e2e8f0; min-width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="change_requested" {{ request('status') == 'change_requested' ? 'selected' : '' }}>Change Requests</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        
                        <div class="vr mx-1 text-slate-300"></div>
                        
                        <button type="submit" class="btn btn-sm btn-primary bg-swift-blue px-3 fw-700 btn-small">Filter</button>
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-light border px-3 fw-700">Reset</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- 3. TABLE SECTION --}}
    <div class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">Ref #</th>
                        <th class="py-3 caption">Customer</th>
                        <th class="py-3 caption">Movie & Hall</th>
                        <th class="py-3 caption text-center">Seats</th>
                        <th class="py-3 caption">Total</th>
                        <th class="py-3 caption text-center">Status</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td class="ps-4">
                                <span class="fw-700 text-primary">{{ $booking->reference_number }}</span>
                                <div class="caption" style="font-size: 10px;">{{ $booking->created_at->format('M d, H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-700 text-slate-900 small">{{ $booking->user->full_name }}</div>
                                <div class="caption text-capitalize" style="font-size: 10px;">
                                    <i class="bi bi-wallet2 me-1"></i>{{ $booking->payment_method }}
                                </div>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900 small">{{ $booking->showtime->movie->title }}</div>
                                <div class="caption" style="font-size: 10px;">{{ $booking->showtime->hall->name }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    @if($booking->status === 'cancelled')
                                        <span class="text-muted small" style="font-size: 10px; font-style: italic;">Seats Released</span>
                                    @else
                                        @foreach($booking->bookedSeats as $seat)
                                            <span class="badge bg-light text-secondary border fw-500" style="font-size: 9px;">
                                                {{ $seat->seat_code }}
                                            </span>
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900">₱{{ number_format($booking->total_price, 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge border-0 
                                    {{ $booking->status === 'confirmed' ? 'text-success' : 
                                    ($booking->status === 'pending' ? '' : 
                                    ($booking->status === 'change_requested' ? '' : 'text-danger')) }}" 
                                    style="padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center;
                                        @if($booking->status === 'confirmed') 
                                            background-color: #dcfce7; color: #166534; 
                                        @elseif($booking->status === 'pending') 
                                            background-color: #fef3c7; color: #92400e;
                                        @elseif($booking->status === 'change_requested') 
                                            background-color: #e0e7ff; color: #3730a3;
                                        @else 
                                            background-color: #fee2e2; color: #991b1b;
                                        @endif">
                                    
                                    <i class="bi {{ $booking->status === 'confirmed' ? 'bi-check-circle-fill' : 
                                        ($booking->status === 'pending' ? 'bi-clock-fill' : 
                                        ($booking->status === 'change_requested' ? 'bi-arrow-repeat' : 'bi-x-circle-fill')) }} me-1" 
                                    style="font-size: 11px;"></i> 
                                    
                                    {{ str_replace('_', ' ', $booking->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-1">
                                    
                                    <button class="btn btn-sm btn-light border text-primary rounded-2 shadow-sm" 
                                            style="width: 32px; height: 32px;"
                                            data-bs-toggle="modal" data-bs-target="#viewModal{{ $booking->id }}"
                                            title="View Details">
                                        <i class="bi bi-eye"></i>
                                    </button>

                                    @if($booking->status !== 'cancelled')
                                        <button class="btn btn-sm btn-light border rounded-2 shadow-sm" 
                                                style="color: #6366f1; width: 32px; height: 32px;"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#adminSeatOverride{{ $booking->id }}" 
                                                title="Manage Seats">
                                            <i class="bi bi-grid-3x3-gap"></i>
                                        </button>
                                    @endif

                                    @php
                                        $isActionable = ($booking->status === 'pending' || $booking->status === 'change_requested');
                                    @endphp
                                    
                                    <button type="button" class="btn btn-sm btn-light border rounded-2 shadow-sm" 
                                            style="width: 32px; height: 32px; color: {{ $isActionable ? '#0d9488' : '#94a3b8' }};"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#receiptModal{{ $booking->id }}"
                                            title="{{ $isActionable ? 'Verify & Action' : 'View Verification' }}">
                                        <i class="bi {{ $isActionable ? ($booking->payment_method === 'Pay at Cinema' ? 'bi-cash-stack' : 'bi-receipt-cutoff') : 'bi-shield-check' }}"></i>
                                    </button>
                                </div>

                                @include('admin.bookings.receipt-modal')
                                @include('admin.bookings.details')
                                @include('admin.bookings.select-seat')
                                <input type="hidden" id="required-count-{{ $booking->id }}" value="{{ $booking->bookedSeats->count() }}">
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-ticket-detailed d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                                <span class="fw-700 text-slate-400">No bookings found in the records.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>


@endsection