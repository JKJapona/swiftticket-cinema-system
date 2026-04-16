<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SwiftTicket_Compact_Report_{{ now()->format('Y-m-d') }}</title>
    
    <style>
        :root {
            --swift-blue: #004AAD;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --success-green: #10B981;
            --slate-50: #F8FAFC;
            --slate-200: #E2E8F0;
        }

        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            background: #FFFFFF; 
            padding: 25px;
            color: var(--text-primary);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .h1 { font-size: 24px; font-weight: 700; margin: 0; }
        .h4 { font-size: 16px; font-weight: 600; margin: 0; }
        
        .body-small { font-size: 13px; font-weight: 400; line-height: 1.4; }
        .body-small-semibold { font-size: 13px; font-weight: 600; line-height: 1.4; }
        
        .caption-bold { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-secondary); }
        .caption-regular { font-size: 11px; font-weight: 400; line-height: 1.2; }

        .price-medium { font-size: 20px; font-weight: 700; color: var(--swift-blue); margin: 2px 0 0 0; }

        .primary-text { color: var(--text-primary); }
        .secondary-text { color: var(--text-secondary); }
        .success-text { color: var(--success-green) !important; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .overflow-hidden { overflow: hidden; }

        .logo-container { text-align: center; margin-bottom: 15px; }
        .logo-container img { height: 32px; }

        .header-content { text-align: center; margin-bottom: 25px; }

        .section-card { 
            background: #FFFFFF; 
            border-radius: 0.75rem; 
            border: 1px solid var(--slate-200); 
            margin-bottom: 15px;
        }
        
        .stat-card { 
            padding: 15px 20px; 
            min-width: 200px;
            background: var(--slate-50) !important; 
        }

        .summary-wrapper { display: flex; justify-content: flex-start; margin-bottom: 20px; }

        .table-header { padding: 12px 16px; border-bottom: 1px solid var(--slate-200); }
        
        .report-table { width: 100%; border-collapse: collapse; }
        .report-table th { 
            background-color: var(--slate-50) !important; 
            padding: 8px 16px; 
            border-bottom: 1px solid var(--slate-200); 
            text-align: left;
        }
        .report-table td { padding: 10px 16px; border-bottom: 1px solid var(--slate-50); }
        
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            border-top: 1px solid var(--slate-200); 
            padding-top: 15px; 
        }

        @media print {
            body { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="logo-container">
        <img src="{{ asset('images/swiftticket_abreeza.svg') }}" alt="SwiftTicket Abreeza">
    </div>

    <div class="header-content">
        <h1 class="h1">Sales Report</h1>
        <p class="caption-regular secondary-text">
            Internal Financial Document • {{ now()->format('M d, Y h:i A') }}
        </p>
    </div>

    {{-- Summary --}}
    <div class="summary-wrapper">
        <div class="section-card stat-card">
            <span class="caption-bold">Total Revenue</span>
            <h3 class="price-medium">₱{{ number_format($totalRevenue, 2) }}</h3>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="section-card overflow-hidden">
        <div class="table-header">
            <h5 class="h4">Revenue by Movie</h5>
        </div>

        <table class="report-table">
            <thead>
                <tr>
                    <th class="caption-bold">Movie Title</th>
                    <th class="caption-bold text-center">Tickets</th>
                    <th class="caption-bold text-end">Total Sales</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movieSales as $movie)
                    <tr>
                        <td class="body-small-semibold primary-text">{{ $movie->title }}</td>
                        <td class="body-small text-center secondary-text">{{ $movie->tickets }}</td>
                        <td class="text-end body-small-semibold success-text">
                            ₱{{ number_format($movie->total, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center body-small secondary-text" style="padding: 20px;">
                            No sales recorded.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <div class="footer caption-regular secondary-text">
        © {{ date('Y') }} SwiftTicket CMS • Davao City
    </div>

    {{-- Print & Navigation Logic --}}
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
                
                window.location.href = "{{ route('admin.dashboard') }}"; 
            }, 500);
        }
    </script>

</body>
</html>