<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings for the admin.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.hall'])
                    ->latest()
                    ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->only('status'));
        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->back()->with('success', 'Booking removed.');
    }
}