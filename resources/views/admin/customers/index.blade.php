@extends('layouts.admin')

@section('content')
<div class="admin-container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Customers</h1>
            <p class="text-secondary mb-0">View and manage registered users and their booking history.</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Customers</span>
                <h3 class="stat-value mb-0">{{ number_format($stats['total_customers']) }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-primary border-4">
                <span class="caption d-block mb-1">Active (This Month)</span>
                <h3 class="stat-value mb-0">{{ number_format($stats['active_this_month']) }}</h3>
            </div>
        </div>
    </div>

    {{-- Table --}}
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
                                <div class="d-flex align-items-center gap-3 {{ $customer->status === 'banned' ? 'opacity-50' : '' }}">
                                    <div class="avatar-letter {{ $customer->status === 'banned' ? 'bg-danger-subtle text-danger' : '' }}">
                                        {{ strtoupper(substr($customer->full_name ?? 'C', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-800 text-slate-900" style="font-size: 14px; line-height: 1.2;">
                                            {{ $customer->full_name }}
                                            @if($customer->status === 'banned')
                                                <span class="badge bg-danger ms-1" style="font-size: 8px;">BANNED</span>
                                            @endif
                                        </div>
                                        <div class="caption text-slate-400 mt-1" style="font-size: 9px;">
                                            USR-{{ str_pad($customer->id, 4, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900" style="font-size: 13px;">{{ $customer->email }}</div>
                                <div class="text-secondary small">{{ $customer->phone_number ?? 'No phone' }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-primary border fw-700 px-3 py-2">
                                    {{ $customer->bookings_count }}
                                </span>
                            </td>
                            <td>
                                <div class="fw-600 text-slate-900 small">{{ $customer->created_at->format('M d, Y') }}</div>
                                <div class="caption" style="font-size: 10px;">{{ $customer->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    {{-- View Button --}}
                                    <button type="button" onclick="openCustomerModal({{ $customer->id }})" class="btn btn-sm btn-light border shadow-sm me-2">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    {{-- Toggle Status Button --}}
                                    <form action="{{ route('admin.customers.toggleStatus', $customer->id) }}" method="POST" class="d-inline">
                                        @csrf 
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-light border shadow-sm {{ $customer->status === 'active' ? 'text-danger' : 'text-success' }}"
                                                onclick="return confirm('Are you sure you want to {{ $customer->status === 'active' ? 'ban' : 'unban' }} this user?');"
                                                title="{{ $customer->status === 'active' ? 'Ban User' : 'Unban User' }}">
                                            <i class="bi {{ $customer->status === 'active' ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No customers found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.customers.view')

<script>
function openCustomerModal(id) {
    const modalElement = document.getElementById('customerViewModal');
    const dataContent = document.getElementById('modal-data-content');
    const loader = document.getElementById('modal-loader');
    const myModal = new bootstrap.Modal(modalElement);

    dataContent.classList.add('d-none');
    loader.classList.remove('d-none');
    document.getElementById('m-bookings').innerHTML = '';
    
    myModal.show();

    fetch(`/admin/customers/api/${id}`)
        .then(res => {
            if (!res.ok) throw new Error('Customer not found');
            return res.json();
        })
        .then(user => {
            // Fill basic profile info
            document.getElementById('m-name').innerText = user.full_name;
            document.getElementById('m-email').innerText = user.email;
            document.getElementById('m-id').innerText = 'USR-' + String(user.id).padStart(4, '0');
            document.getElementById('m-phone').innerText = user.phone_number || 'No phone number';
            document.getElementById('m-avatar').innerText = user.full_name.charAt(0).toUpperCase();
            
            // Handle Status Badge styling
            const statusBadge = document.getElementById('m-status');
            statusBadge.innerText = user.status.toUpperCase();
            if (user.status === 'active') {
                statusBadge.className = 'badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3';
            } else {
                statusBadge.className = 'badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3';
            }

            // Handle Bookings List
            const bookingContainer = document.getElementById('m-bookings');
            if (user.bookings && user.bookings.length > 0) {
                // Show the most recent 3 bookings
                user.bookings.slice(0, 3).forEach(b => {
                    const date = new Date(b.created_at).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric'
                    });
                    
                    bookingContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded-3 bg-white shadow-sm">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-ticket-perforated text-primary"></i>
                                <span class="small fw-700 text-slate-900">${b.showtime.movie.title}</span>
                            </div>
                            <span class="caption text-slate-400" style="font-size: 9px;">${date}</span>
                        </div>`;
                });
            } else {
                bookingContainer.innerHTML = `
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x text-slate-300 fs-2"></i>
                        <p class="text-muted small mb-0 mt-1">No recent activity found.</p>
                    </div>`;
            }

            loader.classList.add('d-none');
            dataContent.classList.remove('d-none');
        })
        .catch(err => {
            console.error('Fetch Error:', err);
            loader.classList.add('d-none');
            dataContent.classList.remove('d-none');
            document.getElementById('m-name').innerHTML = '<span class="text-danger">Error Loading Profile</span>';
            document.getElementById('m-bookings').innerHTML = '<p class="text-center small text-danger">Could not retrieve booking history.</p>';
        });
}
</script>

<style>
    :root {
        --swift-blue: #004AAD; 
        --slate-50: #f8fafc;
        --slate-100: #f1f5f9;
        --slate-400: #94a3b8;
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
    .caption { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; color: var(--slate-500); }
    
    .table thead th { border-bottom: 1px solid #f1f5f9; }
    .table-hover tbody tr:hover { background-color: #f8fafc; }

    .avatar-letter {
        width: 38px;
        height: 38px;
        background-color: var(--slate-100);
        color: var(--swift-blue);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }

    tr:hover .avatar-letter {
        background-color: var(--swift-blue);
        color: white;
        border-color: var(--swift-blue);
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 74, 173, 0.15);
    }
    
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
</style>
@endsection