<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;

class DashboardController extends Controller
{
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
            ->take(5)
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
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        $limitInBytes = $this->convertToBytes($memoryLimit);
        
        return round(($memoryUsage / $limitInBytes) * 100, 1);
    }

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