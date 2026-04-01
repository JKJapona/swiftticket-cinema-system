@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Movies</h1>
            <p class="text-secondary mb-0">Manage your cinema's movie inventory and featured carousel.</p>
        </div>
        <button class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 transition-all shadow-sm" 
                data-bs-toggle="modal" data-bs-target="#createMovieModal">
            <i class="bi bi-plus-lg me-2"></i> Add New Movie
        </button>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Movies</span>
                <h3 class="stat-value mb-0">{{ $movies->count() }}</h3>
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
                                         style="width: 48px; height: 68px; object-fit: cover; border: 1px solid #e2e8f0;">
                                    <div>
                                        <div class="fw-700 text-slate-900">{{ $movie->title }}</div>
                                        <div class="caption text-lowercase" style="font-size: 10px;">
                                            <span class="text-primary">{{ $movie->rating ?? 'NR' }}</span> 
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
                                <span class="badge px-3 py-2 rounded-pill border {{ $movie->status_color_class }}" 
                                      style="font-size: 10px; font-weight: 700; text-transform: uppercase;">
                                    <i class="bi {{ $movie->status_icon }} me-1"></i> 
                                    {{ $movie->display_status }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                            <div class="btn-group">

                                {{-- Archive/Unarchive Button --}}
                                <form action="{{ route('admin.movies.toggle-archive', $movie->id) }}" method="POST" class="d-inline movie-action-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm btn-light border shadow-sm me-2 {{ $movie->status === 'archived' ? 'text-success' : 'text-warning' }}" 
                                            title="{{ $movie->status === 'archived' ? 'Unarchive Movie' : 'Archive Movie' }}">
                                        @if($movie->status === 'archived')
                                            <i class="bi bi-archive-fill"></i>
                                        @else
                                            <i class="bi bi-archive"></i>
                                        @endif
                                    </button>
                                </form>

                                {{-- Edit Button --}}
                                <button type="button" 
                                        class="btn btn-sm btn-light border text-slate-900 fw-600 me-2 shadow-sm" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#editMovieModal{{ $movie->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                
                                {{-- Delete Button --}}
                                <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" class="d-inline movie-action-form" onsubmit="return confirm('Delete this movie permanently?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-light border text-danger shadow-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                        </tr>
                        @include('admin.movies.edit')
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
        outline: none;
    }

    [id^="editMovieModal"] .form-control:focus, 
    [id^="editMovieModal"] .form-select:focus {
        border-color: #f59e0b !important; 
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1) !important;
        outline: none;
    }

    .media-dropzone { transition: all 0.2s ease-in-out; }
    #createMovieModal .media-dropzone:hover { border-color: var(--swift-blue) !important; background-color: #f1f5f9 !important; }
    [id^="editMovieModal"] .media-dropzone:hover { border-color: #f0ad4e !important; background-color: #fcf8e3 !important; }

    [id^="editMovieModal"] .form-check-input:checked {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }

    [id^="editMovieModal"] .form-check-input:focus {
        border-color: #ffc107;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25);
    }
</style>

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
    }, 5000);
</script>
@include('admin.movies.create')
@endsection