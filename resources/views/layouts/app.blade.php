<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftTicket | Abreeza</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<nav class="navbar navbar-expand-lg {{ Request::is('checkout/success/*') ? 'success-header' : 'bg-white shadow-sm' }} py-2 sticky-top">
    <div class="container-fluid px-4">

        {{-- VIEW 1: SUCCESS BANNER --}}
        @if(Request::is('checkout/success/*'))
        <div class="container d-flex justify-content-center align-items-center gap-3 py-3">
            <i class="bi bi-check-circle-fill text-white" style="font-size: 24px; filter: drop-shadow(0 0 8px rgba(255,255,255,0.3));"></i>
            
            <div class="text-start text-white">
                <div style="font-size: 18px; font-weight: 700; line-height: 1.2; letter-spacing: 0.2px;">
                    Booking Confirmed!
                </div>
                <div style="font-size: 12px; font-weight: 400; opacity: 0.9; line-height: 1.4;">
                    Your tickets are ready at Ayala Malls Abreeza
                </div>
            </div>
        </div>

        {{-- VIEW 2: SEAT SELECTION NAVBAR --}}
        @elseif(Route::is('book.seats'))
            <div class="d-flex align-items-center w-100">
                <a href="{{ url('/movies/' . $showtime->movie->id) }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-chevron-left fs-5"></i>
                    <span>Back</span>
                </a>
                
                @if(isset($showtime))
                    <div class="vr mx-3 text-dark opacity-10 d-none d-md-block"></div>
                    <div class="d-flex align-items-center">
                        <h1 class="h6 fw-bold mb-0 me-2">{{ $showtime->movie->title }}</h1>
                        <span class="text-secondary opacity-50 me-2">|</span>
                        <p class="mb-0 text-secondary fw-medium" style="font-size: 13px;">
                            {{ \Carbon\Carbon::parse($showtime->show_date)->format('M d') }} • 
                            {{ \Carbon\Carbon::parse($showtime->show_time)->format('h:i A') }} • 
                            {{ $showtime->hall->name }}
                        </p>
                    </div>

                    <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border ms-auto me-3">
                        <i class="bi bi-clock-history text-primary"></i>
                        <span class="caption mb-0 d-none d-sm-inline">Time Left:</span>
                        <span id="timer-display" class="fw-bold text-dark" style="min-width: 45px; font-variant-numeric: tabular-nums;">15:00</span>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2" style="min-width: 100px; justify-content: flex-end;">
                        <span class="text-secondary small text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px; white-space: nowrap;">
                            Selected Seats
                        </span>
                        <div class="h5 fw-bold mb-0 text-slate-900" id="nav-count-display" style="line-height: 1; min-width: 20px; text-align: right;">0</div>
                    </div>
                @endif
            </div>

        

        {{-- VIEW 3: CHECKOUT / PAYMENT NAVBAR --}}
        @elseif(Request::is('checkout/payment'))
            <div class="d-flex align-items-center w-100">
                <a href="{{ url()->previous() }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-chevron-left fs-5"></i>
                    <span>Back</span>
                </a>
                <div class="vr mx-3 text-dark opacity-10"></div>
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 32px;" alt="SwiftTicket">
                </a>

                <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border ms-auto">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span class="caption mb-0 d-none d-sm-inline">Time Left:</span>
                    <span id="timer-display" class="fw-bold text-dark" style="min-width: 45px; font-variant-numeric: tabular-nums;">15:00</span>
                </div>
            </div>



        {{-- VIEW 4: GENERAL NAVBAR --}}
        @else
            <div class="d-flex align-items-center">
                {{-- Show Back Button ONLY on Movie Details and My Bookings --}}
                @if(Request::is('movies/*', 'my-bookings'))
                    <a href="{{ url('/') }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-chevron-left fs-5"></i>
                        <span>Back</span>
                    </a>
                    <div class="vr mx-3 text-dark opacity-10 d-none d-md-block"></div>
                @endif
                
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 32px;" alt="SwiftTicket">
                </a>
                <span class="text-uppercase fw-bold text-muted ms-2 d-none d-sm-inline" style="font-size: 10px; letter-spacing: 2px;">Davao City</span>
            </div>

            <div class="flex-grow-1 mx-5 d-none d-md-block text-center">
                {{-- Hide search bar IF we are on Movie Details OR My Bookings --}}
                @if(!Request::is('movies/*', 'my-bookings'))
                    <div class="position-relative" style="max-width: 500px; margin: 0 auto;">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                            <i class="bi bi-search small"></i>
                        </span>
                        <input type="text" class="search-input" placeholder="Search movies or genres...">
                    </div>
                @endif
            </div>


            {{-- User Actions --}}
            <div class="d-flex align-items-center gap-4">
                @auth
                    <a href="{{ route('bookings.my-bookings') }}" class="nav-link-custom d-flex align-items-center gap-2 small fw-medium">
                        <i class="bi bi-ticket-perforated fs-5"></i>
                        <span class="d-none d-lg-inline">My Bookings</span>
                    </a>
                    
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-slate-900 dropdown-toggle" data-bs-toggle="dropdown">
                            <div class="user-avatar"><i class="bi bi-person-fill"></i></div>
                            <span class="fw-bold small d-none d-sm-inline">{{ Auth::user()->full_name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger fw-bold">
                                        <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-signin">Sign In</a>
                @endauth
            </div>
        @endif
    </div>
</nav>

<main>
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const DURATION = 15 * 60 * 1000;
    const KEY = 'swift_booking_expiry';
    const REDIRECT = "{{ route('home') }}";

    function initializeTimer() {
        const isBookingPath = @json(Request::is('book/*', 'checkout/payment*', 'movies/*'));
        let expiry = localStorage.getItem(KEY);

        if (!isBookingPath) {
            localStorage.removeItem(KEY);
            return null;
        }

        if (!expiry) {
            expiry = Date.now() + DURATION;
            localStorage.setItem(KEY, expiry);
        }

        return parseInt(expiry);
    }

    const expiryTime = initializeTimer();
    if (!expiryTime) return;

    const timerInterval = setInterval(() => {
        const distance = expiryTime - Date.now();
        const display = document.getElementById('timer-display');

        if (distance <= 0) {
            clearInterval(timerInterval);
            localStorage.removeItem(KEY);
            alert("Session expired. Please select your seats again.");
            window.location.href = REDIRECT;
            return;
        }

        if (display) {
            const mins = Math.floor(distance / 60000);
            const secs = Math.floor((distance % 60000) / 1000);
            display.innerText = `${mins}:${secs < 10 ? '0' : ''}${secs}`;

            if (distance < 120000) {
                display.classList.add('text-danger', 'fw-bold');
            }
        }
    }, 1000);

if ("{{ Request::is('checkout/success/*') }}") {
    localStorage.removeItem(KEY);
}
});
</script>
</body>
</html>