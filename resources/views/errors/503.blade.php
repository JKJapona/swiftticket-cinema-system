@extends('layouts.app')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center m-0 p-0" 
     style="min-height: 100vh; width: 100vw; background-color: #F8FAFC; position: fixed; top: 0; left: 0; z-index: 9999;">
    
    <div class="text-center p-4 p-md-5">
        <div class="d-flex align-items-center justify-content-center mb-5">
            <img src="{{ asset('images/swiftticket_abreeza.svg') }}" alt="SwiftTicket Abreeza" 
                style="height: 45px;" loading="eager" fetchpriority="high">
        </div>

        <div class="mb-4 d-flex flex-column align-items-center justify-content-center">
            <div class="position-relative d-inline-block">
                <i class="bi bi-camera-reels" style="font-size: 6rem; color: #004AAD !important;"></i>
                
                <div class="spinner-grow position-absolute" role="status" 
                     style="top: 0; right: 0; width: 22px; height: 22px; opacity: 0.4; color: #004AAD; animation-duration: 2.5s;">
                </div>
            </div>
            <div style="width: 40px; height: 4px; background: #004AAD; border-radius: 2px; margin-top: 10px; opacity: 0.6;"></div>
        </div>

        <h1 style="font-size: 2.5rem; font-weight: 700; color: #1E293B; line-height: 1.2;" class="mb-2">
            System Refinement
        </h1>
        
        <p style="font-size: 1.125rem; font-weight: 400; color: #64748B; line-height: 1.6; max-width: 520px;" class="mx-auto mb-5">
            We are currently conducting scheduled technical upgrades to ensure the highest quality booking experience at our Abreeza Cinema hub. We will be back online shortly.
        </p>

        <div class="d-flex justify-content-center">
            <button onclick="window.location.reload()" 
                class="btn py-3 px-5 shadow-sm transition-all" 
                style="background-color: #004AAD; color: #FFFFFF; font-size: 1.125rem; font-weight: 600; border-radius: 8px; border: none;">
                <i class="bi bi-arrow-clockwise me-2"></i>Check Availability
            </button>
        </div>
        
        <div class="mt-5 pt-4 border-top" style="border-color: #E2E8F0 !important;">
            <p style="font-size: 12px; font-weight: 400; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em;">
                Status 503 • Technical Maintenance • Abreeza Cinema Hub
            </p>
        </div>
    </div>
</div>
@endsection