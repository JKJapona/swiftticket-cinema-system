<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Showtime Management Controller
|--------------------------------------------------------------------------
|
| This controller handles the scheduling and coordination of movie screenings.
| It includes logic for conflict detection based on runtime, hall-specific
| availability, and daily screening analytics for the admin dashboard.
|
*/

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Showtime;
use App\Models\Movie;
use App\Models\CinemaHall;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $selectedDate = $request->get('date', now()->format('Y-m-d'));
        
        $todayShowtimes = Showtime::whereDate('show_date', $selectedDate)->get();
        
        $stats = [
            'total_showtimes'      => Showtime::count(),
            'total_bookings'       => Showtime::sum('booked_seats'),
            'today_shows_count'    => $todayShowtimes->count(),
            'unique_movies_count'  => $todayShowtimes->unique('movie_id')->count(),
            'total_daily_capacity' => $todayShowtimes->sum('total_capacity'),
            'high_occupancy_count' => $todayShowtimes->where('occupancy_rate', '>=', 0.8)->count(),
        ];

        $halls = CinemaHall::with(['showtimes' => function($query) use ($selectedDate) {
            $query->whereDate('show_date', $selectedDate)->orderBy('show_time');
        }])->where('status', 'Active')->get();

        $allMovies = Movie::where('status', '!=', 'archived')->get();

        return view('admin.showtimes.index', compact('halls', 'allMovies', 'selectedDate', 'stats'));
    }

    public function showByMovie(Request $request, $movieId)
    {
        $movie = Movie::findOrFail($movieId);
        $halls = CinemaHall::where('status', 'Active')->get();
        $allMovies = Movie::where('status', '!=', 'archived')->get();
        $selectedDate = $request->get('date', now()->format('Y-m-d'));

        $query = Showtime::with(['hall', 'movie']);

        if ($request->filled('hall_id')) {
            $query->where('hall_id', $request->hall_id);
        } else {
            $query->where('movie_id', $movieId);
        }

        if ($request->filled('date')) {
            $query->whereDate('show_date', $request->date);
        } else {
            $query->whereDate('show_date', $selectedDate);
        }

        if ($request->filled('month')) {
            $query->whereMonth('show_date', $request->month);
        }

        $showtimes = $query->orderBy('show_time')->get();

        return view('admin.showtimes.movie_schedule', compact(
            'movie', 'showtimes', 'halls', 'selectedDate', 'allMovies' 
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Persistence Actions
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'movie_id'  => 'required|exists:movies,id',
            'hall_id'   => 'required|exists:cinema_halls,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required|date_format:H:i', 
            'price'     => 'required|numeric|min:150|max:2000',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('is_create_error', true);
        }

        if ($this->hasSchedulingConflict($request)) {
            return back()
                ->withErrors(['show_time' => 'This hall is already booked for this specific time.'])
                ->withInput()
                ->with('is_create_error', true);
        }

        $hall = CinemaHall::findOrFail($request->hall_id);
        $validated = $validator->validated();
        $validated['total_capacity'] = $hall->total_seats;

        Showtime::create($validated);

        $movie = Movie::find($request->movie_id);
        return redirect()->back()->with('success', "New showtime for '{$movie->title}' has been successfully scheduled.");
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'movie_id'  => 'required|exists:movies,id',
            'hall_id'   => 'required|exists:cinema_halls,id',
            'show_date' => 'required|date|after_or_equal:today',
            'show_time' => 'required', 
            'price'     => 'required|numeric|min:150|max:2000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_showtime_id', $id);
        }

        $request->merge([
            'show_time' => \Carbon\Carbon::parse($request->show_time)->format('H:i')
        ]);

        if ($this->hasSchedulingConflict($request, $id)) {
            return back()->withErrors(['show_time' => 'This schedule overlaps with an existing screening.'])
                         ->withInput()->with('error_showtime_id', $id);
        }

        $showtime = Showtime::findOrFail($id);

        if ($showtime->booked_seats > 0) {
            $dateChanged = \Carbon\Carbon::parse($request->show_date)->format('Y-m-d') !== \Carbon\Carbon::parse($showtime->show_date)->format('Y-m-d');
            $movieChanged = $request->movie_id != $showtime->movie_id;
            $hallChanged = $request->hall_id != $showtime->hall_id;

            if ($dateChanged || $movieChanged || $hallChanged) {
                return back()->withErrors([
                    'movie_id' => 'Critical fields (Date, Movie, or Hall) cannot be changed once bookings exist.'
                ])->withInput()->with('error_showtime_id', $id);
            }
        }

        $hall = CinemaHall::findOrFail($request->hall_id);
        
        $data = $validator->validated();
        $data['show_time'] = $request->show_time;
        $data['total_capacity'] = $hall->total_seats;

        $showtime->update($data);

        $showtime = Showtime::findOrFail($id);
        return redirect()->back()->with('success', "Showtime for '{$showtime->movie->title}' updated. Changes are now live.");
    }

    public function destroy($id)
    {
        $showtime = Showtime::findOrFail($id);

        if ($showtime->booked_seats > 0) {
            return redirect()->back()->with('error', 'Cannot delete a showtime that already has bookings!');
        }

        $showtime->delete();

        $movieTitle = $showtime->movie->title;
        $showtime->delete();

        return redirect()->back()->with('success', "The screening for '{$movieTitle}' has been removed from the schedule.");
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Validation Logic
    |--------------------------------------------------------------------------
    */

    private function hasSchedulingConflict(Request $request, $ignoreId = null)
    {
        $dateStr = Carbon::parse($request->show_date)->format('Y-m-d');
        $newStartTime = Carbon::parse($request->show_time)->startOfMinute();
        
        $movie = Movie::findOrFail($request->movie_id);
        $newEndTime = (clone $newStartTime)->addMinutes($movie->runtime_minutes);

        $query = Showtime::where('hall_id', $request->hall_id)
            ->whereDate('show_date', $dateStr);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        $existingShows = $query->with('movie')->get();

        foreach ($existingShows as $existing) {
            $existingStart = Carbon::parse($existing->show_time)->startOfMinute();
            $existingEnd = (clone $existingStart)->addMinutes($existing->movie->runtime_minutes);

            if ($newStartTime->lt($existingEnd) && $newEndTime->gt($existingStart)) {
                return true; 
            }
        }

        return false;
    }

    private function getAvailableBookingDates()
    {
        return collect(range(0, 6))->map(fn($i) => now()->addDays($i));
    }
}