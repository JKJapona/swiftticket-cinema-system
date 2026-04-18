@extends('layouts.admin')

@section('content')
<div class="admin-container">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Master Schedule</h1>
            <p class="text-secondary mb-0">Visualizing cinema hall availability for <strong>{{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}</strong></p>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <div class="d-flex align-items-center bg-white border shadow-sm px-2" style="border-radius: 0.5rem;">
                {{-- Previous Day --}}
                <a href="{{ route('admin.showtimes.index', ['date' => \Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}" 
                class="btn btn-link text-slate-500 p-2 hover-primary">
                    <i class="bi bi-chevron-left"></i>
                </a>

                {{-- Date Picker Form --}}
                <form action="{{ route('admin.showtimes.index') }}" method="GET" class="d-flex mx-1">
                    <input type="date" name="date" 
                        class="form-control border-0 fw-600 p-1" 
                        value="{{ $selectedDate }}" 
                        onchange="this.form.submit()" 
                        style="font-size: 14px; width: 150px; cursor: pointer;">
                </form>

                {{-- Next Day --}}
                <a href="{{ route('admin.showtimes.index', ['date' => \Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}" 
                class="btn btn-link text-slate-500 p-2 hover-primary">
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>

            <button class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 shadow-sm btn-swift-action" data-bs-toggle="modal" data-bs-target="#createShowtimeModal">
                <i class="bi bi-plus-lg me-2"></i> Add Showtime
            </button>
        </div>
    </div>

    {{-- STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Showtimes</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['total_showtimes'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-primary border-4">
                <span class="caption d-block mb-1">Movies ({{ $selectedDate }})</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['unique_movies_count'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-info border-4">
                <span class="caption d-block mb-1">Daily Capacity</span>
                <h3 class="stat-value mb-0 text-end">{{ number_format($stats['total_daily_capacity']) }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-warning border-4">
                <span class="caption d-block mb-1">High Occupancy</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['high_occupancy_count'] }}</h3>
            </div>
        </div>
    </div>

    {{-- VISUALIZER --}}
    <div class="section-card px-4 py-4 mb-4 border-0 shadow-sm bg-white">
        @php
            $now = \Carbon\Carbon::now('Asia/Manila'); 
            $totalMinutesInScale = 14.5 * 60; 
            $nowPos = null;
            $currentHour = $now->hour;
            $currentMinute = $now->minute;
            $adjustedHour = ($currentHour < 10) ? $currentHour + 24 : $currentHour;

            if ($adjustedHour >= 10 && ($adjustedHour < 24 || ($adjustedHour == 24 && $currentMinute <= 30))) {
                $minutesFromStartScale = ($adjustedHour - 10) * 60 + $currentMinute;
                $nowPos = ($minutesFromStartScale / $totalMinutesInScale) * 100;
            }
        @endphp

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-800 text-slate-800 mb-0" style="font-size: 16px;">Hall Occupancy Timelines</h4>
                <p class="text-slate-400 mb-0" style="font-size: 11px;">10:00 AM to 12:30 AM midnight range.</p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($nowPos)
                    <span class="badge bg-danger-subtle text-danger border-0 px-2 py-1 fw-700 pulse-animation" style="font-size: 9px;">
                        <i class="bi bi-record-fill me-1"></i> LIVE
                    </span>
                @endif
                <span class="badge bg-light text-slate-500 border px-3 py-2 fw-700" style="font-size: 10px;">
                    <i class="bi bi-calendar3 me-1"></i> {{ \Carbon\Carbon::parse($selectedDate)->format('M d, Y') }}
                </span>
            </div>
        </div>

        <div class="hall-timelines-wrapper position-relative">
            <div class="d-flex justify-content-between mb-2 px-1 ms-auto" style="width: calc(100% - 120px);">
                @foreach(['10 AM', '12 PM', '02 PM', '04 PM', '06 PM', '08 PM', '10 PM', '12 AM', '12:30'] as $time)
                    <span class="text-slate-400" style="font-size: 9px; font-weight: 700;">{{ $time }}</span>
                @endforeach
            </div>

            @if($nowPos && $nowPos <= 100)
                <div class="position-absolute" style="left: calc(105px + 1rem + {{ $nowPos }}% - ({{ $nowPos }}% * 120px / 100%)); top: 25px; bottom: 0; width: 2px; background-color: #ef4444; z-index: 10; pointer-events: none;">
                    <div class="position-absolute translate-middle-x bg-danger text-white px-1 rounded-1 fw-bold" style="top: -15px; font-size: 8px; left: 50%; white-space: nowrap;">
                        NOW
                    </div>
                </div>
            @endif

            @forelse($halls as $hall)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 105px;">
                        <span class="fw-800 text-slate-700 text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">{{ $hall->name }}</span>
                        <div class="caption text-slate-400" style="font-size: 9px;">{{ $hall->screen_type }}</div>
                    </div>

                    <div class="flex-grow-1 position-relative">
                        <div class="progress rounded-pill shadow-inner" style="height: 24px; background-color: #f8fafc; border: 1px solid #e2e8f0; overflow: visible;">
                            @forelse($hall->showtimes as $st)
                                @php
                                    $runtime = $st->movie->runtime_minutes ?? 120;
                                    $start = \Carbon\Carbon::parse($st->show_time);
                                    $startHour = $start->hour < 10 ? $start->hour + 24 : $start->hour;
                                    $minutesFromStartScale = ($startHour - 10) * 60 + $start->minute;
                                    $leftPos = ($minutesFromStartScale / $totalMinutesInScale) * 100;
                                    $width = ($runtime / $totalMinutesInScale) * 100;
                                @endphp

                                @if($leftPos >= 0 && $leftPos < 100)
                                    <div class="position-absolute timeline-block" 
                                        style="left: {{ $leftPos }}%; width: {{ $width }}%; height: 24px; top: -1px; background: linear-gradient(to right, #004AAD, #3b82f6); border: 1.5px solid white; border-radius: 6px; z-index: 2;"
                                        data-bs-toggle="tooltip" 
                                        data-bs-html="true"
                                        title="<div class='p-1'><div class='text-white' style='font-size: 13px;'>{{ addslashes($st->movie->title) }}</div><div style='color: #94a3b8; font-size: 11px;'>{{ \Carbon\Carbon::parse($st->show_time)->format('h:i A') }} • {{ $st->movie->runtime_minutes }} mins</div></div>">
                                    </div>
                                @endif
                            @empty
                                <div class="w-100 d-flex align-items-center justify-content-center" style="height: 24px;">
                                    <span class="text-slate-300 fw-600" style="font-size: 8px; letter-spacing: 1px; text-transform: uppercase;">No screenings scheduled</span>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5 border rounded-3 bg-light">
                    <i class="bi bi-building-exclamation text-slate-300 d-block mb-2" style="font-size: 2.5rem;"></i>
                    <span class="fw-700 text-slate-400">No cinema halls registered.</span>
                </div>
            @endforelse
        </div>
    </div>

    {{-- MANAGEMENT GRID --}}
    <div class="section-card shadow-sm border-0 p-4 bg-white rounded-4">
    @forelse($halls as $hall)
        <div class="hall-row mb-5">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-slate-100 rounded-2 px-3 py-2 border d-flex align-items-center gap-2 shadow-sm">
                    <i class="bi bi-door-open-fill text-primary"></i>
                    <span class="fw-800 text-slate-900 text-uppercase" style="font-size: 13px; letter-spacing: 0.5px;">{{ $hall->name }}</span>
                    <span class="badge bg-light text-slate-600 border ms-1 fw-700" style="font-size: 10px;">{{ $hall->screen_type }}</span>
                </div>
                <div class="ms-3 flex-grow-1 border-bottom border-dashed" style="opacity: 0.3;"></div>
            </div>

            <div class="d-flex gap-3 overflow-auto pb-3 py-3 custom-scrollbar">
                @forelse($hall->showtimes as $slot)
                    @php 
                        $perc = $slot->total_capacity > 0 ? ($slot->booked_seats / $slot->total_capacity) * 100 : 0; 
                    @endphp

                    <div class="showtime-card p-3 rounded-3 border-start border-2 shadow-sm bg-slate-50" style="min-width: 300px; border-color: var(--swift-blue) !important;">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $slot->movie->poster_url }}" alt="Poster" class="movie-poster rounded shadow-sm" width="48" height="68" onerror="this.src='{{ asset('images/placeholder-poster.svg') }}'">
                            <div class="flex-grow-1 overflow-hidden">
                                <div class="fw-800 text-slate-900 text-truncate" style="font-size: 15px;" title="{{ $slot->movie->title }}">
                                    {{ $slot->movie->title }}
                                </div>
                                <div class="bg-white d-inline-block px-2 py-1 rounded border shadow-sm mt-1">
                                    <span class="fw-800 text-primary" style="font-size: 12px;">
                                        <i class="bi bi-clock-fill me-1"></i>{{ \Carbon\Carbon::parse($slot->show_time)->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                            <span class="text-slate-500 fw-700" style="font-size: 10px; text-transform: uppercase;">Price Per Seat</span>
                            <span class="fw-800 text-slate-900" style="font-size: 15px;">₱{{ number_format($slot->price, 0) }}</span>
                        </div>

                        <div class="progress mb-2" style="height: 8px; border-radius: 10px; background-color: #e2e8f0;">
                            <div class="progress-bar bg-swift-blue shadow-sm" style="width: {{ $perc }}%; border-radius: 10px;"></div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex flex-column">
                                <span class="text-slate-400 mb-0" style="font-size: 9px; font-weight: 700; text-transform: uppercase;">Occupancy</span>
                                <span class="fw-800 text-slate-700" style="font-size: 13px;">
                                    <i class="bi bi-people-fill me-1 text-primary"></i>{{ $slot->booked_seats }} / {{ $slot->total_capacity }}
                                </span>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-action shadow-sm border" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editShowtimeModal{{ $slot->id }}"
                                        style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: white; color: #64748b;">
                                    <i class="bi bi-pencil-square" style="font-size: 18px;"></i>
                                </button>

                                <form id="delete-showtime-{{ $slot->id }}" action="{{ route('admin.showtimes.destroy', $slot->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            class="btn btn-action-delete shadow-sm border" 
                                            style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: white; color: #ef4444;"
                                            onclick="swiftConfirm(
                                                'Remove Screening?', 
                                                'Are you sure you want to delete this showtime? This will affect ticket availability for this slot.', 
                                                'danger', 
                                                () => document.getElementById('delete-showtime-{{ $slot->id }}').submit()
                                            )">
                                        <i class="bi bi-trash" style="font-size: 18px;"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="d-flex align-items-center justify-content-center border-dashed border-2 rounded-4 w-100 py-5" style="background: #f8fafc; min-height: 120px;">
                        <div class="text-center">
                            <i class="bi bi-calendar-x text-slate-300 d-block mb-2" style="font-size: 2rem;"></i>
                            <span class="fw-700 text-slate-400" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">No Screenings Scheduled</span>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-building-exclamation text-slate-300 d-block mb-3" style="font-size: 4rem;"></i>
            <h5 class="fw-800 text-slate-700">No Cinema Halls Found</h5>
            <p class="text-slate-400 small">You need to add cinema halls before you can schedule showtimes.</p>
            <a href="{{ route('admin.cinema-halls.index') }}" class="btn btn-primary bg-swift-blue btn-sm px-4 fw-bold rounded-2 shadow-sm">
                Add Your First Hall
            </a>
        </div>
    @endforelse
</div>
</div>

{{-- MODALS --}}
@include('admin.showtimes.create')
@foreach($halls as $hall)
    @foreach($hall->showtimes as $slot)
        {{-- Pass formatted date here to ensure edit modal fields populate --}}
        @include('admin.showtimes.edit', [
            'showtime' => $slot, 
            'formattedDate' => \Carbon\Carbon::parse($slot->show_date)->format('Y-m-d')
        ])
    @endforeach
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Validation Error Handling
    @if ($errors->any())
        let modalId = '';
        @if (session('error_showtime_id'))
            modalId = 'editShowtimeModal{{ session('error_showtime_id') }}';
        @else
            modalId = 'createShowtimeModal';
        @endif

        const modalEl = document.getElementById(modalId);
        if (modalEl) {
            const modalInstance = new bootstrap.Modal(modalEl);
            setTimeout(() => { modalInstance.show(); }, 200);
        }
    @endif

    // 2. Alert Auto-close
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 10000);

    // 3. Tooltip Initialization
    const myDefaultAllowList = bootstrap.Tooltip.Default.allowList;
    myDefaultAllowList.div = ['class', 'style'];
    myDefaultAllowList.span = ['class', 'style'];
    myDefaultAllowList.strong = [];
    myDefaultAllowList.i = ['class'];
    
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl, {
            allowList: myDefaultAllowList
        })
    });
});
</script>

@endsection