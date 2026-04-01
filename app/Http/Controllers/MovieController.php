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
     * Display movies currently available for booking.
     */
    public function index()
    {
        $movies = Movie::orderBy('release_date', 'desc')
            ->get();

        return view('home', compact('movies'));
    }

    /**
     * Display movie details and filterable showtimes.
     */
    public function show($id, $date = null)
    {
        $movie = Movie::findOrFail($id);
        
        $showtimes = Showtime::with('hall')
            ->where('movie_id', $id)
            ->where('show_date', '>=', now()->toDateString())
            ->orderBy('show_date', 'asc')
            ->orderBy('show_time', 'asc')
            ->get();

        $dates = collect(range(0, 6))->map(fn($i) => now()->addDays($i));
        
        $selectedDate = $date ?? now()->format('Y-m-d');

        return view('movies.show', compact('movie', 'showtimes', 'dates', 'selectedDate'));
    }

    /**
     * Display the interactive seat map for a specific showtime.
     */
    public function showSeatMap($showtimeId)
    {
        $showtime = Showtime::with(['movie', 'hall', 'bookedSeats'])
            ->findOrFail($showtimeId);

        $occupiedSeats = $showtime->bookedSeats
            ->pluck('seat_code')
            ->toArray();

        return view('booking.seats', [
            'showtime'   => $showtime,
            'takenSeats' => $occupiedSeats
        ]);
    }
}