<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Movie & Showtime Browsing Logic
    |--------------------------------------------------------------------------
    |
    | This controller handles the public-facing catalog of SwiftTicket. 
    | It manages the "Now Showing" list, specific movie details with 
    | date-based showtime filtering, and the interactive seat selection map.
    |
    */

    /**
     * Display a listing of movies currently available for booking.
     */
    public function index()
    {
        // Fetch movies flagged as 'now_showing', prioritized by the most recent releases
        $movies = Movie::where('status', 'now_showing')
            ->orderBy('release_date', 'desc')
            ->get();

        return view('home', compact('movies'));
    }

    /**
     * Display movie details and available showtimes for a specific date.
     * * @param int $id
     * @param string|null $date
     */
    public function show($id, $date = null)
    {
        $movie = Movie::findOrFail($id);
        
        // Default to today if no date is selected
        $selectedDate = $date ?? Carbon::now()->format('Y-m-d');
        
        // Generate a 7-day rolling window for the date-picker UI
        $dates = collect(range(0, 6))->map(fn($i) => Carbon::now()->addDays($i));

        // Query showtimes for the specific date
        $query = Showtime::with('hall')
            ->where('movie_id', $id)
            ->whereDate('show_time', $selectedDate);

        // UI Optimization: If the selected date is today, hide showtimes that have already passed
        if ($selectedDate === Carbon::now()->format('Y-m-d')) {
            $query->where('show_time', '>=', Carbon::now());
        }

        $showtimes = $query->orderBy('show_time', 'asc')->get();

        return view('movies.show', compact('movie', 'showtimes', 'dates', 'selectedDate'));
    }

    /**
     * Display the interactive seat map for a specific showtime.
     * * @param int $showtime_id
     */
    public function showSeatMap($showtime_id)
    {
        // Eager load relationships to prevent N+1 query issues
        $showtime = Showtime::with(['movie', 'hall', 'bookedSeats'])->findOrFail($showtime_id);

        // Extract already booked seat codes into a flat array for the front-end 'disabled' state
        $takenSeats = $showtime->bookedSeats->pluck('seat_code')->toArray();

        return view('booking.seats', compact('showtime', 'takenSeats'));
    }
}