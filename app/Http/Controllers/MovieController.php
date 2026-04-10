<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::orderBy('release_date', 'desc')->get();

        return view('home', compact('movies'));
    }

    public function show($id, $date = null)
    {
        $movie = Movie::findOrFail($id);
        $selectedDate = $date ?? now()->toDateString();
        
        $showtimes = $this->getUpcomingShowtimes($movie->id);
        $dates = $this->getAvailableBookingDates();

        return view('movies.show', compact(
            'movie', 
            'showtimes', 
            'dates', 
            'selectedDate'
        ));
    }

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

    private function getUpcomingShowtimes($movieId)
    {
        return Showtime::with('hall')
            ->where('movie_id', $movieId)
            ->where('show_date', '>=', now()->toDateString())
            ->orderBy('show_date', 'asc')
            ->orderBy('show_time', 'asc')
            ->get();
    }

    private function getAvailableBookingDates()
    {
        return collect(range(0, 6))->map(fn($i) => now()->addDays($i));
    }
}