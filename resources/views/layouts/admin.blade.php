<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | SwiftTicket</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="preload" as="image" href="{{ asset('images/SwiftTicket_Abreeza.svg') }}" type="image/svg+xml">
</head>
<body class="bg-light">

<div id="loadingOverlay" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" 
     style="z-index: 9999; background: rgba(248, 250, 252, 0.9);"> {{-- --slate-50 --}}
    
    <div class="d-flex flex-column align-items-center">
        <div class="position-relative mb-3" style="width: 60px; height: 60px;">
            <div class="spinner-border position-absolute top-0 start-0" 
                 style="width: 60px; height: 60px; color: var(--slate-200); border-width: 5px; opacity: 0.5; animation: none;" 
                 role="status">
            </div>
            <div class="spinner-border position-absolute top-0 start-0" 
                 style="width: 60px; height: 60px; color: var(--swift-blue) !important; border-width: 5px; border-right-color: transparent;" 
                 role="status">
            </div>
        </div>

        <h5 class="text-slate-900 mb-1 fw-800" style="font-size: 16px; letter-spacing: -0.3px;">Updating Database</h5>
        <p class="text-slate-500 small mb-0 fw-600" style="font-size: 12px;">Processing your request...</p>
    </div>
</div>

    <div class="d-flex">
        @include('layouts.admin-sidebar')

        <main id="admin-main-wrapper" class="admin-main-content w-90 p-4" style="margin-left: 240px;">
            @yield('content')
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('submit', function (e) {
            const form = e.target;
            
            const isTargetForm = form.id.includes('MovieForm') || 
                                form.id === 'deleteForm';

            if (isTargetForm) {
                if (e.defaultPrevented) {
                    return; 
                }

                const overlay = document.getElementById('loadingOverlay');
                const submitBtn = form.querySelector('button[type="submit"]');

                if (overlay) {
                    overlay.classList.remove('d-none');
                    overlay.classList.add('d-flex');
                }

                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span> Processing...`;
                }
            }
        });
    </script>
</body>
</html>