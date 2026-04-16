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

        <h1 class="fw-bold mb-1 text-slate-900" style="font-size: 32px;">Terms & Conditions</h1>
        <p class="text-muted mb-4 small">Last updated: March 24, 2026</p>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Agreement to Terms</h5>
            <p class="text-secondary small leading-relaxed">
                By accessing and using SwiftTicket, you agree to be bound by these Terms and Conditions. If you disagree with any part of these terms, you may not access the service.
            </p>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Ticket Purchases</h5>
            <p class="text-secondary small mb-2">When purchasing tickets through SwiftTicket:</p>
            <ul class="text-secondary small ps-3">
                <li class="mb-1">All sales are final unless the screening is cancelled</li>
                <li class="mb-1">Tickets are non-transferable and non-refundable</li>
                <li class="mb-1">You must present a valid ticket (digital or printed) for entry</li>
                <li class="mb-1">One ticket admits one person to one screening</li>
            </ul>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">User Account</h5>
            <p class="text-secondary small mb-2">When you create an account with us:</p>
            <ul class="text-secondary small ps-3">
                <li class="mb-1">You must provide accurate and complete information</li>
                <li class="mb-1">You are responsible for maintaining account security</li>
                <li class="mb-1">You must notify us immediately of any unauthorized access</li>
                <li class="mb-1">You are responsible for all activities under your account</li>
            </ul>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Cinema Rules</h5>
            <p class="text-secondary small mb-2">By purchasing a ticket, you agree to:</p>
            <ul class="text-secondary small ps-3">
                <li class="mb-1">Follow all cinema rules and regulations</li>
                <li class="mb-1">Not record, photograph, or livestream the screening</li>
                <li class="mb-1">Arrive on time (late entry may not be permitted)</li>
                <li class="mb-1">Respect other patrons and cinema staff</li>
            </ul>
        </div>

        <div class="content-section mb-4">
            <h5 class="fw-bold text-dark mb-2">Limitation of Liability</h5>
            <p class="text-secondary small leading-relaxed">
                SwiftTicket shall not be liable for any indirect, incidental, special, consequential or punitive damages resulting from your use of or inability to use the service.
            </p>
        </div>

        <div class="content-section pt-3 border-top">
            <h5 class="fw-bold text-dark mb-2">Contact Information</h5>
            <p class="text-secondary small">
                For questions about these Terms & Conditions, please contact us at 
                <a href="mailto:legal@swiftticket.com" class="text-primary text-decoration-none fw-medium">legal@swiftticket.com</a>
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
    
    /* Scoped override for the main wrapper width */
    .auth-card-container { 
        max-width: 800px !important; 
    }
</style>
@endsection