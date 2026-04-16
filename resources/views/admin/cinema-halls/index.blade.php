@extends('layouts.admin')

@section('content')
<div class="admin-container">

    {{-- 1. HEADER & PRIMARY ACTIONS --}}
    <header class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-800 text-slate-900 mb-1" style="font-size: 32px;">Cinema Halls</h1>
            <p class="text-secondary mb-0">Manage cinema rooms and their configurations.</p>
        </div>
        <button class="btn btn-primary bg-swift-blue border-0 px-4 py-2 fw-700 transition-all shadow-sm btn-swift-action" 
                data-bs-toggle="modal" data-bs-target="#createHallModal">
            <i class="bi bi-plus-lg me-2"></i> Add Cinema Hall
        </button>
    </header>

    {{-- 2. STATISTICS CARDS --}}
    <section class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Halls</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['total_halls'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm">
                <span class="caption d-block mb-1">Total Seats</span>
                <h3 class="stat-value mb-0 text-end">{{ number_format($stats['total_seats']) }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-success border-4">
                <span class="caption d-block mb-1 text-success">Active Halls</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['active_halls'] }}</h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="section-card p-4 border-0 shadow-sm border-start border-danger border-4">
                <span class="caption d-block mb-1 text-danger">Under Maintenance</span>
                <h3 class="stat-value mb-0 text-end">{{ $stats['maintenance_halls'] }}</h3>
            </div>
        </div>
    </section>

    {{-- 3. QUICK FILTERS --}}
    <nav class="section-card p-3 mb-4 border-0 shadow-sm">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <span class="caption text-slate-500">Filter Status:</span>
                <div class="btn-group gap-2" role="group">
                    <button type="button" class="btn btn-sm px-3 rounded-2 fw-600 border transition-all filter-btn active-filter bg-white" 
                            onclick="filterHalls('All', this)">All</button>
                    <button type="button" class="btn btn-sm px-3 rounded-2 fw-600 border transition-all filter-btn text-success bg-white" 
                            onclick="filterHalls('Active', this)">Active</button>
                    <button type="button" class="btn btn-sm px-3 rounded-2 fw-600 border transition-all filter-btn bg-white" 
                            style="color: #b45309; border-color: #f59e0b;"
                            onclick="filterHalls('Maintenance', this)">Maintenance</button>
                    <button type="button" class="btn btn-sm px-3 rounded-2 fw-600 border transition-all filter-btn text-secondary bg-white" 
                            onclick="filterHalls('Inactive', this)">Inactive</button>
                </div>
            </div>
            <div class="caption text-slate-400">
                Showing <span id="visibleCount">{{ $halls->count() }}</span> Cinema Halls
            </div>
        </div>
    </nav>

    {{-- 4. DATA TABLE --}}
    <main class="section-card shadow-sm border-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 caption">Hall Name</th>
                        <th class="py-3 caption">Configuration</th>
                        <th class="py-3 caption">Screen / Audio</th>
                        <th class="py-3 caption text-center">Status</th>
                        <th class="py-3 caption text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($halls as $hall)
                        <tr>
                            {{-- Identity --}}
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-swift-blue text-white rounded shadow-sm d-flex align-items-center justify-content-center" 
                                         style="width: 45px; height: 60px;">
                                        <i class="bi bi-door-open-fill fs-4"></i>
                                    </div>
                                    <div>
                                        <div class="fw-700 text-slate-900">{{ $hall->name }}</div>
                                        <div class="caption text-lowercase" style="font-size: 10px;">{{ $hall->total_seats }} Total Seats</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Layout --}}
                            <td>
                                <span class="badge bg-light text-secondary border fw-500">
                                    {{ $hall->number_of_rows }} Rows × {{ $hall->seats_per_row }} Seats
                                </span>
                            </td>

                            {{-- Tech Specs --}}
                            <td>
                                <div class="fw-600 text-slate-700 small">{{ $hall->screen_type }}</div>
                                <div class="caption text-muted" style="font-size: 10px;">{{ $hall->audio_system ?? 'Standard Audio' }}</div>
                            </td>

                            {{-- Status Badge Logic --}}
                            <td class="text-center">
                                @php
                                    $statusStyles = [
                                        'Active'      => ['bg' => '#dcfce7', 'text' => '#166534', 'icon' => 'bi-check-circle-fill'],
                                        'Maintenance' => ['bg' => '#fef3c7', 'text' => '#92400e', 'icon' => 'bi-tools'],
                                        'Default'     => ['bg' => '#f3f4f6', 'text' => '#374151', 'icon' => 'bi-pause-circle-fill']
                                    ];
                                    $style = $statusStyles[$hall->status] ?? $statusStyles['Default'];
                                @endphp

                                <span class="badge border-0" 
                                      style="padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-flex; align-items: center; background-color: {{ $style['bg'] }}; color: {{ $style['text'] }};">
                                    <i class="bi {{ $style['icon'] }} me-1" style="font-size: 11px;"></i> 
                                    {{ $hall->status }}
                                </span>
                            </td>

                            {{-- Row Actions --}}
                            <td class="text-end pe-4">
                                <div class="d-flex align-items-center justify-content-end gap-2">
                                    <button type="button" class="btn btn-sm btn-light border text-slate-900 fw-600 shadow-sm" 
                                            data-bs-toggle="modal" data-bs-target="#editHallModal{{ $hall->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    
                                    <form id="delete-hall-{{ $hall->id }}" action="{{ route('admin.cinema-halls.destroy', $hall->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-light border text-danger shadow-sm"
                                                onclick="swiftConfirm('Delete Cinema Hall?', 'Are you sure you want to remove \'{{ addslashes($hall->name) }}\'? This will also delete all associated seating layouts.', 'danger', () => document.getElementById('delete-hall-{{ $hall->id }}').submit())">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @include('admin.cinema-halls.edit')
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-secondary">
                                <i class="bi bi-building-add d-block mb-2 text-slate-300" style="font-size: 3rem;"></i>
                                <span class="fw-700 text-slate-400">No cinema halls found.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</div>

{{-- MODALS --}}
@include('admin.cinema-halls.create')

{{-- SCRIPTS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Validation Error Modal Handling
        @if ($errors->any())
            let modalId = '{{ session("error_hall_id") ? "editHallModal" . session("error_hall_id") : "createHallModal" }}';
            const modalElement = document.getElementById(modalId);
            if (modalElement) {
                const myModal = new bootstrap.Modal(modalElement);
                setTimeout(() => myModal.show(), 100);
            }
        @endif

        // Auto-close alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                new bootstrap.Alert(alert).close();
            });
        }, 10000);
    });

    // Client-side Filtering Logic
    function filterHalls(status, btn) {
        const rows = document.querySelectorAll('tbody tr');
        let visibleCount = 0;

        // UI Reset
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.classList.remove('bg-swift-blue', 'text-white', 'shadow-sm');
            b.classList.add('bg-white');
        });

        // Activate Button
        btn.classList.replace('bg-white', 'bg-swift-blue');
        btn.classList.add('text-white', 'shadow-sm');

        // Filtering
        rows.forEach(row => {
            if (row.cells.length < 5) return;
            const hallStatus = row.cells[3].textContent.trim();
            
            const isVisible = (status === 'All' || hallStatus === status);
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });

        document.getElementById('visibleCount').textContent = visibleCount;
    }
</script>
@endsection