<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Dashboard & Analytics Controller
|--------------------------------------------------------------------------
|
| This controller generates the administrative overview, including high-level
| business stats, system health monitoring (CPU/DB), and sales reporting.
| It utilizes caching for hardware-intensive server load calculations.
|
*/

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Booking;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index() 
    {
        $startTime = microtime(true);

        $stats = [
            'total_revenue'   => DB::table('bookings')->where('status', 'confirmed')->sum('total_price'),
            'total_bookings'  => DB::table('bookings')->count(),
            'total_customers' => DB::table('users')->where('role', 'customer')->count(),
            'active_movies'   => DB::table('movies')->where('status', 'now_showing')->count(),
        ];

        $recent_bookings = Booking::with(['user', 'showtime.movie'])
            ->latest()
            ->take(7)
            ->get();

        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2);

        $dbStatus = $this->checkDatabaseStatus();
        $serverLoad = $this->calculateServerLoad();
        $status = $serverLoad > 80 ? 'Heavy' : 'Optimal';

        return view('admin.dashboard', compact(
            'stats', 
            'recent_bookings', 
            'dbStatus', 
            'serverLoad', 
            'status', 
            'responseTime'
        ));
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

    /*
    |--------------------------------------------------------------------------
    | System Monitoring Logic
    |--------------------------------------------------------------------------
    */

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
        return Cache::remember('server_cpu_load', 300, function () {
            
            if (stristr(PHP_OS, 'WIN')) {
                $command = "powershell -NoProfile -Command \"(Get-WmiObject Win32_Processor | Measure-Object -Property LoadPercentage -Average).Average\"";
                $output = @shell_exec($command);
                
                if ($output !== null) {
                    return (float)trim($output);
                }
                
                $wmic = @shell_exec("wmic cpu get loadpercentage /value");
                if ($wmic && preg_match('/LoadPercentage=(\d+)/i', $wmic, $matches)) {
                    return (float)$matches[1];
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

    /*
    |--------------------------------------------------------------------------
    | Financial Data Logic
    |--------------------------------------------------------------------------
    */

    private function getTotalConfirmedRevenue() 
    {
        return DB::table('bookings')
            ->where('status', 'confirmed')
            ->sum('total_price');
    }

    private function getSalesByMovie() 
    {
        return DB::table('bookings')
            ->join('showtimes', 'bookings.showtime_id', '=', 'showtimes.id')
            ->join('movies', 'showtimes.movie_id', '=', 'movies.id')
            ->select(
                'movies.title', 
                DB::raw('SUM(bookings.total_price) as total'), 
                DB::raw('COUNT(bookings.id) as tickets')
            )
            ->where('bookings.status', 'confirmed')
            ->groupBy('movies.title')
            ->get();
    }

    private function convertToBytes($from) 
    {
        $number = (int)substr($from, 0, -1);
        $unit = strtoupper(substr($from, -1));

        switch ($unit) {
            case 'G': $number *= 1024;
            case 'M': $number *= 1024;
            case 'K': $number *= 1024;
        }

        return $number;
    }
}