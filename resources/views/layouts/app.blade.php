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
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        :root {
            --swift-blue: #004AAD;
            --slate-900: #0f172a;
        }

        #top-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 3px;
            background: var(--swift-blue);
            z-index: 10001;
            transition: width 0.4s cubic-bezier(0.1, 0.05, 0, 1);
            box-shadow: 0 0 10px rgba(0, 74, 173, 0.4);
            display: none;
        }

        #top-progress-bar::after {
            content: '';
            position: absolute;
            right: 0;
            width: 100px;
            height: 100%;
            box-shadow: 0 0 10px var(--swift-blue), 0 0 5px var(--swift-blue);
            opacity: 1;
            transform: rotate(3deg) translate(0px, -4px);
        }

        .success-header { background-color: #10b981; }
        .nav-link-custom { color: inherit; text-decoration: none; transition: opacity 0.2s; }
        .nav-link-custom:hover { opacity: 0.7; }
        
        .app-header {
            position: sticky;
            top: 0;
            z-index: 1000; 
        }
    </style>
</head>
<body>

    <div id="top-progress-bar"></div>

    <header class="app-header">
        @include('layouts.app-topbar')
    </header>

    <div class="toast-container-custom position-fixed top-0 end-0 p-3" style="z-index: 10050;">
        @if(session('success'))
            <div class="alert alert-dismissible fade show shadow-sm border-0 mb-2 p-3" role="alert" 
                 style="background: white; border-left: 4px solid #10b981 !important; min-width: 300px; border-radius: 8px;">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-check-circle-fill" style="color: #10b981; font-size: 1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1 me-4">
                        <p class="mb-0 text-slate-900 fw-800" style="font-size: 14px;">Success</p>
                        <p class="mb-0 text-slate-500 fw-600" style="font-size: 12px; line-height: 1.4;">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.6rem;"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-dismissible fade show shadow-sm border-0 mb-2 p-3" role="alert" 
                 style="background: white; border-left: 4px solid #ef4444 !important; min-width: 300px; border-radius: 8px;">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 me-3">
                        <i class="bi bi-exclamation-circle-fill" style="color: #ef4444; font-size: 1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1 me-4">
                        <p class="mb-0 text-slate-900 fw-800" style="font-size: 14px;">System Error</p>
                        <p class="mb-0 text-slate-500 fw-600" style="font-size: 12px; line-height: 1.4;">{{ session('error') }}</p>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close" style="font-size: 0.6rem;"></button>
                </div>
            </div>
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const progressBar = document.getElementById('top-progress-bar');

    // 1. LOADING BAR LOGIC
    function startLoadingBar() {
        if (!progressBar) return;
        progressBar.style.display = 'block';
        progressBar.style.opacity = '1';
        progressBar.style.width = '0%';
        void progressBar.offsetWidth; 
        progressBar.style.transition = 'width 2s cubic-bezier(0.1, 0.05, 0, 1)';
        progressBar.style.width = '90%';
    }

    // Handle Link Clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a');
        if (link && link.href && !link.href.includes('#') && !link.getAttribute('target') && 
            !link.hasAttribute('data-bs-toggle') && link.hostname === window.location.hostname) {
            startLoadingBar();
        }
    });

    // 2. FORM SUBMISSION LOGIC
    document.addEventListener('submit', function (e) {
        const form = e.target;

        const submitBtn = form.querySelector('[type="submit"]') || document.activeElement;

        if (submitBtn && submitBtn.hasAttribute('data-skip-global-loader')) {
        return;
    }

        if (submitBtn && (submitBtn.type === 'submit' || submitBtn.tagName === 'BUTTON')) {

            submitBtn.disabled = true;
            const originalText = submitBtn.innerText.trim();


            if (submitBtn.classList.contains('btn-sm') || submitBtn.classList.contains('btn-small')) {
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${originalText}`;
            } else {
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;
            }
        }
        
        // Also trigger top progress bar on form submit
        if (progressBar) {
            startLoadingBar();
            progressBar.style.width = '100%';
        }
    });

    // 3. BOOKING TIMER LOGIC
    const DURATION = 15 * 60 * 1000;
    const KEY = 'swift_booking_expiry';
    const REDIRECT = "{{ route('home') }}";

    function initializeTimer() {
        const isAuthenticated = @json(auth()->check());
        if (!isAuthenticated) {
            localStorage.removeItem(KEY);
            return null;
        }
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
    if (expiryTime) {
        const updateTimerDisplay = () => {
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
                display.style.opacity = '1';
                if (distance < 120000) display.classList.add('text-danger', 'fw-bold');
            }
        };
        updateTimerDisplay();
        const timerInterval = setInterval(updateTimerDisplay, 1000);
    }

    if ("{{ Request::is('checkout/success/*') }}") {
        localStorage.removeItem(KEY);
    }
});
</script>
</body>
</html>