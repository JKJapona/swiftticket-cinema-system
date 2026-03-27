@extends('layouts.app')

@section('content')

<div class="pt-3">
    <div class="container-fluid px-4">
        
        {{-- Hero Carousel --}}
        @if($movies->count() > 0)
        <div id="heroCarousel" class="carousel slide mb-4 shadow-lg rounded-3 overflow-hidden" data-bs-ride="carousel">
            <div class="carousel-indicators" style="z-index: 5;">
                @foreach($movies->take(5) as $index => $movie)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></button>
                @endforeach
            </div>

            <div class="carousel-inner">
                @foreach($movies->take(5) as $index => $movie)
                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="hero-compact position-relative overflow-hidden" style="background-color: #004AAD !important; height: 330px;">
                        <div class="container-fluid h-100">
                            <div class="row align-items-center h-100 position-relative mx-0" style="z-index: 2;">
                                <div class="col-lg-7 ps-5"> 
                                    <span class="badge bg-warning text-dark mb-2 text-uppercase fw-bold">Featured Blockbuster</span>
                                    <h1 class="display-large mb-2 text-white">{{ $movie->title }}</h1>
                                    <p class="body-large opacity-75 mb-3 text-white text-truncate-2" style="max-width: 90%;">{{ $movie->synopsis }}</p>
                                    
                                    <div class="d-flex gap-3 align-items-center mb-3 text-white">
                                        <span class="badge bg-white-50 border border-white-50 px-2 py-1">{{ $movie->rating }}</span>
                                        <span>{{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}m</span>
                                        <span>{{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}</span>
                                    </div>
                                    
                                    <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-warning px-4 py-2 rounded-3 fw-bold shadow-sm">
                                        Book Now →
                                    </a>
                                </div>
                            </div>

                            <div class="position-absolute end-0 top-0 h-100 w-100 d-none d-lg-block">
                                <div class="w-100 h-100" style="background: linear-gradient(to right, #004AAD 10%, transparent); position: absolute; z-index: 1;"></div>
                                <img src="{{ asset($movie->poster_path) }}" class="w-100 h-100 object-fit-cover">
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

        {{-- Navigation & Filter --}}
        <div class="d-flex justify-content-between align-items-center border-bottom mb-3 pb-2">
            <div class="nav nav-underline gap-3">
                <a class="nav-link active fw-bold text-primary pb-2 border-primary border-3" href="#">Now Showing</a>
                <a class="nav-link text-slate-500 fw-bold pb-2" href="#">Coming Soon</a>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="text-slate-500 fw-bold text-uppercase small">Filter:</span>
                <select class="form-select form-select-sm border-0 bg-light fw-bold text-slate-900" style="width: auto;">
                    <option>All Genres</option>
                    @foreach(['Action', 'Horror', 'Sci-Fi', 'Drama'] as $genre)
                        <option>{{ $genre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Movie Grid --}}
        <div class="row g-4 mb-5 row-cols-2 row-cols-md-3 row-cols-lg-6 mx-0">
            @foreach($movies as $movie)
            <div class="col px-2">
                <div class="movie-card border-0">
                    <div class="movie-poster-container position-relative">
                        <img src="{{ asset($movie->poster_path) }}" class="w-100 object-fit-cover rounded-3" style="height: 280px !important;">
                        <div class="hover-overlay d-flex align-items-center justify-content-center p-3">
                            <a href="{{ route('movies.show', $movie->id) }}" class="btn btn-warning w-100 py-2 rounded-3 fw-bold shadow">
                                Book Now
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-2 text-center text-lg-start">
                        <h3 class="text-slate-900 mb-0 fw-bold" style="font-size: 0.9rem; line-height: 1.2;">
                            {{ $movie->title }}
                        </h3>
                        <p class="text-slate-500 fw-medium mb-0 small">
                            {{ $movie->genre }} • {{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}m
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .hero-compact, .carousel-item { height: 330px; min-height: 330px; }
    
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }

    .footer-swift { background-color: #004AAD !important; border-top: 1px solid rgba(255, 255, 255, 0.1); }
    .footer-title { color: #FFFFFF; font-size: 16px; font-weight: 700; letter-spacing: 0.02em; }
    .footer-subtitle { color: rgba(255, 255, 255, 0.75); font-size: 12px; }
    .footer-copyright { color: rgba(255, 255, 255, 0.6); font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
    .footer-divider { height: 1px; width: 40px; background-color: rgba(255, 255, 255, 0.2); margin: 0 auto; }

    body { display: flex; flex-direction: column; min-height: 100vh; }
    main { flex: 1; }
</style>

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

@endsection