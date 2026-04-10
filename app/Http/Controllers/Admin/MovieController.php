<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::latest()->get();

        return view('admin.movies.index', compact('movies'));
    }

    public function store(Request $request)
    {
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $this->extractMovieData($request);
        $data['poster_path'] = $this->handleImage($request, 'poster', 'posters');
        $data['cover_path'] = $this->handleImage($request, 'cover', 'covers');

        Movie::create($data);

        return redirect()->back()->with('success', 'Movie published successfully.');
    }

    public function update(Request $request, Movie $movie)
    {
        $validator = $this->validateMovie($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_movie_id', $movie->id);
        }

        $data = $this->extractMovieData($request);
        $data['poster_path'] = $this->handleImage($request, 'poster', 'posters', $movie->poster_path);
        $data['cover_path'] = $this->handleImage($request, 'cover', 'covers', $movie->cover_path);

        $movie->update($data);

        return redirect()->back()->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie)
    {
        $this->deletePhysicalFile($movie->poster_path);
        $this->deletePhysicalFile($movie->cover_path);

        $movie->delete();

        return redirect()->back()->with('success', 'Movie deleted.');
    }

    public function toggleArchive(Movie $movie)
    {
        if ($movie->status === 'archived') {
            $hasActiveShowtimes = $movie->showtimes()
                ->where('show_date', '>=', now()->toDateString())
                ->exists();

            $movie->status = $hasActiveShowtimes ? 'now_showing' : 'coming_soon';
            $message = 'Movie unarchived and set to ' . str_replace('_', ' ', $movie->status) . '.';
        } else {
            $movie->status = 'archived';
            $message = 'Movie moved to archives.';
        }

        $movie->save();

        return back()->with('success', $message);
    }

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
        ]);
    }

    private function extractMovieData(Request $request)
    {
        return $request->only([
            'title', 'synopsis', 'cast_members', 'genre',
            'runtime_minutes', 'rating', 'trailer_url',
            'release_date'
        ]);
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