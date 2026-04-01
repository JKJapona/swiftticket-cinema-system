@extends('layouts.admin')

@section('content')
<div class="admin-container">
    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Showtimes</h1>
            <p class="text-secondary mb-0">Select a movie to manage its scheduled screenings</p>
        </div>
        {{-- Global Create (Select Movie manually) --}}
        <button class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 transition-all shadow-sm" 
                data-bs-toggle="modal" data-bs-target="#createShowtimeModal">
            <i class="bi bi-plus-lg me-2"></i> Create New Slot
        </button>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Showtimes</span>
                <h3 class="fw-800 mb-0" style="font-size: 28px;">{{ $totalShowtimes }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Bookings</span>
                <h3 class="fw-800 mb-0" style="font-size: 28px;">{{ $totalBookings }}</h3>
            </div>
        </div>
    </div>

    {{-- 3. MOVIE SELECTION TABLE --}}
    <div class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">Movie</th>
                        <th class="py-3 caption">Schedule Status</th>
                        <th class="py-3 caption">Halls Used</th>
                        <th class="py-3 caption">Price Range</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                    <tr>
                        <td class="ps-4" style="width: 35%;">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $movie->poster_url }}" class="rounded shadow-sm" 
                                    style="width: 48px; height: 68px; object-fit: cover; border: 1px solid #e2e8f0;">
                                <div>         
                                    <div class="fw-800 text-slate-700 mb-0" style="font-size: 16px;">{{ $movie->title }}</div>
                                    <div class="caption text-uppercase fw-700" style="font-size: 10px; color: #64748b;">
                                        {{ $movie->genre ?? 'General' }}
                                    </div>
                                </div>
                            </div> 
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border fw-700 text-uppercase px-2 py-1" style="font-size: 11px;">
                                {{ $movie->showtimes_count }} Active Slots
                            </span>
                        </td>
                        <td>
                            <div class="text-slate-600 fw-600" style="font-size: 13px;">
                                {{ $movie->showtimes->unique('hall_id')->count() }} Cinema Rooms
                            </div>
                        </td>
                        <td>
                            <span class="fw-800 text-slate-900" style="font-size: 15px;">
                                ₱{{ number_format($movie->showtimes->min('price'), 0) }} - ₱{{ number_format($movie->showtimes->max('price'), 0) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('admin.showtimes.movie', $movie->id) }}" 
                               class="btn btn-sm btn-light border text-slate-900 fw-700 px-3 shadow-sm">
                                Manage Schedule <i class="bi bi-chevron-right ms-1"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-secondary">
                            <i class="bi bi-film d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                            <span class="fw-700 text-slate-400">No movies found in the schedule.</span>
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