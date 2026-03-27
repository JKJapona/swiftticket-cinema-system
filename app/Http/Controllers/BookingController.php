<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\BookedSeat;
use App\Models\Showtime;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Store the finalized booking in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string', 
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);
        $seatsArray = explode(',', $request->selected_seats);
        $totalPrice = count($seatsArray) * $showtime->price;

        try {
            DB::beginTransaction();

            // 1. Create the main Booking record
            $booking = Booking::create([
                'user_id' => Auth::id() ?? 1, 
                'showtime_id' => $showtime->id,
                'reference_number' => strtoupper(Str::random(8)), 
                'payment_method' => $request->payment_method ?? 'Pay at Cinema',
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // 2. Save each seat into the booked_seats table
            foreach ($seatsArray as $seatCode) {
                BookedSeat::create([
                    'booking_id' => $booking->id,
                    'showtime_id' => $showtime->id,
                    'seat_code' => trim($seatCode),
                ]);
            }

            DB::commit();

            return redirect()->route('home')->with('success', 'Booking confirmed! Ref: ' . $booking->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Booking failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the payment/summary page before finalizing the booking.
     */
    public function showPayment(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
        ]);

        $showtime = Showtime::with(['movie', 'hall'])->findOrFail($request->showtime_id);
        $selectedSeats = explode(',', $request->selected_seats);
        $totalAmount = count($selectedSeats) * $showtime->price;

        return view('booking.payment', compact('showtime', 'selectedSeats', 'totalAmount'));
    }
}