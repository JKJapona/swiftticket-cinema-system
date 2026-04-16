<?php

namespace App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Booking & Transaction Controller
|--------------------------------------------------------------------------
|
| This controller manages the complete movie ticket purchase lifecycle, 
| including seat availability verification, payment processing, 
| and seat change requests.
|
*/

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
    | Checkout & Payment Process
    |--------------------------------------------------------------------------
    */

    public function showPayment(Request $request)
    {
        $request->validate([
            'showtime_id'    => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
        ]);

        $showtime = Showtime::with(['movie', 'hall'])->findOrFail($request->showtime_id);
        $selectedSeats = explode(',', $request->selected_seats);
        $totalAmount = count($selectedSeats) * $showtime->price;

        return view('customer.booking.payment', compact('showtime', 'selectedSeats', 'totalAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'showtime_id'    => 'required|exists:showtimes,id',
            'selected_seats' => 'required|string',
            'payment_method' => 'required|string',
            'payment_receipt' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $showtime = Showtime::findOrFail($request->showtime_id);
        $seatsArray = explode(',', $request->selected_seats);

        try {
            DB::beginTransaction();

            $this->verifySeatAvailability($showtime->id, $seatsArray);

            $receiptPath = null;
            if ($request->hasFile('payment_receipt')) {
                // Stores in storage/app/public/receipts
                $receiptPath = $request->file('payment_receipt')->store('receipts', 'public');
            }

            $status = 'pending';

            $booking = Booking::create([
                'user_id'          => Auth::id(),
                'showtime_id'      => $showtime->id,
                'reference_number' => strtoupper(Str::random(10)),
                'payment_method'   => $request->payment_method,
                'payment_receipt'  => $receiptPath,
                'total_price'      => count($seatsArray) * $showtime->price,
                'status'           => $status,
            ]);

            $this->attachSeatsToBooking($booking, $showtime, $seatsArray);

            DB::commit();

            return redirect()->route('checkout.success', $booking->reference_number);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Post-Transaction Views
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

        return view('customer.booking.success', [
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

    /*
    |--------------------------------------------------------------------------
    | Modifications & Requests
    |--------------------------------------------------------------------------
    */

    public function requestSeatChange(Request $request, Booking $booking)
    {
        // Security: Ensure only the owner can request a change
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'new_seats' => 'required|string', 
        ]);

        $booking->update([
            'status' => 'change_requested',
            'requested_seats' => $request->new_seats 
        ]);

        return back()->with('success', 'Your seat change request for ' . $request->new_seats . ' has been sent for approval!');
    }

    /*
    |--------------------------------------------------------------------------
    | Internal Helper Logic
    |--------------------------------------------------------------------------
    */

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