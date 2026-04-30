@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row g-4">
        
        {{-- SIDEBAR --}}
        <div class="col-lg-3">
            <div class="card border border-primary border-opacity-25 shadow-sm rounded-3 sticky-top" style="top: 4rem; z-index: 10;">
                <div class="card-body p-0">
                    <div class="p-4 border-bottom">
                        <h6 class="text-uppercase x-small fw-bold text-primary mb-0" style="letter-spacing: 2px;">Account Menu</h6>
                    </div>
                    <div class="list-group list-group-flush rounded-0" id="profile-tabs">
                        <a href="{{ route('profile') }}" 
                           class="list-group-item list-group-item-action py-3 px-4 border-0 rounded-0 d-flex align-items-center gap-3 {{ request()->routeIs('profile') ? 'active-sidebar' : '' }}">
                            <i class="bi bi-grid-1x2-fill fs-5"></i>
                            <span class="fw-bold">Overview</span>
                        </a>

                        <a href="{{ route('profile.edit') }}" 
                           class="list-group-item list-group-item-action py-3 px-4 border-0 rounded-0 d-flex align-items-center gap-3 {{ request()->routeIs('profile.edit') ? 'active-sidebar' : '' }}">
                            <i class="bi bi-person-gear fs-5"></i>
                            <span class="fw-bold">Profile Settings</span>
                        </a>

                        <div class="p-3 mt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-light-danger w-100 py-2 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i>
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="col-lg-9">
            
            {{-- HEADER CARD --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4 profile-header-gradient overflow-hidden">
                <div class="card-body p-4 p-md-5 d-flex flex-column flex-md-row align-items-center text-center text-md-start gap-4 position-relative">
                    <div class="profile-avatar-wrapper mx-auto mx-md-0">
                        <div class="avatar-circle">
                            <span class="fs-1 fw-black">{{ substr($user->full_name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h2 class="fw-black text-white mb-1">{{ $user->full_name }}</h2>
                        <p class="text-white text-opacity-75 mb-0 fw-medium">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            {{-- PERSONAL DETAILS CARD --}}
            <div class="card border border-primary border-opacity-25 shadow-sm rounded-3 overflow-hidden bg-white mb-4">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center gap-2">
                    <i class="bi bi-pencil-square text-primary"></i>
                    <h5 class="fw-800 text-slate-700 mb-0">Personal Details</h5>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            {{-- Full Name --}}
                            <div class="col-md-12">
                                <label class="label-caps mb-2">Full Name</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-person-badge"></i></span>
                                    <input type="text" name="full_name" class="form-control rounded-0 @error('full_name') is-invalid @enderror" 
                                        value="{{ old('full_name', $user->full_name) }}" required>
                                </div>
                                @error('full_name')
                                    <div class="text-danger small fw-bold mt-1">
                                        <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            {{-- Email & Phone --}}
                            <div class="col-md-6">
                                <label class="label-caps mb-2">Email Address</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope-at"></i></span>
                                    <input type="email" name="email" class="form-control rounded-0 @error('email') is-invalid @enderror" 
                                        value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="label-caps mb-2">Phone Number</label>
                                <div class="input-group custom-input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="text" name="phone_number" class="form-control rounded-0" 
                                        value="{{ old('phone_number', $user->phone_number) }}">
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary bg-swift-blue px-4 fw-bold">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

    </div>
</div>


@endsection