@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        
        {{-- Left Column: Poster & Trailer --}}
        <div class="col-lg-3 col-md-4">
            <div class="position-sticky" style="top: 100px;">
                <div class="rounded-3 overflow-hidden shadow-sm mb-2 border">
                    <img src="{{ Str::startsWith($movie->poster_path, 'http') ? $movie->poster_path : asset($movie->poster_path) }}" 
                         class="img-fluid w-100" 
                         alt="{{ $movie->title }}" 
                         style="aspect-ratio: 2/3; object-fit: cover;">
                </div>

                @if($movie->trailer_url)
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 fw-600" data-bs-toggle="modal" data-bs-target="#trailerModal">
                        <i class="bi bi-play-circle me-1"></i> Watch Trailer
                    </button>
                @endif
            </div>
        </div>


        {{-- Right Column: Details & Showtimes --}}
        <div class="col-lg-9 col-md-8">
            <div class="mb-3">
                <h1 class="h2 fw-700 text-slate-900 mb-1">{{ $movie->title }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <span class="badge bg-swift-blue px-2 py-1 rounded-1 small">
                        {{ \Carbon\Carbon::parse($movie->release_date)->format('Y') }}
                    </span>
                    <div class="badge border border-warning text-warning fw-700 small px-2 py-1">
                        {{ $movie->rating }}
                    </div>
                    <span class="text-secondary small fw-500">
                        <i class="bi bi-clock me-1"></i> {{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}m
                    </span>
                    <span class="badge border text-secondary border-secondary px-2 py-1" style="font-size: 10px;">
                        {{ $movie->genre }}
                    </span>
                </div>
            </div>


            {{-- Showtime Selection --}}
            <section class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="bi bi-calendar4-event text-primary fs-5"></i>
                    <h2 class="h5 fw-700 text-slate-900 mb-0">Select Date & Time</h2>
                </div>


                {{-- Date Picker --}}
                <div class="mb-3">
                    <div class="d-flex gap-2 overflow-auto pb-2 scrollbar-hidden" id="date-picker-container">
                        @foreach($dates as $date)
                            @php $is_active = $date->format('Y-m-d') == $selectedDate; @endphp
                            <button type="button" 
                                    data-date="{{ $date->format('Y-m-d') }}" 
                                    class="btn date-picker-btn date-card {{ $is_active ? 'active' : '' }} d-flex flex-column align-items-center justify-content-center">
                                <span class="date-label text-uppercase">{{ $date->format('D') }}</span>
                                <span class="date-number fw-700 fs-5 my-0">{{ $date->format('d') }}</span>
                                <span class="date-label text-uppercase">{{ $date->format('M') }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>


                {{-- Grouped Time Slots Container --}}
                <div id="showtimes-wrapper" style="transition: opacity 0.2s ease;">
                    {{-- The inner div is what we replace --}}
                    <div id="showtimes-content">
                        <div class="bg-light-soft rounded-3 p-3 border-dashed">
                            @if($showtimes->isEmpty())
                                <div class="py-4 text-center">
                                    <i class="bi bi-calendar-x fs-2 text-muted opacity-50 mb-2 d-block"></i>
                                    <p class="text-secondary small fw-500 mb-0">No showtimes available for this date</p>
                                </div>
                            @else
                                <p class="text-secondary small fw-600 mb-3 text-uppercase" style="letter-spacing: 0.05em;">Available Showtimes</p>
                                <div class="row g-3">
                                    @foreach($showtimes as $show)
                                        <div class="col-md-4 col-sm-6">
                                            <a href="{{ route('book.seats', $show->id) }}" class="showtime-card p-3 text-center text-decoration-none d-block">
                                                <div class="fw-800 text-slate-900 fs-5 mb-1">{{ \Carbon\Carbon::parse($show->show_time)->format('H:i') }}</div>
                                                <div class="text-muted small mb-1">
                                                    {{ $show->hall->name ?? 'Cinema ' . ($show->hall->hall_number ?? '') }}
                                                </div>
                                                <div class="price-tag-green fw-700">₱{{ number_format($show->price, 0) }}</div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>


            {{-- Synopsis & Cast --}}
            <div class="row g-3">
                <div class="col-12">
                    <h5 class="h6 fw-700 text-slate-900 mb-1">Synopsis</h5>
                    <p class="small text-secondary mb-3" style="line-height: 1.5;">{{ $movie->synopsis }}</p>
                </div>
                <div class="col-12">
                    <h5 class="h6 fw-700 text-slate-900 mb-1">Cast</h5>
                    <p class="small text-secondary mb-0">{{ $movie->cast_members }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Trailer Modal --}}
@if($movie->trailer_url)
    @php
        $videoId = '';
        if (str_contains($movie->trailer_url, 'v=')) {
            parse_str(parse_url($movie->trailer_url, PHP_URL_QUERY), $vars);
            $videoId = $vars['v'] ?? '';
        } elseif (str_contains($movie->trailer_url, 'youtu.be/')) {
            $videoId = basename(parse_url($movie->trailer_url, PHP_URL_PATH));
        } else {
            $videoId = basename($movie->trailer_url);
        }
        $embedUrl = "https://www.youtube.com/embed/" . $videoId;
    @endphp

    <div class="modal fade" id="trailerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0 p-2 d-flex justify-content-between align-items-center bg-dark bg-opacity-85 rounded-top-4">
                    <div class="ps-2">
                        <h5 class="modal-title text-white h6 mb-0 fw-600">{{ $movie->title }}</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 shadow-lg">
                    <div class="ratio ratio-16x9 rounded-bottom-4 overflow-hidden">
                        <iframe id="trailerVideo" src="{{ $embedUrl }}?enablejsapi=1&rel=0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<style>
    :root { 
        --slate-900: #1E293B; 
        --slate-500: #64748B; 
        --swift-blue: #004AAD; 
    }
    .text-slate-900 { color: var(--slate-900); }
    .text-secondary { color: var(--slate-500); }
    .bg-swift-blue { background-color: var(--swift-blue); color: white; }
    .fw-600 { font-weight: 600; }
    .fw-700 { font-weight: 700; }
    .fw-800 { font-weight: 800; }
    
    .date-card {
        min-width: 65px; height: 75px; background: white; border: 1px solid #E2E8F0;
        border-radius: 10px; transition: all 0.2s ease; color: var(--slate-900); cursor: pointer;
    }
    .date-card:hover { border: 1px solid #7f8388; }
    .date-card .date-label { color: #64748B; font-size: 9px; }
    .date-card.active { background: var(--swift-blue) !important; border-color: var(--swift-blue) !important; }
    .date-card.active .date-label { color: rgba(255, 255, 255, 0.7) !important; }
    .date-card.active .date-number { color: #ffffff !important; }

    .showtime-card {
        background: #ffffff; border: 1px solid #eef2f7; border-radius: 12px;
        transition: all 0.2s ease-in-out; box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    .showtime-card:hover { 
        border-color: var(--swift-blue); transform: translateY(-3px); 
        box-shadow: 0 10px 15px -3px rgba(0, 74, 173, 0.1); 
    }
    .price-tag-green { color: #2dc275; font-size: 13px; }
    .bg-light-soft { background-color: #F8FAFC; }
    .border-dashed { border: 1px dashed #E2E8F0; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const trailerModal = document.getElementById('trailerModal');
    const videoIframe = document.getElementById('trailerVideo');
    if (trailerModal && videoIframe) {
        const baseSrc = videoIframe.src;
        trailerModal.addEventListener('shown.bs.modal', () => { videoIframe.src = `${baseSrc}&autoplay=1`; });
        trailerModal.addEventListener('hide.bs.modal', () => { videoIframe.src = ""; });
    }

    const datePicker = document.getElementById('date-picker-container');
    const wrapper = document.getElementById('showtimes-wrapper');
    const movieId = "{{ $movie->id }}";
    
    const baseUrl = window.location.origin + '/movies/' + movieId;
    
    let isLoading = false;

    if (datePicker) {
        datePicker.addEventListener('click', function(e) {
            const btn = e.target.closest('.date-picker-btn');
            
            if (!btn || btn.classList.contains('active') || isLoading) return;

            isLoading = true;
            const selectedDate = btn.getAttribute('data-date');

            document.querySelectorAll('.date-picker-btn').forEach(el => el.classList.remove('active'));
            btn.classList.add('active');
            wrapper.style.opacity = '0.3';

            const targetUrl = `${baseUrl}/${selectedDate}`;

            fetch(targetUrl, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newInnerContent = doc.getElementById('showtimes-content');

                if (newInnerContent) {
                    document.getElementById('showtimes-content').innerHTML = newInnerContent.innerHTML;
                }
                
                wrapper.style.opacity = '1';
                // Update browser URL to /movies/2/2026-04-02
                window.history.pushState({}, '', targetUrl);
                isLoading = false;
            })
            .catch(err => {
                console.error("AJAX Error:", err);
                wrapper.style.opacity = '1';
                isLoading = false;
            });
        });
    }
});
</script>
@endsection