@extends('layouts.app')

@section('content')

<div class="pt-3">
    <div class="container-fluid" style="padding: 0 80px;">
        
        {{-- HERO SECTION: CAROUSEL --}}
        @if($movies->count() > 0)
        <div id="heroCarousel" class="carousel slide mb-4 shadow-lg rounded-3 overflow-hidden" data-bs-ride="carousel">
            
            <div class="carousel-indicators" style="z-index: 5;">
                @foreach($movies as $index => $movie)
                    <button type="button" 
                            data-bs-target="#heroCarousel" 
                            data-bs-slide-to="{{ $index }}" 
                            class="{{ $index == 0 ? 'active' : '' }}">
                    </button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($movies as $index => $movie)
                @php
                    $path = $movie->cover_path ?? $movie->poster_path;
                    $imageUrl = str_contains($path, 'http') ? $path : Storage::url($path);
                @endphp

                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="hero-compact position-relative overflow-hidden" style="background-color: #004AAD !important;">
                        <div class="container-fluid h-100">
                            
                            <div class="row align-items-center h-100 position-relative mx-0" style="z-index: 2;">
                                <div class="col-lg-7 ps-5"> 
                                    <span class="badge bg-warning text-dark mb-2 text-uppercase fw-bold">Featured Blockbuster</span>
                                    <h1 class="display-large mb-2 text-white">{{ $movie->title }}</h1>
                                    <p class="body-large opacity-75 mb-3 text-white text-truncate-2" style="max-width: 90%;">{{ $movie->synopsis }}</p>
                                    
                                    <div class="d-flex gap-3 align-items-center mb-3 text-white">
                                        <span class="badge bg-white-50 border border-white-50 px-2 py-1">{{ $movie->rating }}</span>
                                        <span>{{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}m</span>
                                        <span>{{ $movie->release_date ? $movie->release_date->format('Y') : 'TBA' }}</span>
                                    </div>
                                    
                                    <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-warning px-4 py-2 rounded-3 fw-bold shadow-sm">
                                        Book Now →
                                    </a>
                                </div>
                            </div>

                            <div class="position-absolute end-0 top-0 h-100 w-100 d-none d-lg-block">
                                <div class="w-100 h-100" style="background: linear-gradient(to right, #004AAD 5%, transparent); position: absolute; z-index: 1;"></div>
                                <img src="{{ $movie->cover_url }}" 
                                     class="w-100 h-100 object-fit-cover" 
                                     alt="{{ $movie->title }} Backdrop"
                                     loading="lazy">
                            </div>                         

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" style="z-index: 5; width: 5%;">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" style="z-index: 5; width: 5%;">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
        @endif

        {{-- NAVIGATION & FILTER BAR --}}
        <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-2">
            <div class="nav nav-underline gap-3">
                <a class="nav-link active fw-bold text-primary pb-2 border-primary border-3 filter-status" href="#" data-status="Now Showing">Now Showing</a>
                <a class="nav-link text-muted fw-bold pb-2 filter-status" href="#" data-status="Coming Soon">Coming Soon</a>
            </div>
            
            <div class="d-flex align-items-center gap-2">
                <span class="text-secondary fw-bold text-uppercase small">Filter:</span>
                <select id="genreSelect" class="form-select form-select-sm border-0 bg-light fw-bold text-dark" style="width: auto;">
                    <option value="All Genres">All Genres</option>
                    @php 
                        $genres = $movies->map(function($movie) {
                            return $movie->genre ?: 'TBA';
                        })->unique()->sort(); 
                    @endphp

                    @foreach($genres as $genre)
                        <option value="{{ $genre }}">{{ $genre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- MOVIE GRID --}}
        <div class="row g-4 mb-5 row-cols-2 row-cols-md-3 row-cols-lg-6 mx-0" id="movieGrid">
            @foreach($movies as $movie)
                {{-- 
                    We use $movie->display_status
                    This handles Archived, Coming Soon, and Now Showing automatically based on your DB logic.
                --}}
                <div class="col px-2 movie-item" 
                    data-genre="{{ $movie->genre ?? 'TBA' }}" 
                    data-status="{{ $movie->display_status }}">
                    
                    <div class="movie-card border-0">
                        <div class="movie-poster-container position-relative">
                            <img src="{{ $movie->poster_url }}" 
                                class="w-100 object-fit-cover rounded-3 shadow-sm"
                                loading="lazy"
                                style="height: 280px !important;">
                                
                            <div class="hover-overlay d-flex align-items-center justify-content-center p-3">
                                <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-warning w-100 py-2 rounded-3 fw-bold shadow">
                                    {{ $movie->display_status === 'Now Showing' ? 'Book Now' : 'View Details' }}
                                </a>
                            </div>
                        </div>
                        
                        <div class="mt-2 text-center text-lg-start">
                            <h3 class="text-dark mb-0 fw-bold text-truncate" style="font-size: 0.9rem; line-height: 1.2;">
                                {{ $movie->title }}
                            </h3>
                            <p class="text-secondary fw-medium mb-0 small">
                                {{ $movie->genre ?? 'TBA' }} • 
                                @if($movie->runtime_minutes > 0)
                                    {{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}
                                @else
                                    Coming Soon
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>

{{-- FOOTER SECTION --}}
<footer class="footer-swift mt-auto py-4">
    <div class="container text-center">
        <h5 class="footer-title mb-1">
            SwiftTicket <span class="fw-normal mx-1">×</span> Ayala Malls Abreeza
        </h5>
        <p class="footer-subtitle mb-3">Exclusive Cinema Ticketing Partner</p>
        <div class="footer-divider mb-3"></div>
        <p class="footer-copyright mb-0">© 2026 SwiftTicket. All rights reserved.</p>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusLinks = document.querySelectorAll('.filter-status');
    const genreSelect = document.getElementById('genreSelect');
    const movieItems = document.querySelectorAll('.movie-item');
    const grid = document.getElementById('movieGrid');

    let currentStatus = 'Now Showing';
    let currentGenre = 'All Genres';

    function applyFilters() {
        let visibleCount = 0;
        
        movieItems.forEach(item => {
            const matchStatus = (item.getAttribute('data-status') === currentStatus);
            const matchGenre = (currentGenre === 'All Genres' || item.getAttribute('data-genre') === currentGenre);

            if (matchStatus && matchGenre) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        const existingMsg = document.getElementById('empty-msg');
        if (visibleCount === 0) {
            if (!existingMsg) {
                const msg = document.createElement('div');
                msg.id = 'empty-msg';
                msg.className = 'col-12 text-center py-5 text-muted';
                msg.innerHTML = '<i class="bi bi-search mb-2 d-block fs-1"></i> No movies found in this category.';
                grid.appendChild(msg);
            }
        } else if (existingMsg) {
            existingMsg.remove();
        }
    }

    statusLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            statusLinks.forEach(l => {
                l.classList.remove('active', 'text-primary', 'border-primary', 'border-3');
                l.classList.add('text-muted');
            });
            this.classList.add('active', 'text-primary', 'border-primary', 'border-3');
            this.classList.remove('text-muted');
            currentStatus = this.getAttribute('data-status');
            applyFilters();
        });
    });

    genreSelect.addEventListener('change', function() {
        currentGenre = this.value;
        applyFilters();
    });

    applyFilters();
});
</script>
@endsection