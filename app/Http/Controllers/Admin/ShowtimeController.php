<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\CinemaHall;

class ShowtimeController extends Controller
{
    /**
     * Display the Movie Hub 
     */
    public function index()
    {
        $movies = Movie::has('showtimes')
            ->with(['showtimes'])
            ->withCount('showtimes')
            ->get();
        $allMovies = Movie::where('status', '!=', 'archived')->get();
        $halls = CinemaHall::where('status', 'Active')->get();
        
        $totalShowtimes = Showtime::count();
        $totalBookings = Showtime::sum('booked_seats');

        return view('admin.showtimes.index', [
            'movies' => $movies,
            'allMovies' => $allMovies,
            'halls' => $halls,
            'totalShowtimes' => $totalShowtimes,
            'totalBookings' => $totalBookings
        ]);
    }

    /**
     * Display the detailed schedule for a specific movie
     */
    public function showByMovie($movieId)
    {
        $movie = Movie::findOrFail($movieId);
        
        $showtimes = Showtime::where('movie_id', $movieId)
            ->with('hall')
            ->orderBy('show_date')
            ->orderBy('show_time')
            ->get();

        $allMovies = Movie::where('status', '!=', 'archived')->get();
        $halls = CinemaHall::where('status', 'Active')->get();

        return view('admin.showtimes.movie_schedule', compact('movie', 'showtimes', 'allMovies', 'halls'));
    }

    /**
     * Store a newly created showtime
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:cinema_halls,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $conflict = Showtime::where('hall_id', $request->hall_id)
            ->where('show_date', $request->show_date)
            ->where('show_time', $request->show_time)
            ->exists();

        if ($conflict) {
            return back()->withErrors(['show_time' => 'This hall is already booked for this specific time.']);
        }

        $hall = CinemaHall::findOrFail($request->hall_id);
        $validated['total_capacity'] = $hall->total_seats;

        Showtime::create($validated);

        return redirect()->back()->with('success', 'Showtime created successfully!');
    }

    /**
     * Remove the specified showtime from storage
     */
    public function destroy($id)
    {
        $showtime = Showtime::findOrFail($id);

        if ($showtime->booked_seats > 0) {
            return redirect()->back()->with('error', 'Cannot delete a showtime that already has bookings!');
        }

        $showtime->delete();

        return redirect()->back()->with('success', 'Showtime deleted successfully!');
    }
}