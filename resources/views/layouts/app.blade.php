<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftTicket | Abreeza</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root { 
            --swift-blue: #004AAD; 
            --slate-900: #1E293B; 
            --slate-500: #64748B; 
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #ffffff; 
            color: var(--slate-900); 
        }
        
        /* Navigation Links */
        .nav-link-custom { 
            color: var(--slate-900) !important; 
            text-decoration: none !important;
            transition: color 0.2s ease;
        }
        .nav-link-custom:hover { 
            color: var(--swift-blue) !important; 
        }

        /* Search Bar */
        .search-input { 
            background-color: #f1f3f5; 
            border: 1px solid #e9ecef; 
            border-radius: 8px; 
            padding: 6px 12px 6px 40px; 
            font-size: 14px; 
            width: 100%; 
        }

        /* Buttons & Auth */
        .btn-signin { 
            color: var(--swift-blue); 
            border: 1.5px solid var(--swift-blue); 
            border-radius: 8px; 
            padding: 5px 20px; 
            font-weight: 600; 
            font-size: 14px; 
            text-decoration: none; 
        }
        .btn-signin:hover { 
            background-color: var(--swift-blue); 
            color: white; 
        }
        .user-avatar { 
            width: 32px; 
            height: 32px; 
            background-color: #f1f5f9; 
            color: var(--swift-blue); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 50%; 
        }
        .dropdown-toggle::after { display: none; }

        /* Movie Cards */
        .movie-card { transition: transform 0.3s ease; cursor: pointer; }
        .movie-card:hover { transform: translateY(-8px); }
        .movie-poster-container { 
            position: relative; 
            overflow: hidden; 
            border-radius: 12px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); 
        }
        .hover-overlay { 
            position: absolute; 
            top: 0; left: 0; width: 100%; height: 100%; 
            background: rgba(0, 74, 173, 0.4); 
            display: flex; align-items: center; justify-content: center; 
            opacity: 0; transition: opacity 0.3s; 
        }
        .movie-card:hover .hover-overlay { opacity: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white py-2 sticky-top shadow-sm">
    <div class="container-fluid px-4">

        {{-- VIEW 1: SEAT SELECTION NAVBAR --}}
        @if(Route::is('book.seats'))
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
                    
                    <div class="ms-auto d-flex align-items-center gap-2">
                        <span class="text-secondary small text-uppercase fw-semibold" style="font-size: 10px; letter-spacing: 0.5px; white-space: nowrap;">
                            Selected Seats
                        </span>
                        <div class="h5 fw-bold mb-0 text-slate-900" id="nav-count-display" style="line-height: 1;">0</div>
                    </div>
                @endif
            </div>

        {{-- VIEW 2: CHECKOUT / PAYMENT NAVBAR --}}
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
            </div>

        {{-- VIEW 3: GENERAL NAVBAR (Home, Movie Details) --}}
        @else
            <div class="d-flex align-items-center">
                @if(Request::is('movies/*'))
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

            <div class="flex-grow-1 mx-5 d-none d-md-block">
                @if(!Request::is('movies/*'))
                    <div class="position-relative" style="max-width: 500px; margin: 0 auto;">
                        <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"><i class="bi bi-search small"></i></span>
                        <input type="text" class="search-input" placeholder="Search movies or genres...">
                    </div>
                @endif
            </div>

            <div class="d-flex align-items-center gap-4">
                @auth
                    <a href="#" class="nav-link-custom d-flex align-items-center gap-2 small fw-medium">
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

</body>
</html>