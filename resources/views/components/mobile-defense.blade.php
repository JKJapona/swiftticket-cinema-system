<div id="mobile-defense" style="display: none;">
    <script>
        (function() {
            const defenseOverlay = document.getElementById('mobile-defense');

            function checkResolution() {
                const isSmallScreen = window.innerWidth < 768; 
                const isMobileIdentity = navigator.userAgent.includes('Mobi');

                if (isSmallScreen && isMobileIdentity) {
                    defenseOverlay.style.setProperty('display', 'flex', 'important');
                    document.documentElement.classList.add('no-scroll');
                    document.body.classList.add('no-scroll');
                } else {
                    defenseOverlay.style.setProperty('display', 'none', 'important');
                    document.documentElement.classList.remove('no-scroll');
                    document.body.classList.remove('no-scroll');
                }
            }
            checkResolution();
            window.addEventListener('resize', checkResolution);
        })();
    </script>
    
    <div class="defense-card">      
        <i class="bi {{ $icon ?? 'bi-display-fill' }} text-warning" style="font-size: 3.5rem;"></i>

        <h2 class="mt-3 fw-bold">{{ $title ?? 'Desktop Site Required' }}</h2>
        
        <p class="text-white-50 mx-auto px-3" style="max-width: 350px;">
           {{ 'The cinema management dashboard requires a larger screen for accurate seating and schedule adjustments. Please switch to a computer to manage showtimes.' }}
        </p>
        
        <div class="mt-4 py-2 px-3 d-inline-block" style="background: rgba(255,193,7,0.1); border-radius: 8px;">
            <p class="text-warning fw-bold small mb-0">PLEASE SWITCH TO PC OR TABLET</p>
        </div>

        <div class="mt-4">
            <button onclick="window.history.back()" class="btn-back">
                <i class="bi bi-arrow-left me-2"></i> Go Back
            </button>
        </div>
    </div>
</div>

<style>
    #mobile-defense {
        display: none !important;
    }

    @media (max-width: 767.98px) {
        #mobile-defense {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #000c33 0%, #002696 100%);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            padding: 20px;
        }

        .defense-card {
            padding: 20px;
            transform: translateY(-8%);
            animation: fadeIn 0.4s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(calc(-8% + 15px)); }
            to { opacity: 1; transform: translateY(-8%); }
        }

        body.no-scroll, html.no-scroll {
            overflow: hidden !important;
            height: 100vh !important;
            position: fixed;
            width: 100%;
        }
    }

    .btn-back {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
        transform: translateX(-5px);
    }

    .btn-back i {
        transition: transform 0.3s ease;
    }

    .btn-back:hover i {
        transform: translateX(-3px);
    }
</style>