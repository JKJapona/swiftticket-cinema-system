<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookedSeat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Booking Lifecycle Logic
    |--------------------------------------------------------------------------
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

    public function store(Request $request)
    {
        $request->validate([
            'showtime_id' => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);
        $seatsArray = explode(',', $request->selected_seats);
        $seatCount = count($seatsArray);

        try {
            DB::beginTransaction();

            $alreadyBooked = BookedSeat::where('showtime_id', $showtime->id)
                ->whereIn('seat_code', $seatsArray)
                ->exists();

            if ($alreadyBooked) {
                throw new \Exception('One or more seats are no longer available.');
            }

            $status = ($request->payment_method === 'Pay at Cinema') ? 'pending' : 'confirmed';

            $booking = Booking::create([
                'user_id' => Auth::id(), 
                'showtime_id' => $showtime->id,
                'reference_number' => strtoupper(Str::random(10)), 
                'payment_method' => $request->payment_method,
                'total_price' => count($seatsArray) * $showtime->price,
                'status' => $status,
            ]);

            $showtime->increment('booked_seats', $seatCount);

            foreach ($seatsArray as $seatCode) {
                BookedSeat::create([
                    'booking_id' => $booking->id,
                    'showtime_id' => $showtime->id,
                    'seat_code' => trim($seatCode),
                ]);
            }

            DB::commit();
            
            return redirect()->route('checkout.success', $booking->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Post-Purchase Confirmation
    |--------------------------------------------------------------------------
    */

    public function success($reference)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.hall'])
            ->where('reference_number', $reference)
            ->firstOrFail();

        $selectedSeats = BookedSeat::where('booking_id', $booking->id)
            ->pluck('seat_code')
            ->toArray();

        return view('booking.success', [
            'booking' => $booking,
            'showtime' => $booking->showtime,
            'selectedSeats' => $selectedSeats
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | User Booking History
    |--------------------------------------------------------------------------
    */

    public function myBookings()
    {
        $bookings = Booking::where('user_id', auth()->id())
            ->with(['showtime.movie', 'showtime.hall', 'seats'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }
}