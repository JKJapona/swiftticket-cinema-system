<div id="mobile-defense" style="display: none;">
    <script>
        (function() {
            const defenseOverlay = document.getElementById('mobile-defense');

            function checkResolution() {
                if (window.innerWidth < 992) {
                    // Mobile/Tablet: Show overlay and lock scroll
                    defenseOverlay.style.setProperty('display', 'flex', 'important');
                    document.documentElement.classList.add('no-scroll');
                    document.body.classList.add('no-scroll');
                } else {
                    // Desktop: Hide overlay and unlock scroll
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
            {{ $slot ?? 'SwiftTicket Abreeza is optimized for high-fidelity desktop displays. Mobile access is restricted to ensure booking accuracy and security.' }}
        </p>
        
        <div class="mt-4 py-2 px-3 d-inline-block" style="background: rgba(255,193,7,0.1); border-radius: 8px;">
            <p class="text-warning fw-bold small mb-0">PLEASE SWITCH TO PC OR TABLET</p>
        </div>
    </div>
</div>

<style>
    #mobile-defense {
        display: none !important;
    }

    @media (max-width: 991.98px) {
        #mobile-defense {
            position: fixed;
            inset: 0;
            background: linear-gradient(135deg, #000c33 0%, #001a66 100%);
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
</style>