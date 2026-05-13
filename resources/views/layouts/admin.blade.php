<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel | SwiftTicket</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin-bundle.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div id="top-progress-bar"></div>
    @include('components.mobile-defense')

    <div class="d-flex">
        @include('layouts.admin-sidebar')

        <main id="admin-main-wrapper" class="admin-main-content flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

    @include('components.toast')
    @include('components.confirmation-modal')


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.scripts.global-logic')
</body>
</html>