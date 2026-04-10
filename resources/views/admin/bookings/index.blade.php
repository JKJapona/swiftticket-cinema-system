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

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Revenue</span>
                <h3 class="stat-value mb-0">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section-card p-4 border-0 shadow-sm border-start border-warning border-4">
                <span class="caption d-block mb-1">Pending Payments</span>
                <h3 class="stat-value mb-0">{{ $stats['pending_count'] }}</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="section-card p-4 border-0 shadow-sm border-start border-success border-4">
                <span class="caption d-block mb-1">Confirmed Bookings</span>
                <h3 class="stat-value mb-0">{{ $stats['confirmed_count'] }}</h3>
            </div>
        </div>
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
                                <div class="fw-700 text-slate-900">{{ $booking->user->full_name }}</div>
                                <div class="caption text-lowercase" style="font-size: 10px;">{{ $booking->payment_method }}</div>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900">{{ $booking->showtime->movie->title }}</div>
                                <div class="caption" style="font-size: 10px;">{{ $booking->showtime->hall->name }}</div>
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-1">
                                    @foreach($booking->bookedSeats as $seat)
                                        <span class="badge bg-light text-secondary border fw-500" style="font-size: 9px;">
                                            {{ $seat->seat_code }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="fw-800 text-slate-900">₱{{ number_format($booking->total_price, 2) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge px-3 py-2 rounded-pill border {{ $booking->status === 'confirmed' ? 'bg-success-soft' : ($booking->status === 'pending' ? 'bg-warning-soft' : 'bg-secondary-soft') }}" 
                                      style="font-size: 10px; font-weight: 700; text-transform: uppercase;">
                                    <i class="bi {{ $booking->status === 'confirmed' ? 'bi-check-circle-fill' : ($booking->status === 'pending' ? 'bi-clock-fill' : 'bi-x-circle-fill') }} me-1"></i> 
                                    {{ $booking->status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-light border text-success shadow-sm me-2" title="Confirm Payment">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this booking?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light border text-danger shadow-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-secondary">
                                <i class="bi bi-ticket-perforated d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                                <span class="fw-700 text-slate-400">No bookings recorded yet.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    :root {
        --swift-blue: #004AAD; 
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-500: #64748B; 
        --slate-900: #1E293B; 
    }

    .stat-value {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        line-height: 1.2 !important; 
        display: block;
        margin-top: 2px; 
    }

    .section-card { background: white; border-radius: 1.25rem; border: 1px solid #e2e8f0; }
    .caption { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; }
    .table thead th { border-bottom: 1px solid #f1f5f9; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    
    /* Status Colors matching Movie status logic */
    .bg-success-soft { background-color: #ecfdf5 !important; border-color: #10b981 !important; color: #059669 !important; }
    .bg-warning-soft { background-color: #fffbeb !important; border-color: #f59e0b !important; color: #d97706 !important; }
    .bg-secondary-soft { background-color: #f8fafc !important; border-color: #e2e8f0 !important; color: #475569 !important; }

    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .fw-500 { font-weight: 500; }
</style>
@endsection