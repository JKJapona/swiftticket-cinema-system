<aside>
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
        @elseif(Route::is('book.seats') && isset($showtime))
            <div class="d-flex align-items-center w-100">
                <a href="{{ url('/movies/' . $showtime->movie->id) }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-chevron-left fs-5"></i>
                    <span>Back</span>
                </a>
                
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
                    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 32px;" alt="SwiftTicket" loading="eager" fetchpriority="high">
                </a>

                <div class="d-flex align-items-center gap-2 bg-light px-3 py-2 rounded-pill border ms-auto">
                    <i class="bi bi-clock-history text-primary"></i>
                    <span class="caption mb-0 d-none d-sm-inline">Time Left:</span>
                    <span id="timer-display" class="fw-bold text-dark" style="min-width: 45px; font-variant-numeric: tabular-nums; opacity: 0; transition: opacity 0.2s ease;">--:--</span>
                </div>
            </div>

        {{-- VIEW 4: GENERAL NAVBAR --}}
        @else
            <div class="d-flex align-items-center justify-content-between w-100">
                <div class="d-flex align-items-center">
                    
                    @if(Request::is('movies/*'))
                        <a href="{{ url('/') }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-chevron-left fs-5"></i>
                            <span>Back</span>
                        </a>
                        <div class="vr mx-3 text-dark opacity-10 d-none d-md-block"></div>

                    @elseif(request()->routeIs('profile', 'profile.edit'))
                        <a href="{{ route('home') }}" class="nav-link-custom fw-bold d-flex align-items-center gap-2">
                            <i class="bi bi-chevron-left fs-5"></i>
                            <span>Back</span>
                        </a>
                        <div class="vr mx-3 text-dark opacity-10 d-none d-md-block"></div>
                    @endif
                    
                     {{-- LEFT SIDE: LOGO --}}
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" style="height: 32px;" alt="SwiftTicket" loading="eager" fetchpriority="high">
                    </a>
                    <span class="text-uppercase fw-bold text-muted ms-2 d-none d-sm-inline" style="font-size: 10px; letter-spacing: 2px;">Davao City</span>
                </div>

                {{-- RIGHT SIDE: PROFILE --}}
                <div class="d-flex align-items-center gap-4">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center gap-2 text-decoration-none text-slate-900 dropdown-toggle" data-bs-toggle="dropdown">
                                <div class="user-avatar d-flex align-items-center justify-content-center rounded-circle border" 
                                    style="width: 32px; 
                                            height: 32px; 
                                            background-color: #f8fafc; 
                                            border-color: #e2e8f0 !important;
                                            color: #334155; /* Slightly darker for better contrast */
                                            font-size: 15px; /* Increased size slightly */
                                            font-weight: 800; /* Extra Bold */
                                            letter-spacing: -0.02em;
                                            box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
                                </div>
                                <span class="fw-bold small d-none d-sm-inline" style="color: #1e293b;">{{ Auth::user()->full_name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow p-2">
                                @if(Auth::user()->role === 'admin')
                                    <li>
                                        <a class="dropdown-item rounded-2" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider opacity-50"></li>
                                @endif

                                <li>
                                    <a class="dropdown-item rounded-2" href="{{ route('profile') }}">
                                        <i class="bi bi-person me-2"></i>Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider opacity-50"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit" class="dropdown-item rounded-2 text-danger fw-bold">
                                            <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-signin text-decoration-none">Sign In</a>
                    @endauth
                </div>
            </div>
        @endif
    </div>

</aside>