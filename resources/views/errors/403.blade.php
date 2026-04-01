@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0" 
     style="min-height: 100vh; width: 100vw; background-color: #F8FAFC; position: fixed; top: 0; left: 0; z-index: 9999;">
    
    <div class="text-center p-4 p-md-5">
        <div class="d-flex align-items-center justify-content-center mb-5">
            <img src="{{ asset('images/swiftticket_abreeza.svg') }}" alt="SwiftTicket Abreeza" 
                style="height: 45px;">
        </div>

        <div class="mb-4 d-flex flex-column align-items-center justify-content-center">
            <div class="position-relative d-inline-block">
                <i class="bi bi-shield-check" style="font-size: 6rem; color: #004AAD !important;"></i>
                
                <div class="spinner-grow position-absolute" role="status" 
                     style="top: 0; right: 0; width: 22px; height: 22px; opacity: 0.4; color: #004AAD; animation-duration: 3s;">
                </div>
            </div>
            <div style="width: 40px; height: 4px; background: #004AAD; border-radius: 2px; margin-top: 10px; opacity: 0.6;"></div>
        </div>

        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1E293B; line-height: 1.2;" class="mb-2">
            Restricted Access
        </h1>
        
        <p style="font-size: 1.125rem; font-weight: 400; color: #64748B; line-height: 1.6; max-width: 500px;" class="mx-auto mb-5">
            This section is reserved for authorized Cinema Staff and IT personnel only. 
            Please return to the lobby to continue your movie experience.
        </p>

        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="{{ url('/') }}" 
                class="btn py-3 px-5 shadow-sm transition-all text-decoration-none" 
                style="background-color: #004AAD; color: #FFFFFF; font-size: 1.125rem; font-weight: 600; border-radius: 8px; border: none;">
                <i class="bi bi-house-door me-2"></i>Return to Lobby
            </a>
            
            <button onclick="window.history.back()" 
                class="btn py-3 px-5 border transition-all" 
                style="color: #1E293B; border-color: #CBD5E1; font-size: 1.125rem; font-weight: 600; border-radius: 8px; background-color: #FFFFFF;">
                Go Back
            </button>
        </div>
        
        <div class="mt-5 pt-4 border-top" style="border-color: #E2E8F0 !important;">
            <p style="font-size: 12px; font-weight: 400; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em;">
                Status 403 • Authorized Personnel Only • Abreeza Cinema Hub
            </p>
        </div>
    </div>
</div>
@endsection