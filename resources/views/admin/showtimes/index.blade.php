@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Showtimes</h1>
            <p class="text-secondary mb-0">Schedule and manage movie showtimes</p>
        </div>
        <button class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 transition-all shadow-sm" 
                data-bs-toggle="modal" data-bs-target="#createShowtimeModal">
            <i class="bi bi-plus-lg me-2"></i> Create Showtime
        </button>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Showtimes</span>
                <h3 class="fw-800 mb-0" style="font-size: 28px;">{{ $showtimes->count() }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Bookings</span>
                <h3 class="fw-800 mb-0" style="font-size: 28px;">{{ $showtimes->sum('booked_seats') }}</h3>
            </div>
        </div>
    </div>

    {{-- 3. TABLE SECTION --}}
    <div class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">Movie</th>
                        <th class="py-3 caption">Date & Time</th>
                        <th class="py-3 caption">Cinema Room</th>
                        <th class="py-3 caption">Price</th>
                        <th class="py-3 caption">Occupancy</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($showtimes as $showtime)
                    <tr>
                        <td class="ps-4" style="width: 30%;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $showtime->movie->poster_url }}" 
                                    alt="Poster" class="rounded shadow-sm" 
                                    style="width: 48px; height: 68px; object-fit: cover; border: 1px solid #e2e8f0;">
                                <div>         
                                    <div class="fw-800 text-slate-700 mb-0" style="font-size: 16px;">{{ $showtime->movie->title }}</div>
                                    <div class="caption text-uppercase fw-700" style="font-size: 10px; color: #64748b;">
                                        {{ $showtime->movie->genre ?? 'General' }}
                                    </div>
                                </div>
                            </div> 
                        </td>
                        <td>
                            <div>
                                <div class="fw-700 text-slate-900 mb-0" style="font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($showtime->show_date)->format('M d, Y') }}
                                </div>
                                <div class="caption text-uppercase fw-700" style="font-size: 10px; color: #64748b;">
                                    Starts at {{ \Carbon\Carbon::parse($showtime->show_time)->format('h:i A') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-secondary border fw-500 text-uppercase px-2 py-1" style="font-size: 11px;">
                                <i class="bi bi-door-open me-1"></i> {{ $showtime->hall->name }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-800 text-slate-900" style="font-size: 16px;">₱{{ number_format($showtime->price, 0) }}</span>
                        </td>
                        <td style="min-width: 160px;">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="fw-800 text-slate-700" style="font-size: 11px;">{{ $showtime->booked_seats }}/{{ $showtime->total_capacity }}</span>
                                    <span class="caption" style="font-size: 10px;">{{ number_format(($showtime->booked_seats / $showtime->total_capacity) * 100, 0) }}%</span>
                                </div>
                                <div class="progress rounded-pill" style="height: 6px; background-color: #f1f5f9;">
                                    @php 
                                        $percentage = ($showtime->booked_seats / $showtime->total_capacity) * 100;
                                        $barColor = $percentage > 80 ? '#ef4444' : '#004AAD';
                                    @endphp
                                    <div class="progress-bar rounded-pill" style="width: {{ $percentage }}%; background-color: {{ $barColor }};"></div>
                                </div>
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <div class="btn-group">
                                <button class="btn btn-sm btn-light border text-slate-900 fw-700 me-2 shadow-sm">
                                    <i class="bi bi-pencil-square"></i>
                                </button>

                                <form action="{{ route('admin.showtimes.destroy', $showtime->id) }}" method="POST" 
                                    class="d-inline" id="deleteForm"
                                    onsubmit="return confirm('Are you sure you want to delete this showtime?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger shadow-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-secondary">
                            <i class="bi bi-calendar-x d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                            <span class="fw-700 text-slate-400">No showtimes scheduled.</span>
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
    
    .bg-success-soft { background-color: #ecfdf5 !important; border-color: #10b981 !important; color: #059669 !important; }
    .bg-warning-soft { background-color: #fffbeb !important; border-color: #f59e0b !important; color: #d97706 !important; }
    .bg-secondary-soft { background-color: #f8fafc !important; border-color: #e2e8f0 !important; color: #475569 !important; }

    .bg-swift-blue { background-color: var(--swift-blue) !important; }
    .bg-slate-50 { background-color: var(--slate-50) !important; }
    .bg-slate-100 { background-color: var(--slate-100) !important; }
    .text-slate-900 { color: var(--slate-900); }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .border-dashed { border-style: dashed !important; border-color: #CBD5E1 !important; }
    .cursor-pointer { cursor: pointer; }

    #createMovieModal .h2, [id^="editMovieModal"] .h2 { font-size: 20px !important; font-weight: 700; color: var(--slate-900); line-height: 1.2; }
    #createMovieModal .h4, [id^="editMovieModal"] .h4 { font-size: 16px !important; font-weight: 600; color: var(--slate-900); }
    #createMovieModal .label, [id^="editMovieModal"] .label { font-size: 11px !important; font-weight: 600; color: var(--slate-500); letter-spacing: 0.025em; }
    #createMovieModal .caption, [id^="editMovieModal"] .caption { font-size: 10px !important; font-weight: 500; color: var(--slate-500); }

    #createMovieModal .form-control:focus, 
    #createMovieModal .form-select:focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    [id^="editMovieModal"] .form-control:focus, 
    [id^="editMovieModal"] .form-select:focus {
        border-color: #f59e0b !important; 
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1) !important;
    }

    .media-dropzone { transition: all 0.2s ease-in-out; }
    #createMovieModal .media-dropzone:hover { border-color: var(--swift-blue) !important; background-color: #f1f5f9 !important; }
    [id^="editMovieModal"] .media-dropzone:hover { border-color: #f0ad4e !important; background-color: #fcf8e3 !important; }
</style>
@endsection