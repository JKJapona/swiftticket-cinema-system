<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Movie Management Controller
|--------------------------------------------------------------------------
|
| This controller handles the CRUD operations for the movie catalog.
| It manages media uploads (posters and covers), movie status toggling,
| and provides aggregate statistics for the movie management dashboard.
|
*/

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $movies = Movie::all();

        $stats = [
            'total_count'        => $movies->count(),
            'now_showing_count'  => $movies->where('status', 'now_showing')->count(),
            'coming_soon_count'  => $movies->where('status', 'coming_soon')->count(),
            'active_showtimes'   => $movies->sum('active_showtimes_count'), // Optimized!
        ];

        return view('admin.movies.index', compact('movies', 'stats'));
    }

    /*
    |--------------------------------------------------------------------------
    | Persistence Actions
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Failed to publish movie. Please check the form for errors.');
        }

        $data = $this->extractMovieData($request);
        $data['poster_path'] = $this->handleImage($request, 'poster', 'posters');
        $data['cover_path'] = $this->handleImage($request, 'cover', 'covers');

        Movie::create($data);

        return redirect()->back()->with('success', "Movie '{$request->title}' has been successfully published.");
    }

    public function update(Request $request, Movie $movie)
    {
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_movie_id', $movie->id)
                ->with('error', "Update failed for '{$movie->title}'. Please verify the provided details.");
        }

        $data = $this->extractMovieData($request);
        $data['poster_path'] = $this->handleImage($request, 'poster', 'posters', $movie->poster_path);
        $data['cover_path'] = $this->handleImage($request, 'cover', 'covers', $movie->cover_path);

        $movie->update($data);

        return redirect()->back()->with('success', "Information for '{$movie->title}' has been updated successfully.");
    }

    public function destroy(Movie $movie)
    {
        $title = $movie->title;
        $this->deletePhysicalFile($movie->poster_path);
        $this->deletePhysicalFile($movie->cover_path);

        $movie->delete();

        return redirect()->back()->with('success', "Movie '{$title}' and its associated media have been deleted.");
    }

    public function toggleArchive(Movie $movie)
    {
        if ($movie->status === 'archived') {
            $hasActiveShowtimes = $movie->showtimes()
                ->where('show_date', '>=', now()->toDateString())
                ->exists();

            $movie->status = $hasActiveShowtimes ? 'now_showing' : 'coming_soon';
            $statusLabel = str_replace('_', ' ', $movie->status);
            $message = "'{$movie->title}' unarchived and status set to {$statusLabel}.";
        } else {
            $movie->status = 'archived';
            $message = "'{$movie->title}' has been moved to the archives.";
        }

        $movie->save();

        return back()->with('success', $message);
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper Logic
    |--------------------------------------------------------------------------
    */

    private function validateMovie(Request $request)
    {
        return Validator::make($request->all(), [
            'title'           => 'required|string|max:150',
            'genre'           => 'nullable|string|max:50',
            'runtime_minutes' => 'nullable|integer|min:30|max:600',
            'rating'          => 'nullable|in:G,PG,R-13,R-16,R-18,TBA',
            'release_date'    => 'nullable|date|after_or_equal:1895-12-28',
            'cast_members'    => 'nullable|string|max:1000',
            'synopsis'        => 'nullable|string|max:2000',
            'trailer_url'     => 'nullable|url|max:255',
            'poster_file'     => 'nullable|image|max:4096',
            'cover_file'      => 'nullable|image|max:4096',
            'poster_url'      => 'nullable|url|max:500',
            'cover_url'       => 'nullable|url|max:500',
            'is_featured'     => 'nullable|boolean',
        ]);
    }

    private function extractMovieData(Request $request)
    {
        $data = $request->only([
            'title', 'synopsis', 'cast_members', 'genre',
            'runtime_minutes', 'rating', 'trailer_url',
            'release_date'
        ]);

        $data['is_featured'] = $request->has('is_featured');

        return $data;
    }

    private function handleImage(Request $request, $type, $folder, $existingPath = null)
    {
        $fileKey = $type . '_file';
        $urlKey = $type . '_url';

        if ($request->hasFile($fileKey)) {
            $this->deletePhysicalFile($existingPath);

            $extension = $request->file($fileKey)->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($request->title) . "_{$type}." . $extension;

            return $request->file($fileKey)->storeAs($folder, $filename, 'public');
        }

        return $request->filled($urlKey) ? $request->$urlKey : $existingPath;
    }

    private function deletePhysicalFile($path)
    {
        if ($path && !str_contains($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }
}