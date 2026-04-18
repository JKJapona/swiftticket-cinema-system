<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwiftTicket | Abreeza</title>

    <meta name="title" content="SwiftTicket | Abreeza Cinema">
    <meta name="description" content="Book your favorite movies at Ayala Malls Abreeza quickly and securely.">

    <meta property="og:type" content="website">
    <meta property="og:url" content="https://swiftticket-cinema-system.onrender.com/">
    <meta property="og:title" content="SwiftTicket | Abreeza Cinema">
    <meta property="og:description" content="Experience seamless movie ticketing at Abreeza. Simple, fast, and secure.">
    <meta property="og:image" content="{{ asset('images/og-banner.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.ico') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div id="top-progress-bar"></div>
    @include('components.mobile-defense')

    <header class="app-header">
        @include('layouts.app-topbar')
    </header>

    @include('components.toast')

    <main>
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @include('layouts.scripts.global-logic')
</body>
</html>