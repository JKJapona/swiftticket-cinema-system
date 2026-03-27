<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::where('status', 'now_showing')
            ->orderBy('release_date', 'desc')
            ->get();

        return view('home', compact('movies'));
    }

    public function show($id, $date = null)
    {
        $movie = Movie::findOrFail($id);
        $selectedDate = $date ?? Carbon::now()->format('Y-m-d');
        
        // Generate 7-day range for date picker
        $dates = collect(range(0, 6))->map(fn($i) => Carbon::now()->addDays($i));

        $showtimes = Showtime::with('hall')
            ->where('movie_id', $id)
            ->whereDate('show_time', $selectedDate) 
            ->orderBy('show_time', 'asc')
            ->get();

        return view('movies.show', compact('movie', 'showtimes', 'dates', 'selectedDate'));
    }

    public function showSeatMap($showtime_id)
    {
        $showtime = Showtime::with(['movie', 'hall', 'bookedSeats'])->findOrFail($showtime_id);

        // Convert booked seats to array for selection logic
        $takenSeats = $showtime->bookedSeats->pluck('seat_code')->toArray();

        return view('booking.seats', compact('showtime', 'takenSeats'));
    }
}