<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .auth-wrapper { display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .auth-card-container { width: 100%; max-width: 400px; }

        button[type="submit"] { transition: all 0.2s ease-in-out; cursor: pointer; }
        button[style*="#004AAD"]:hover { background-color: #003580 !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0, 74, 173, 0.2); }
        button[style*="#FFD700"]:hover { background-color: #e6c200 !important; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(230, 194, 0, 0.2); }
        button[type="submit"]:active { transform: translateY(0); filter: brightness(0.9); }
    </style>
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card-container">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>