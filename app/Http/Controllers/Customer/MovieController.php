<?php

namespace App\Http\Controllers\Customer;

/*
|--------------------------------------------------------------------------
| Movie & Screening Controller
|--------------------------------------------------------------------------
|
| This controller manages the public-facing movie catalog, detailed
| movie information, showtime scheduling for the upcoming week,
| and the interactive seat selection map.
|
*/

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Catalog & Homepage
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $movies = Movie::orderBy('release_date', 'desc')->get();

        $featuredmovies = Movie::where('is_featured', true)->get();

        if ($featuredmovies->isEmpty()) {
            $featuredmovies = Movie::where('status', '!=', 'archived')
                ->latest('release_date')
                ->take(3)
                ->get();
        }

         return view('customer.home', compact('featuredmovies', 'movies'));
    }

    /*
    |--------------------------------------------------------------------------
    | Movie Details & Showtimes
    |--------------------------------------------------------------------------
    */

    public function show($id, $date = null)
    {
        $movie = Movie::findOrFail($id);
        $selectedDate = $date ? Carbon::parse($date)->toDateString() : now()->toDateString(); 
        
        // Fetch showtimes for the next 7 days
        $showtimes = Showtime::with('hall')
            ->where('movie_id', $movie->id)
            ->whereBetween('show_date', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('show_time', 'asc')
            ->get();

        // Get all dates that have showtimes for the "Next Screening" logic
        $allAvailableDates = Showtime::where('movie_id', $movie->id)
            ->whereDate('show_date', '>=', now()->toDateString())
            ->orderBy('show_date', 'asc')
            ->pluck('show_date')
            ->map(function($date) {
                return \Carbon\Carbon::parse($date)->toDateString();
            })
            ->unique()
            ->values()
            ->toArray();

        $dates = $this->getAvailableBookingDates();

        return view('customer.movies.show', compact(
            'movie', 
            'showtimes', 
            'dates', 
            'selectedDate', 
            'allAvailableDates'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Booking & Seat Selection
    |--------------------------------------------------------------------------
    */

    public function showSeatMap($showtime_id) 
    {
        $showtime = Showtime::with(['movie', 'hall', 'bookedSeats'])->findOrFail($showtime_id);
        $occupiedSeats = $showtime->bookedSeats->pluck('seat_code')->toArray();

        return view('customer.booking.seats', ['showtime' => $showtime, 'takenSeats' => $occupiedSeats]);
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper Logic
    |--------------------------------------------------------------------------
    */

    private function getUpcomingShowtimes($movieId, $startDate)
    {
        return Showtime::with('hall')
            ->where('movie_id', $movieId)
            ->where('show_date', $startDate)
            ->orderBy('show_time', 'asc')
            ->get();
    }

    private function getAvailableBookingDates()
    {
        return collect(range(0, 6))->map(fn($i) => now()->addDays($i));
    }
}