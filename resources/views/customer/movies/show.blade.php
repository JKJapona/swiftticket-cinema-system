@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        
        {{-- Left Column: Poster & Trailer --}}
        <div class="col-lg-3 col-md-4">
            <div class="position-sticky" style="top: 100px;">
                <div class="rounded-3 overflow-hidden shadow-sm mb-2 border skeleton-loader" 
                    style="aspect-ratio: 2/3; width: 100%;">
                            <img src="{{ $movie->poster_url ?: asset('images/placeholder-poster.svg') }}" 
                                class="w-100 h-100" 
                                alt="{{ $movie->title }}" 
                                style="object-fit: cover; opacity: 0; transition: opacity 0.3s ease; display: block;"
                                onload="this.parentElement.classList.remove('skeleton-loader'); this.style.opacity='1';"
                                onerror="this.onerror=null; this.src='{{ asset('images/placeholder-poster.svg') }}'; this.style.opacity='1'; this.parentElement.classList.remove('skeleton-loader');">
                        </div>

                @if($movie->trailer_url)
                    <button type="button" 
                            class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 fw-600 d-flex align-items-center justify-content-center" 
                            data-bs-toggle="modal" 
                            data-bs-target="#trailerModal">
                        <i class="bi bi-play-circle me-2"></i> Watch Trailer
                    </button>
                @endif
            </div>
        </div>

        {{-- Right Column: Details & Showtimes --}}
        <div class="col-lg-9 col-md-8">
            <div class="mb-3">
                <h1 class="h2 fw-700 text-slate-900 mb-1">{{ $movie->title }}</h1>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if($movie->status === 'coming_soon')
                        <span class="badge bg-warning text-dark px-2 py-1 rounded-1 small fw-800">COMING SOON</span>
                    @endif
                    <span class="badge bg-primary px-2 py-1 rounded-1 small">
                        {{ $movie->release_date ? \Carbon\Carbon::parse($movie->release_date)->format('Y') : 'TBA' }}
                    </span>
                    <div class="badge border border-warning text-warning fw-700 small px-2 py-1">
                        {{ $movie->rating ?: 'TBA' }}
                    </div>
                    <span class="text-secondary small fw-500">
                        <i class="bi bi-clock me-1"></i> 
                        @if($movie->runtime_minutes > 0)
                            {{ floor($movie->runtime_minutes / 60) }}h {{ $movie->runtime_minutes % 60 }}m
                        @else
                            TBA
                        @endif
                    </span>
                    <span class="badge border text-secondary border-secondary px-2 py-1" style="font-size: 10px;">
                        {{ $movie->genre ?? 'Uncategorized' }}
                    </span>
                </div>
            </div>

            {{-- Booking Section --}}
            <section class="mb-4">
                @if($movie->display_status === 'Now Showing')
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="bi bi-calendar4-event text-primary fs-5"></i>
                        <h2 class="h5 fw-700 text-slate-900 mb-0">Select Date & Time</h2>
                    </div>

                    {{-- Date Picker --}}
                    <div class="mb-3">
                        <div class="d-flex gap-2 overflow-auto pb-2 scrollbar-hidden" id="date-picker-container">
                            @foreach($dates as $date)
                                @php $dateStr = $date->format('Y-m-d'); @endphp
                                <button type="button" 
                                        data-date="{{ $dateStr }}" 
                                        class="btn date-picker-btn date-card {{ $dateStr == $selectedDate ? 'active' : '' }} d-flex flex-column align-items-center justify-content-center">
                                    <span class="date-label text-uppercase small">{{ $date->format('D') }}</span>
                                    <span class="date-number fw-700 fs-5 my-0">{{ $date->format('d') }}</span>
                                    <span class="date-label text-uppercase small">{{ $date->format('M') }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Showtime List --}}
                    <div id="showtimes-wrapper">
                        <div class="bg-light rounded-3 p-3 border">
                            <p class="text-secondary small fw-600 mb-3 text-uppercase" style="letter-spacing: 0.05em;">Available Showtimes</p>
                            <div class="row g-3" id="showtimes-list">
                                @foreach($showtimes as $show)
                                    <div class="col-md-4 col-sm-6 showtime-item" 
                                        data-date="{{ \Carbon\Carbon::parse($show->show_date)->format('Y-m-d') }}">
                                        <a href="{{ route('book.seats', $show->id) }}" class="showtime-card p-3 text-center text-decoration-none d-block shadow-sm bg-white border rounded transition-hover">
                                            <div class="fw-800 text-slate-900 fs-5 mb-1">
                                                {{ \Carbon\Carbon::parse($show->show_time)->format('h:i A') }}
                                            </div>
                                            <div class="text-muted small mb-1">
                                                {{ $show->hall->name ?? 'Cinema ' . ($show->hall->hall_number ?? '') }}
                                            </div>
                                            <div class="text-success fw-700">₱{{ number_format($show->price, 0) }}</div>
                                        </a>
                                    </div>
                                @endforeach

                                {{-- Updated Message Div --}}
                                <div id="no-showtimes-msg" class="col-12 py-5 text-center d-none">
                                    <i class="bi bi-calendar-x fs-2 text-muted opacity-50 mb-2 d-block"></i>
                                    <p class="text-secondary small fw-700 mb-1">This movie is not available on this date.</p>
                                    <p class="text-primary small fw-600 mb-0 d-none" id="next-screening-container">
                                        Next screening is on the <span id="next-date-text">...</span>
                                    </p>
                                </div>  
                            </div>
                        </div>
                    </div>

                @elseif($movie->display_status === 'Archived')
                    <div class="bg-light rounded-4 p-5 text-center border">
                        <i class="bi bi-archive text-secondary display-5 mb-3 d-block"></i>
                        <h3 class="h5 fw-800 text-slate-900 mb-2">Movie Archived</h3>
                        <p class="text-secondary small mb-0 mx-auto" style="max-width: 450px;">
                            This movie has completed its theatrical run and is no longer available for booking.
                        </p>
                    </div>

                @else
                    <div class="bg-light rounded-4 p-5 text-center border">
                        <i class="bi bi-stars text-warning display-5 mb-3 d-block"></i>
                        <h3 class="h5 fw-800 text-slate-900 mb-2">Coming Soon to Theaters</h3>
                        <p class="text-secondary small mb-4 mx-auto" style="max-width: 450px;">
                            We're currently preparing the schedules for this movie. 
                            @if($movie->release_date)
                                Catch it on the big screen starting <strong>{{ \Carbon\Carbon::parse($movie->release_date)->format('F d, Y') }}</strong>!
                            @else
                                Stay tuned for the official schedule announcement.
                            @endif
                        </p>
                    </div>
                @endif
            </section>

            {{-- Synopsis & Cast --}}
            <div class="row g-3 mt-2">
                <div class="col-12">
                    <h5 class="h6 fw-700 text-slate-900 mb-1">Synopsis</h5>
                    <p class="small text-secondary mb-3" style="line-height: 1.6;">{{ $movie->synopsis ?? 'No synopsis available.' }}</p>
                </div>
                <div class="col-12">
                    <h5 class="h6 fw-700 text-slate-900 mb-1">Cast</h5>
                    <p class="small text-secondary mb-0">{{ $movie->cast_members ?? 'Cast information TBA' }}</p>
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
                <div class="modal-header border-0 p-2 d-flex justify-content-between align-items-center bg-dark rounded-top-4">
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trailerModal = document.getElementById('trailerModal');
        const videoIframe = document.getElementById('trailerVideo');
        
        if (trailerModal && videoIframe) {
            const baseSrc = videoIframe.src;
            trailerModal.addEventListener('shown.bs.modal', () => { 
                videoIframe.src = `${baseSrc}${baseSrc.includes('?') ? '&' : '?'}autoplay=1`; 
            });
            trailerModal.addEventListener('hide.bs.modal', () => { 
                videoIframe.src = ""; 
            });
        }

        const dateButtons = document.querySelectorAll('.date-picker-btn');
        const showtimeItems = document.querySelectorAll('.showtime-item');
        const noMsg = document.getElementById('no-showtimes-msg');
        
        const allAvailableDates = @json($allAvailableDates); 
        const initialDate = "{{ $selectedDate }}";

        function updateFilter(selectedDate) {
            let foundCount = 0;

            showtimeItems.forEach(item => {
                if (item.getAttribute('data-date') === selectedDate) {
                    item.classList.remove('d-none');
                    foundCount++;
                } else {
                    item.classList.add('d-none');
                }
            });

            if (noMsg) {
                if (foundCount === 0) {
                    noMsg.classList.remove('d-none');
                    
                    const nextContainer = document.getElementById('next-screening-container');
                    const nextSpan = document.getElementById('next-date-text');

                    const nextDate = allAvailableDates.find(date => date > selectedDate);

                    if (nextDate && nextContainer && nextSpan) {
                        nextContainer.classList.remove('d-none');
                        
                        const [year, month, day] = nextDate.split('-').map(Number);
                        const dateObj = new Date(year, month - 1, day);
                        
                        const dayNum = dateObj.getDate();
                        const monthName = dateObj.toLocaleString('en-US', { month: 'long' });
                        
                        const s = ["th", "st", "nd", "rd"];
                        const v = dayNum % 100;
                        const suffix = s[(v - 20) % 10] || s[v] || s[0];
                        
                        nextSpan.innerText = `${dayNum}${suffix} of ${monthName}`;
                    } else if (nextContainer) {
                        nextContainer.classList.add('d-none');
                    }
                } else {
                    noMsg.classList.add('d-none');
                }
            }
        }

        // Run filter on page load
        if (initialDate) {
            updateFilter(initialDate);
        }

        dateButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetDate = this.getAttribute('data-date');
                
                dateButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                updateFilter(targetDate);

                const baseUrl = "{{ url('/movies/' . $movie->id) }}";
                window.history.pushState({ date: targetDate }, '', `${baseUrl}/${targetDate}`);
            });
        });
    });
