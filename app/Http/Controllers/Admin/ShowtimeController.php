<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\CinemaHall;

class ShowtimeController extends Controller
{
    public function index()
    {
        $showtimes = Showtime::with(['movie', 'hall'])->get();
        $movies = Movie::where('status', '!=', 'archived')->get();
        $halls = CinemaHall::where('status', 'Active')->get();

        return view('admin.showtimes.index', compact('showtimes', 'movies', 'halls'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'hall_id' => 'required|exists:cinema_halls,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required',
            'price' => 'required|numeric|min:0',
        ]);

        $hall = CinemaHall::findOrFail($request->hall_id);
        
        $validated['total_capacity'] = $hall->total_seats;

        Showtime::create($validated);

        return redirect()->back()->with('success', 'Showtime created successfully!');
    }

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
