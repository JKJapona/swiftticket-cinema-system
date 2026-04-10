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
    public function showPayment(Request $request)
    {
        $request->validate([
            'showtime_id'    => 'required|exists:showtimes,id',
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
            'showtime_id'    => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);
        $seatsArray = explode(',', $request->selected_seats);

        try {
            DB::beginTransaction();

            $this->verifySeatAvailability($showtime->id, $seatsArray);

            $booking = Booking::create([
                'user_id'          => Auth::id(),
                'showtime_id'      => $showtime->id,
                'reference_number' => strtoupper(Str::random(10)),
                'payment_method'   => $request->payment_method,
                'total_price'      => count($seatsArray) * $showtime->price,
                'status'           => $request->payment_method === 'Pay at Cinema' ? 'pending' : 'confirmed',
            ]);

            $this->attachSeatsToBooking($booking, $showtime, $seatsArray);

            DB::commit();

            return redirect()->route('checkout.success', $booking->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function success($reference)
    {
        $booking = Booking::with(['showtime.movie', 'showtime.hall'])
            ->where('reference_number', $reference)
            ->firstOrFail();

        $selectedSeats = BookedSeat::where('booking_id', $booking->id)
            ->pluck('seat_code')
            ->toArray();

        return view('booking.success', [
            'booking'       => $booking,
            'showtime'      => $booking->showtime,
            'selectedSeats' => $selectedSeats
        ]);
    }

    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['showtime.movie', 'showtime.hall', 'seats'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('booking.index', compact('bookings'));
    }

    private function verifySeatAvailability($showtimeId, array $seats)
    {
        $alreadyBooked = BookedSeat::where('showtime_id', $showtimeId)
            ->whereIn('seat_code', $seats)
            ->exists();

        if ($alreadyBooked) {
            throw new \Exception('One or more seats are no longer available.');
        }
    }

    private function attachSeatsToBooking(Booking $booking, Showtime $showtime, array $seats)
    {
        $showtime->increment('booked_seats', count($seats));

        foreach ($seats as $seatCode) {
            BookedSeat::create([
                'booking_id'  => $booking->id,
                'showtime_id' => $showtime->id,
                'seat_code'   => trim($seatCode),
            ]);
        }
    }
}