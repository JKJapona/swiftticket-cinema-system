<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use Illuminate\Http\Request;

class CinemaHallController extends Controller
{
    public function index()
    {
        $halls = CinemaHall::latest()->get();

        return view('admin.cinema-halls.index', compact('halls'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateHall($request, true);

        CinemaHall::create($validated);

        return redirect()->back()->with('success', 'Cinema Hall created successfully!');
    }

    public function update(Request $request, CinemaHall $cinemaHall)
    {
        $validated = $this->validateHall($request, false);

        $cinemaHall->update($validated);

        return redirect()->back()->with('success', 'Cinema Hall updated successfully!');
    }

    public function destroy(CinemaHall $cinemaHall)
    {
        $cinemaHall->delete();

        return redirect()->back()->with('success', 'Cinema Hall deleted.');
    }

    private function validateHall(Request $request, bool $isStore)
    {
        $rules = [
            'name'         => 'required|string|max:50',
            'screen_type'  => 'required|in:Standard,IMAX,Premium,4DX',
            'audio_system' => 'nullable|string|max:50',
            'status'       => 'required|in:Active,Maintenance,Inactive',
        ];

        if ($isStore) {
            $rules['number_of_rows'] = 'required|integer|min:1|max:26';
            $rules['seats_per_row']  = 'required|integer|min:1|max:40';
        }

        return $request->validate($rules);
    }
}