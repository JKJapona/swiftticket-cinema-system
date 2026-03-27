@extends('layouts.auth')

@section('content')
<div class="card shadow-sm p-3 p-md-4 rounded-4" style="border: 1px solid #e2e8f0; border-top: 4px solid #004AAD !important;">
    <div class="text-center mb-3">
        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 32px;" alt="Logo">
    </div>

    <div class="text-center mb-3">
        <span class="badge rounded-pill px-3 py-2 border text-primary fw-bold text-uppercase" style="font-size: 10px; background-color: #f0f7ff; border-color: #d0e7ff !important; letter-spacing: 1px;">
            🛡️ Admin Access
        </span>
    </div>

    <h1 class="fw-bold text-center mb-1 text-slate-900" style="font-size: 32px;">Admin Sign In</h1>
    <p class="text-muted text-center mb-3 small">Access the admin dashboard</p>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label class="form-label fw-medium text-secondary mb-1 small">Admin Email</label>
            <input type="email" name="email" class="form-control border-secondary-subtle bg-light" required>
        </div>
        
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary mb-1 small">Password</label>
            <input type="password" name="password" class="form-control border-secondary-subtle bg-light" required>
        </div>

        <button type="submit" class="btn w-100 py-2.5 fw-semibold text-white rounded-3 border-0 shadow-sm" style="font-size: 18px; background-color: #004AAD;">
            Sign In as Admin
        </button>
    </form>

    <div class="text-center mt-3 pt-3 border-top">
        <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none small">Back to User Login</a>
        <div class="mt-3">
            <p class="text-muted opacity-50 mb-0" style="font-size: 10px;">Protected by reCAPTCHA</p>
        </div>
    </div>
</div>

<div class="text-center mt-4 d-flex justify-content-center gap-3 opacity-50">
    <a href="#" class="text-secondary text-decoration-none small" style="font-size: 12px;">Privacy Policy</a>
    <span class="text-secondary opacity-25">•</span>
    <a href="#" class="text-secondary text-decoration-none small" style="font-size: 12px;">Terms & Conditions</a>
    <span class="text-secondary opacity-25">•</span>
    <a href="#" class="text-secondary text-decoration-none small" style="font-size: 12px;">Contact Us</a>
</div>
@endsection