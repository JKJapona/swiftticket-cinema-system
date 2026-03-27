<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftTicket | Abreeza</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="preload" href="{{ asset('images/swiftticket_abreeza.svg') }}" as="image" type="image/svg+xml">

    <style>
        :root { --swift-blue: #004AAD; --slate-900: #1E293B; --slate-500: #64748B; }
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: var(--slate-900); }
        
        /* Navigation */
        .search-input { background-color: #f1f3f5; border: 1px solid #e9ecef; border-radius: 8px; padding: 6px 12px 6px 40px; font-size: 14px; width: 100%; transition: all 0.2s; }
        .search-input:focus { background-color: #fff; border-color: #4db8c1; box-shadow: 0 0 0 0.25rem rgba(77, 184, 193, 0.1); outline: none; }
        
        .btn-signin { color: var(--swift-blue); border: 1.5px solid var(--swift-blue); border-radius: 8px; padding: 5px 20px; font-weight: 600; font-size: 14px; text-decoration: none; transition: all 0.2s; }
        .btn-signin:hover { background-color: var(--swift-blue); color: white; transform: translateY(-1px); }

        /* User Dropdown */
        .user-avatar { width: 32px; height: 32px; background-color: #f1f5f9; color: var(--swift-blue); display: flex; align-items: center; justify-content: center; border-radius: 50%; }
        .dropdown-menu { border: none; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); border-radius: 12px; padding: 0.5rem; }
        .dropdown-item { border-radius: 8px; padding: 0.6rem 1rem; font-size: 14px; font-weight: 500; }
        .dropdown-toggle::after { display: none; }

        /* Movie Cards */
        .movie-card { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
        .movie-card:hover { transform: translateY(-8px); }
        .movie-poster-container { position: relative; overflow: hidden; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .movie-poster-container img { transition: transform 0.5s ease; }
        .movie-card:hover .movie-poster-container img { transform: scale(1.1); }
        
        .hover-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 74, 173, 0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s; backdrop-filter: blur(2px); }
        .movie-card:hover .hover-overlay { opacity: 1; }

        .nav-link-custom { transition: color 0.2s; }
        .nav-link-custom:hover { color: var(--swift-blue) !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-white py-2 sticky-top shadow-sm">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center">
            @if(Request::is('movies/*'))
                <a href="{{ url('/') }}" class="text-decoration-none text-slate-900 fw-bold d-flex align-items-center gap-2 nav-link-custom">
                    <i class="bi bi-chevron-left fs-5"></i>
                    <span>Back</span>
                </a>
                <div class="vr mx-3 text-dark opacity-10 d-none d-md-block"></div>
                <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid d-none d-md-block" style="height: 32px;" alt="SwiftTicket">
            @else
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 32px;" alt="SwiftTicket">
                </a>
                <span class="text-uppercase fw-bold text-muted ms-2 d-none d-sm-inline" style="font-size: 10px; letter-spacing: 2px;">Davao City</span>
            @endif
        </div>

        <div class="flex-grow-1 mx-5 d-none d-md-block">
            @if(!Request::is('movies/*'))
                <div class="position-relative" style="max-width: 500px; margin: 0 auto;">
                    <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary">
                        <i class="bi bi-search small"></i>
                    </span>
                    <input type="text" class="search-input" placeholder="Search movies or genres...">
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-4">
            <a href="#" class="text-decoration-none text-secondary d-flex align-items-center gap-2 small fw-medium nav-link-custom">
                <i class="bi bi-ticket-perforated fs-5"></i>
                <span class="d-none d-lg-inline">My Bookings</span>
            </a>

            @auth
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-slate-900 dropdown-toggle" data-bs-toggle="dropdown">
                        <div class="user-avatar"><i class="bi bi-person-fill"></i></div>
                        <span class="fw-bold small d-none d-sm-inline">{{ Auth::user()->full_name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow">
                        <li><h6 class="dropdown-header small text-uppercase tracking-wider">Account</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i>History</a></li>
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
    </div>
</nav>

<main>@yield('content')</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>