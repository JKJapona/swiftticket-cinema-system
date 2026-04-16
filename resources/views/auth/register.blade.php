@extends('layouts.auth')

@section('content')
<div class="card shadow-sm p-3 p-md-4 rounded-4" style="border: 1px solid #e2e8f0;">
    <div class="text-center mb-3">
        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 32px;" alt="Logo">
    </div>

    <h1 class="fw-bold text-center mb-1 text-slate-900" style="font-size: 32px;">Create Account</h1>
    <p class="text-muted text-center mb-3 small">Sign up to get started with SwiftTicket</p>

    <form id="registerForm" action="{{ route('register') }}" method="POST">
        @csrf
        
        {{-- Full Name --}}
        <div class="mb-2">
            <label class="form-label fw-medium text-secondary mb-1 small">Full Name</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}" 
                   class="form-control border-secondary-subtle bg-light @error('full_name') is-invalid @enderror" 
                   required autofocus>
            @error('full_name')
                <div class="invalid-feedback small fw-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email Address --}}
        <div class="mb-2">
            <label class="form-label fw-medium text-secondary mb-1 small">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="form-control border-secondary-subtle bg-light @error('email') is-invalid @enderror" 
                   required>
            @error('email')
                <div class="invalid-feedback small fw-500">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Password --}}
        <div class="mb-2">
            <label class="form-label fw-medium text-secondary mb-1 small">Password</label>
            <input type="password" name="password" 
                   class="form-control border-secondary-subtle bg-light @error('password') is-invalid @enderror" 
                   required>
            @error('password')
                <div class="invalid-feedback small fw-500">{{ $message }}</div>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary mb-1 small">Confirm Password</label>
            <input type="password" name="password_confirmation" 
                   class="form-control border-secondary-subtle bg-light" 
                   required>
        </div>

        <button type="submit" id="registerBtn" class="btn w-100 py-2.5 fw-semibold text-dark rounded-3 border-0 shadow-sm d-flex align-items-center justify-content-center gap-2" style="font-size: 18px; background-color: #FFD700;">
        <span id="registerSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        <span id="registerText">Create Account</span>
    </button>
    </form>

    <div class="text-center mt-3 pt-3 border-top">
        <p class="mb-0 text-muted small">Already have an account? <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Sign in</a></p>
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