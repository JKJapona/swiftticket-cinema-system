<aside class="admin-sidebar d-flex flex-column shadow-sm border-end">

    {{-- 1. Logo Section --}}
    <div class="sidebar-header p-3 mb-2">
        <div class="logo-container d-flex align-items-center" style="height: 40px;">
            <img src="{{ asset('images/swiftticket_abreeza.svg') }}" 
                 alt="SwiftTicket Logo" 
                 style="max-width: 210px; width: auto; height: 100%; object-fit: contain;"
                 loading="eager"
                 fetchpriority="high">
        </div>
        
        <div class="d-flex align-items-center mt-2 ps-1">
            <span class="badge bg-primary-subtle text-primary fw-800 text-uppercase px-2 py-1" 
                  style="font-size: 9px; letter-spacing: 0.05em; opacity: 0.8;">
                Admin Panel
            </span>
        </div>
    </div>

    {{-- 2. Navigation --}}
    <nav class="flex-grow-1 px-3">
        <ul class="nav flex-column gap-1">
            @php
                $navItems = [
                    ['route' => 'admin.dashboard',          'icon' => 'grid-1x2-fill',      'label' => 'Dashboard'],
                    ['route' => 'admin.movies.index',       'icon' => 'film',               'label' => 'Movies'],
                    ['route' => 'admin.showtimes.index',    'icon' => 'calendar3',          'label' => 'Showtimes'],
                    ['route' => 'admin.cinema-halls.index', 'icon' => 'building',           'label' => 'Cinema Halls'],
                    ['route' => 'admin.bookings.index',     'icon' => 'ticket-perforated',  'label' => 'Seat Bookings'],
                    ['route' => 'admin.customers.index',    'icon' => 'people',             'label' => 'Customers'],
                ];
            @endphp

            @foreach($navItems as $item)
                <li class="nav-item">
                    <a href="{{ route($item['route']) }}" 
                       class="nav-link transition-all {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                        <i class="bi bi-{{ $item['icon'] }} me-2"></i> 
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    {{-- 3. User Profile --}}
    <div class="px-3 mb-3">
        <div class="dropdown">
            <a href="#" 
               class="d-flex align-items-center text-decoration-none dropdown-toggle user-profile-link p-2 rounded-3" 
               id="dropdownUser" 
               data-bs-toggle="dropdown" 
               aria-expanded="false">
               
                <div class="profile-circle text-white d-flex align-items-center justify-content-center fw-bold me-2" 
                     style="background-color: var(--swift-blue);">
                    {{ substr(Auth::user()->full_name ?? 'A', 0, 1) }}
                </div>

                <div class="user-info overflow-hidden">
                    <p class="mb-0 fw-700 text-slate-900 text-truncate" style="font-size: 13px;">
                        {{ Auth::user()->full_name ?? 'Admin User' }}
                    </p>
                    <p class="mb-0 text-secondary" style="font-size: 11px;">System Admin</p>
                </div>
            </a>

            <ul class="dropdown-menu shadow border-0 p-2 mb-2" aria-labelledby="dropdownUser">
                <li>
                    <a class="dropdown-item rounded-2" href="{{ url('/') }}">
                        <i class="bi bi-ticket-perforated me-2"></i> Home
                    </a>
                </li>
                <li>
                    <a class="dropdown-item rounded-2" href="{{ route('profile') }}">
                        <i class="bi bi-person me-2"></i>Profile
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-2 text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>

    {{-- 4. Footer --}}
    <div class="sidebar-footer p-3 border-top bg-light-subtle">
        <p class="text-secondary mb-0 text-center" style="font-size: 10px; line-height: 1.4;">
            <span class="fw-700 text-slate-900">SwiftTicket</span> × Abreeza<br>
            © 2026 All rights reserved.
        </p>
    </div>

</aside>