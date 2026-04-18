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
            !link.hasAttribute('data-bs-toggle') && link.hostname === window.location.hostname &&
            !link.classList.contains('logout-btn')) {
            startLoadingBar();
        }
    });

    // 2. FORM SUBMISSION LOGIC
    document.addEventListener('submit', function (e) {
        const form = e.target;
        let submitBtn = e.submitter;

        if (!submitBtn) {
            submitBtn = form.querySelector('[type="submit"]') || document.activeElement;
        }

        if (submitBtn && submitBtn.hasAttribute('data-skip-global-loader')) {
            return;
        }

        if (submitBtn && (submitBtn.type === 'submit' || submitBtn.tagName === 'BUTTON' || submitBtn.hasAttribute('form'))) {
            submitBtn.disabled = true;
            const originalText = submitBtn.innerText.trim();

            if (submitBtn.classList.contains('btn-sm') || submitBtn.classList.contains('btn-small')) {
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${originalText}`;
            } else {
                submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;
            }
        }
        
        if (progressBar) {
            progressBar.style.display = 'block';
            progressBar.style.width = '0%';
            void progressBar.offsetWidth;
            progressBar.style.transition = 'width 2s cubic-bezier(0.1, 0.05, 0, 1)';
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