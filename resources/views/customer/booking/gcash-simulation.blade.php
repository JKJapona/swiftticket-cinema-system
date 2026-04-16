<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GCash Payment Simulation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #e9ecef; 
            font-family: 'Inter', -apple-system, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
        }

        .header-banner {
            background-color: #0056e3;
            height: 200px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 15px;
        }

        .main-card {
            background: white;
            width: 90%;
            max-width: 900px;
            margin: -130px auto 0;
            border-radius: 12px;
            padding: 60px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .gcash-logo {
            width: 140px;
        }

        .instruction-top {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .instruction-main {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 30px;
        }

        .qr-container {
            display: inline-block;
            padding: 10px;
            background: white;
            border-radius: 4px;
        }

        .qr-image {
            width: 240px;
            height: 240px;
            object-fit: contain;
        }

        .btn-simulate {
            margin-top: 40px;
            background: transparent;
            border: 1px solid #dee2e6;
            color: #adb5bd;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 12px;
            transition: all 0.3s;
            cursor: pointer;
        }

        .btn-simulate:hover {
            border-color: #0056e3;
            color: #0056e3;
        }
    </style>
</head>
<body>

    <div class="header-banner">
        <img src="{{ asset('images/gcash_logo.png') }}" alt="GCash" class="gcash-logo">
    </div>

    <div class="main-card">
        <p class="instruction-top">Securely complete the payment with your GCash app</p>
        <p class="instruction-main">Log in to GCash and scan this QR with the QR Scanner.</p>

        <div class="qr-container">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=SwiftTicket-Payment-Simulation" 
                 alt="Payment QR" class="qr-image">
        </div>

        <div class="d-block">
            <button class="btn-simulate" onclick="handleSuccess()">
                [ Simulation: Click to confirm payment ]
            </button>
        </div>
    </div>

    <script>
        function handleSuccess() {
            if(confirm("Payment Successful! Return to SwiftTicket to confirm your seats?")) {
                window.close();
            }
        }
    </script>
</body>
</html>