<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'showtime.movie', 'showtime.hall'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_revenue'   => $this->getTotalConfirmedRevenue(),
            'pending_count'   => $this->getBookingCountByStatus('pending'),
            'confirmed_count' => $this->getBookingCountByStatus('confirmed'),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Showtime $showtime)
    {
        $showtime->load(['movie', 'cinemaHall']);
        
        $bookedSeats = $this->getReservedSeatCodes($showtime->id);

        return view('customer.bookings.select-seats', compact('showtime', 'bookedSeats'));
    }

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->only('status'));

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function confirm(Booking $booking) 
    {
        $booking->update(['status' => 'confirmed']);

        return back()->with('success', "Booking {$booking->reference_number} confirmed!");
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->back()->with('success', 'Booking removed.');
    }

    private function getTotalConfirmedRevenue()
    {
        return Booking::where('status', 'confirmed')->sum('total_price');
    }

    private function getBookingCountByStatus(string $status)
    {
        return Booking::where('status', $status)->count();
    }

    private function getReservedSeatCodes(int $showtimeId)
    {
        return DB::table('booked_seats')
            ->where('showtime_id', $showtimeId)
            ->pluck('seat_code')
            ->toArray();
    }
}