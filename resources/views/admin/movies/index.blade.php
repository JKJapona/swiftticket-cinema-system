@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Movies</h1>
            <p class="text-secondary mb-0">Manage your cinema's movie inventory and featured carousel.</p>
        </div>
        <button class="btn btn-primary bg-swift-blue border-0 fw-700 shadow-sm btn-swift-action"
                data-bs-toggle="modal" data-bs-target="#createMovieModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Movie
        </button>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        {{-- Total Movies --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Movies</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['total_count'] }}</h3>
            </div>
        </div>

        {{-- Now Showing --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-primary border-4">
                <span class="caption d-block mb-1">Now Showing</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['now_showing_count'] }}</h3>
            </div>
        </div>

        {{-- Coming Soon --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-warning border-4">
                <span class="caption d-block mb-1">Coming Soon</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['coming_soon_count'] }}</h3>
            </div>
        </div>

        {{-- Active Showtimes --}}
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-success border-4">
                <span class="caption d-block mb-1">Total Showtimes</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['active_showtimes'] }}</h3>
            </div>
        </div>
    </div>

    {{-- MOVIE QUICK FILTERS --}}
    <div class="section-card p-3 mb-4 border-0 shadow-sm">
        <div class="row align-items-center g-3">
            {{-- Search Input --}}
            <div class="col-md-7">
                <div class="d-flex align-items-center gap-3">
                    <span class="caption text-slate-500 text-nowrap">Search:</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white border-end-0 text-slate-400 py-2">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" id="movieSearch" 
                            class="form-control border-start-0 ps-0 shadow-none py-2 fw-600" 
                            placeholder="Movie title, genre, or rating..."
                            style="border-color: #e2e8f0;">
                    </div>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="col-md-5">
                <div class="d-flex align-items-center justify-content-md-end gap-3">
                    <span class="caption text-slate-500 text-nowrap">Status:</span>
                    <select id="statusFilter" class="form-select form-select-sm shadow-none fw-600 w-auto" 
                            style="border-color: #e2e8f0; min-width: 160px;">
                        <option value="all">All Statuses</option>
                        <option value="now showing">Now Showing</option>
                        <option value="coming soon">Coming Soon</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. TABLE SECTION --}}
    <div class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">Movie Details</th>
                        <th class="py-3 caption">Genre</th>
                        <th class="py-3 caption text-center">Status</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movies as $movie)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ $movie->poster_url }}" 
                                         alt="Poster" class="rounded shadow-sm" 
                                         style="width: 48px; height: 68px; object-fit: cover; border: 1px solid #e2e8f0;"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-poster.svg') }}';">
                                    <div>
                                        <div class="fw-700 text-slate-900">{{ $movie->title }}</div>
                                        <div class="caption text-lowercase" style="font-size: 10px;">
                                            <span class="text-primary text-uppercase">{{ $movie->rating ?? 'NR' }}</span>
                                            <span class="mx-1">•</span> 
                                            {{ $movie->runtime_minutes ?? '0' }} MINS
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-secondary border fw-500">
                                    {{ $movie->genre ?? 'TBA' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge border-0" 
                                    style="padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center;
                                        @if($movie->display_status === 'Now Showing') 
                                            background-color: #dcfce7; color: #166534; 
                                        @elseif($movie->display_status === 'Coming Soon') 
                                            background-color: #fef3c7; color: #92400e;
                                        @else {{-- Archived --}}
                                            background-color: #f3f4f6; color: #374151;
                                        @endif">
                                    
                                    <i class="bi {{ $movie->display_status === 'Now Showing' ? 'bi-play-circle-fill' : ($movie->display_status === 'Coming Soon' ? 'bi-calendar-event-fill' : 'bi-archive-fill') }} me-1" 
                                    style="font-size: 11px;"></i> 
                                    
                                    {{ $movie->display_status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                            <div class="d-flex align-items-center justify-content-end gap-2"> {{-- Added gap-2 and removed manual margins --}}

                            {{-- Archive/Unarchive Button --}}
                            <form id="archive-movie-{{ $movie->id }}" action="{{ route('admin.movies.toggle-archive', $movie->id) }}" method="POST" class="d-inline movie-action-form">
                                    @csrf @method('PATCH')
                                    <button type="button" 
                                            class="btn btn-sm btn-light border shadow-sm" 
                                            style="color: #334155; border-color: currentColor;"
                                            onclick="swiftConfirm(
                                                '{{ $movie->status === 'archived' ? 'Unarchive Movie?' : 'Archive Movie?' }}', 
                                                '{{ $movie->status === 'archived' ? 'This movie will be visible to users again.' : 'This will hide the movie from the main listing.' }}', 
                                                '{{ $movie->status === 'archived' ? 'primary' : 'secondary' }}', 
                                                () => document.getElementById('archive-movie-{{ $movie->id }}').submit()
                                            )">
                                        @if($movie->status === 'archived')
                                            <i class="bi bi-archive-fill"></i>
                                        @else
                                            <i class="bi bi-archive"></i>
                                        @endif
                                    </button>
                                </form>

                                {{-- Edit Button --}}
                                <button type="button" 
                                        class="btn btn-sm btn-light border text-slate-900 fw-600 shadow-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editMovieModal{{ $movie->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                {{-- Delete Button --}}
                                <form id="delete-movie-{{ $movie->id }}" action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" class="d-inline movie-action-form">
                                    @csrf @method('DELETE')
                                    <button type="button" 
                                            class="btn btn-sm btn-light border text-danger shadow-sm"
                                            onclick="swiftConfirm(
                                                'Delete Movie?', 
                                                'Are you sure you want to permanently remove \'{{ addslashes($movie->title) }}\'? This action cannot be undone.', 
                                                'danger', 
                                                () => document.getElementById('delete-movie-{{ $movie->id }}').submit()
                                            )">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-secondary">
                                <i class="bi bi-film d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                                <span class="fw-700 text-slate-400">No movies found in the database.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($movies as $movie)
    @include('admin.movies.edit')
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('movieSearch');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('tbody tr');

    function filterTable() {
        const searchTerm = searchInput.value.toLowerCase();
        const filterValue = statusFilter.value.toLowerCase();

        tableRows.forEach(row => {
            if (row.cells.length < 4) return;

            const titleEl = row.querySelector('.fw-700');
            const genreEl = row.querySelector('.badge.bg-light');
            const statusCell = row.cells[2];

            if (!titleEl || !statusCell) return;

            const title = titleEl.textContent.toLowerCase();
            const genre = genreEl ? genreEl.textContent.toLowerCase() : '';
            const status = statusCell.textContent.trim().toLowerCase();
            
            const matchesSearch = title.includes(searchTerm) || genre.includes(searchTerm);
            const matchesStatus = filterValue === 'all' || status.includes(filterValue);

            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if ($errors->any())
            @if (session('error_movie_id'))
                var editModalId = 'editMovieModal{{ session('error_movie_id') }}';
                var myModal = new bootstrap.Modal(document.getElementById(editModalId));
            @else
                var myModal = new bootstrap.Modal(document.getElementById('createMovieModal'));
            @endif
            myModal.show();
        @endif
    });

    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 10000);
</script>
@include('admin.movies.create')
@endsection