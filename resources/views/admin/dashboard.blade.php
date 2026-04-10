@extends('layouts.admin')

@section('content')
<div class="admin-container">
    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Dashboard</h1>
            <p class="text-secondary mb-0">Overview of Abreeza cinema performance and activity.</p>
        </div>
        <div class="text-secondary caption">
            <i class="bi bi-clock-history me-1"></i> Last updated: {{ now()->format('h:i A') }}
        </div>
    </div>

    {{-- 2. STATS CARDS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-primary border-4">
                <span class="caption d-block mb-1">Total Revenue</span>
                <h3 class="stat-value mb-0">₱{{ number_format($stats['total_revenue'], 2) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Bookings</span>
                <h3 class="stat-value mb-0">{{ $stats['total_bookings'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Customers</span>
                <h3 class="stat-value mb-0">{{ $stats['total_customers'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Movies Now Showing</span>
                <h3 class="stat-value mb-0">{{ $stats['active_movies'] }}</h3>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- 3. RECENT BOOKINGS TABLE --}}
        <div class="col-lg-8">
            <div class="section-card shadow-sm border-0 overflow-hidden h-100">
                <div class="p-4 border-bottom bg-white">
                    <h5 class="fw-700 text-slate-900 mb-0">Recent Bookings</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 caption">Customer Details</th>
                                <th class="py-3 caption">Movie</th>
                                <th class="py-3 caption">Amount</th>
                                <th class="py-3 caption text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_bookings as $booking)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-slate-100 rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="bi bi-person text-slate-500"></i>
                                        </div>
                                        <div>
                                            <div class="fw-700 text-slate-900">{{ $booking->user->full_name }}</div>
                                            <div class="caption text-lowercase" style="font-size: 10px;">REF: {{ $booking->reference_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-600 text-slate-900">{{ $booking->showtime->movie->title }}</span>
                                </td>
                                <td>
                                    <span class="fw-700 text-primary">₱{{ number_format($booking->total_price, 2) }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <span class="badge px-3 py-2 rounded-pill border {{ $booking->status == 'confirmed' ? 'bg-success-soft' : 'bg-warning-soft' }}">
                                        <i class="bi {{ $booking->status == 'confirmed' ? 'bi-check-circle' : 'bi-clock' }} me-1"></i>
                                        {{ strtoupper($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3 text-center border-top bg-slate-50">
                    <a href="{{ route('admin.bookings.index') }}" class="text-primary fw-700 text-decoration-none small caption">
                        View All Activity <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- 4. SIDEBAR (QUICK ACTIONS & STATUS) --}}
        <div class="col-lg-4">
            {{-- Quick Actions --}}
            <div class="section-card shadow-sm border-0 mb-4 p-4">
                <h5 class="fw-700 text-slate-900 mb-3" style="font-size: 16px;">Quick Actions</h5>
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-light border text-start py-2 px-3 fw-600 shadow-sm transition-all text-decoration-none text-dark">
                        <i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i> Export Sales Report
                    </a>
                    <a href="{{ route('admin.showtimes.index') }}" class="btn btn-light border text-start py-2 px-3 fw-600 shadow-sm transition-all text-decoration-none text-dark">
                        <i class="bi bi-calendar3 me-2 text-success"></i> Manage Showtimes
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-light border text-start py-2 px-3 fw-600 shadow-sm transition-all text-decoration-none text-dark">
                        <i class="bi bi-people me-2 text-warning"></i> View Customers
                    </a>
                </div>
            </div>

{{-- Wrap your System Status Card in this div --}}
<div id="status-card-ajax-wrapper">
    <div class="section-card shadow-sm border-0 p-4">
        <div class="d-flex align-items-center mb-3">
            {{-- Added a 'pulse' class for a nice effect --}}
            <div class="{{ $dbStatus ? 'bg-success' : 'bg-danger' }} rounded-circle me-2 pulse-light" style="width: 8px; height: 8px;"></div>
            <h5 class="fw-700 text-slate-900 mb-0" style="font-size: 16px;">System Status</h5>
        </div>
        
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-1 align-items-end">
                <span class="caption" style="font-size: 10px;">
                    Server Load <span class="text-slate-900 fw-800 ms-1">{{ $serverLoad }}%</span>
                </span>
                <span class="caption {{ $serverLoad > 80 ? 'text-danger' : 'text-success' }}" style="font-size: 10px;">
                    {{ $status }}
                </span>
            </div>
            <div class="progress" style="height: 6px; background: #f1f5f9; border-radius: 10px;">
                <div class="progress-bar {{ $serverLoad > 80 ? 'bg-danger' : 'bg-success' }}" 
                     style="width: {{ $serverLoad }}%; border-radius: 10px; transition: width 0.4s ease;">
                </div>
            </div>
        </div>

        <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="caption text-secondary" style="font-size: 9px;">Database Latency</span>
                <span class="fw-700 text-slate-900" style="font-size: 12px;">{{ $responseTime }} ms</span>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                <span class="caption text-secondary" style="font-size: 9px;">Memory Usage</span>
                <span class="fw-700 text-slate-900" style="font-size: 12px;">{{ round(memory_get_usage(true) / 1024 / 1024, 1) }} MB</span>
            </div>
        </div>
    </div>
</div>
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

    /* Standardized Card & Typography */
    .section-card { background: white; border-radius: 1.25rem; border: 1px solid #e2e8f0; }
    .stat-value { font-size: 28px !important; font-weight: 800 !important; color: #0f172a !important; line-height: 1.2 !important; display: block; margin-top: 2px; }
    .caption { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; }
    
    /* Table Styling */
    .table thead th { border-bottom: 1px solid #f1f5f9; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    
    /* Status Badges */
    .bg-success-soft { background-color: #ecfdf5 !important; border-color: #10b981 !important; color: #059669 !important; }
    .bg-warning-soft { background-color: #fffbeb !important; border-color: #f59e0b !important; color: #d97706 !important; }
    .badge { font-size: 10px; font-weight: 700; text-transform: uppercase; }

    /* Utility Classes */
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .btn-light:hover { background-color: #f1f5f9 !important; border-color: #cbd5e1 !important; }

    /* Dark Mode Overrides for System Status Card */
    .bg-slate-900 .caption, 
    .bg-slate-900 h5, 
    .bg-slate-900 p {
        color: white !important;
    }
</style>

<script>
    function refreshStatusCard() {
        // Fetch the current page URL
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                // Create a temporary DOM element to parse the response
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Find the new status card in the response
                const newCard = doc.querySelector('#status-card-ajax-wrapper');
                const oldCard = document.querySelector('#status-card-ajax-wrapper');
                
                if (newCard && oldCard) {
                    // Only update the innerHTML so the page doesn't "jump"
                    oldCard.innerHTML = newCard.innerHTML;
                    console.log('Status updated via AJAX at ' + new Date().toLocaleTimeString());
                }
            })
            .catch(err => console.warn('AJAX Refresh Failed (Offline?):', err));
    }

    // Run the refresh every 5 seconds
    setInterval(refreshStatusCard, 5000);
</script>
@endsection