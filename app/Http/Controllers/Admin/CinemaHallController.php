<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Cinema Hall Management Controller
|--------------------------------------------------------------------------
|
| This controller manages the physical infrastructure of the cinema.
| It handles the configuration of halls, including screen types, 
| seating capacities, and operational status monitoring.
|
*/

use App\Http\Controllers\Controller;
use App\Models\CinemaHall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CinemaHallController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $halls = CinemaHall::all();

        $stats = [
            'total_halls'       => $halls->count(),
            'total_seats'       => $halls->sum('total_seats'),
            'active_halls'      => $halls->where('status', 'Active')->count(),
            'maintenance_halls' => $halls->where('status', 'Maintenance')->count(),
        ];

        return view('admin.cinema-halls.index', compact('halls', 'stats'));
    }

    /*
    |--------------------------------------------------------------------------
    | Persistence Actions
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $this->validateHall($request, true);

        CinemaHall::create($validated);

        return redirect()->back()->with('success', "Cinema Hall '{$request->name}' has been created successfully.");
    }

    public function update(Request $request, CinemaHall $cinemaHall)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required|string|max:50',
            'screen_type'  => 'required|in:Standard,IMAX,Premium,4DX',
            'audio_system' => 'nullable|string|max:50',
            'status'       => 'required|in:Active,Maintenance,Inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error_hall_id', $cinemaHall->id)
                ->with('error', "Update failed for '{$cinemaHall->name}'. Please check the input fields.");
        }

        $cinemaHall->update($validator->validated());

        return redirect()->back()->with('success', "Configuration for '{$cinemaHall->name}' has been updated successfully.");
    }

    public function destroy(CinemaHall $cinemaHall)
    {
        $name = $cinemaHall->name;
        $cinemaHall->delete();

        return redirect()->back()->with('success', "Cinema Hall '{$name}' has been removed from the system.");
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper Logic
    |--------------------------------------------------------------------------
    */

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