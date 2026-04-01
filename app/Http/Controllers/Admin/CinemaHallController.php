<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class CinemaHallController extends Controller
{
    public function index()
    {
        $halls = CinemaHall::all(); 

        return view('admin.cinema-halls.index', compact('halls'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:50',
            'screen_type'     => 'required|in:Standard,IMAX,Premium,4DX',
            'audio_system'    => 'nullable|string|max:50',
            'number_of_rows'  => 'required|integer|min:1',
            'seats_per_row'   => 'required|integer|min:1',
            'status'          => 'required|in:Active,Maintenance,Inactive',
        ]);

        CinemaHall::create($request->all());

        return redirect()->back()->with('success', 'Cinema Hall created successfully!');
    }

    public function destroy(CinemaHall $cinemaHall)
    {
        $cinemaHall->delete();
        return redirect()->back()->with('success', 'Cinema Hall deleted.');
    }
}