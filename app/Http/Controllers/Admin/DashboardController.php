<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index() 
    {
        $recent_bookings = Booking::with(['user', 'showtime.movie'])
            ->latest()
            ->take(7)
            ->get();

        return view('admin.dashboard', compact('recent_bookings'));
    }

        public function salesReport() 
    {
        $totalRevenue = $this->getTotalConfirmedRevenue();
        $movieSales = $this->getSalesByMovie();

        return view('admin.sales', compact('totalRevenue', 'movieSales'));
    }

    public function downloadPDF() 
    {
        $totalRevenue = $this->getTotalConfirmedRevenue();
        $movieSales = $this->getSalesByMovie();

        return view('admin.sales_pdf', compact('totalRevenue', 'movieSales'));
    }

    public function getLiveStats()
    {
        $viewStats = DB::table('booking_analytics_view')->first();
        
        $customerCount = DB::table('customer_analytics_view')->count();
        $movieCount = DB::table('movie_details_view')->where('status', 'now_showing')->count();

        $serverLoad = $this->calculateServerLoad();
        $startTime = microtime(true);
        $dbStatus = $this->checkDatabaseStatus();
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);

        return response()->json([
            'revenue'         => '₱' . number_format($viewStats->total_revenue ?? 0, 2),
            'bookings_count'  => number_format($viewStats->confirmed_count + $viewStats->pending_count),
            'customers_count' => number_format($customerCount),
            'movies_count'    => $movieCount,
            'server_load'     => $serverLoad,
            'server_status'   => $serverLoad > 80 ? 'Heavy' : 'Optimal',
            'db_latency'      => $responseTime . ' ms',
            'memory'          => round(memory_get_usage(true) / 1024 / 1024, 1) . ' MB',
            'db_online'       => $dbStatus
        ]);
    }

    private function checkDatabaseStatus() 
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function calculateServerLoad() 
    {
        return Cache::remember('server_cpu_load', 15, function () {
            if (stristr(PHP_OS, 'WIN')) {
                try {
                    $command = "powershell -NoProfile -Command \"& {Get-WmiObject Win32_Processor | Measure-Object -Property LoadPercentage -Average | Select-Object -ExpandProperty Average}\"";
                    
                    $output = @shell_exec($command);

                    if ($output !== null && is_numeric(trim($output))) {
                        return round((float)trim($output), 2);
                    }
                } catch (\Exception $e) {
                    $wmic = @shell_exec("wmic cpu get loadpercentage /value");
                    if ($wmic && preg_match('/LoadPercentage=(\d+)/i', $wmic, $matches)) {
                        return (float)$matches[1];
                    }
                }
            } else {
                if (function_exists('sys_getloadavg')) {
                    $load = sys_getloadavg();
                    return (float)($load[0] * 10); 
                }
            }
            return 0;
        });
    }

    private function getTotalConfirmedRevenue()
    {
        return DB::table('booking_analytics_view')->value('total_revenue') ?? 0;
    }

    private function getSalesByMovie()
    {
        return DB::table('movie_sales_analytics_view')
            ->select('title', 'tickets_sold as tickets', 'total_revenue as total')
            ->orderBy('total_revenue', 'desc')
            ->get();
    }
}