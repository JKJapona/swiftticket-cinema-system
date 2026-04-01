@extends('layouts.auth')

@section('content')
@php
    $prev = url()->previous();
    $current = url()->current();
    
    $authPages = ['login', 'register'];

    if ($prev == $current || Str::contains($prev, $authPages)) {
        $backTarget = url('/');
    } else {
        $backTarget = $prev;
    }
@endphp

<div class="mb-3">
    <a href="{{ $backTarget }}" class="text-secondary text-decoration-none small d-inline-flex align-items-center gap-2 opacity-75">
        <i class="bi bi-arrow-left fs-6"></i> 
        <span class="fw-medium">Back</span>
    </a>
</div>

<div class="card shadow-sm p-3 p-md-4 rounded-4" style="border: 1px solid #e2e8f0;">
    <div class="text-center mb-3">
        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 32px;" alt="Logo">
    </div>

    <h1 class="fw-bold text-center mb-1 text-slate-900" style="font-size: 32px;">Sign in</h1>
    <p class="text-muted text-center mb-3 small">Enter your credentials to proceed</p>

    {{-- Global Error Alert --}}
    @if ($errors->any())
        <div class="alert alert-danger border-0 small rounded-3 mb-3 d-flex align-items-center gap-2 py-2" role="alert">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>Invalid email or password. Please try again.</div>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        
        {{-- Email Address --}}
        <div class="mb-2">
            <label class="form-label fw-medium text-secondary mb-1 small">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" 
                   class="form-control border-secondary-subtle bg-light @error('email') is-invalid @enderror" 
                   required autofocus>
            @error('email')
                <div class="invalid-feedback small fw-500">{{ $message }}</div>
            @enderror
        </div>
        
        {{-- Password --}}
        <div class="mb-3">
            <label class="form-label fw-medium text-secondary mb-1 small">Password</label>
            <input type="password" name="password" 
                   class="form-control border-secondary-subtle bg-light @error('password') is-invalid @enderror" 
                   required>
            @error('password')
                <div class="invalid-feedback small fw-500">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2">
                <input class="form-check-input mt-0" type="checkbox" name="remember" id="remember">
                <label class="form-check-label text-secondary fw-medium small" for="remember">Keep me signed in</label>
            </div>
            <a href="#" class="text-primary text-decoration-none fw-medium small">Forgot password?</a>
        </div>

        <button type="submit" class="btn w-100 py-2.5 fw-semibold text-dark rounded-3 border-0 shadow-sm" style="font-size: 18px; background-color: #FFD700;">
            Log In
        </button>
    </form>

    <div class="text-center mt-3 pt-3 border-top">
        <p class="mb-1 text-muted small">Don't have an account? <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Sign up</a></p>
        <div class="mt-3">
            <p class="text-muted opacity-50 mb-1" style="font-size: 10px;">Protected by reCAPTCHA</p>
            {{-- Admin Login Button Removed --}}
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