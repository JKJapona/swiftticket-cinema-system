@extends('layouts.auth')

@section('content')
<div class="text-center mb-4">
    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 35px;" alt="Logo">
</div>

<div class="mx-auto" style="max-width: 750px;">
    
    <div class="card shadow-sm p-4 p-md-5 rounded-4 border-0" style="background-color: #fff; border: 1px solid #e2e8f0 !important;">
        
        <div class="mb-4">
            <a href="{{ route('login') }}" class="text-secondary text-decoration-none small d-inline-flex align-items-center gap-2 opacity-75 hover-opacity-100">
                <i class="bi bi-arrow-left fs-6"></i> 
                <span class="fw-medium">Back to login</span>
            </a>
        </div>

        <h1 class="fw-bold mb-1 text-slate-900" style="font-size: 32px;">Privacy Policy</h1>
        <p class="text-muted mb-4 small">Last updated: March 24, 2026</p>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Introduction</h5>
            <p class="text-secondary small leading-relaxed">
                SwiftTicket ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our cinema ticketing platform.
            </p>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Information We Collect</h5>
            <p class="text-secondary small mb-2">We collect information that you provide directly to us, including:</p>
            <ul class="text-secondary small ps-3">
                <li class="mb-1">Name and contact information</li>
                <li class="mb-1">Email address and phone number</li>
                <li class="mb-1">Payment information</li>
                <li class="mb-1">Booking history and preferences</li>
            </ul>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">How We Use Your Information</h5>
            <p class="text-secondary small mb-2">We use the information we collect to:</p>
            <ul class="text-secondary small ps-3">
                <li class="mb-1">Process your ticket bookings</li>
                <li class="mb-1">Send booking confirmations and updates</li>
                <li class="mb-1">Improve our services</li>
                <li class="mb-1">Communicate with you about promotions and offers</li>
            </ul>
        </div>

        <div class="content-section pt-3 border-top">
            <h5 class="fw-bold text-dark mb-2">Contact Us</h5>
            <p class="text-secondary small">
                If you have questions about this Privacy Policy, please contact us at 
                <a href="mailto:privacy@swiftticket.com" class="text-primary text-decoration-none fw-medium">privacy@swiftticket.com</a>
            </p>
        </div>
    </div>

    <div class="text-center mt-4 mb-5">
        <p class="text-muted opacity-50 mb-0" style="font-size: 10px;">&copy; {{ date('Y') }} SwiftTicket Abreeza. All rights reserved.</p>
    </div>
</div>

<style>
    .leading-relaxed { line-height: 1.6; }
    .text-slate-900 { color: #0f172a; }
    ul li::marker { color: #cbd5e1; }
    .hover-opacity-100:hover { opacity: 1 !important; }
    
    .auth-card-container { 
        max-width: 800px !important; 
    }
</style>
@endsection