</script>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-3px); border-color: #0d6efd !important; }
    .scrollbar-hidden::-webkit-scrollbar { display: none; }
    .scrollbar-hidden { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<style>
/* --- MOBILE RESPONSIVENESS (Screens smaller than 992px) --- */
@media (max-width: 991.98px) {
    /* 1. Remove sticky position so the poster sits on top of the details */
    .position-sticky {
        position: static !important;
        margin-bottom: 1.5rem;
    }

    /* 2. Scale down the poster size slightly so it doesn't take up the whole screen */
    .col-md-4 .skeleton-loader {
        max-width: 280px;
        margin: 0 auto; /* Center the poster */
    }

    /* 3. Center the Watch Trailer button */
    .btn-outline-primary.w-100 {
        max-width: 280px;
        margin: 10px auto 0;
    }

    /* 4. Increase font size of the title for readability */
    h1.h2 {
        font-size: 1.5rem !important;
        text-align: center;
    }

    /* 5. Center the badges/meta info */
    .d-flex.flex-wrap.align-items-center.gap-2 {
        justify-content: center;
        margin-bottom: 1rem;
    }

    /* 6. Make showtime cards 2-columns on mobile instead of stacking vertically */
    #showtimes-list .col-sm-6 {
        width: 50% !important;
    }
}

/* Extra small devices (phones, 576px and down) */
@media (max-width: 575.98px) {
    /* Make Date Picker cards slightly smaller to fit more on screen */
    .date-picker-btn {
        min-width: 60px !important;
        padding: 8px !important;
    }

    .date-number {
        font-size: 1.1rem !important;
    }

    /* Stack showtimes in a single column on very small phones */
    #showtimes-list .col-sm-6 {
        width: 100% !important;
    }

    /* Center text for Synopsis and Cast */
    .col-12 h5, .col-12 p {
        text-align: center;
    }
}

/* Fix for the horizontal date picker scrolling */
#date-picker-container {
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    display: flex;
    flex-wrap: nowrap;
}
</style>
@endsection