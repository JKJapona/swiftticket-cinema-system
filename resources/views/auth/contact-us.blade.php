@extends('layouts.auth')

@section('content')
<div class="text-center mb-4">
    <img src="{{ asset('images/swiftticket_abreeza.svg') }}" class="img-fluid" style="height: 35px;" alt="Logo">
</div>

<div class="mx-auto" style="max-width: 850px;">
    <div class="card shadow-sm p-4 p-md-5 rounded-4 border-0" style="background-color: #fff; border: 1px solid #e2e8f0 !important;">
        
        <div class="mb-4">
            <a href="{{ route('login') }}" class="text-secondary text-decoration-none small d-inline-flex align-items-center gap-2 opacity-75 hover-opacity-100">
                <i class="bi bi-arrow-left fs-6"></i> 
                <span class="fw-medium">Back to login</span>
            </a>
        </div>

        <h1 class="fw-bold mb-1 text-slate-900" style="font-size: 32px;">Contact Us</h1>
        <p class="text-muted mb-5 small">Have a question or need assistance? We're here to help!</p>

        <div class="row g-5">
            <div class="col-lg-5">
                <div class="d-flex flex-column gap-4">
                    
                    <div class="d-flex align-items-start gap-3">
                        <div class="contact-icon-circle shadow-sm">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Email</h6>
                            <p class="text-secondary small mb-0">support@swiftticket.com</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="contact-icon-circle shadow-sm">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Phone</h6>
                            <p class="text-secondary small mb-0">+63 (82) 123-4567</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div class="contact-icon-circle shadow-sm">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Location</h6>
                            <p class="text-secondary small mb-0">
                                Abreeza Mall<br>
                                J.P. Laurel Avenue, Bajada<br>
                                Davao City, Philippines
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-lg-7">
                <div id="successState" class="d-none flex-column align-items-center justify-content-center h-100 text-center py-4">
                    <div class="success-icon-circle mb-3">
                        <i class="bi bi-check2"></i>
                    </div>
                    <h4 class="fw-bold text-slate-900 mb-1">Message Sent!</h4>
                    <p class="text-secondary small mb-0 leading-relaxed">We'll get back to you as soon as possible.</p>
                    <button onclick="resetSim()" class="btn btn-link text-decoration-none small mt-3 fw-medium">Send another message</button>
                </div>

                <div id="contactForm">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1">Name</label>
                            <input type="text" class="form-control border-light-subtle py-2 px-3 small shadow-none custom-input" placeholder="Your name">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1">Email</label>
                            <input type="email" class="form-control border-light-subtle py-2 px-3 small shadow-none custom-input" placeholder="your@email.com">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1">Subject</label>
                            <input type="text" class="form-control border-light-subtle py-2 px-3 small shadow-none custom-input" placeholder="What is this about?">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold small text-dark mb-1">Message</label>
                            <textarea class="form-control border-light-subtle py-2 px-3 small shadow-none custom-input" rows="4" placeholder="Your message..." style="resize: none;"></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="button" id="sendBtn" onclick="simulateSend()" class="btn w-100 py-2.5 fw-bold text-dark rounded-3 border-0 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background-color: #FFD700; font-size: 16px;">
                                <span id="sendSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                <span id="sendText">Send Message</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4 mb-5">
        <p class="text-muted opacity-50 mb-0" style="font-size: 10px;">&copy; {{ date('Y') }} SwiftTicket Abreeza. All rights reserved.</p>
    </div>
</div>

<style>
    .text-slate-900 { color: #0f172a; }
    .hover-opacity-100:hover { opacity: 1 !important; }
    .Leading-relaxed { line-height: 1.6; }
    
    .contact-icon-circle {
        width: 42px;
        height: 42px;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .success-icon-circle {
        width: 56px;
        height: 56px;
        background-color: #eff6ff;
        color: #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        flex-shrink: 0;
    }

    .custom-input {
        background-color: #fbfcfe;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .custom-input::placeholder {
        color: #cbd5e1;
        font-size: 14px;
    }
    
    .custom-input:focus {
        background-color: #fff;
        border: 1px solid #3b82f6 !important;
        box-shadow: 0 0 0 1px #3b82f6;
    }

    .auth-card-container { 
        max-width: 900px !important; 
    }
</style>

<script>
    function simulateSend() {
        const btn = document.getElementById('sendBtn');
        const spinner = document.getElementById('sendSpinner');
        const text = document.getElementById('sendText');
        const contactForm = document.getElementById('contactForm');
        const successState = document.getElementById('successState');

        btn.disabled = true;
        btn.style.opacity = '0.8';
        spinner.classList.remove('d-none');
        text.textContent = 'Sending...';

        setTimeout(() => {
            contactForm.style.transition = 'opacity 0.3s ease';
            contactForm.style.opacity = '0';

            setTimeout(() => {
                contactForm.classList.add('d-none');
                
                btn.disabled = false;
                btn.style.opacity = '1';
                spinner.classList.add('d-none');
                text.textContent = 'Send Message';

                successState.classList.remove('d-none');
                successState.classList.add('d-flex');
                successState.style.opacity = '0';
                
                void successState.offsetWidth; 
                
                successState.style.transition = 'opacity 0.4s ease';
                successState.style.opacity = '1';
            }, 300);
        }, 1500);
    }

    function resetSim() {
        const contactForm = document.getElementById('contactForm');
        const successState = document.getElementById('successState');

        successState.style.opacity = '0';
        
        setTimeout(() => {
            successState.classList.add('d-none');
            successState.classList.remove('d-flex');
            
            contactForm.classList.remove('d-none');
            contactForm.style.opacity = '1';
        }, 300);
    }
</script>
@endsection