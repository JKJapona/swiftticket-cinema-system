<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::latest()->get();
        return view('admin.movies.index', compact('movies'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:150',
            'genre'           => 'nullable|string|max:50', 
            'runtime_minutes' => 'nullable|integer|min:1',     
            'rating'          => 'nullable|in:G,PG,R-13,R-16,R-18,TBA', 
            'release_date'    => 'nullable|date',        
            'cast_members'    => 'nullable|string',      
            'synopsis'        => 'nullable|string',      
            'trailer_url'     => 'nullable|url',
            'poster_file'     => 'nullable|image|max:4096',
            'cover_file'      => 'nullable|image|max:4096',
            'poster_url'      => 'nullable|url',
            'cover_url'       => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'title', 'synopsis', 'cast_members', 'genre', 
            'runtime_minutes', 'rating', 'trailer_url', 
            'release_date'
        ]);

        if ($request->hasFile('poster_file')) {
            $extension = $request->file('poster_file')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($request->title) . '_poster.' . $extension;
            $data['poster_path'] = $request->file('poster_file')->storeAs('posters', $filename, 'public');
        } else {
            $data['poster_path'] = $request->poster_url;
        }

        if ($request->hasFile('cover_file')) {
            $extension = $request->file('cover_file')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($request->title) . '_cover.' . $extension;
            $data['cover_path'] = $request->file('cover_file')->storeAs('covers', $filename, 'public');
        } else {
            $data['cover_path'] = $request->cover_url;
        }

        Movie::create($data);

        return redirect()->back()->with('success', 'Movie published successfully.');
    }

    public function update(Request $request, Movie $movie)
    {
        $validator = Validator::make($request->all(), [
            'title'           => 'required|string|max:150',
            'genre'           => 'nullable|string|max:50', 
            'runtime_minutes' => 'nullable|integer|min:1',     
            'rating'          => 'nullable|in:G,PG,R-13,R-16,R-18,TBA', 
            'release_date'    => 'nullable|date',        
            'cast_members'    => 'nullable|string',      
            'synopsis'        => 'nullable|string',      
            'trailer_url'     => 'nullable|url',
            'poster_file'     => 'nullable|image|max:4096',
            'cover_file'      => 'nullable|image|max:4096',
            'poster_url'      => 'nullable|url',
            'cover_url'       => 'nullable|url',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_movie_id', $movie->id);
        }

        $data = $request->only([
            'title', 'synopsis', 'cast_members', 'genre', 
            'runtime_minutes', 'rating', 'trailer_url', 
            'release_date'
        ]);

        if ($request->hasFile('poster_file')) {
            if ($movie->poster_path && !str_contains($movie->poster_path, 'http')) {
                Storage::disk('public')->delete($movie->poster_path);
            }
            $extension = $request->file('poster_file')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($request->title) . '_poster.' . $extension;
            $data['poster_path'] = $request->file('poster_file')->storeAs('posters', $filename, 'public');
        } elseif ($request->filled('poster_url')) {
            $data['poster_path'] = $request->poster_url;
        }

        if ($request->hasFile('cover_file')) {
            if ($movie->cover_path && !str_contains($movie->cover_path, 'http')) {
                Storage::disk('public')->delete($movie->cover_path);
            }
            $extension = $request->file('cover_file')->getClientOriginalExtension();
            $filename = time() . '_' . Str::slug($request->title) . '_cover.' . $extension;
            $data['cover_path'] = $request->file('cover_file')->storeAs('covers', $filename, 'public');
        } elseif ($request->filled('cover_url')) {
            $data['cover_path'] = $request->cover_url;
        }

        $movie->update($data);

        return redirect()->back()->with('success', 'Movie updated successfully.');
    }

    public function destroy(Movie $movie)
    {
        // Delete Poster if it's a local file
        if ($movie->poster_path && !str_contains($movie->poster_path, 'http')) {
            Storage::disk('public')->delete($movie->poster_path);
        }

        // Delete Cover if it's a local file
        if ($movie->cover_path && !str_contains($movie->cover_path, 'http')) {
            Storage::disk('public')->delete($movie->cover_path);
        }

        $movie->delete();
        
        return redirect()->back()->with('success', 'Movie deleted.');
    }

    public function toggleArchive(Movie $movie)
{
    if ($movie->status === 'archived') {
        // Check if there are showtimes for today or the future
        $hasActiveShowtimes = $movie->showtimes()
            ->where('show_date', '>=', now()->toDateString())
            ->exists();

        // If it has showtimes, return it to 'now_showing', otherwise 'coming_soon'
        $movie->status = $hasActiveShowtimes ? 'now_showing' : 'coming_soon';
        
        $message = 'Movie unarchived and set to ' . str_replace('_', ' ', $movie->status) . '.';
    } else {
        $movie->status = 'archived';
        $message = 'Movie moved to archives.';
    }

    $movie->save();

    return back()->with('success', $message);
}
}