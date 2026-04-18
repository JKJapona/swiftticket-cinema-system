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
        @php
            $cards = [
                ['label' => 'Total Revenue', 'value' => '₱' . number_format($stats['total_revenue'], 2), 'class' => 'border-primary border-4 border-start'],
                ['label' => 'Total Bookings', 'value' => number_format($stats['total_bookings']), 'class' => ''],
                ['label' => 'Customers', 'value' => number_format($stats['total_customers']), 'class' => ''],
                ['label' => 'Movies Now Showing', 'value' => $stats['active_movies'], 'class' => '']
            ];
        @endphp

        @foreach($cards as $card)
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm {{ $card['class'] }}">
                <span class="caption d-block mb-1">{{ $card['label'] }}</span>
                <h3 class="stat-value mb-0 text-end">{{ $card['value'] }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="section-card shadow-sm border-0 overflow-hidden h-100">
                <div class="p-3 border-bottom bg-white d-flex justify-content-between align-items-center">
                    <h5 class="fw-700 text-slate-900 mb-0" style="font-size: 16px;">Recent Bookings</h5>
                    <span class="badge bg-slate-100 text-slate-500 rounded-pill" style="font-size: 9px; font-weight: 600; letter-spacing: 0.5px;">LATEST ACTIVITY</span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-2 caption" style="font-size: 9px;">Customer Details</th>
                                <th class="py-2 caption" style="font-size: 9px;">Movie</th>
                                <th class="py-2 caption" style="font-size: 9px;">Amount</th>
                                <th class="py-2 caption text-end pe-4" style="font-size: 9px;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($recent_bookings as $booking)
                            <tr>
                                <td class="ps-4 py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-slate-100 rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                                            <i class="bi bi-person text-slate-500" style="font-size: 12px;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-700 text-slate-900" style="font-size: 13px; line-height: 1.2;">{{ $booking->user->full_name }}</div>
                                            <div class="text-secondary" style="font-size: 9px;">REF: {{ $booking->reference_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2">
                                    <span class="fw-600 text-slate-700" style="font-size: 13px;">{{ $booking->showtime->movie->title }}</span>
                                </td>
                                <td class="py-2">
                                    <span class="fw-700 text-primary" style="font-size: 13px;">₱{{ number_format($booking->total_price, 2) }}</span>
                                </td>
                                <td class="text-end pe-4 py-2">
                                    @php
                                        $statusMap = [
                                            'confirmed'        => ['bg' => '#dcfce7', 'color' => '#166534', 'icon' => 'bi-check-circle-fill'],
                                            'pending'          => ['bg' => '#fef3c7', 'color' => '#92400e', 'icon' => 'bi-clock-fill'],
                                            'change_requested' => ['bg' => '#e0e7ff', 'color' => '#3730a3', 'icon' => 'bi-arrow-repeat'],
                                            'cancelled'        => ['bg' => '#fee2e2', 'color' => '#991b1b', 'icon' => 'bi-x-circle-fill'],
                                        ];
                                        $curr = $statusMap[$booking->status] ?? $statusMap['cancelled'];
                                    @endphp
                                    <span class="badge border-0" style="padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; background-color: {{ $curr['bg'] }}; color: {{ $curr['color'] }};">
                                        <i class="bi {{ $curr['icon'] }} me-1" style="font-size: 11px;"></i> 
                                        {{ str_replace('_', ' ', $booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-secondary">
                                    <i class="bi bi-clipboard-x d-block mb-2 text-slate-300" style="font-size: 2.5rem;"></i>
                                    <span class="fw-700 text-slate-400" style="font-size: 12px;">No recent transactions today.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    </table>
                </div>
                <div class="p-2 text-center border-top bg-slate-50">
                    <a href="{{ route('admin.bookings.index') }}" class="text-primary fw-700 text-decoration-none" style="font-size: 11px;">
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
                    @php
                        $actions = [
                            ['route' => 'admin.reports.sales', 'icon' => 'bi-file-earmark-bar-graph', 'color' => 'text-primary', 'label' => 'Export Sales Report'],
                            ['route' => 'admin.showtimes.index', 'icon' => 'bi-calendar3', 'color' => 'text-success', 'label' => 'Manage Showtimes'],
                            ['route' => 'admin.customers.index', 'icon' => 'bi-people', 'color' => 'text-warning', 'label' => 'View Customers'],
                        ];
                    @endphp
                    @foreach($actions as $action)
                        <a href="{{ route($action['route']) }}" class="btn btn-light border text-start py-2 px-3 fw-600 shadow-sm transition-all text-decoration-none text-dark">
                            <i class="bi {{ $action['icon'] }} me-2 {{ $action['color'] }}"></i> {{ $action['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- System Status Card --}}
            <div id="status-card-ajax-wrapper">
                <div class="section-card shadow-sm border-0 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="{{ $dbStatus ? 'bg-success' : 'bg-danger' }} rounded-circle me-2 pulse-light" style="width: 8px; height: 8px;"></div>
                        <h5 class="fw-700 text-slate-900 mb-0" style="font-size: 16px;">System Status</h5>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1 align-items-end">
                            <span class="caption" style="font-size: 10px;">
                                CPU Usage <span class="text-slate-900 fw-800 ms-1">{{ number_format($serverLoad, 1) }}%</span>
                            </span>
                            <span class="caption {{ $serverLoad > 80 ? 'text-danger' : 'text-success' }}" style="font-size: 10px;">
                                {{ $status }}
                            </span>
                        </div>
                        <div class="progress" style="height: 6px; background: #f1f5f9; border-radius: 10px;">
                            <div class="progress-bar {{ $serverLoad > 80 ? 'bg-danger' : 'bg-success' }}" 
                                style="width: {{ min($serverLoad, 100) }}%; border-radius: 10px; transition: width 0.4s ease;">
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3" style="background-color: #f8fafc; border: 1px solid #f1f5f9;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="caption text-secondary" style="font-size: 9px;">Database Latency</span>
                            <span class="fw-700 text-slate-900" style="font-size: 12px;">{{ number_format($responseTime, 0) }} ms</span>
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

<script>
    async function refreshStatusCard() {
        try {
            const response = await fetch(window.location.href);
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newCard = doc.querySelector('#status-card-ajax-wrapper');
            const oldCard = document.querySelector('#status-card-ajax-wrapper');
            
            if (newCard && oldCard) {
                oldCard.innerHTML = newCard.innerHTML;
            }
        } catch (err) {
            console.warn('AJAX Refresh Failed:', err);
        }
    }
    setInterval(refreshStatusCard, 60000);
</script>
@endsection