@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Customers</h1>
            <p class="text-secondary mb-0">View and manage registered users and their booking history.</p>
        </div>
    </div>

    {{-- 2. STATS ROW --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Customers</span>
                <h3 class="stat-value mb-0">{{ $stats['total_customers'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-primary border-4">
                <span class="caption d-block mb-1">Active (This Month)</span>
                <h3 class="stat-value mb-0">{{ $stats['active_this_month'] }}</h3>
            </div>
        </div>
    </div>

    {{-- 3. TABLE SECTION --}}
    <div class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">User Profile</th>
                        <th class="py-3 caption">Contact Info</th>
                        <th class="py-3 caption text-center">Total Bookings</th>
                        <th class="py-3 caption">Joined Date</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($customers as $customer)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    {{-- User Avatar Placeholder --}}
                                    <div class="rounded-circle bg-slate-100 d-flex align-items-center justify-content-center text-primary fw-800" 
                                         style="width: 40px; height: 40px; border: 1px solid #e2e8f0;">
                                        {{ substr($customer->first_name, 0, 1) }}{{ substr($customer->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-700 text-slate-900">{{ $customer->full_name }}</div>
                                        <div class="caption text-lowercase" style="font-size: 10px;">ID: #USR-{{ $customer->id }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900" style="font-size: 13px;">{{ $customer->email }}</div>
                                <div class="text-secondary small">{{ $customer->phone_number ?? 'No phone' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border fw-700 px-3">
                                    {{ $customer->bookings_count }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900">{{ $customer->created_at->format('M d, Y') }}</div>
                                <div class="caption" style="font-size: 10px;">{{ $customer->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- View Details / History --}}
                                    <a href="#" class="btn btn-sm btn-light border text-slate-900 fw-600 shadow-sm me-2">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    {{-- Delete/Block --}}
                                    <form action="#" method="POST" class="d-inline" onsubmit="return confirm('Archive this customer?');">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-light border text-danger shadow-sm">
                                            <i class="bi bi-person-x"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="bi bi-people d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                                <span class="fw-700 text-slate-400">No customers found.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    :root {
        --swift-blue: #004AAD; 
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-500: #64748B; 
        --slate-900: #1E293B; 
    }

    .stat-value {
        font-size: 28px !important;
        font-weight: 800 !important;
        color: #0f172a !important;
        line-height: 1.2 !important; 
        display: block;
        margin-top: 2px; 
    }

    .section-card { background: white; border-radius: 1.25rem; border: 1px solid #e2e8f0; }
    .caption { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: #64748b; }
    .table thead th { border-bottom: 1px solid #f1f5f9; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }
    
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
</style>
@endsection