<?php

namespace App\Http\Controllers\Admin;

/*
|--------------------------------------------------------------------------
| Admin Booking Management Controller
|--------------------------------------------------------------------------
|
| This controller manages the lifecycle of movie ticket bookings. It handles
| seat reservations, status transitions (confirm/cancel), administrative
| seat overrides, and reporting/exporting of booking data.
|
*/

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Showtime;
use App\Models\BookedSeat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | View Actions
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Booking::with(['user', 'showtime.movie', 'showtime.hall', 'bookedSeats']);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('full_name', 'LIKE', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('created_at', 'desc')->get();

        $statsData = DB::table('booking_analytics_view')->first();

        $stats = [
            'total_revenue'         => $statsData->total_revenue ?? 0,
            'pending_count'         => $statsData->pending_count ?? 0,
            'confirmed_count'       => $statsData->confirmed_count ?? 0,
            'change_requests_count' => $statsData->change_requests_count ?? 0,
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function show(Showtime $showtime)
    {
        $showtime->load(['movie', 'cinemaHall']);
        $bookedSeats = $this->getReservedSeatCodes($showtime->id);

        return view('customer.bookings.select-seats', compact('showtime', 'bookedSeats'));
    }

    /*
    |--------------------------------------------------------------------------
    | Status & Seat Management
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Booking $booking)
    {
        $booking->update($request->only(['status', 'requested_seats']));

        return redirect()->back()->with('success', "Booking #{$booking->reference_number} has been updated.");
    }

    public function confirm(Request $request, Booking $booking) 
    {
        $booking->update([
            'status' => 'confirmed', 
            'cancellation_reason' => null
            ]);

        return back()->with('success', "Booking #{$booking->reference_number} is now confirmed. Tickets are ready for pickup/entry.");
    }

    public function cancel(Request $request, Booking $booking)
    {
        try {
            DB::beginTransaction();

            $booking->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->cancellation_reason
                ]);
            DB::table('booked_seats')->where('booking_id', $booking->id)->delete();

            DB::commit();
            return redirect()->back()->with('success', "Booking #{$booking->reference_number} cancelled. Seats have been released back to the hall.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', "System encountered an error while cancelling the booking. Please try again.");
        }
    }

    public function approveChange(Request $request, $id)
    {
        $request->validate(['manual_seats' => 'required|string']);

        $booking = Booking::findOrFail($id);
        $newSeats = array_filter(explode(',', $request->manual_seats));

        try {
            DB::transaction(function () use ($booking, $newSeats) {
                $booking->bookedSeats()->delete(); 

                foreach ($newSeats as $code) {
                    BookedSeat::create([
                        'booking_id'  => $booking->id,
                        'showtime_id' => $booking->showtime_id,
                        'seat_code'   => trim($code),
                    ]);
                }

                if ($booking->status === 'change_requested') {
                    $booking->update(['status' => 'pending']);
                } else {
                    $booking->update(['status' => $booking->status]);
                }
            });

            return back()->with('success', "Seats for #{$booking->reference_number} have been manually overridden to: " . implode(', ', $newSeats));
        } catch (\Exception $e) {
            return back()->with('error', "Failed to override seats: " . $e->getMessage());
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Data Export & Utilities
    |--------------------------------------------------------------------------
    */

    public function export(Request $request)
    {
        $fileName = 'bookings_export_' . date('Y-m-d') . '.csv';
        $bookings = Booking::with(['user', 'showtime.movie'])->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Ref #', 'Customer', 'Movie', 'Total', 'Status', 'Date'];

        $callback = function() use($bookings, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->reference_number,
                    $booking->user->full_name,
                    $booking->showtime->movie->title,
                    $booking->total_price,
                    $booking->status,
                    $booking->created_at
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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